<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\DollarRate;
use App\Models\Tenant;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Throwable;

class DollarRateService
{
    /**
     * Cache key for the current rate.
     */
    private const CACHE_KEY = 'dollar_rate_current';

    /**
     * Cache key for the current Euro rate.
     */
    private const EURO_CACHE_KEY = 'euro_rate_current';

    /**
     * Cache TTL in seconds (1 hour).
     */
    private const CACHE_TTL = 3600;

    /**
     * HTTP timeout in seconds.
     */
    private const HTTP_TIMEOUT = 5;

    /**
     * USD BCV sources in priority order.
     * Each entry: url + extractor callable (array $data): ?float
     */
    private function usdSources(): array
    {
        return [
            [
                'name'    => 'dolarapi',
                'url'     => 'https://ve.dolarapi.com/v1/dolares/oficial',
                'extract' => static fn(array $d): ?float =>
                    isset($d['promedio']) && is_numeric($d['promedio']) ? (float) $d['promedio'] : null,
            ],
            [
                'name'    => 'pydolarve',
                'url'     => 'https://pydolarve.org/api/v1/dollar?monitor=bcv',
                'extract' => static fn(array $d): ?float =>
                    isset($d['price']) && is_numeric($d['price']) ? (float) $d['price']
                    : (isset($d['monitors']['bcv']['price']) && is_numeric($d['monitors']['bcv']['price'])
                        ? (float) $d['monitors']['bcv']['price'] : null),
            ],
        ];
    }

    /**
     * EUR BCV sources in priority order.
     */
    private function eurSources(): array
    {
        return [
            [
                'name'    => 'dolarapi',
                'url'     => 'https://ve.dolarapi.com/v1/euros/oficial',
                'extract' => static fn(array $d): ?float =>
                    isset($d['promedio']) && is_numeric($d['promedio']) ? (float) $d['promedio'] : null,
            ],
            [
                'name'    => 'pydolarve',
                'url'     => 'https://pydolarve.org/api/v1/dollar?page=bcv&monitor=euro',
                'extract' => static fn(array $d): ?float =>
                    isset($d['monitors']['euro']['price']) && is_numeric($d['monitors']['euro']['price'])
                        ? (float) $d['monitors']['euro']['price'] : null,
            ],
        ];
    }

    /**
     * Try each source in order and return the first valid rate.
     *
     * @param  array<array{name: string, url: string, extract: callable}> $sources
     * @return array{value: float, source: string}|null
     */
    private function fetchFromSources(array $sources): ?array
    {
        foreach ($sources as $src) {
            try {
                $response = Http::timeout(self::HTTP_TIMEOUT)->acceptJson()->get($src['url']);

                if (!$response->successful()) {
                    Log::warning('DollarRateService: Source failed, trying next', [
                        'source' => $src['name'],
                        'status' => $response->status(),
                    ]);
                    continue;
                }

                $value = ($src['extract'])($response->json() ?? []);

                if ($value !== null && $value > 0) {
                    Log::info('DollarRate source: ' . $src['name'], ['rate' => $value]);
                    return ['value' => $value, 'source' => $src['name']];
                }

                Log::warning('DollarRateService: Source returned invalid value, trying next', [
                    'source' => $src['name'],
                    'value'  => $value,
                ]);
            } catch (Throwable $e) {
                Log::warning('DollarRateService: Source threw exception, trying next', [
                    'source' => $src['name'],
                    'error'  => $e->getMessage(),
                ]);
            }
        }

        return null;
    }

    /**
     * Maximum rate change percentage to trigger alert.
     */
    private const MAX_RATE_CHANGE_PERCENT = 10.0;

    /**
     * Get the current dollar rate with cache.
     *
     * @return float
     */
    public function getCurrentRate(): float
    {
        try {
            return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function (): float {
                $rate = DollarRate::query()
                    ->where('currency_type', 'USD')
                    ->where('is_active', true)
                    ->orderByDesc('effective_from')
                    ->first();

                if ($rate === null) {
                    $fallback = (float) env('DOLLAR_FALLBACK_RATE', 40.00);
                    Log::info('DollarRate source: env_fallback', ['rate' => $fallback]);
                    return $fallback;
                }

                Log::info('DollarRate source: database', [
                    'rate' => $rate->rate,
                    'effective_from' => $rate->effective_from,
                ]);

                return (float) $rate->rate;
            });
        } catch (Throwable $e) {
            Log::warning('DollarRateService: Failed to get current USD rate, using fallback', [
                'error' => $e->getMessage(),
            ]);

            $fallback = (float) env('DOLLAR_FALLBACK_RATE', 40.00);
            Log::info('DollarRate source: env_fallback', ['rate' => $fallback]);
            return $fallback;
        }
    }

    /**
     * Get the current Euro rate with cache.
     *
     * @return float
     */
    public function getCurrentEuroRate(): float
    {
        try {
            return Cache::remember(self::EURO_CACHE_KEY, self::CACHE_TTL, function (): float {
                $rate = DollarRate::query()
                    ->where('currency_type', 'EUR')
                    ->where('is_active', true)
                    ->orderByDesc('effective_from')
                    ->first();

                if ($rate === null) {
                    Log::warning('DollarRateService: No active EUR rate found, using fallback');
                    return (float) config('currency.fallback_eur', 495.00);
                }

                Log::debug('DollarRateService: EUR rate retrieved from database', [
                    'rate' => $rate->rate,
                    'effective_from' => $rate->effective_from,
                ]);

                return (float) $rate->rate;
            });
        } catch (Throwable $e) {
            Log::warning('DollarRateService: Failed to get current EUR rate, using fallback', [
                'error' => $e->getMessage(),
            ]);

            return (float) config('currency.fallback_eur', 495.00);
        }
    }

    /**
     * Fetch USD rate from all sources (fallback chain) and store in database.
     *
     * @return array{success: bool, rate?: float, message: string, source?: string}
     */
    public function fetchAndStore(): array
    {
        Log::info('DollarRateService: Starting USD rate fetch (multi-source)');

        $fetched = $this->fetchFromSources($this->usdSources());

        if ($fetched === null) {
            Log::error('DollarRateService: All USD sources failed');
            return ['success' => false, 'message' => 'All USD sources failed'];
        }

        $newRate    = $fetched['value'];
        $sourceName = $fetched['source'];

        // Check for unusual rate changes
        $previousRate = $this->getCurrentRate();
        $changePercent = $previousRate > 0
            ? abs(($newRate - $previousRate) / $previousRate) * 100
            : 0;

        if ($changePercent > self::MAX_RATE_CHANGE_PERCENT) {
            Log::warning('DollarRateService: Unusual USD rate change detected', [
                'previous_rate'  => $previousRate,
                'new_rate'       => $newRate,
                'change_percent' => round($changePercent, 2),
                'source'         => $sourceName,
            ]);
        }

        if (abs($changePercent) > 20) {
            Log::critical('DollarRate: rejected suspicious value', [
                'new'     => $newRate,
                'current' => $previousRate,
            ]);
            return ['success' => false, 'reason' => 'suspicious_rate'];
        }

        try {
            DollarRate::query()
                ->where('currency_type', 'USD')
                ->where('is_active', true)
                ->update(['is_active' => false, 'effective_until' => Carbon::now()]);

            $dollarRate = DollarRate::create([
                'rate'           => $newRate,
                'source'         => $sourceName,
                'currency_type'  => 'USD',
                'effective_from' => Carbon::now(),
                'effective_until'=> null,
                'is_active'      => true,
            ]);

            Cache::forget(self::CACHE_KEY);

            Log::info('DollarRateService: USD rate stored', [
                'rate'    => $newRate,
                'source'  => $sourceName,
                'rate_id' => $dollarRate->id,
            ]);

            return [
                'success' => true,
                'rate'    => $newRate,
                'source'  => $sourceName,
                'message' => "USD rate fetched from {$sourceName}",
            ];
        } catch (Throwable $e) {
            Log::error('DollarRateService: Failed to persist USD rate', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => "DB error: {$e->getMessage()}"];
        }
    }

    /**
     * Fetch EUR rate from all sources (fallback chain) and store in database.
     *
     * @return array{success: bool, rate?: float, message: string, source?: string}
     */
    public function fetchAndStoreEuro(): array
    {
        Log::info('DollarRateService: Starting EUR rate fetch (multi-source)');

        $fetched = $this->fetchFromSources($this->eurSources());

        if ($fetched === null) {
            Log::error('DollarRateService: All EUR sources failed');
            return ['success' => false, 'message' => 'All EUR sources failed'];
        }

        $newRate    = $fetched['value'];
        $sourceName = $fetched['source'];

        try {
            DollarRate::query()
                ->where('currency_type', 'EUR')
                ->where('is_active', true)
                ->update(['is_active' => false, 'effective_until' => Carbon::now()]);

            DollarRate::create([
                'rate'           => $newRate,
                'source'         => $sourceName,
                'currency_type'  => 'EUR',
                'effective_from' => Carbon::now(),
                'effective_until'=> null,
                'is_active'      => true,
            ]);

            Cache::forget(self::EURO_CACHE_KEY);

            Log::info('DollarRateService: EUR rate stored', ['rate' => $newRate, 'source' => $sourceName]);

            return [
                'success' => true,
                'rate'    => $newRate,
                'source'  => $sourceName,
                'message' => "EUR rate fetched from {$sourceName}",
            ];
        } catch (Throwable $e) {
            Log::error('DollarRateService: Failed to persist EUR rate', ['error' => $e->getMessage()]);
            return ['success' => false, 'message' => "DB error: {$e->getMessage()}"];
        }
    }

    /**
     * Propagate Euro rate to all tenants with auto_update enabled.
     *
     * @param float|null $rate
     * @return array{success: bool, updated_count: int, message: string}
     */
    public function propagateEuroRateToTenants(?float $rate = null): array
    {
        $rate ??= $this->getCurrentEuroRate();

        Log::info('DollarRateService: Starting EUR propagation to tenants', ['rate' => $rate]);

        try {
            $tenants = Tenant::query()->where('status', 'active')->get();
            $updatedCount = 0;
            $now = Carbon::now()->toDateString();

            foreach ($tenants as $tenant) {
                $settings = $tenant->settings ?? [];
                $autoUpdate = data_get($settings, 'engine_settings.currency.auto_update', true);

                if (!$autoUpdate) {
                    continue;
                }

                data_set($settings, 'engine_settings.currency.euro_rate', $rate);
                data_set($settings, 'engine_settings.currency.euro_last_update', $now);

                $tenant->settings = $settings;
                $tenant->save();
                $updatedCount++;
            }

            Log::info('DollarRateService: EUR propagation completed', [
                'updated_count' => $updatedCount,
                'rate' => $rate,
            ]);

            return [
                'success' => true,
                'updated_count' => $updatedCount,
                'message' => "EUR rate propagated to {$updatedCount} tenants",
            ];
        } catch (Throwable $e) {
            Log::error('DollarRateService: Exception during EUR propagation', ['error' => $e->getMessage()]);

            return ['success' => false, 'updated_count' => 0, 'message' => "Exception: {$e->getMessage()}"];
        }
    }

    /**
     * Fetch and propagate both USD and EUR rates.
     *
     * @return array{success: bool, usd?: float, eur?: float, message: string}
     */
    public function fetchAndPropagateAll(): array
    {
        $usd = $this->fetchAndPropagate();
        $eur = $this->fetchAndStoreEuro();

        if ($eur['success']) {
            $this->propagateEuroRateToTenants($eur['rate']);
        }

        return [
            'success' => $usd['success'],
            'usd'     => $usd['rate'] ?? null,
            'eur'     => $eur['rate'] ?? null,
            'message' => "USD: {$usd['message']} | EUR: {$eur['message']}",
        ];
    }

    /**
     * Propagate current rate to all tenants with auto_update enabled.
     *
     * @param float|null $rate The rate to propagate. If null, uses current rate.
     * @return array{success: bool, updated_count: int, message: string}
     */
    public function propagateRateToTenants(?float $rate = null): array
    {
        $rate ??= $this->getCurrentRate();

        if ($rate === null) {
            Log::warning('DollarRateService: Cannot propagate - no rate available');

            return [
                'success' => false,
                'updated_count' => 0,
                'message' => 'No rate available to propagate',
            ];
        }

        Log::info('DollarRateService: Starting rate propagation to tenants', [
            'rate' => $rate,
        ]);

        try {
            $tenants = Tenant::query()
                ->where('status', 'active')
                ->get();

            $updatedCount = 0;
            $now = Carbon::now()->toDateString();

            foreach ($tenants as $tenant) {
                // Get current settings or initialize empty array
                $settings = $tenant->settings ?? [];

                // Check if tenant has auto_update enabled (default to true)
                $autoUpdate = data_get($settings, 'engine_settings.currency.auto_update', true);

                if (!$autoUpdate) {
                    Log::debug('DollarRateService: Skipping tenant (auto_update disabled)', [
                        'tenant_id' => $tenant->id,
                        'business_name' => $tenant->business_name,
                    ]);
                    continue;
                }

                // Update currency settings
                data_set($settings, 'engine_settings.currency.exchange_rate', $rate);
                data_set($settings, 'engine_settings.currency.source', 'dolarapi');
                data_set($settings, 'engine_settings.currency.last_update', $now);

                // Initialize default display settings if not present
                if (!data_get($settings, 'engine_settings.currency.display')) {
                    data_set($settings, 'engine_settings.currency.display', [
                        'mode' => 'toggle',
                        'default_currency' => 'REF',
                        'show_conversion_button' => true,
                        'symbols' => [
                            'reference' => 'REF',
                            'bolivares' => 'Bs.',
                        ],
                        'decimals' => 2,
                        'rounding' => false,
                    ]);
                }

                $tenant->settings = $settings;
                $tenant->save();
                $updatedCount++;

                Log::debug('DollarRateService: Tenant updated', [
                    'tenant_id' => $tenant->id,
                    'business_name' => $tenant->business_name,
                    'rate' => $rate,
                ]);
            }

            Log::info('DollarRateService: Rate propagation completed', [
                'total_tenants' => $tenants->count(),
                'updated_count' => $updatedCount,
                'rate' => $rate,
            ]);

            return [
                'success' => true,
                'updated_count' => $updatedCount,
                'message' => "Rate propagated to {$updatedCount} tenants",
            ];
        } catch (Throwable $e) {
            Log::error('DollarRateService: Exception during propagation', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'updated_count' => 0,
                'message' => "Exception: {$e->getMessage()}",
            ];
        }
    }

    /**
     * Get historical rates for a given number of days.
     *
     * @param int $days Number of days to retrieve (default: 30)
     * @return array<int, array{rate: float, source: string, date: string, is_active: bool}>
     */
    public function getHistoricalRates(int $days = 30): array
    {
        try {
            $rates = DollarRate::query()
                ->where('effective_from', '>=', Carbon::now()->subDays($days))
                ->orderByDesc('effective_from')
                ->get();

            Log::debug('DollarRateService: Historical rates retrieved', [
                'days' => $days,
                'count' => $rates->count(),
            ]);

            return $rates->map(function (DollarRate $rate): array {
                return [
                    'rate' => (float) $rate->rate,
                    'source' => $rate->source,
                    'date' => $rate->effective_from->toDateTimeString(),
                    'is_active' => $rate->is_active,
                ];
            })->toArray();
        } catch (Throwable $e) {
            Log::error('DollarRateService: Failed to get historical rates', [
                'error' => $e->getMessage(),
            ]);

            return [];
        }
    }

    /**
     * Force refresh the cached rate.
     *
     * @return float|null The refreshed rate
     */
    public function refreshCache(): ?float
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::EURO_CACHE_KEY);

        Log::info('DollarRateService: USD + EUR cache invalidated and refreshed');

        return $this->getCurrentRate();
    }

    /**
     * Get the last update timestamp.
     *
     * @return Carbon|null
     */
    public function getLastUpdateTime(): ?Carbon
    {
        $rate = DollarRate::query()
            ->where('currency_type', 'USD')
            ->where('is_active', true)
            ->orderByDesc('effective_from')
            ->first();

        return $rate?->effective_from;
    }

    /**
     * Check if the current rate is stale (older than cache TTL).
     *
     * @return bool
     */
    public function isRateStale(): bool
    {
        $lastUpdate = $this->getLastUpdateTime();

        if ($lastUpdate === null) {
            return true;
        }

        return $lastUpdate->addSeconds(self::CACHE_TTL)->isPast();
    }

    /**
     * Fetch and update: combines fetchAndStore and propagateRateToTenants.
     *
     * @return array{success: bool, rate?: float, updated_tenants?: int, message: string}
     */
    public function fetchAndPropagate(): array
    {
        $fetchResult = $this->fetchAndStore();

        if (!$fetchResult['success']) {
            return [
                'success' => false,
                'message' => $fetchResult['message'],
            ];
        }

        $propagateResult = $this->propagateRateToTenants($fetchResult['rate']);

        return [
            'success' => true,
            'rate' => $fetchResult['rate'],
            'updated_tenants' => $propagateResult['updated_count'],
            'message' => "Rate {$fetchResult['rate']} fetched and propagated to {$propagateResult['updated_count']} tenants",
        ];
    }
}

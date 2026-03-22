<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CurrencyFetcherService
{
    private const TIMEOUT         = 8;
    private const CONNECT_TIMEOUT = 5;

    /**
     * Fetch USD (BCV oficial) from all available sources in priority order.
     *
     * @return array{success: bool, rate: float|null, source: string}
     */
    public function fetchUSD(): array
    {
        // FUENTE 1: dolarapi
        try {
            $r = Http::timeout(self::TIMEOUT)
                ->connectTimeout(self::CONNECT_TIMEOUT)
                ->acceptJson()
                ->get('https://ve.dolarapi.com/v1/dolares/oficial');

            if ($r->successful()) {
                $rate = (float) ($r->json('promedio') ?? 0);
                if ($rate > 10 && $rate < 10000) {
                    Log::info('[CurrencyFetcher] USD OK via dolarapi', ['rate' => $rate]);
                    return ['success' => true, 'rate' => $rate, 'source' => 'dolarapi'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[CurrencyFetcher] USD FAIL dolarapi: ' . $e->getMessage());
        }

        // FUENTE 2: brecha-cambiaria
        try {
            $r = Http::timeout(self::TIMEOUT)
                ->connectTimeout(self::CONNECT_TIMEOUT)
                ->acceptJson()
                ->get('https://brecha-cambiaria.com/api/prices');

            if ($r->successful()) {
                $rate = (float) ($r->json('bcv_usd') ?? 0);
                if ($rate > 10 && $rate < 10000) {
                    Log::info('[CurrencyFetcher] USD OK via brecha-cambiaria', ['rate' => $rate]);
                    return ['success' => true, 'rate' => $rate, 'source' => 'brecha-cambiaria'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[CurrencyFetcher] USD FAIL brecha-cambiaria: ' . $e->getMessage());
        }

        Log::error('[CurrencyFetcher] USD FAIL todas las fuentes — se requiere tasa manual');
        return ['success' => false, 'rate' => null, 'source' => 'all_failed'];
    }

    /**
     * Fetch EUR (BCV oficial) from all available sources in priority order.
     *
     * @return array{success: bool, rate: float|null, source: string}
     */
    public function fetchEUR(): array
    {
        // FUENTE 1: dolarapi (array de monedas, buscar entrada cuya fuente sea 'euro')
        try {
            $r = Http::timeout(self::TIMEOUT)
                ->connectTimeout(self::CONNECT_TIMEOUT)
                ->acceptJson()
                ->get('https://ve.dolarapi.com/v1/dolares');

            if ($r->successful()) {
                $euro = collect($r->json())->firstWhere('fuente', 'euro');
                $rate = (float) ($euro['promedio'] ?? 0);
                if ($rate > 10 && $rate < 10000) {
                    Log::info('[CurrencyFetcher] EUR OK via dolarapi', ['rate' => $rate]);
                    return ['success' => true, 'rate' => $rate, 'source' => 'dolarapi'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[CurrencyFetcher] EUR FAIL dolarapi: ' . $e->getMessage());
        }

        // FUENTE 2: brecha-cambiaria
        try {
            $r = Http::timeout(self::TIMEOUT)
                ->connectTimeout(self::CONNECT_TIMEOUT)
                ->acceptJson()
                ->get('https://brecha-cambiaria.com/api/prices');

            if ($r->successful()) {
                $rate = (float) ($r->json('bcv_eur') ?? 0);
                if ($rate > 10 && $rate < 10000) {
                    Log::info('[CurrencyFetcher] EUR OK via brecha-cambiaria', ['rate' => $rate]);
                    return ['success' => true, 'rate' => $rate, 'source' => 'brecha-cambiaria'];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('[CurrencyFetcher] EUR FAIL brecha-cambiaria: ' . $e->getMessage());
        }

        Log::error('[CurrencyFetcher] EUR FAIL todas las fuentes');
        return ['success' => false, 'rate' => null, 'source' => 'all_failed'];
    }
}

<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use App\Models\CompanySetting;

class ParallelRateController extends Controller
{
    public function __invoke(): JsonResponse
    {
        $settings = CompanySetting::first();

        if ($settings?->parallel_rate_override) {
            return response()->json([
                'rate'   => (float) $settings->parallel_rate_override,
                'source' => 'manual',
            ]);
        }

        $rate = Cache::remember('parallel_rate', 1800, function () {
            $response = Http::timeout(5)->get('https://ve.dolarapi.com/v1/dolares');
            if ($response->successful()) {
                $paralelo = collect($response->json())
                    ->firstWhere('fuente', 'paralelo');
                return $paralelo['promedio'] ?? null;
            }
            return null;
        });

        return response()->json([
            'rate'   => $rate,
            'source' => 'api',
        ]);
    }
}

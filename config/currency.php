<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Fallback Rates
    |--------------------------------------------------------------------------
    | Used when the external API is unavailable and no rate exists in the DB.
    | Update these values periodically to stay close to the real market rate.
    |
    */
    'fallback_usd' => (float) env('CURRENCY_FALLBACK_USD', 36.50),
    'fallback_eur' => (float) env('CURRENCY_FALLBACK_EUR', 495.00),
];

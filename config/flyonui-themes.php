<?php

declare(strict_types=1);

/**
 * FlyonUI Themes Configuration
 *
 * Single source of truth for all 17 available themes.
 * Plan 1 (OPORTUNIDAD): 10 themes
 * Plan 2 (CRECIMIENTO): 7 additional themes
 *
 * Usage:
 *   config('flyonui-themes.all')          // All 17 themes
 *   config('flyonui-themes.plan1')        // Plan 1 themes only
 *   config('flyonui-themes.plan2_extra')  // Plan 2 additional themes
 */

return [
    // ══════════════════════════════════════════════════════════════════════
    // PLAN 1 (OPORTUNIDAD) - 10 Base Themes
    // ══════════════════════════════════════════════════════════════════════
    'plan1' => [
        'light',
        'ghibli',
        'gourmet',
        'perplexity',
        'soft',
        'dark',
        'shadcn',
        'vscode',
        'pastel',
        'corporate',
    ],

    // ══════════════════════════════════════════════════════════════════════
    // PLAN 2 (CRECIMIENTO) - 7 Additional Premium Themes
    // ══════════════════════════════════════════════════════════════════════
    'plan2_extra' => [
        'black',
        'luxury',
        'slack',
        'spotify',
        'mintlify',
        'claude',
        'valorant',
    ],

    // ══════════════════════════════════════════════════════════════════════
    // ALL 17 THEMES (combined: Plan 1 + Plan 2)
    // ══════════════════════════════════════════════════════════════════════
    'all' => [
        'light',
        'dark',
        'black',
        'claude',
        'corporate',
        'ghibli',
        'gourmet',
        'luxury',
        'mintlify',
        'pastel',
        'perplexity',
        'shadcn',
        'slack',
        'soft',
        'spotify',
        'valorant',
        'vscode',
    ],

    // ══════════════════════════════════════════════════════════════════════
    // PLAN REQUIREMENTS
    // ══════════════════════════════════════════════════════════════════════
    'by_plan' => [
        1 => [
            'light',
            'ghibli',
            'gourmet',
            'perplexity',
            'soft',
            'dark',
            'shadcn',
            'vscode',
            'pastel',
            'corporate',
        ],
        2 => [
            'light',
            'ghibli',
            'gourmet',
            'perplexity',
            'soft',
            'dark',
            'shadcn',
            'vscode',
            'pastel',
            'corporate',
            'black',
            'luxury',
            'slack',
            'spotify',
            'mintlify',
            'claude',
            'valorant',
        ],
        3 => [
            'light',
            'ghibli',
            'gourmet',
            'perplexity',
            'soft',
            'dark',
            'shadcn',
            'vscode',
            'pastel',
            'corporate',
            'black',
            'luxury',
            'slack',
            'spotify',
            'mintlify',
            'claude',
            'valorant',
        ],
    ],
];

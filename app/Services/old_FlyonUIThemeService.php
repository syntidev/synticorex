<?php

declare(strict_types=1);

namespace App\Services;

/**
 * FlyonUI Theme Service
 *
 * Centralized service for managing FlyonUI theme validation and retrieval.
 * Single source of truth: config('flyonui-themes')
 */
class FlyonUIThemeService
{
    /**
     * Get all available themes.
     *
     * @return array<int, string>
     */
    public static function getAllThemes(): array
    {
        return config('flyonui-themes.all', []);
    }

    /**
     * Get themes available for a specific plan.
     *
     * @param int $planId
     * @return array<int, string>
     */
    public static function getThemesByPlan(int $planId): array
    {
        return config("flyonui-themes.by_plan.{$planId}") ?? self::getAllThemes();
    }

    /**
     * Check if a theme is valid for a given plan.
     *
     * @param string $theme
     * @param int $planId
     * @return bool
     */
    public static function isValidTheme(string $theme, int $planId): bool
    {
        $themes = self::getThemesByPlan($planId);
        return in_array($theme, $themes, true);
    }

    /**
     * Get theme validation rule for a plan.
     *
     * @param int $planId
     * @return string
     */
    public static function getValidationRule(int $planId): string
    {
        $themes = self::getThemesByPlan($planId);
        return 'in:' . implode(',', $themes);
    }

    /**
     * Get Plan 1 themes (OPORTUNIDAD).
     *
     * @return array<int, string>
     */
    public static function getPlan1Themes(): array
    {
        return config('flyonui-themes.plan1', []);
    }

    /**
     * Get Plan 2 additional themes (CRECIMIENTO premium).
     *
     * @return array<int, string>
     */
    public static function getPlan2ExtraThemes(): array
    {
        return config('flyonui-themes.plan2_extra', []);
    }

    /**
     * Get default theme.
     *
     * @return string
     */
    public static function getDefaultTheme(): string
    {
        return 'light';
    }
}

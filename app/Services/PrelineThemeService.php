<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Preline Theme Service
 *
 * Centralized service for managing Preline theme validation and retrieval.
 * Single source of truth: config('preline-themes')
 */
class PrelineThemeService
{
    /**
     * Get all available themes.
     *
     * @return array<int, string>
     */
    public static function getAllThemes(): array
    {
        return config('preline-themes.all', []);
    }

    /**
     * Get themes available for a specific plan.
     *
     * @param int $planId
     * @return array<int, string>
     */
    public static function getThemesByPlan(int $planId): array
    {
        return config("preline-themes.by_plan.{$planId}") ?? self::getAllThemes();
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
     * Get default theme.
     *
     * @return string
     */
    public static function getDefaultTheme(): string
    {
        return 'default';
    }

    /**
     * Return the most appropriate theme_slug for a given business segment.
     * Uses the 7 canonical segment keys from the onboarding wizard.
     *
     * @param string $segment
     * @return string
     */
    public static function getThemeForSegment(string $segment): string
    {
        $map = [
            'restaurante'  => 'sabor-tradicional',
            'retail'       => 'default',
            'salud'        => 'azul-confianza',
            'profesional'  => 'azul-profesional',
            'tecnico'      => 'industrial-pro',
            'educacion'    => 'azul-academico',
            'transporte'   => 'default',
        ];

        return $map[$segment] ?? 'default';
    }
}

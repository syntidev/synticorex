<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Carbon\Carbon;

/**
 * BusinessHoursService - Gestión de horarios de negocio
 * 
 * Determina si un negocio está abierto/cerrado según su horario configurado
 * y calcula próximos horarios de apertura
 */
final class BusinessHoursService
{
    /**
     * Determina si el negocio está abierto en este momento
     * 
     * @param Tenant $tenant
     * @return bool
     */
    /** Venezuela timezone constant */
    private const TENANT_TZ = 'America/Caracas';

    /**
     * Normaliza business_hours: maneja tanto array como string (double-encoded JSON).
     */
    private function normalizeHours(mixed $raw): array
    {
        if (is_array($raw)) {
            return $raw;
        }
        if (is_string($raw) && $raw !== '') {
            $decoded = json_decode($raw, true);
            return is_array($decoded) ? $decoded : [];
        }
        return [];
    }

    public function isOpen(Tenant $tenant): bool
    {
        $businessHours = $this->normalizeHours($tenant->business_hours);
        if (empty($businessHours)) {
            return true;
        }

        $now = Carbon::now(self::TENANT_TZ);
        $currentDay = strtolower($now->locale('en')->dayName);
        $previousDay = strtolower($now->copy()->subDay()->locale('en')->dayName);
        $currentTime = $now->format('H:i');

        $todaySchedule = $businessHours[$currentDay] ?? null;
        if ($this->isDayEnabled($todaySchedule)) {
            $openTime = $todaySchedule['open'] ?? '09:00';
            $closeTime = $todaySchedule['close'] ?? '18:00';

            // Normal schedule (same-day close): 09:00 -> 18:00
            if ($openTime < $closeTime) {
                return $currentTime >= $openTime && $currentTime < $closeTime;
            }

            // Overnight schedule (crosses midnight): 20:00 -> 04:00
            // On current day, this branch only covers the "late" segment (>= open).
            if ($openTime > $closeTime && $currentTime >= $openTime) {
                return true;
            }
        }

        // Check if we are still inside yesterday's overnight tail: 00:00 -> close.
        $yesterdaySchedule = $businessHours[$previousDay] ?? null;
        if ($this->isDayEnabled($yesterdaySchedule)) {
            $yOpen = $yesterdaySchedule['open'] ?? '09:00';
            $yClose = $yesterdaySchedule['close'] ?? '18:00';

            if ($yOpen > $yClose && $currentTime < $yClose) {
                return true;
            }
        }

        return false;
    }

    /**
     * Obtiene el próximo horario de apertura cuando el negocio está cerrado
     * 
     * @param Tenant $tenant
     * @return string Mensaje descriptivo del próximo horario
     */
    public function getNextOpenTime(Tenant $tenant): string
    {
        $businessHours = $this->normalizeHours($tenant->business_hours);
        if (empty($businessHours)) {
            return 'Horario no configurado';
        }

        $now = Carbon::now(self::TENANT_TZ);
        $daysToCheck = 7; // Revisar próximos 7 días

        for ($i = 0; $i < $daysToCheck; $i++) {
            $checkDate = $now->copy()->addDays($i);
            $dayName = strtolower($checkDate->locale('en')->dayName);

            if (!isset($businessHours[$dayName]) || !$this->isDayEnabled($businessHours[$dayName])) {
                continue;
            }

            $openTime = $businessHours[$dayName]['open'] ?? '09:00';
            $openDateTime = $checkDate->copy()->setTimeFromTimeString($openTime);

            // Si es hoy y aún no ha llegado la hora de apertura
            if ($i === 0 && $now->lt($openDateTime)) {
                $hoursUntil = $now->diffInHours($openDateTime);
                if ($hoursUntil < 1) {
                    $minutesUntil = $now->diffInMinutes($openDateTime);
                    return "Abrimos en {$minutesUntil} minutos";
                }
                return "Abrimos hoy a las {$this->formatTime($openTime)}";
            }

            // Si es mañana
            if ($i === 1) {
                return "Abrimos mañana a las {$this->formatTime($openTime)}";
            }

            // Si es otro día
            if ($i > 1) {
                $dayNameEs = $this->getDayNameInSpanish($dayName);
                return "Abrimos el {$dayNameEs} a las {$this->formatTime($openTime)}";
            }
        }

        return 'Horario no disponible';
    }

    /**
     * Determina si un día está habilitado en el horario.
     * Soporta dos formatos:
     *  - Nuevo: null | {open, close} | {closed: true}
     *  - Legado: {enabled: true/false, open, close}
     *
     * @param mixed $dayData
     * @return bool
     */
    private function isDayEnabled(mixed $dayData): bool
    {
        if (!is_array($dayData)) {
            return false;
        }
        // Formato legado: clave 'enabled'
        if (array_key_exists('enabled', $dayData)) {
            return (bool) $dayData['enabled'];
        }
        // Formato nuevo: {closed: true} → cerrado
        if (!empty($dayData['closed'])) {
            return false;
        }
        // Formato nuevo: tiene open/close → abierto
        return isset($dayData['open'], $dayData['close']);
    }

    /**
     * Verifica si la funcionalidad de indicador de horario está habilitada
     * 
     * @param Tenant $tenant
     * @return bool
     */
    public function isHoursFeatureEnabled(Tenant $tenant): bool
    {
        return (bool) data_get(
            $tenant->settings,
            'engine_settings.features.show_hours_indicator',
            false
        );
    }

    /**
     * Formatea hora de 24h a 12h con AM/PM
     * 
     * @param string $time Hora en formato 24h (HH:mm)
     * @return string Hora formateada (h:mm A)
     */
    private function formatTime(string $time): string
    {
        try {
            return Carbon::createFromFormat('H:i', $time)->format('g:i A');
        } catch (\Exception $e) {
            return $time;
        }
    }

    /**
     * Traduce nombre de día al español
     * 
     * @param string $dayName Nombre del día en inglés (lowercase)
     * @return string Nombre del día en español
     */
    private function getDayNameInSpanish(string $dayName): string
    {
        $days = [
            'monday' => 'lunes',
            'tuesday' => 'martes',
            'wednesday' => 'miércoles',
            'thursday' => 'jueves',
            'friday' => 'viernes',
            'saturday' => 'sábado',
            'sunday' => 'domingo',
        ];

        return $days[$dayName] ?? $dayName;
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * QRService - Generación de códigos QR para tracking de tráfico web
 * 
 * Genera QR codes con shortlink único por tenant que redirige a la landing
 * y registra eventos de escaneo en analytics_events
 * 
 * Formato shortlink: https://syntiweb.me/t/{tenant_id}/{unique_code}
 */
final class QRService
{
    /**
     * Genera código único para el tenant (basado en ID + hash)
     * 
     * @param int $tenantId
     * @return string Código único de 8 caracteres
     */
    private function generateUniqueCode(int $tenantId): string
    {
        // Generar código único basado en tenant_id + timestamp + hash
        $raw = $tenantId . config('app.key') . 'qr_tracking';
        return substr(hash('sha256', $raw), 0, 8);
    }
    
    /**
     * Genera shortlink de tracking para el tenant
     * 
     * @param int $tenantId
     * @return string URL del shortlink
     */
    public function getTrackingShortlink(int $tenantId): string
    {
        $uniqueCode = $this->generateUniqueCode($tenantId);
        return "https://syntiweb.me/t/{$tenantId}/{$uniqueCode}";
    }
    
    /**
     * Genera QR SVG con shortlink de tracking
     * 
     * @param int $tenantId ID del tenant
     * @param int $size Tamaño del QR en píxeles (default: 300)
     * @return string SVG del código QR
     */
    public function generateQR(int $tenantId, int $size = 300): string
    {
        $trackingUrl = $this->getTrackingShortlink($tenantId);
        
        // Generar QR como SVG
        return (string) QrCode::size($size)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($trackingUrl);
    }
    
    /**
     * Genera QR PNG para descarga
     * 
     * @param int $tenantId ID del tenant
     * @param int $size Tamaño del QR en píxeles (default: 300)
     * @return string Contenido binario del PNG
     */
    public function generateQRPNG(int $tenantId, int $size = 300): string
    {
        $trackingUrl = $this->getTrackingShortlink($tenantId);
        
        // Generar QR como PNG
        return (string) QrCode::format('png')
            ->size($size)
            ->margin(1)
            ->errorCorrection('M')
            ->generate($trackingUrl);
    }
    
    /**
     * Verifica si el código único es válido para el tenant
     * 
     * @param int $tenantId
     * @param string $code
     * @return bool
     */
    public function verifyUniqueCode(int $tenantId, string $code): bool
    {
        return $this->generateUniqueCode($tenantId) === $code;
    }
}

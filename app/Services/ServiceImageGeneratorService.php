<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceImageGeneratorService
{
    /**
     * Generate a placeholder image for a service
     */
    public function generateServiceImage(
        int $tenantId,
        string $serviceName,
        string $segment
    ): string {
        $filename = 'service_' . Str::slug($serviceName) . '_' . Str::random(8) . '.jpg';
        $path = "tenants/{$tenantId}";

        // Ensure directory exists
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Generate color based on segment
        $color = $this->getColorForSegment($segment);

        // Create placeholder image using placeholder service
        $imageUrl = $this->generatePlaceholderUrl($serviceName, $color);

        // Download and save image
        $imageContent = @file_get_contents($imageUrl);

        if ($imageContent === false) {
            // Fallback: create a simple solid color image
            return $this->createFallbackImage($tenantId, $filename, $color);
        }

        Storage::disk('public')->put("{$path}/{$filename}", $imageContent);

        return $filename;
    }

    /**
     * Get color hex code based on business segment
     */
    private function getColorForSegment(string $segment): string
    {
        $colors = [
            'Tecnología' => '06B6D4',      // Cyan
            'Comercio' => 'F59E0B',        // Amber
            'Consultoría' => 'EC4899',     // Pink
        ];

        return $colors[$segment] ?? '6366F1'; // Indigo as default
    }

    /**
     * Generate placeholder image URL
     */
    private function generatePlaceholderUrl(string $serviceName, string $color): string
    {
        $encodedText = urlencode(substr($serviceName, 0, 20));
        return "https://placehold.co/400x300/{$color}/ffffff?text={$encodedText}";
    }

    /**
     * Create a fallback image if online service fails
     */
    private function createFallbackImage(
        int $tenantId,
        string $filename,
        string $color
    ): string {
        $path = "tenants/{$tenantId}";

        // Create a simple GD image
        $image = imagecreatetruecolor(400, 300);

        // Convert hex to RGB
        $rgb = sscanf($color, "%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Add text
        $text = "Service Image";
        imagestring($image, 5, 150, 140, $text, $textColor);

        // Save image
        ob_start();
        imagejpeg($image, null, 85);
        $imageContent = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put("{$path}/{$filename}", $imageContent);

        return $filename;
    }
}

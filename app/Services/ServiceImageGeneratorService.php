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
        string $segment,
        ?int $index = null
    ): string {
        // Use standard naming: service_01.webp, service_02.webp, etc.
        // If no index provided, count existing services
        if ($index === null) {
            $existingCount = \App\Models\Service::where('tenant_id', $tenantId)->count();
            $index = $existingCount + 1;
        }

        $filename = 'service_' . str_pad((string)$index, 2, '0', STR_PAD_LEFT) . '.webp';
        $path = "tenants/{$tenantId}";

        // Ensure directory exists
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Generate color based on segment
        $color = $this->getColorForSegment($segment);

        // Create placeholder image using placeholder service
        $imageUrl = $this->generatePlaceholderUrl($serviceName, $color);

        // Download and save image (convert to WebP)
        $imageContent = @file_get_contents($imageUrl);

        if ($imageContent === false) {
            // Fallback: create a simple solid color image
            return $this->createFallbackImage($tenantId, $filename, $color);
        }

        // Convert to WebP using Intervention Image
        try {
            $imageManager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $image = $imageManager->read($imageContent);
            $webpContent = $image->toWebp(80)->toString();
            Storage::disk('public')->put("{$path}/{$filename}", $webpContent);
        } catch (\Exception $e) {
            // Fallback to original format if conversion fails
            Storage::disk('public')->put("{$path}/{$filename}", $imageContent);
        }

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

        // Convert to WebP
        ob_start();
        imagejpeg($image, null, 85);
        $jpgContent = ob_get_clean();
        imagedestroy($image);

        // Convert JPEG to WebP
        try {
            $imageManager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
            $webpImage = $imageManager->read($jpgContent);
            $webpContent = $webpImage->toWebp(80)->toString();
            Storage::disk('public')->put("{$path}/{$filename}", $webpContent);
        } catch (\Exception $e) {
            // Fallback to JPEG if conversion fails
            Storage::disk('public')->put("{$path}/{$filename}", $jpgContent);
        }

        return $filename;
    }
}

<?php

declare(strict_types=1);

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductImageGeneratorService
{
    /**
     * Generate a placeholder image for a product
     * Uses placeholder image service to create realistic product mockups
     */
    public function generateProductImage(
        int $tenantId,
        string $productName,
        string $segment
    ): string {
        $filename = Str::slug($productName) . '_' . Str::random(8) . '.jpg';
        $path = "tenants/{$tenantId}";

        // Ensure directory exists
        if (!Storage::disk('public')->exists($path)) {
            Storage::disk('public')->makeDirectory($path);
        }

        // Generate color based on segment
        $color = $this->getColorForSegment($segment);

        // Create placeholder image using placeholder service
        $imageUrl = $this->generatePlaceholderUrl($productName, $color);

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
            'Tecnología' => '3B82F6',      // Blue
            'Comercio' => '10B981',         // Green
            'Consultoría' => '8B5CF6',      // Purple
        ];

        return $colors[$segment] ?? '6366F1'; // Indigo as default
    }

    /**
     * Generate placeholder image URL
     * Using a combination of services for better variety
     */
    private function generatePlaceholderUrl(string $productName, string $color): string
    {
        // Using placehold.co which provides high-quality placeholders
        $encodedText = urlencode(substr($productName, 0, 20));
        return "https://placehold.co/500x400/{$color}/ffffff?text={$encodedText}";
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
        $image = imagecreatetruecolor(500, 400);

        // Convert hex to RGB
        $rgb = sscanf($color, "%02x%02x%02x");
        $bgColor = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
        $textColor = imagecolorallocate($image, 255, 255, 255);

        imagefill($image, 0, 0, $bgColor);

        // Add text
        $text = "Product Image";
        imagestring($image, 5, 200, 190, $text, $textColor);

        // Save image
        ob_start();
        imagejpeg($image, null, 85);
        $imageContent = ob_get_clean();
        imagedestroy($image);

        Storage::disk('public')->put("{$path}/{$filename}", $imageContent);

        return $filename;
    }
}

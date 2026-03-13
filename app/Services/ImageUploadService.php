<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ImageUploadService
{
    private const MAX_FILE_SIZE = 2 * 1024 * 1024; // 2MB in bytes
    private const ALLOWED_MIME_TYPES = ['image/jpeg', 'image/png', 'image/webp'];
    private const MAX_WIDTHS = [
        'logo'    => 400,
        'hero'    => 1600,
        'product' => 1000,
        'service' => 1000,
        'hero-slot-1' => 1600,
        'hero-slot-2' => 1600,
        'hero-slot-3' => 1600,
        'hero-slot-4' => 1600,
        'hero-slot-5' => 1600,
    ];
    private const WEBP_QUALITY = 90;

    /**
     * Process and save uploaded image.
     *
     * @param UploadedFile $file
     * @param int $tenantId
     * @param string $type
     * @param int $index
     * @return string
     * @throws Exception
     */
    public function process(
        UploadedFile $file,
        int $tenantId,
        string $type,
        int $index = 1
    ): string {
        // Validate file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new Exception('El archivo excede el tamaño máximo permitido de 2MB');
        }

        // Validate mime type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new Exception('Tipo de archivo no permitido. Solo se aceptan imágenes JPEG, PNG o WebP');
        }

        // Create ImageManager with GD driver
        $manager = new ImageManager(new Driver());

        // Load image — getPathname() instead of getRealPath() (returns false on Windows)
        $image = $manager->read($file->getPathname());

        // Resize if width exceeds per-type maximum
        $maxWidth = self::MAX_WIDTHS[$type] ?? 1000;
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Generate filename based on type
        $filename = $this->generateFilename($type, $index);

        // Destination path
        $directory = storage_path("app/public/tenants/{$tenantId}");
        $filePath = "{$directory}/{$filename}";

        // Create directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Delete old file if exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Encode to WebP and save
        $image->toWebp(self::WEBP_QUALITY)->save($filePath);

        return $filename;
    }

    /**
     * Delete image file.
     *
     * @param int $tenantId
     * @param string $filename
     * @return void
     */
    public function delete(int $tenantId, string $filename): void
    {
        $filePath = storage_path("app/public/tenants/{$tenantId}/{$filename}");

        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Generate filename based on type and index.
     *
     * @param string $type
     * @param int $index
     * @return string
     */
    private function generateFilename(string $type, int $index): string
    {
        return match (true) {
            $type === 'logo' => 'logo.webp',
            $type === 'hero' => 'hero.webp',
            $type === 'product' => 'product_' . str_pad((string)$index, 2, '0', STR_PAD_LEFT) . '.webp',
            $type === 'service' => 'service_' . str_pad((string)$index, 2, '0', STR_PAD_LEFT) . '.webp',
            str_starts_with($type, 'hero-slot-') => 'hero_slot_' . substr($type, 10) . '.webp',
            default => throw new Exception("Tipo de imagen no válido: {$type}"),
        };
    }

    /**
     * Process and save uploaded image with a custom filename.
     * Used for gallery images where filename pattern differs from standard.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $tenantId
     * @param string $customFilename
     * @return string
     * @throws Exception
     */
    public function processWithCustomFilename(
        \Illuminate\Http\UploadedFile $file,
        int $tenantId,
        string $customFilename,
        int $maxWidth = 1000
    ): string {
        // Validate file size
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new Exception('El archivo excede el tamaño máximo permitido de 2MB');
        }

        // Validate mime type
        if (!in_array($file->getMimeType(), self::ALLOWED_MIME_TYPES)) {
            throw new Exception('Tipo de archivo no permitido. Solo se aceptan imágenes JPEG, PNG o WebP');
        }

        // Create ImageManager with GD driver
        $manager = new ImageManager(new Driver());

        // Load image — getPathname() instead of getRealPath() (returns false on Windows)
        $image = $manager->read($file->getPathname());

        // Resize if width exceeds maximum
        if ($image->width() > $maxWidth) {
            $image->scale(width: $maxWidth);
        }

        // Destination path
        $directory = storage_path("app/public/tenants/{$tenantId}");
        $filePath = "{$directory}/{$customFilename}";

        // Create directory if it doesn't exist
        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        // Delete old file if exists
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Encode to WebP and save
        $image->toWebp(self::WEBP_QUALITY)->save($filePath);

        return $customFilename;
    }
}

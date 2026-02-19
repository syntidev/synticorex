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
    private const MAX_WIDTH = 800;
    private const WEBP_QUALITY = 80;

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

        // Load image
        $image = $manager->read($file->getRealPath());

        // Resize if width exceeds maximum
        if ($image->width() > self::MAX_WIDTH) {
            $image->scale(width: self::MAX_WIDTH);
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
        return match ($type) {
            'logo' => 'logo.webp',
            'hero' => 'hero.webp',
            'product' => 'product_' . str_pad((string)$index, 2, '0', STR_PAD_LEFT) . '.webp',
            'service' => 'service_' . str_pad((string)$index, 2, '0', STR_PAD_LEFT) . '.webp',
            default => throw new Exception("Tipo de imagen no válido: {$type}"),
        };
    }
}

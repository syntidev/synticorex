<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Tenant;
use Illuminate\Support\Facades\Storage;
use InvalidArgumentException;
use RuntimeException;

/**
 * Servicio agnóstico de contenido por tenant.
 * Solo entrega y persiste datos; no conoce vistas ni presentación.
 * Contenido en: storage/tenants/{slug}.json (disco local).
 */
final class TenantContentService
{
    private const STORAGE_PATH = 'tenants';

    /**
     * Devuelve el contenido JSON del tenant. Si no existe archivo, devuelve estructura por defecto (estado Empty).
     * Lanza excepción si el archivo existe pero está corrupto (estado Error para el llamador).
     *
     * @return array<string, mixed> Contenido listo para consumo (settings, secciones, productos, servicios, etc.)
     *
     * @throws InvalidArgumentException Si el slug está vacío o no es válido para path.
     * @throws RuntimeException Si el archivo existe pero no se puede leer o el JSON es inválido.
     */
    public function get(string $slug): array
    {
        if ($this->isInvalidSlug($slug)) {
            throw new InvalidArgumentException('El slug del tenant no puede estar vacío ni contener caracteres no válidos.');
        }

        $path = $this->getPath($slug);

        if (!Storage::exists($path)) {
            return $this->getDefaultContent();
        }

        $raw = Storage::get($path);
        if ($raw === null || $raw === false) {
            throw new RuntimeException("No se pudo leer el contenido del tenant: {$slug}");
        }

        $decoded = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new RuntimeException(
                "Contenido JSON inválido para el tenant: {$slug}. " . json_last_error_msg()
            );
        }

        if (!is_array($decoded)) {
            throw new RuntimeException("El contenido del tenant debe ser un objeto JSON: {$slug}");
        }

        return $this->mergeWithDefault($decoded);
    }

    /**
     * Indica si existe un archivo de contenido persistido para el slug.
     * Útil para que el llamador distinga estado Empty (sin archivo) de Loaded.
     */
    public function exists(string $slug): bool
    {
        if ($this->isInvalidSlug($slug)) {
            return false;
        }
        return Storage::exists($this->getPath($slug));
    }

    /**
     * Guarda el contenido del tenant. Valida según plan, hace backup, sanitiza y escribe.
     *
     * @param array<string, mixed> $data
     *
     * @throws InvalidArgumentException
     * @throws \Illuminate\Validation\ValidationException
     */
    public function save(Tenant $tenant, array $data): void
    {
        if ($this->isInvalidSlug($tenant->slug)) {
            throw new InvalidArgumentException('El slug del tenant no es válido.');
        }

        $data = $this->sanitize($data);
        $path = $this->getPath($tenant->slug);

        $this->ensureTenantsDirectoryExists();
        Storage::put($path, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * Crea una copia de respaldo del contenido actual del tenant.
     */
    public function backup(string $slug): void
    {
        if ($this->isInvalidSlug($slug)) {
            return;
        }

        $content = $this->get($slug);
        $backupPath = sprintf(
            'backups/tenants/%s/%s.json',
            $slug,
            now()->format('Y-m-d-His')
        );
        Storage::put($backupPath, json_encode($content, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->pruneOldBackups($slug, 10);
    }

    /**
     * Ruta relativa al disco de storage para el JSON del tenant.
     * Ej: C:\laragon\www\synticorex\storage\app\private\tenants\mi-tenant.json (depende del disco).
     */
    private function getPath(string $slug): string
    {
        return self::STORAGE_PATH . '/' . $slug . '.json';
    }

    private function isInvalidSlug(string $slug): bool
    {
        $trimmed = trim($slug);
        if ($trimmed === '') {
            return true;
        }
        return (bool) preg_match('/[^a-zA-Z0-9\-_]/', $slug);
    }

    /**
     * Estructura por defecto cuando no hay archivo (estado Empty).
     *
     * @return array<string, mixed>
     */
    private function getDefaultContent(): array
    {
        return [
            'plan' => 'basico',
            'settings' => [
                'nombre_negocio' => 'Mi Negocio',
                'color_primario' => '#3B82F6',
                'color_secundario' => '#1E40AF',
                'logo' => '',
                'favicon' => '',
            ],
            'secciones' => [
                'header' => [
                    'nombre_sitio' => 'Mi Negocio',
                    'logo_url' => '',
                    'menu' => [],
                ],
                'hero' => [
                    'titulo' => 'Bienvenido',
                    'subtitulo' => 'Tu negocio online',
                    'imagen_fondo' => '',
                    'cta_texto' => 'Contáctanos',
                    'cta_link' => '#contacto',
                ],
                'hero_banner' => [
                    'titulo' => 'Bienvenido',
                    'subtitulo' => 'Tu negocio online',
                    'imagen_fondo' => '',
                    'cta_texto' => 'Contáctanos',
                    'cta_link' => '#contacto',
                ],
                'acerca' => [
                    'titulo' => 'Acerca de Nosotros',
                    'descripcion' => '',
                    'imagen' => '',
                ],
                'footer' => [
                    'direccion' => '',
                    'telefono' => '',
                    'email' => '',
                    'redes_sociales' => [
                        'instagram' => '',
                        'facebook' => '',
                        'whatsapp' => '',
                    ],
                ],
            ],
            'productos' => [],
            'servicios' => [],
            'faq' => [],
        ];
    }

    /**
     * Fusiona el contenido leído con la estructura por defecto para garantizar claves esperadas.
     *
     * @param array<string, mixed> $decoded
     * @return array<string, mixed>
     */
    private function mergeWithDefault(array $decoded): array
    {
        $default = $this->getDefaultContent();
        return array_replace_recursive($default, $decoded);
    }

    /**
     * Sanitiza strings para evitar XSS; solo permite etiquetas seguras.
     *
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    private function sanitize(array $data): array
    {
        $allowedTags = '<b><i><u><a><br><p><strong><em>';
        foreach ($data as $key => $value) {
            if (is_string($value)) {
                $data[$key] = strip_tags($value, $allowedTags);
                continue;
            }
            if (is_array($value)) {
                $data[$key] = $this->sanitize($value);
            }
        }
        return $data;
    }

    private function ensureTenantsDirectoryExists(): void
    {
        if (!Storage::exists(self::STORAGE_PATH)) {
            Storage::makeDirectory(self::STORAGE_PATH);
        }
    }

    private function pruneOldBackups(string $slug, int $keep): void
    {
        $dir = 'backups/tenants/' . $slug;
        if (!Storage::exists($dir)) {
            return;
        }

        $files = Storage::files($dir);
        if (count($files) <= $keep) {
            return;
        }

        usort($files, fn (string $a, string $b): int => strcmp($a, $b));
        $toDelete = array_slice($files, 0, count($files) - $keep);
        foreach ($toDelete as $file) {
            Storage::delete($file);
        }
    }
}

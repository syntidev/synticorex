<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Service;
use App\Models\Tenant;
use App\Services\ImageUploadService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ImageUploadController extends Controller
{
    /**
     * @param ImageUploadService $imageUploadService
     */
    public function __construct(
        private readonly ImageUploadService $imageUploadService
    ) {}

    /**
     * Upload logo image for tenant.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function uploadLogo(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Verify tenant exists and is active
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Validate image exists in request
            $request->validate([
                'image' => 'required|image'
            ]);

            $file = $request->file('image');

            // Process image
            $filename = $this->imageUploadService->process($file, $tenantId, 'logo');

            // Update tenant customization
            $customization = $tenant->customization;
            if ($customization) {
                $customization->update(['logo_filename' => $filename]);
            }

            $url = asset('storage/tenants/' . $tenantId . '/' . $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => $url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload hero image for tenant.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function uploadHero(Request $request, int $tenantId): JsonResponse
    {
        try {
            // Verify tenant exists and is active
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Validate image exists in request
            $request->validate([
                'image' => 'required|image'
            ]);

            $file = $request->file('image');

            // Process image
            $filename = $this->imageUploadService->process($file, $tenantId, 'hero');

            // Update tenant customization
            $customization = $tenant->customization;
            if ($customization) {
                $customization->update(['hero_filename' => $filename]);
            }

            $url = asset('storage/tenants/' . $tenantId . '/' . $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => $url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload product image.
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $productId
     * @return JsonResponse
     */
    public function uploadProduct(Request $request, int $tenantId, int $productId): JsonResponse
    {
        try {
            // Verify tenant exists and is active
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Find product and verify it belongs to tenant
            $product = Product::where('id', $productId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Validate image exists in request
            $request->validate([
                'image' => 'required|image'
            ]);

            $file = $request->file('image');

            // Calculate index: position + 1
            $index = $product->position ?? ($product->id % 99) + 1;

            // Delete old image if exists
            if ($product->image_filename) {
                $this->imageUploadService->delete($tenantId, $product->image_filename);
            }

            // Process image
            $filename = $this->imageUploadService->process($file, $tenantId, 'product', $index);

            // Update product
            $product->update(['image_filename' => $filename]);

            $url = asset('storage/tenants/' . $tenantId . '/' . $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => $url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload service image.
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $serviceId
     * @return JsonResponse
     */
    public function uploadService(Request $request, int $tenantId, int $serviceId): JsonResponse
    {
        try {
            // Verify tenant exists and is active
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Find service and verify it belongs to tenant
            $service = Service::where('id', $serviceId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Validate image exists in request
            $request->validate([
                'image' => 'required|image'
            ]);

            $file = $request->file('image');

            // Calculate index: position + 1
            $index = $service->position ?? ($service->id % 99) + 1;

            // Delete old image if exists
            if ($service->image_filename) {
                $this->imageUploadService->delete($tenantId, $service->image_filename);
            }

            // Process image
            $filename = $this->imageUploadService->process($file, $tenantId, 'service', $index);

            // Update service
            $service->update(['image_filename' => $filename]);

            $url = asset('storage/tenants/' . $tenantId . '/' . $filename);

            return response()->json([
                'success' => true,
                'filename' => $filename,
                'url' => $url
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Upload additional gallery image for product (Plan 3 / VISIÓN only).
     * Max 2 gallery images per product (+ main image = 3 total).
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $productId
     * @return JsonResponse
     */
    public function uploadProductGallery(Request $request, int $tenantId, int $productId): JsonResponse
    {
        try {
            // Verify tenant exists, is active, and is Plan 3
            $tenant = Tenant::with('plan')
                ->where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            if ((int) $tenant->plan_id !== 3) {
                return response()->json([
                    'success' => false,
                    'error' => 'La galería de imágenes solo está disponible en el Plan Visión'
                ], 403);
            }

            // Find product
            $product = Product::where('id', $productId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Check gallery limit (max 2 additional images)
            $currentGalleryCount = ProductImage::where('product_id', $productId)->count();
            if ($currentGalleryCount >= 2) {
                return response()->json([
                    'success' => false,
                    'error' => 'Máximo 2 imágenes adicionales por producto (3 total con la principal)'
                ], 422);
            }

            // Validate image
            $request->validate([
                'image' => 'required|image'
            ]);

            $file = $request->file('image');

            // Generate unique gallery filename
            $position = $currentGalleryCount; // 0 or 1
            $productIndex = $product->position ?? ($product->id % 99) + 1;
            $galleryFilename = 'product_' . str_pad((string) $productIndex, 2, '0', STR_PAD_LEFT)
                . '_gallery_' . ($position + 1) . '.webp';

            // Process image through ImageUploadService (manual process for custom filename)
            $directory = storage_path("app/public/tenants/{$tenantId}");
            if (!is_dir($directory)) {
                mkdir($directory, 0755, true);
            }

            // Use ImageUploadService internal logic (validate, resize, convert to webp)
            $filename = $this->imageUploadService->processWithCustomFilename(
                $file,
                $tenantId,
                $galleryFilename
            );

            // Create database record
            $productImage = ProductImage::create([
                'product_id' => $productId,
                'image_filename' => $filename,
                'position' => $position,
            ]);

            $url = asset('storage/tenants/' . $tenantId . '/' . $filename);

            return response()->json([
                'success' => true,
                'id' => $productImage->id,
                'filename' => $filename,
                'url' => $url,
                'position' => $position,
                'gallery_count' => $currentGalleryCount + 1,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }

    /**
     * Delete a gallery image from a product (Plan 3 only).
     *
     * @param int $tenantId
     * @param int $productId
     * @param int $imageId
     * @return JsonResponse
     */
    public function deleteProductGalleryImage(int $tenantId, int $productId, int $imageId): JsonResponse
    {
        try {
            // Verify tenant
            $tenant = Tenant::where('id', $tenantId)
                ->where('status', 'active')
                ->firstOrFail();

            // Find product
            $product = Product::where('id', $productId)
                ->where('tenant_id', $tenantId)
                ->firstOrFail();

            // Find gallery image
            $galleryImage = ProductImage::where('id', $imageId)
                ->where('product_id', $productId)
                ->firstOrFail();

            // Delete file from disk
            $this->imageUploadService->delete($tenantId, $galleryImage->image_filename);

            // Delete DB record
            $galleryImage->delete();

            // Reorder remaining gallery images
            $remaining = ProductImage::where('product_id', $productId)
                ->orderBy('position')
                ->get();

            foreach ($remaining as $index => $img) {
                $img->update(['position' => $index]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Imagen eliminada correctamente',
                'gallery_count' => $remaining->count(),
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 422);
        }
    }
}

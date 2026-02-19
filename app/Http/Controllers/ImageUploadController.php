<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
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
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductController extends Controller
{
    /**
     * Display a listing of products for a tenant.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function index(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $query = Product::query()
                ->where('tenant_id', $tenantId)
                ->with('tenant:id,business_name,subdomain,settings');

            // Filter by active status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
            }

            // Filter by featured
            if ($request->has('is_featured')) {
                $query->where('is_featured', $request->boolean('is_featured'));
            }

            // Filter by badge
            if ($request->has('badge')) {
                $query->where('badge', $request->input('badge'));
            }

            // Search by name
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where('name', 'like', "%{$search}%");
            }

            // Order by position ASC by default
            $query->orderBy('position')->orderByDesc('created_at');

            // Pagination
            $perPage = min($request->input('per_page', 15), 100);
            $products = $query->paginate($perPage);

            // Calculate price_bs for each product
            $exchangeRate = $this->getExchangeRate($tenant);
            $products->getCollection()->transform(function ($product) use ($exchangeRate) {
                $product->price_bs_calculated = $this->calculatePriceBs($product->price_usd, $exchangeRate);
                $product->exchange_rate = $exchangeRate;
                return $product;
            });

            Log::debug('ProductController: Listing products', [
                'tenant_id' => $tenantId,
                'total' => $products->total(),
            ]);

            return $this->successResponse($products, 'Products retrieved successfully');
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to list products', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve products');
        }
    }

    /**
     * Display the specified product.
     *
     * @param int $tenantId
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $tenantId, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $product = Product::with('tenant:id,business_name,subdomain,settings')
                ->where('tenant_id', $tenantId)
                ->find($id);

            if ($product === null) {
                return $this->notFoundResponse('Product not found');
            }

            // Calculate price_bs
            $exchangeRate = $this->getExchangeRate($tenant);
            $product->price_bs_calculated = $this->calculatePriceBs($product->price_usd, $exchangeRate);
            $product->exchange_rate = $exchangeRate;

            Log::debug('ProductController: Product retrieved', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
            ]);

            return $this->successResponse($product, 'Product retrieved successfully');
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to retrieve product', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve product');
        }
    }

    /**
     * Store a newly created product.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function store(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            // Check product limit based on plan
            $plan = $tenant->plan;
            $currentCount = Product::where('tenant_id', $tenantId)->count();

            if ($plan && $currentCount >= $plan->products_limit) {
                Log::warning('ProductController: Product limit reached', [
                    'tenant_id' => $tenantId,
                    'current_count' => $currentCount,
                    'limit' => $plan->products_limit,
                ]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => "Has alcanzado el límite de {$plan->products_limit} productos para el plan {$plan->name}",
                    'upgrade_url' => 'https://syntiweb.com/planes',
                ], 403);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:2000'],
                'price_usd' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
                'image_filename' => ['nullable', 'string', 'max:255'],
                'position' => ['nullable', 'integer', 'min:0', 'max:255'],
                'is_active' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'badge' => ['nullable', 'string', 'in:popular,nuevo,promo,destacado'],
            ]);

            // Set tenant_id
            $validated['tenant_id'] = $tenantId;

            // Set defaults
            $validated['position'] ??= $this->getNextPosition($tenantId);
            $validated['is_active'] ??= true;
            $validated['is_featured'] ??= false;

            $product = Product::create($validated);

            // Load tenant relationship
            $product->load('tenant:id,business_name,subdomain,settings');

            // Calculate price_bs
            $exchangeRate = $this->getExchangeRate($tenant);
            $product->price_bs_calculated = $this->calculatePriceBs($product->price_usd, $exchangeRate);
            $product->exchange_rate = $exchangeRate;

            Log::info('ProductController: Product created', [
                'tenant_id' => $tenantId,
                'product_id' => $product->id,
                'name' => $product->name,
            ]);

            return $this->successResponse($product, 'Product created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ProductController: Validation failed on store', [
                'tenant_id' => $tenantId,
                'errors' => $e->errors(),
            ]);

            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to create product', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to create product');
        }
    }

    /**
     * Update the specified product.
     *
     * @param Request $request
     * @param int $tenantId
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $tenantId, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $product = Product::where('tenant_id', $tenantId)->find($id);

            if ($product === null) {
                return $this->notFoundResponse('Product not found');
            }

            $validated = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:2000'],
                'price_usd' => ['sometimes', 'required', 'numeric', 'min:0.01', 'max:999999.99'],
                'image_filename' => ['nullable', 'string', 'max:255'],
                'position' => ['nullable', 'integer', 'min:0', 'max:255'],
                'is_active' => ['nullable', 'boolean'],
                'is_featured' => ['nullable', 'boolean'],
                'badge' => ['nullable', 'string', 'in:popular,nuevo,promo,destacado'],
            ]);

            $product->update($validated);

            // Load tenant relationship
            $product->load('tenant:id,business_name,subdomain,settings');

            // Calculate price_bs
            $exchangeRate = $this->getExchangeRate($tenant);
            $product->price_bs_calculated = $this->calculatePriceBs($product->price_usd, $exchangeRate);
            $product->exchange_rate = $exchangeRate;

            Log::info('ProductController: Product updated', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'updated_fields' => array_keys($validated),
            ]);

            return $this->successResponse($product, 'Product updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ProductController: Validation failed on update', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'errors' => $e->errors(),
            ]);

            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to update product', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to update product');
        }
    }

    /**
     * Remove the specified product.
     *
     * @param int $tenantId
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $tenantId, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $product = Product::where('tenant_id', $tenantId)->find($id);

            if ($product === null) {
                return $this->notFoundResponse('Product not found');
            }

            $productName = $product->name;
            $product->delete();

            Log::info('ProductController: Product deleted', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'name' => $productName,
            ]);

            return $this->successResponse([
                'id' => $id,
                'name' => $productName,
            ], 'Product deleted successfully');
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to delete product', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to delete product');
        }
    }

    /**
     * Toggle product active status.
     *
     * @param int $tenantId
     * @param int $id
     * @return JsonResponse
     */
    public function toggleActive(int $tenantId, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $product = Product::where('tenant_id', $tenantId)->find($id);

            if ($product === null) {
                return $this->notFoundResponse('Product not found');
            }

            $product->is_active = !$product->is_active;
            $product->save();

            Log::info('ProductController: Product active status toggled', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'is_active' => $product->is_active,
            ]);

            return $this->successResponse([
                'id' => $product->id,
                'name' => $product->name,
                'is_active' => $product->is_active,
            ], $product->is_active ? 'Product activated' : 'Product deactivated');
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to toggle product status', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to toggle product status');
        }
    }

    /**
     * Toggle product featured status.
     *
     * @param int $tenantId
     * @param int $id
     * @return JsonResponse
     */
    public function toggleFeatured(int $tenantId, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $product = Product::where('tenant_id', $tenantId)->find($id);

            if ($product === null) {
                return $this->notFoundResponse('Product not found');
            }

            $product->is_featured = !$product->is_featured;
            $product->save();

            Log::info('ProductController: Product featured status toggled', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'is_featured' => $product->is_featured,
            ]);

            return $this->successResponse([
                'id' => $product->id,
                'name' => $product->name,
                'is_featured' => $product->is_featured,
            ], $product->is_featured ? 'Product marked as featured' : 'Product unmarked as featured');
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to toggle product featured status', [
                'tenant_id' => $tenantId,
                'product_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to toggle product featured status');
        }
    }

    /**
     * Update products positions (bulk reorder).
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function reorder(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            $validated = $request->validate([
                'positions' => ['required', 'array'],
                'positions.*.id' => ['required', 'integer', 'exists:products,id'],
                'positions.*.position' => ['required', 'integer', 'min:0', 'max:255'],
            ]);

            foreach ($validated['positions'] as $item) {
                Product::where('id', $item['id'])
                    ->where('tenant_id', $tenantId)
                    ->update(['position' => $item['position']]);
            }

            Log::info('ProductController: Products reordered', [
                'tenant_id' => $tenantId,
                'count' => count($validated['positions']),
            ]);

            return $this->successResponse([
                'updated_count' => count($validated['positions']),
            ], 'Products reordered successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ProductController: Failed to reorder products', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to reorder products');
        }
    }

    /**
     * Get exchange rate from tenant settings.
     *
     * @param Tenant $tenant
     * @return float
     */
    private function getExchangeRate(Tenant $tenant): float
    {
        $settings = $tenant->settings ?? [];
        return (float) data_get($settings, 'engine_settings.currency.exchange_rate', 36.50);
    }

    /**
     * Calculate price in Bs from USD.
     *
     * @param float|string|null $priceUsd
     * @param float $exchangeRate
     * @return float|null
     */
    private function calculatePriceBs(float|string|null $priceUsd, float $exchangeRate): ?float
    {
        if ($priceUsd === null) {
            return null;
        }

        return round((float) $priceUsd * $exchangeRate, 2);
    }

    /**
     * Get next position for new product.
     *
     * @param int $tenantId
     * @return int
     */
    private function getNextPosition(int $tenantId): int
    {
        $maxPosition = Product::where('tenant_id', $tenantId)->max('position');
        return ($maxPosition ?? 0) + 1;
    }

    /**
     * Return success JSON response.
     *
     * @param mixed $data
     * @param string $message
     * @param int $statusCode
     * @return JsonResponse
     */
    private function successResponse(mixed $data, string $message, int $statusCode = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $data,
            'message' => $message,
        ], $statusCode);
    }

    /**
     * Return not found JSON response.
     *
     * @param string $message
     * @return JsonResponse
     */
    private function notFoundResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
        ], 404);
    }

    /**
     * Return validation error JSON response.
     *
     * @param array $errors
     * @return JsonResponse
     */
    private function validationErrorResponse(array $errors): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => 'Validation failed',
            'errors' => $errors,
        ], 422);
    }

    /**
     * Return error JSON response.
     *
     * @param string $message
     * @return JsonResponse
     */
    private function errorResponse(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'data' => null,
            'message' => $message,
        ], 500);
    }
}

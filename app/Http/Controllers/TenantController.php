<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Throwable;

class TenantController extends Controller
{
    /**
     * Display a listing of tenants.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $query = Tenant::query()
                ->with(['plan:id,name,slug', 'user:id,name,email', 'colorPalette:id,name,slug']);

            // Filter by status
            if ($request->has('status')) {
                $query->where('status', $request->input('status'));
            }

            // Filter by plan
            if ($request->has('plan_id')) {
                $query->where('plan_id', $request->input('plan_id'));
            }

            // Search by business name or subdomain
            if ($request->has('search')) {
                $search = $request->input('search');
                $query->where(function ($q) use ($search) {
                    $q->where('business_name', 'like', "%{$search}%")
                      ->orWhere('subdomain', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            // Pagination
            $perPage = min($request->input('per_page', 15), 100);
            $tenants = $query->orderByDesc('created_at')->paginate($perPage);

            Log::debug('TenantController: Listing tenants', [
                'total' => $tenants->total(),
                'filters' => $request->only(['status', 'plan_id', 'search']),
            ]);

            return response()->json([
                'success' => true,
                'data' => $tenants,
                'message' => 'Tenants retrieved successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to list tenants', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve tenants',
            ], 500);
        }
    }

    /**
     * Display the specified tenant.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $tenant = Tenant::with([
                'plan',
                'user:id,name,email',
                'colorPalette',
                'customization',
                'products' => fn($q) => $q->where('is_active', true)->orderBy('position'),
                'services' => fn($q) => $q->where('is_active', true)->orderBy('position'),
            ])->find($id);

            if ($tenant === null) {
                Log::warning('TenantController: Tenant not found', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Tenant not found',
                ], 404);
            }

            Log::debug('TenantController: Tenant retrieved', [
                'id' => $id,
                'business_name' => $tenant->business_name,
            ]);

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'message' => 'Tenant retrieved successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to retrieve tenant', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve tenant',
            ], 500);
        }
    }

    /**
     * Store a newly created tenant.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                // Required fields
                'business_name' => ['required', 'string', 'max:255'],
                'subdomain' => ['required', 'string', 'max:50', 'unique:tenants,subdomain', 'regex:/^[a-z0-9-]+$/'],
                'plan_id' => ['required', 'exists:plans,id'],
                'email' => ['required', 'email', 'max:255'],
                'user_id' => ['required', 'exists:users,id'],
                'edit_pin' => ['required', 'string', 'min:4', 'max:255'],

                // Optional fields
                'base_domain' => ['nullable', 'string', 'max:100'],
                'custom_domain' => ['nullable', 'string', 'max:255', 'unique:tenants,custom_domain'],
                'business_segment' => ['nullable', 'string', 'max:50'],
                'slogan' => ['nullable', 'string', 'max:500'],
                'description' => ['nullable', 'string', 'max:2000'],
                'phone' => ['nullable', 'string', 'max:20'],
                'whatsapp_sales' => ['nullable', 'string', 'max:20'],
                'whatsapp_support' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
                'city' => ['nullable', 'string', 'max:100'],
                'country' => ['nullable', 'string', 'max:100'],
                'business_hours' => ['nullable', 'array'],
                'is_open' => ['nullable', 'boolean'],
                'currency_display' => ['nullable', 'string', 'in:usd,bs,both'],
                'color_palette_id' => ['nullable', 'exists:color_palettes,id'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
                'meta_keywords' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', 'string', 'in:active,suspended,cancelled'],
                'trial_ends_at' => ['nullable', 'date'],
                'subscription_ends_at' => ['nullable', 'date'],
                'settings' => ['nullable', 'array'],
            ]);

            // Set defaults
            $validated['base_domain'] ??= 'menu.vip';
            $validated['country'] ??= 'Venezuela';
            $validated['currency_display'] ??= 'both';
            $validated['status'] ??= 'active';
            $validated['is_open'] ??= true;
            $validated['color_palette_id'] ??= 1;

            // Hash edit_pin if provided as plain text
            if (strlen($validated['edit_pin']) <= 6) {
                $validated['edit_pin'] = bcrypt($validated['edit_pin']);
            }

            $tenant = Tenant::create($validated);

            // Load relationships for response
            $tenant->load(['plan:id,name,slug', 'user:id,name,email', 'colorPalette:id,name,slug']);

            Log::info('TenantController: Tenant created', [
                'id' => $tenant->id,
                'business_name' => $tenant->business_name,
                'subdomain' => $tenant->subdomain,
            ]);

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'message' => 'Tenant created successfully',
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('TenantController: Validation failed on store', [
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to create tenant', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to create tenant',
            ], 500);
        }
    }

    /**
     * Update the specified tenant.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($id);

            if ($tenant === null) {
                Log::warning('TenantController: Tenant not found for update', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Tenant not found',
                ], 404);
            }

            $validated = $request->validate([
                // Updatable fields
                'business_name' => ['sometimes', 'required', 'string', 'max:255'],
                'subdomain' => ['sometimes', 'required', 'string', 'max:50', Rule::unique('tenants')->ignore($id), 'regex:/^[a-z0-9-]+$/'],
                'plan_id' => ['sometimes', 'required', 'exists:plans,id'],
                'email' => ['sometimes', 'required', 'email', 'max:255'],
                'user_id' => ['sometimes', 'required', 'exists:users,id'],

                // Optional fields
                'base_domain' => ['nullable', 'string', 'max:100'],
                'custom_domain' => ['nullable', 'string', 'max:255', Rule::unique('tenants')->ignore($id)],
                'domain_verified' => ['nullable', 'boolean'],
                'business_segment' => ['nullable', 'string', 'max:50'],
                'slogan' => ['nullable', 'string', 'max:500'],
                'description' => ['nullable', 'string', 'max:2000'],
                'phone' => ['nullable', 'string', 'max:20'],
                'whatsapp_sales' => ['nullable', 'string', 'max:20'],
                'whatsapp_support' => ['nullable', 'string', 'max:20'],
                'address' => ['nullable', 'string', 'max:500'],
                'city' => ['nullable', 'string', 'max:100'],
                'country' => ['nullable', 'string', 'max:100'],
                'business_hours' => ['nullable', 'array'],
                'is_open' => ['nullable', 'boolean'],
                'edit_pin' => ['nullable', 'string', 'min:4', 'max:255'],
                'currency_display' => ['nullable', 'string', 'in:usd,bs,both'],
                'color_palette_id' => ['nullable', 'exists:color_palettes,id'],
                'meta_title' => ['nullable', 'string', 'max:255'],
                'meta_description' => ['nullable', 'string', 'max:500'],
                'meta_keywords' => ['nullable', 'string', 'max:255'],
                'status' => ['nullable', 'string', 'in:active,suspended,cancelled'],
                'trial_ends_at' => ['nullable', 'date'],
                'subscription_ends_at' => ['nullable', 'date'],
                'settings' => ['nullable', 'array'],
            ]);

            // Hash edit_pin if provided as plain text
            if (isset($validated['edit_pin']) && strlen($validated['edit_pin']) <= 6) {
                $validated['edit_pin'] = bcrypt($validated['edit_pin']);
            }

            $tenant->update($validated);

            // Load relationships for response
            $tenant->load(['plan:id,name,slug', 'user:id,name,email', 'colorPalette:id,name,slug']);

            Log::info('TenantController: Tenant updated', [
                'id' => $tenant->id,
                'business_name' => $tenant->business_name,
                'updated_fields' => array_keys($validated),
            ]);

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'message' => 'Tenant updated successfully',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('TenantController: Validation failed on update', [
                'id' => $id,
                'errors' => $e->errors(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to update tenant', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to update tenant',
            ], 500);
        }
    }

    /**
     * Remove the specified tenant.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($id);

            if ($tenant === null) {
                Log::warning('TenantController: Tenant not found for deletion', ['id' => $id]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Tenant not found',
                ], 404);
            }

            $businessName = $tenant->business_name;
            $subdomain = $tenant->subdomain;

            // Delete tenant (cascades to related records via FK)
            $tenant->delete();

            Log::info('TenantController: Tenant deleted', [
                'id' => $id,
                'business_name' => $businessName,
                'subdomain' => $subdomain,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $id,
                    'business_name' => $businessName,
                ],
                'message' => 'Tenant deleted successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to delete tenant', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to delete tenant',
            ], 500);
        }
    }

    /**
     * Get tenant by subdomain (public endpoint for landing pages).
     *
     * @param string $subdomain
     * @return JsonResponse
     */
    public function showBySubdomain(string $subdomain): JsonResponse
    {
        try {
            $tenant = Tenant::with([
                'plan:id,name,slug,products_limit,services_limit,show_dollar_rate,show_payment_methods,show_faq',
                'colorPalette',
                'customization',
                'products' => fn($q) => $q->where('is_active', true)->orderBy('position'),
                'services' => fn($q) => $q->where('is_active', true)->orderBy('position'),
            ])
            ->where('subdomain', $subdomain)
            ->where('status', 'active')
            ->first();

            if ($tenant === null) {
                Log::debug('TenantController: Tenant not found by subdomain', [
                    'subdomain' => $subdomain,
                ]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Tenant not found or inactive',
                ], 404);
            }

            // Hide sensitive fields for public endpoint
            $tenant->makeHidden(['edit_pin', 'user_id', 'settings']);

            Log::debug('TenantController: Tenant retrieved by subdomain', [
                'subdomain' => $subdomain,
                'business_name' => $tenant->business_name,
            ]);

            return response()->json([
                'success' => true,
                'data' => $tenant,
                'message' => 'Tenant retrieved successfully',
            ]);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to retrieve tenant by subdomain', [
                'subdomain' => $subdomain,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve tenant',
            ], 500);
        }
    }

    /**
     * Toggle tenant status (active/suspended).
     *
     * @param int $id
     * @return JsonResponse
     */
    public function toggleStatus(int $id): JsonResponse
    {
        try {
            $tenant = Tenant::find($id);

            if ($tenant === null) {
                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => 'Tenant not found',
                ], 404);
            }

            $newStatus = $tenant->status === 'active' ? 'suspended' : 'active';
            $tenant->update(['status' => $newStatus]);

            Log::info('TenantController: Tenant status toggled', [
                'id' => $id,
                'old_status' => $tenant->status === 'active' ? 'suspended' : 'active',
                'new_status' => $newStatus,
            ]);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $tenant->id,
                    'status' => $newStatus,
                ],
                'message' => "Tenant status changed to {$newStatus}",
            ]);
        } catch (Throwable $e) {
            Log::error('TenantController: Failed to toggle tenant status', [
                'id' => $id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to toggle tenant status',
            ], 500);
        }
    }
}

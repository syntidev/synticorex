<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\Tenant;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class ServiceController extends Controller
{
    /**
     * Display a listing of services for a tenant.
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

            $query = Service::query()
                ->where('tenant_id', $tenantId)
                ->with('tenant:id,business_name,subdomain');

            // Filter by active status
            if ($request->has('is_active')) {
                $query->where('is_active', $request->boolean('is_active'));
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
            $services = $query->paginate($perPage);

            Log::debug('ServiceController: Listing services', [
                'tenant_id' => $tenantId,
                'total' => $services->total(),
            ]);

            return $this->successResponse($services, 'Services retrieved successfully');
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to list services', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve services');
        }
    }

    /**
     * Display the specified service.
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

            $service = Service::with('tenant:id,business_name,subdomain')
                ->where('tenant_id', $tenantId)
                ->find($id);

            if ($service === null) {
                return $this->notFoundResponse('Service not found');
            }

            Log::debug('ServiceController: Service retrieved', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
            ]);

            return $this->successResponse($service, 'Service retrieved successfully');
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to retrieve service', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to retrieve service');
        }
    }

    /**
     * Store a newly created service.
     *
     * @param Request $request
     * @param int $tenantId
     * @return JsonResponse
     */
    public function store(Request $request, int $tenantId): JsonResponse
    {
        try {
            $tenant = Tenant::with('plan')->find($tenantId);

            if ($tenant === null) {
                return $this->notFoundResponse('Tenant not found');
            }

            // Check service limit based on plan
            $plan = $tenant->plan;
            $currentCount = Service::where('tenant_id', $tenantId)->count();

            if ($plan && $currentCount >= $plan->services_limit) {
                Log::warning('ServiceController: Service limit reached', [
                    'tenant_id' => $tenantId,
                    'current_count' => $currentCount,
                    'limit' => $plan->services_limit,
                ]);

                return response()->json([
                    'success' => false,
                    'data' => null,
                    'message' => "Service limit reached ({$plan->services_limit} services for {$plan->name} plan)",
                ], 422);
            }

            $validated = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:2000'],
                'icon_name' => ['nullable', 'string', 'max:50'],
                'image_filename' => ['nullable', 'string', 'max:255'],
                'overlay_text' => ['nullable', 'string', 'max:100'],
                'cta_text' => ['nullable', 'string', 'max:50'],
                'cta_link' => ['nullable', 'string', 'max:500'],
                'position' => ['nullable', 'integer', 'min:0', 'max:255'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            // Set tenant_id
            $validated['tenant_id'] = $tenantId;

            // Set defaults
            $validated['position'] ??= $this->getNextPosition($tenantId);
            $validated['is_active'] ??= true;
            $validated['cta_text'] ??= 'Más información';

            $service = Service::create($validated);

            // Load tenant relationship
            $service->load('tenant:id,business_name,subdomain');

            Log::info('ServiceController: Service created', [
                'tenant_id' => $tenantId,
                'service_id' => $service->id,
                'name' => $service->name,
            ]);

            return $this->successResponse($service, 'Service created successfully', 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ServiceController: Validation failed on store', [
                'tenant_id' => $tenantId,
                'errors' => $e->errors(),
            ]);

            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to create service', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to create service');
        }
    }

    /**
     * Update the specified service.
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

            $service = Service::where('tenant_id', $tenantId)->find($id);

            if ($service === null) {
                return $this->notFoundResponse('Service not found');
            }

            $validated = $request->validate([
                'name' => ['sometimes', 'required', 'string', 'max:255'],
                'description' => ['nullable', 'string', 'max:2000'],
                'icon_name' => ['nullable', 'string', 'max:50'],
                'image_filename' => ['nullable', 'string', 'max:255'],
                'overlay_text' => ['nullable', 'string', 'max:100'],
                'cta_text' => ['nullable', 'string', 'max:50'],
                'cta_link' => ['nullable', 'string', 'max:500'],
                'position' => ['nullable', 'integer', 'min:0', 'max:255'],
                'is_active' => ['nullable', 'boolean'],
            ]);

            $service->update($validated);

            // Load tenant relationship
            $service->load('tenant:id,business_name,subdomain');

            Log::info('ServiceController: Service updated', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'updated_fields' => array_keys($validated),
            ]);

            return $this->successResponse($service, 'Service updated successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('ServiceController: Validation failed on update', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'errors' => $e->errors(),
            ]);

            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to update service', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to update service');
        }
    }

    /**
     * Remove the specified service.
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

            $service = Service::where('tenant_id', $tenantId)->find($id);

            if ($service === null) {
                return $this->notFoundResponse('Service not found');
            }

            $serviceName = $service->name;
            $service->delete();

            Log::info('ServiceController: Service deleted', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'name' => $serviceName,
            ]);

            return $this->successResponse([
                'id' => $id,
                'name' => $serviceName,
            ], 'Service deleted successfully');
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to delete service', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to delete service');
        }
    }

    /**
     * Toggle service active status.
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

            $service = Service::where('tenant_id', $tenantId)->find($id);

            if ($service === null) {
                return $this->notFoundResponse('Service not found');
            }

            $service->is_active = !$service->is_active;
            $service->save();

            Log::info('ServiceController: Service active status toggled', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'is_active' => $service->is_active,
            ]);

            return $this->successResponse([
                'id' => $service->id,
                'name' => $service->name,
                'is_active' => $service->is_active,
            ], $service->is_active ? 'Service activated' : 'Service deactivated');
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to toggle service status', [
                'tenant_id' => $tenantId,
                'service_id' => $id,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to toggle service status');
        }
    }

    /**
     * Update services positions (bulk reorder).
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
                'positions.*.id' => ['required', 'integer', 'exists:services,id'],
                'positions.*.position' => ['required', 'integer', 'min:0', 'max:255'],
            ]);

            foreach ($validated['positions'] as $item) {
                Service::where('id', $item['id'])
                    ->where('tenant_id', $tenantId)
                    ->update(['position' => $item['position']]);
            }

            Log::info('ServiceController: Services reordered', [
                'tenant_id' => $tenantId,
                'count' => count($validated['positions']),
            ]);

            return $this->successResponse([
                'updated_count' => count($validated['positions']),
            ], 'Services reordered successfully');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (Throwable $e) {
            Log::error('ServiceController: Failed to reorder services', [
                'tenant_id' => $tenantId,
                'error' => $e->getMessage(),
            ]);

            return $this->errorResponse('Failed to reorder services');
        }
    }

    /**
     * Get next position for new service.
     *
     * @param int $tenantId
     * @return int
     */
    private function getNextPosition(int $tenantId): int
    {
        $maxPosition = Service::where('tenant_id', $tenantId)->max('position');
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

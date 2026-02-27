<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Tenant;
use App\Models\Plan;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PlanLimitValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\PlanSeeder::class);
    }

    /** @test */
    public function it_blocks_product_creation_beyond_opportunity_plan_limit()
    {
        // 1. Crear usuario y tenant con Plan Oportunidad (límite 6 productos)
        $user = User::factory()->create();
        $plan = Plan::where('slug', 'oportunidad')->first();
        $tenant = Tenant::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        // 2. Crear 6 productos (límite permitido)
        for ($i = 1; $i <= 6; $i++) {
            Product::factory()->create([
                'tenant_id' => $tenant->id,
                'name' => "Producto {$i}",
                'price' => 100 * $i,
                'active' => true,
            ]);
        }

        // 3. Verificar que tenemos exactamente 6 productos
        $this->assertEquals(6, $tenant->products()->count());

        // 4. Intentar crear el 7mo producto (debería fallar)
        $response = $this->actingAs($user)->postJson("/dashboard/tenants/{$tenant->id}/products", [
            'name' => 'Producto 7 (debería fallar)',
            'description' => 'Este producto no debería crearse',
            'price' => 700,
            'active' => true,
        ]);

        // 5. Verificar que la API bloquea la creación
        $response->assertStatus(422)
                 ->assertJson([
                     'success' => false,
                     'message' => "Product limit reached (6 products for {$plan->name} plan)"
                 ]);

        // 6. Asegurar que no se creó el 7mo producto
        $this->assertEquals(6, $tenant->fresh()->products()->count());
    }

    /** @test */
    public function it_allows_product_creation_within_plan_limit()
    {
        // 1. Crear usuario y tenant con Plan Crecimiento (límite 12 productos)
        $user = User::factory()->create();
        $plan = Plan::where('slug', 'crecimiento')->first();
        $tenant = Tenant::factory()->create([
            'user_id' => $user->id,
            'plan_id' => $plan->id,
        ]);

        // 2. Crear 11 productos (dentro del límite)
        for ($i = 1; $i <= 11; $i++) {
            Product::factory()->create([
                'tenant_id' => $tenant->id,
                'name' => "Producto {$i}",
                'price' => 100 * $i,
                'active' => true,
            ]);
        }

        // 3. Verificar que tenemos 11 productos
        $this->assertEquals(11, $tenant->products()->count());

        // 4. Crear el 12mo producto (debería permitirse)
        $response = $this->actingAs($user)->postJson("/dashboard/tenants/{$tenant->id}/products", [
            'name' => 'Producto 12 (permitido)',
            'description' => 'Este producto debería crearse',
            'price' => 1200,
            'active' => true,
        ]);

        // 5. Verificar que la API permite la creación
        $response->assertStatus(200)
                 ->assertJson([
                     'success' => true,
                     'message' => 'Product created successfully'
                 ]);

        // 6. Asegurar que ahora tenemos 12 productos
        $this->assertEquals(12, $tenant->fresh()->products()->count());
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantCustomization;
use App\Services\PrelineThemeService;
use App\Services\TenantBootstrapCat;
use App\Services\TenantBootstrapFood;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class OnboardingController extends Controller
{
    /** @var array<string,string> */
    private const SEGMENTS = [
        'restaurante'        => '🍕 Restaurante / Comida',
        'retail'             => '🛍️ Tienda / Comercio',
        'salud'              => '💆 Salud & Belleza',
        'profesional'        => '💼 Servicios Profesionales',
        'tecnico'            => '🔧 Servicios Técnicos',
        'educacion'          => '🎓 Educación / Academia',
        'transporte'         => '🚚 Transporte / Delivery',
    ];

    /**
     * Show the onboarding wizard form.
     */
    public function index(): View|RedirectResponse
    {
        $mode = env('ONBOARDING_MODE', 'admin');

        if ($mode !== 'admin') {
            return redirect('/register');
        }

        $plans    = $this->getPlansForBlueprint('studio');
        $segments = self::SEGMENTS;

        return view('onboarding.wizard', compact('plans', 'segments', 'mode'));
    }

    /**
     * Validate subdomain availability (AJAX).
     */
    public function checkSubdomain(Request $request): JsonResponse
    {
        $subdomain = Str::slug($request->string('subdomain')->toString(), '-');
        $taken     = Tenant::where('subdomain', $subdomain)->exists();

        return response()->json([
            'available' => ! $taken,
            'subdomain' => $subdomain,
        ]);
    }

    /**
     * Store a new tenant created through the wizard.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name'                    => 'required|string|max:255',
            'business_segment'                 => 'required|string',
            'plan_id'                          => [
                'required',
                Rule::exists('plans', 'id')->where(static fn ($query) => $query->where('blueprint', 'studio')),
            ],
            'subdomain'                        => 'required|alpha_dash|unique:tenants,subdomain',
            'slogan'                           => 'nullable|string|max:255',
            'description'                      => 'nullable|string|max:500',
            'content_blocks'                   => 'nullable|array',
            'content_blocks.hero'              => 'nullable|array',
            'content_blocks.hero.title'        => 'nullable|string|max:100',
            'content_blocks.hero.subtitle'     => 'nullable|string|max:200',
            'about_text'                       => 'nullable|string|max:1000',
            'phone'                            => 'nullable|string|max:20',
            'whatsapp_sales'                   => 'required|string|max:20',
            'email'                            => 'nullable|email|max:255',
            'city'                             => 'nullable|string|max:100',
            'value_prop_1'                     => 'nullable|string|max:100',
            'value_prop_2'                     => 'nullable|string|max:100',
            'value_prop_3'                     => 'nullable|string|max:100',
        ]);

        $whatsappSales = $this->normalizePhoneOrFail($validated['whatsapp_sales'] ?? null, 'whatsapp_sales');
        $phone = $this->normalizePhone($validated['phone'] ?? null) ?? $whatsappSales;
        $accountEmail = (string) (auth()->user()?->email ?? '');

        $tenant = DB::transaction(function () use ($validated, $phone, $whatsappSales, $accountEmail): Tenant {

            // Use the authenticated user as tenant owner
            $adminUserId = auth()->id();

            // 1. Crear tenant
            $tenant = Tenant::create([
                'user_id'          => $adminUserId,
                'plan_id'          => $validated['plan_id'],
                'subdomain'        => Str::slug($validated['subdomain'], '-'),
                'base_domain'      => 'syntiweb.com',
                'business_name'    => $validated['business_name'],
                'business_segment' => $validated['business_segment'],
                'slogan'           => $validated['slogan'] ?? null,
                'description'      => $validated['description'] ?? null,
                'phone'            => $phone,
                'whatsapp_sales'   => $whatsappSales,
                'email'            => $accountEmail,
                'city'             => $validated['city'] ?? null,
                'status'           => 'active',
                'plan_activated_at'    => Carbon::now(),
                'subscription_ends_at' => Carbon::now()->addYear(),
                'edit_pin'             => bcrypt('1234'),
            ]);

            // 2. Armar content_blocks
            $contentBlocks = [
                'hero' => [
                    'title'    => $validated['content_blocks']['hero']['title'] ?? null,
                    'subtitle' => $validated['content_blocks']['hero']['subtitle'] ?? null,
                    'cta_text' => 'Contáctanos',
                ],
                'about' => [
                    'text'        => $validated['about_text'] ?? null,
                    'value_props' => array_values(array_filter([
                        $validated['value_prop_1'] ?? null,
                        $validated['value_prop_2'] ?? null,
                        $validated['value_prop_3'] ?? null,
                    ])),
                ],
            ];

            // 3. Crear customization
            TenantCustomization::create([
                'tenant_id'      => $tenant->id,
                'content_blocks' => $contentBlocks,
                'about_text'     => $validated['about_text'] ?? null,
                'hero_layout'    => 'gradient',
                'theme_slug'     => PrelineThemeService::getThemeForSegment($validated['business_segment']),
            ]);

            return $tenant;
        });

        return redirect()->route('onboarding.preview', $tenant->id);
    }

    /**
     * Onboarding product selector.
     */
    public function selector(): View
    {
        return view('onboarding.selector');
    }

    /**
     * Show the SYNTIfood onboarding wizard.
     */
    public function food(): View|RedirectResponse
    {
        $mode = env('ONBOARDING_MODE', 'admin');

        if ($mode !== 'admin') {
            return redirect('/register');
        }

        $plans = $this->getPlansForBlueprint('food');

        return view('onboarding.wizard-food', compact('plans', 'mode'));
    }

    /**
     * Show the SYNTIcat onboarding wizard.
     */
    public function cat(): View|RedirectResponse
    {
        $mode = env('ONBOARDING_MODE', 'admin');

        if ($mode !== 'admin') {
            return redirect('/register');
        }

        $plans = $this->getPlansForBlueprint('cat');

        return view('onboarding.wizard-cat', compact('plans', 'mode'));
    }

    /**
     * Store a new food tenant.
     */
    public function storeFood(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name'  => 'required|string|max:255',
            'business_type'  => 'required|string',
            'plan_id'        => [
                'required',
                Rule::exists('plans', 'id')->where(static fn ($query) => $query->where('blueprint', 'food')),
            ],
            'subdomain'      => 'required|alpha_dash|unique:tenants,subdomain',
            'whatsapp_sales' => 'required|string|max:20',
            'first_category' => 'required|string|max:100',
            'items'          => 'required|string',
        ]);

        $whatsappSales = $this->normalizePhoneOrFail($validated['whatsapp_sales'] ?? null, 'whatsapp_sales');
        $accountEmail = (string) (auth()->user()?->email ?? '');

        $items = json_decode($validated['items'], true) ?? [];

        $tenant = DB::transaction(function () use ($validated, $items, $whatsappSales, $accountEmail): Tenant {

            $adminUserId = auth()->id();

            // 1. Crear tenant
            $tenant = Tenant::create([
                'user_id'          => $adminUserId,
                'plan_id'          => $validated['plan_id'],
                'subdomain'        => Str::slug($validated['subdomain'], '-'),
                'base_domain'      => 'syntiweb.com',
                'business_name'    => $validated['business_name'],
                'business_segment' => $validated['business_type'],
                'phone'            => $whatsappSales,
                'whatsapp_sales'   => $whatsappSales,
                'email'            => $accountEmail,
                'status'           => 'active',
                'plan_activated_at'    => Carbon::now(),
                'subscription_ends_at' => Carbon::now()->addYear(),
                'edit_pin'             => bcrypt('1234'),
            ]);

            // 2. Crear customization
            TenantCustomization::create([
                'tenant_id'      => $tenant->id,
                'content_blocks' => [
                    'hero' => [
                        'title'    => $validated['business_name'],
                        'cta_text' => 'Ver menú',
                    ],
                ],
                'hero_layout' => 'gradient',
            ]);

            // 3. Bootstrap directorio + menu.json
            TenantBootstrapFood::bootstrap($tenant);

            // 4. Agregar primera categoría e ítems
            TenantBootstrapFood::addInitialCategory($tenant, $validated['first_category'], $items);

            return $tenant;
        });

        return redirect()->route('onboarding.preview', $tenant->id);
    }

    /**
     * Store a new cat tenant.
     */
    public function storeCat(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'business_name'             => 'required|string|max:255',
            'store_type'                => 'required|string',
            'plan_id'                   => [
                'required',
                Rule::exists('plans', 'id')->where(static fn ($query) => $query->where('blueprint', 'cat')),
            ],
            'subdomain'                 => 'required|alpha_dash|unique:tenants,subdomain',
            'whatsapp_sales'            => 'required|string|max:20',
            'first_product_name'        => 'required|string|max:255',
            'first_product_price'       => 'required|numeric|min:0',
            'first_product_description' => 'nullable|string|max:500',
        ]);

        $whatsappSales = $this->normalizePhoneOrFail($validated['whatsapp_sales'] ?? null, 'whatsapp_sales');
        $accountEmail = (string) (auth()->user()?->email ?? '');

        $tenant = DB::transaction(function () use ($validated, $whatsappSales, $accountEmail): Tenant {

            $adminUserId = auth()->id();

            // 1. Crear tenant
            $tenant = Tenant::create([
                'user_id'          => $adminUserId,
                'plan_id'          => $validated['plan_id'],
                'subdomain'        => Str::slug($validated['subdomain'], '-'),
                'base_domain'      => 'syntiweb.com',
                'business_name'    => $validated['business_name'],
                'business_segment' => $validated['store_type'],
                'phone'            => $whatsappSales,
                'whatsapp_sales'   => $whatsappSales,
                'email'            => $accountEmail,
                'status'           => 'active',
                'plan_activated_at'    => Carbon::now(),
                'subscription_ends_at' => Carbon::now()->addYear(),
                'edit_pin'             => bcrypt('1234'),
            ]);

            // 2. Crear customization
            TenantCustomization::create([
                'tenant_id'      => $tenant->id,
                'content_blocks' => [
                    'hero' => [
                        'title'    => $validated['business_name'],
                        'cta_text' => 'Ver catálogo',
                    ],
                ],
                'hero_layout' => 'gradient',
            ]);

            // 3. Bootstrap directorio + catalog.json
            TenantBootstrapCat::bootstrap($tenant);

            // 4. Agregar primer producto
            TenantBootstrapCat::addInitialProduct(
                $tenant,
                $validated['first_product_name'],
                (float) $validated['first_product_price'],
                $validated['first_product_description'] ?? ''
            );

            return $tenant;
        });

        return redirect()->route('onboarding.preview', $tenant->id);
    }

    /**
     * Show a preview of the newly created tenant landing.
     */
    public function preview(Tenant $tenant): View
    {
        $mode = env('ONBOARDING_MODE', 'admin');

        return view('onboarding.preview', compact('tenant', 'mode'));
    }

    /**
     * Publish the tenant (set status to active).
     */
    public function publish(Request $request, Tenant $tenant): RedirectResponse
    {
        $tenant->update(['status' => 'active']);

        return redirect("/tenant/{$tenant->id}/dashboard")
            ->with('success', "¡Página de {$tenant->business_name} publicada exitosamente!");
    }

    /**
     * Return plans for a product blueprint, removing duplicated labels safely.
     */
    private function getPlansForBlueprint(string $blueprint)
    {
        $plans = Plan::where('blueprint', $blueprint)
            ->orderByDesc('id')
            ->get();

        // Studio had legacy + prefixed records in some environments.
        if ($blueprint === 'studio') {
            $prefixedStudioPlans = $plans->filter(static fn (Plan $plan): bool => str_starts_with((string) $plan->slug, 'studio-'));

            if ($prefixedStudioPlans->isNotEmpty()) {
                $plans = $prefixedStudioPlans;
            }
        }

        return $plans
            ->unique(static fn (Plan $plan): string => strtolower(Str::ascii((string) $plan->name)))
            ->sortBy('price_usd')
            ->values();
    }

    private function normalizePhone(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $digits = preg_replace('/\D+/', '', $value);

        if (!is_string($digits) || $digits === '') {
            return null;
        }

        if (strlen($digits) < 10 || strlen($digits) > 15) {
            return null;
        }

        return '+' . $digits;
    }

    private function normalizePhoneOrFail(?string $value, string $field): string
    {
        $normalized = $this->normalizePhone($value);

        if ($normalized === null) {
            throw ValidationException::withMessages([
                $field => 'Ingresa un numero de celular valido (10 a 15 digitos).',
            ]);
        }

        return $normalized;
    }
}

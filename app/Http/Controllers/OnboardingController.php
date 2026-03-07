<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\TenantCustomization;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

        $plans    = Plan::orderBy('id')->get();
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
            'plan_id'                          => 'required|exists:plans,id',
            'subdomain'                        => 'required|alpha_dash|unique:tenants,subdomain',
            'slogan'                           => 'nullable|string|max:255',
            'description'                      => 'nullable|string|max:500',
            'content_blocks'                   => 'nullable|array',
            'content_blocks.hero'              => 'nullable|array',
            'content_blocks.hero.title'        => 'nullable|string|max:100',
            'content_blocks.hero.subtitle'     => 'nullable|string|max:200',
            'about_text'                       => 'nullable|string|max:1000',
            'phone'                            => 'nullable|string|max:20',
            'whatsapp_sales'                   => 'nullable|string|max:20',
            'email'                            => 'nullable|email|max:255',
            'city'                             => 'nullable|string|max:100',
            'value_prop_1'                     => 'nullable|string|max:100',
            'value_prop_2'                     => 'nullable|string|max:100',
            'value_prop_3'                     => 'nullable|string|max:100',
        ]);

        $tenant = DB::transaction(function () use ($validated): Tenant {

            // Get admin user ID for tenant_id assignment
            $adminUserId = \App\Models\User::first()->id;

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
                'phone'            => $validated['phone'] ?? null,
                'whatsapp_sales'   => $validated['whatsapp_sales'] ?? null,
                'email'            => $validated['email'] ?? null,
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
            ]);

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
}

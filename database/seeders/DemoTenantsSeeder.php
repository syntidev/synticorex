<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoTenantsSeeder extends Seeder
{
    public function run(): void
    {
        // User demo compartido para todos los tenants demo
        $demoUser = User::firstOrCreate(
            ['email' => 'demo@syntiweb.com'],
            [
                'name'               => 'Demo SyntiWeb',
                'password'           => Hash::make('demo-not-accessible'),
                'email_verified_at'  => now(),
            ]
        );

        // PIN de 4 dígitos para tenants demo — no accesible por usuarios reales
        $demoPin = Hash::make('0000');

        $tenants = [
            [
                'subdomain'         => 'sintiburguer',
                'business_name'     => 'SYNTI Burguer',
                'plan_slug'         => 'food-vision',
                'is_demo'           => true,
                'demo_product'      => 'food',
            ],
            [
                'subdomain'         => 'donaz',
                'business_name'     => 'Donaz — Repostería',
                'plan_slug'         => 'food-vision',
                'is_demo'           => true,
                'demo_product'      => 'food',
            ],
            [
                'subdomain'         => 'tecnofix',
                'business_name'     => 'TecnoFix — Electrónica',
                'plan_slug'         => 'studio-vision',
                'is_demo'           => true,
                'demo_product'      => 'studio',
            ],
            [
                'subdomain'         => 'urbanstore',
                'business_name'     => 'Urban Store',
                'plan_slug'         => 'cat-vision',
                'is_demo'           => true,
                'demo_product'      => 'cat',
            ],
        ];

        foreach ($tenants as $data) {
            $plan = Plan::where('slug', $data['plan_slug'])->firstOrFail();

            Tenant::updateOrCreate(
                ['subdomain' => $data['subdomain']],
                [
                    'user_id'       => $demoUser->id,
                    'plan_id'       => $plan->id,
                    'business_name' => $data['business_name'],
                    'edit_pin'      => $demoPin,
                    'whatsapp_sales' => '580000000001',
                    'whatsapp_support' => null,
                    'whatsapp_active' => 'sales',
                    'status'        => 'active',
                    'is_demo'       => true,
                    'demo_product'  => $data['demo_product'],
                ]
            );
        }
    }
}

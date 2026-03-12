<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TenantsController extends Controller
{
    /**
     * Display a listing of the authenticated user's tenants.
     */
    public function index(Request $request): View
    {
        $user = $request->user();
        $tenants = $user->tenants()
            ->with('plan', 'colorPalette')
            ->withCount(['products', 'services'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.tenants-index', compact('tenants'));
    }
}

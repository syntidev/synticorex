<?php

declare(strict_types=1);

namespace App\View\Components;

use App\Models\Tenant;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class FeatureGate extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(
        public Tenant $tenant,
        public string $feature,
        public string $upgradeMessage = '',
    ) {
        if (empty($this->upgradeMessage)) {
            $this->upgradeMessage = "Esta función está disponible en un plan superior. Actualiza para acceder.";
        }
    }

    /**
     * Determine if the feature is unlocked.
     */
    public function isUnlocked(): bool
    {
        return $this->tenant->isFeatureUnlocked($this->feature);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.feature-gate');
    }
}

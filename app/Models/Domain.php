<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Domain extends Model
{
    protected $fillable = [
        'tenant_id', 'domain', 'tld', 'type', 'managed_by',
        'registrar', 'registrar_account', 'registrar_login', 'auth_code',
        'registered_at', 'expires_at', 'last_renewed_at',
        'auto_renew', 'transfer_lock',
        'cost_price', 'sale_price', 'billing_cycle',
        'dns_status', 'dns_verified_at', 'dns_expected_ip', 'nameservers',
        'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'registered_at'   => 'date',
            'expires_at'      => 'date',
            'last_renewed_at' => 'date',
            'dns_verified_at' => 'datetime',
            'auto_renew'      => 'boolean',
            'transfer_lock'   => 'boolean',
            'nameservers'     => 'array',
            'cost_price'      => 'decimal:2',
            'sale_price'      => 'decimal:2',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(DomainEvent::class);
    }

    public function daysUntilExpiry(): ?int
    {
        if ($this->expires_at === null) {
            return null;
        }

        return (int) Carbon::now()->diffInDays($this->expires_at, false);
    }

    public function isExpiringSoon(): bool
    {
        $days = $this->daysUntilExpiry();

        return $days !== null && $days >= 0 && $days <= 45;
    }

    public function logEvent(string $type, array $payload = [], ?int $userId = null): void
    {
        $this->events()->create([
            'type'         => $type,
            'payload'      => $payload ?: null,
            'performed_by' => $userId,
        ]);
    }
}

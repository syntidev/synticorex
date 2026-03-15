<?php

declare(strict_types=1);

namespace App\Models;

use EslamRedaDiv\FilamentCopilot\Concerns\HasCopilotChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasCopilotChat;

    public const ROLE_ADMIN    = 'admin';
    public const ROLE_VENDEDOR = 'vendedor';
    public const ROLE_SOPORTE  = 'soporte';
    public const ROLE_CLIENTE  = 'cliente';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'google_id',
        'avatar',
        'role',
        'vendor_profile',
        'vendor_sales_month',
        'vendor_total_earned',
        'pago_movil_phone',
        'pago_movil_cedula',
        'pago_movil_bank',
        'referral_code',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_admin' => 'boolean',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN || $this->is_admin === true;
    }

    public function isVendedor(): bool
    {
        return $this->role === self::ROLE_VENDEDOR;
    }

    public function isSoporte(): bool
    {
        return $this->role === self::ROLE_SOPORTE;
    }

    public function isVendor(): bool
    {
        return !is_null($this->vendor_profile);
    }

    public function getCommissionRate(): float
    {
        $rates = [
            'standard' => [1 => 12, 2 => 15, 3 => 18],
            'pro'      => [1 => 15, 2 => 18, 3 => 22],
        ];

        $level = match (true) {
            $this->vendor_sales_month >= 10 => 3,
            $this->vendor_sales_month >= 5  => 2,
            default => 1,
        };

        return (float) ($rates[$this->vendor_profile ?? 'standard'][$level] ?? 12);
    }

    /**
     * Get the tenants for the user.
     *
     * @return HasMany<Tenant, $this>
     */
    public function tenants(): HasMany
    {
        return $this->hasMany(Tenant::class);
    }
}

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

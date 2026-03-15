<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MailSetting extends Model
{
    protected $fillable = [
        'driver',
        'host',
        'port',
        'encryption',
        'username',
        'password',
        'from_address',
        'from_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'port' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function getPasswordAttribute(?string $value): ?string
    {
        return $value ? decrypt($value) : null;
    }

    public function setPasswordAttribute(?string $value): void
    {
        $this->attributes['password'] = $value ? encrypt($value) : null;
    }

    public static function current(): ?self
    {
        return static::first();
    }
}

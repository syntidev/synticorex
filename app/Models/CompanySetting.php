<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CompanySetting extends Model
{
    protected $fillable = [
        'company_name',
        'rif',
        'address',
        'phone',
        'whatsapp_support',
        'email_support',
        'website',
        'instagram',
        'twitter',
        'logo_path',
    ];

    public static function current(): self
    {
        return static::firstOrCreate(['id' => 1], ['company_name' => 'SYNTIweb']);
    }
}

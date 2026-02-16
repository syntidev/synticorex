<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsEvent extends Model
{
    /**
     * Indicates if the model should be timestamped.
     * Only uses created_at, not updated_at.
     *
     * @var bool
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'event_type',
        'reference_type',
        'reference_id',
        'user_ip',
        'user_agent',
        'referer',
        'event_date',
        'event_hour',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'reference_id' => 'integer',
            'event_date' => 'date',
            'event_hour' => 'integer',
            'created_at' => 'datetime',
        ];
    }

    /**
     * Get the tenant that owns the analytics event.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

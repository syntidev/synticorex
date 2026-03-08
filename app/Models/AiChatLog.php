<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiChatLog extends Model
{
    protected $fillable = [
        'tenant_id',
        'product',
        'question',
        'answer',
        'helpful',
    ];

    protected $casts = [
        'helpful' => 'integer',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

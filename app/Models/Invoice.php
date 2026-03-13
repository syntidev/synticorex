<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'tenant_id',
        'invoice_number',
        'amount_usd',
        'currency',
        'payment_method',
        'payment_channel',
        'payment_reference',
        'payment_date',
        'pdf_filename',
        'receipt_path',
        'status',
        'admin_notes',
        'reviewed_at',
        'reviewed_by',
        'period_start',
        'period_end',
    ];

    protected function casts(): array
    {
        return [
            'amount_usd' => 'decimal:2',
            'payment_date' => 'datetime',
            'reviewed_at' => 'datetime',
            'period_start' => 'date',
            'period_end' => 'date',
        ];
    }

    // ── Relationships ────────────────────────────────────────────────

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'reviewed_by');
    }

    // ── Scopes ───────────────────────────────────────────────────────

    public function scopePendingReview(Builder $query): Builder
    {
        return $query->where('status', 'pending_review');
    }

    public function scopePaid(Builder $query): Builder
    {
        return $query->where('status', 'paid');
    }

    // ── Helpers ──────────────────────────────────────────────────────

    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    /**
     * Generate next invoice number: SYNTI-YYYY-XXXXX
     */
    public static function generateNumber(): string
    {
        $year = date('Y');
        $last = static::where('invoice_number', 'like', "SYNTI-{$year}-%")
            ->orderByDesc('id')
            ->value('invoice_number');

        $next = 1;
        if ($last !== null) {
            $parts = explode('-', $last);
            $next = ((int) end($parts)) + 1;
        }

        return sprintf('SYNTI-%s-%05d', $year, $next);
    }

    /**
     * Label legible del canal de pago
     */
    public function getChannelLabelAttribute(): string
    {
        return match ($this->payment_channel) {
            'pago_movil' => 'Pago Móvil',
            'paypal' => 'PayPal',
            'zinli' => 'Zinli',
            default => $this->payment_channel ?? '—',
        };
    }

    /**
     * Label legible del status
     */
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'Pendiente',
            'pending_review' => 'En Revisión',
            'paid' => 'Pagado',
            'rejected' => 'Rechazado',
            'cancelled' => 'Cancelado',
            default => $this->status ?? '—',
        };
    }

    /**
     * Color CSS del status badge
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pending' => 'bg-gray-100 text-gray-700',
            'pending_review' => 'bg-yellow-100 text-yellow-700',
            'paid' => 'bg-green-100 text-green-700',
            'rejected' => 'bg-red-100 text-red-700',
            'cancelled' => 'bg-gray-100 text-gray-500',
            default => 'bg-gray-100 text-gray-700',
        };
    }
}

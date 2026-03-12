<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'tenant_id',
        'name',
        'description',
        'price_usd',
        'compare_price_usd',
        'price_bs',
        'image_filename',
        'image_url',
        'position',
        'is_active',
        'is_featured',
        'badge',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price_usd' => 'decimal:2',
            'compare_price_usd' => 'decimal:2',
            'price_bs' => 'decimal:2',
            'position' => 'integer',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Get the tenant that owns the product.
     *
     * @return BelongsTo<Tenant, $this>
     */
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    /**
     * Get the gallery images for this product (Plan 3 only).
     * These are ADDITIONAL images beyond the main image_filename.
     * Max 2 gallery images per product.
     *
     * @return HasMany<ProductImage, $this>
     */
    public function galleryImages(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('position');
    }

    /**
     * Get all image URLs for this product (main + gallery).
     * Returns array of URLs. First element is always the main image.
     *
     * @param int $tenantId
     * @return array<int, string>
     */
    public function getAllImageUrls(int $tenantId): array
    {
        $urls = [];

        // Main image first
        if ($this->image_filename) {
            $urls[] = asset('storage/tenants/' . $tenantId . '/' . $this->image_filename);
        }

        // Gallery images (Plan 3 only, already loaded via eager load)
        if ($this->relationLoaded('galleryImages')) {
            foreach ($this->galleryImages as $galleryImage) {
                $urls[] = asset('storage/tenants/' . $tenantId . '/' . $galleryImage->image_filename);
            }
        }

        return $urls;
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'id_article';

    protected $fillable = [
        'title',
        'description',
        'image',
        'category_id',
        'price',
        'quantity',
    ];

    // ─── Relations ────────────────────────────────────────────────────────────

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeInStock(Builder $query): Builder
    {
        return $query->where('quantity', '>', 0);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function scopeByCategory(Builder $query, ?int $categoryId): Builder
    {
        if (!$categoryId) {
            return $query;
        }

        return $query->where('category_id', $categoryId);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Retourne l'URL de l'image, qu'elle soit stockée localement ou distante.
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image) {
            return null;
        }

        if (str_starts_with($this->image, 'http')) {
            return $this->image;
        }

        return asset('storage/' . $this->image);
    }

    /**
     * Retourne le prix formaté en euros (ex : "19,99 €").
     */
    public function getPriceFormattedAttribute(): string
    {
        return number_format($this->price, 2, ',', ' ') . ' €';
    }
}

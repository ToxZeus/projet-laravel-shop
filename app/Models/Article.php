<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Article extends Model
{
    protected $primaryKey = 'id_article';

    protected $fillable = [
        'title',
        'description',
        'image',
        'category_id',
        'price',
        'quantity',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}

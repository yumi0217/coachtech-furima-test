<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemCategory extends Model
{
    use HasFactory;

    protected $table = 'item_categories';

    protected $fillable = [
        'item_id',
        'category_id',
    ];

    /**
     * リレーション：ItemCategory は 1つの Item に属する
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    /**
     * リレーション：ItemCategory は 1つの Category に属する
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}

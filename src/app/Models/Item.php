<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Item extends Model
{
    use HasFactory;

    protected $table = 'items';

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'condition',
        'image_url',
        'is_sold',
    ];

    protected $casts = [
        'is_sold' => 'boolean',
    ];

    /**
     * Itemは1人のUserに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Itemは複数のItemCategoryを持つ（中間テーブル）
     */
    public function itemCategories()
    {
        return $this->hasMany(ItemCategory::class);
    }

    /**
     * Itemは複数のCategoryを持つ（多対多リレーション）
     */
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'item_categories');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function favoritedUsers()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function getLikesCountAttribute()
    {
        return $this->favoritedUsers()->count();
    }

    public function buyers()
    {
        return $this->belongsToMany(User::class, 'item_user', 'item_id', 'user_id')->withTimestamps();
    }
}

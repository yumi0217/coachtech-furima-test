<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // 紐づくテーブル名（明示するのが安全）
    protected $table = 'categories';

    // ホワイトリスト
    protected $fillable = [
        'name',
    ];

    /**
     * リレーション：このカテゴリに属する複数の商品（多対多）
     */
    public function items()
    {
        return $this->belongsToMany(Item::class, 'item_category');
    }
}

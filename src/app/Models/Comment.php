<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    // 紐づくテーブル名
    protected $table = 'comments';

    // ホワイトリスト
    protected $fillable = [
        'user_id',
        'item_id',
        'comment',
        'content',
    ];

    /**
     * リレーション：コメントを投稿したユーザー
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * リレーション：コメントされた商品
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

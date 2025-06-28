<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    // 紐づくテーブル名（複数形であるため明示）
    protected $table = 'addresses';

    // ホワイトリスト（または guarded を使ってもOK）
    protected $fillable = [
        'user_id',
        'postal_code',
        'address',
        'building',
    ];

    /**
     * リレーション：この住所は1人のユーザーに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

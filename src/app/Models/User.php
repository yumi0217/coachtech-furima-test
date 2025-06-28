<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Purchase;
use Laravel\Fortify\TwoFactorAuthenticatable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'two_factor_confirmed_at' => 'datetime',
    ];

    /**
     * ユーザーに紐づくプロフィール情報（1対1）
     */
    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    /**
     * ユーザーが出品した商品（1対多）
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }

    /**
     * ユーザーが投稿したコメント（1対多）
     */
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * ユーザーの購入履歴（1対多）
     */
    public function purchases()
    {
        return $this->hasMany(Purchase::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Item::class, 'favorites')->withTimestamps();
    }

    public function hasFavorited(Item $item)
    {
        return $this->favorites()->where('item_id', $item->id)->exists();
    }

    /**
     * ユーザーの住所情報（1対1）
     */
    public function address()
    {
        return $this->hasOne(Address::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $table = 'profiles';

    protected $fillable = [
        'user_id',
        'nickname',
        'birthday',
        'gender',
        'username',
        'postal_code',
        'address',
        'building',
        'profile_image',
    ];


    /**
     * Profileは1人のUserに属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

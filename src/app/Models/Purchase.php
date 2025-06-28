<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'purchases';

    protected $fillable = [
        'user_id',
        'item_id',
        'purchased_at',
    ];

    /**
     * Purchase は 1人の User に属する
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Purchase は 1つの Item に属する
     */
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}

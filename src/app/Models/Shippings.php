<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipping extends Model
{
    use HasFactory;

    protected $table = 'shippings';

    protected $fillable = [
        'purchase_id',
        'address_id',
        'shipped_at',
        'status',
    ];

    /**
     * Shipping は 1つの Purchase に属する
     */
    public function purchase()
    {
        return $this->belongsTo(Purchase::class);
    }

    /**
     * Shipping は 1つの Address に属する
     */
    public function address()
    {
        return $this->belongsTo(Address::class);
    }
}

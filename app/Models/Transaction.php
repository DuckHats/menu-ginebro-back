<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    const TYPE_TOPUP = 'topup';
    const TYPE_ORDER = 'order';
    const TYPE_CORRECTION = 'correction';

    protected $fillable = [
        'user_id',
        'amount',
        'type',
        'description',
        'internal_order_id',
        'status',
        'order_id', // Redsys Order ID
        'response_code',
        'authorization_code',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function internalOrder()
    {
        return $this->belongsTo(Order::class, 'internal_order_id');
    }
}

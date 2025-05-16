<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'option1', 'option2', 'option3'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status';

    protected $fillable = ['name'];

    /**
     * Relación con Orders: Un tipo de pedido puede tener muchos pedidos.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}

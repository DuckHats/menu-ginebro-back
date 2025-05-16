<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    protected $table = 'order_status'; // Define la tabla asociada

    protected $fillable = ['name']; // Define los campos que se pueden llenar de forma masiva

    /**
     * Relación con Orders: Un tipo de pedido puede tener muchos pedidos.
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'order_status_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserType extends Model
{
    use HasFactory;

    protected $table = 'user_types'; // Define la tabla asociada

    protected $fillable = ['name']; // Define los campos que se pueden llenar de forma masiva

    /**
     * Relación con Orders: Un tipo de pedido puede tener muchos pedidos.
     */
    public function orders()
    {
        return $this->belongsTo(Order::class);
    }
}

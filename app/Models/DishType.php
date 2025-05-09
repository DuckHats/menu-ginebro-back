<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DishType extends Model
{
    use HasFactory;

    protected $table = 'dish_type'; // Define la tabla asociada

    protected $fillable = ['name']; // Define los campos que se pueden llenar de forma masiva

    /**
     * Relación con Orders: Un tipo de pedido puede tener muchos pedidos.
     */
    public function Dish()
    {
        return $this->belongsTo(Dish::class);
    }
}

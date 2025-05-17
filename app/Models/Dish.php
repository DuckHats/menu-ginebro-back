<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = [
        'menu_id',
        'dish_date',
        'dish_type_id',
        'options',
    ];

    protected $casts = ['options' => 'array'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function dishType()
    {
        return $this->belongsTo(DishType::class, 'dish_type_id');
    }

    // public function orders()
    // {
    //     return $this->belongsToMany(Order::class, 'order_details')->withTimestamps();
    // }

    public function orderDetails()
    {
        return $this->hasMany(OrderDetail::class);
    }
}

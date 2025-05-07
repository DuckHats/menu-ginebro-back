<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dish extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'dish_date', 'type', 'options'];

    protected $casts = ['options' => 'array'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}


<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = ['month', 'week', 'start_date', 'end_date'];

    public function menuDays()
    {
        return $this->hasMany(MenuDay::class);
    }

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }
}


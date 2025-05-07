<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MenuDay extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'day_id', 'specific_date'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function day()
    {
        return $this->belongsTo(Day::class);
    }
}


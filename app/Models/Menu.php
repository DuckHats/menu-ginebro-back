<?php

namespace App\Models;

use App\Contracts\Exportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Menu extends Model implements Exportable
{
    use HasFactory;

    protected $fillable = ['day'];

    public function dishes()
    {
        return $this->hasMany(Dish::class);
    }

    public function getExportData(): Collection
    {
        return $this->newQuery()
            ->with('dishes')
            ->get()
            ->map(function ($menu) {
                return [
                    'ID' => $menu->id,
                    'Dia' => $menu->day ?? 'N/A',
                    'Plats' => $menu->dishes->pluck('name')->implode(', ') ?: 'N/A',
                ];
            });
    }

    public function getExportHeadings(): array
    {
        return ['ID', 'Dia', 'Plats'];
    }
}

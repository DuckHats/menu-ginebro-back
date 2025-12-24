<?php

namespace App\Models;

use App\Contracts\Exportable;
use App\Contracts\Importable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Menu extends Model implements Exportable, Importable
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
            ->with('dishes.dishType')
            ->get()
            ->map(function ($menu) {
                $platos = $menu->dishes->map(function ($dish) {
                    $type = $dish->dishType->name ?? 'Plato';
                    $options = is_array($dish->options)
                        ? $dish->options
                        : (json_decode($dish->options, true) ?: [$dish->options]);
                    $optionsText = implode(', ', $options);

                    return "{$type}: {$optionsText}";
                })->implode(' | ');

                return [
                    'ID' => $menu->id,
                    'Dia' => $menu->day ?? 'N/A',
                    'Plats' => $platos ?: 'N/A',
                ];
            });
    }

    public function getExportHeadings(): array
    {
        return ['ID', 'Dia', 'Plats'];
    }

    public function importRow(array $data): void
    {
        $menu = self::create([
            'day' => $data['day'],
        ]);

        foreach ($data['dishes'] as $dish) {
            $menu->dishes()->create([
                'dish_type_id' => $dish['dish_type_id'],
                'options' => $dish['options'],
            ]);
        }
    }

    public function getImportValidationRules(): array
    {
        return [
            'day' => 'required|date',
            'dishes' => 'required|array|min:1',
            'dishes.*.dish_type_id' => 'required|exists:dish_type,id',
            'dishes.*.options' => 'required|array|min:1',
        ];
    }

    public function preprocessImportData(array $data): array
    {
        if (isset($data[0]) && is_array($data[0]) && array_key_exists('dishes', $data[0])) {
            return $data;
        }

        $processed = [];
        foreach ($data as $row) {
            if (empty($row['day'])) continue;

            $dishes = [];

            $mapping = [
                'dish_1' => 1,
                'dish_2' => 2,
                'dish_3' => 3
            ];

            foreach ($mapping as $col => $typeId) {
                if (!empty($row[$col])) {

                    $options = array_map('trim', explode(',', $row[$col]));
                    $options = array_filter($options);

                    if (!empty($options)) {
                        $dishes[] = [
                            'dish_type_id' => $typeId,
                            'options' => array_values($options)
                        ];
                    }
                }
            }

            $processed[] = [
                'day' => $row['day'],
                'dishes' => $dishes
            ];
        }

        return $processed;
    }
}

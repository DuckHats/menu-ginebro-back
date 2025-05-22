<?php

namespace App\Models;

use App\Contracts\Exportable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class Order extends Model implements Exportable
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'order_date',
        'allergies',
        'has_tupper',
        'order_type_id',
        'order_status_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orderDetail()
    {
        return $this->hasOne(OrderDetail::class);
    }

    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class);
    }

    public function orderType()
    {
        return $this->belongsTo(OrderType::class);
    }
    public function getExportData(): Collection
    {
        return $this->newQuery()
            ->with(['user', 'orderDetail', 'orderStatus', 'orderType'])
            ->get()
            ->map(function ($order) {
                return [
                    'ID' => $order->id,
                    'Usuari' => $order->user->name ?? 'N/A',
                    'Tipus de comanda' => $order->orderType->name ?? 'N/A',
                    'Estat' => $order->orderStatus->name ?? 'N/A',
                    'Primer' => $order->orderDetail->option1 ?? 'N/A',
                    'Segon' => $order->orderDetail->option2 ?? 'N/A',
                    'Postres' => $order->orderDetail->option3 ?? 'N/A',
                    'Data' => $order->order_date ?? 'N/A',
                    'Alergies' => $order->allergies ?? 'N/A',
                    'Tupper' => $order->has_tupper ? 'Sí' : 'No',
                ];
            });
    }


    public function getExportHeadings(): array
    {
        return [
            'ID',
            'Usuari',
            'Tipus de comanda',
            'Estat',
            'Primer',
            'Segon',
            'Postres',
            'Data',
            'Alergies',
            'Tupper',
        ];
    }
}

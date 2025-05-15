<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with(['orderDetails.dish', 'user', 'orderType', 'orderStatus'])->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'order_date' => 'required|date',
            'order_type_id' => 'required|exists:order_types,id',
            'order_status_id' => 'required|exists:order_status,id',
            'allergies' => 'nullable|string',
            'dish_ids' => 'required|array|min:1',
            'dish_ids.*' => 'exists:dishes,id',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => $validated['user_id'],
                'order_date' => $validated['order_date'],
                'order_type_id' => $validated['order_type_id'],
                'order_status_id' => $validated['order_status_id'],
                'allergies' => $validated['allergies'] ?? null,
            ]);

            foreach ($validated['dish_ids'] as $dishId) {
                $order->orderDetails()->create(['dish_id' => $dishId]);
            }

            DB::commit();
            return response()->json($order->load('orderDetails.dish'), 201);

        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json(['error' => 'Error al crear el pedido', 'message' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        $order = Order::with(['orderDetails.dish', 'user', 'orderType', 'orderStatus'])->findOrFail($id);
        return response()->json($order);
    }

    public function patch(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'order_status_id' => 'required|exists:order_status,id',
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    public function export(Request $request)
    {
        $exportService = new \App\Services\Generic\ExportService(new Order);
        return $exportService->export($request);
    }
}

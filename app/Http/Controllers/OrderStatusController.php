<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Generic\ExportService;
use App\Services\Model\OrderStatusService;
use Illuminate\Http\Request;

class OrderStatusController extends Controller
{
    private $orderStatusService;

    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->orderStatusService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->orderStatusService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->orderStatusService->getById($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->orderStatusService->update($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->orderStatusService->patch($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->orderStatusService->delete($request, $id);
    }

    /**
     * Export the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $exportService = new ExportService(new Order);

        return $exportService->export($request);
    }
}

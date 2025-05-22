<?php

namespace App\Http\Controllers;

use App\Models\OrderDetail;
use App\Services\Generic\ExportService;
use App\Services\Model\OrderDetailsService;
use Illuminate\Http\Request;

class OrderDetailsController extends Controller
{
    private $orderDetailService;

    public function __construct(OrderDetailsService $orderDetailService)
    {
        $this->orderDetailService = $orderDetailService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->orderDetailService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->orderDetailService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->orderDetailService->getById($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->orderDetailService->update($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->orderDetailService->patch($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->orderDetailService->delete($request, $id);
    }

    /**
     * Export the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $exportService = new ExportService(new OrderDetail);

        return $exportService->export($request);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\OrderType;
use App\Services\Generic\ExportService;
use App\Services\Model\OrderTypeService;
use Illuminate\Http\Request;

class OrderTypeController extends Controller
{
    private $orderTypeService;

    public function __construct(OrderTypeService $orderTypeService)
    {
        $this->orderTypeService = $orderTypeService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->orderTypeService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->orderTypeService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->orderTypeService->getById($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->orderTypeService->update($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->orderTypeService->patch($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->orderTypeService->delete($request, $id);
    }

    /**
     * Export the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $exportService = new ExportService(new OrderType);

        return $exportService->export($request);
    }
}

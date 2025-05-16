<?php

namespace App\Http\Controllers;

use App\Models\Dish;
use App\Services\Generic\ExportService;
use App\Services\Model\DishService;
use Illuminate\Http\Request;

class DishController extends Controller
{
    private $dishService;

    public function __construct(DishService $dishService)
    {
        $this->dishService = $dishService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->dishService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->dishService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return $this->dishService->getById($request, $id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->dishService->update($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->dishService->patch($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->dishService->delete($request, $id);
    }


    /**
     * Export the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $exportService = new ExportService(new Dish);

        return $exportService->export($request);
    }
}
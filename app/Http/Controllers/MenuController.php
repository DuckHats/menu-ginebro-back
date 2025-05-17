<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Services\Generic\ExportService;
use App\Services\Model\MenuService;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    private $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->menuService->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        return $this->menuService->create($request);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function show(Request $request, $id)
    // {
    //     return $this->menuService->getById($request, $id);
    // }

    /**
     * Display the specified resource.
     *
     * @param  date  $date
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $day)
    {
        return $this->menuService->getByDate($request, $day);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        return $this->menuService->update($request, $id);
    }

    /**
     * Partially update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function patch(Request $request, $id)
    {
        return $this->menuService->patch($request, $id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        return $this->menuService->delete($request, $id);
    }

    /**
     * Return Current authenticate user information.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function me(Request $request)
    {
        $authUserid = $request->user()->id;

        return $this->menuService->getById($request, $authUserid);
    }

    /**
     * Export the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function export(Request $request)
    {
        $exportService = new ExportService(new Menu);

        return $exportService->export($request);
    }
}

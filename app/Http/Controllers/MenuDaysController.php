<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MenuDay;

class MenuDaysController extends Controller
{
    // Display a listing of the menu days
    public function index()
    {
        $menuDays = MenuDay::with(['menu', 'day'])->get(); // Include related menu and day
        return response()->json($menuDays);
    }

    // Export menu days data
    public function export()
    {
        $menuDays = MenuDay::with(['menu', 'day'])->get();
        // Example: Export logic (e.g., CSV or Excel)
        return response()->json(['message' => 'Export successful', 'data' => $menuDays]);
    }

    // Display the specified menu day
    public function show($id)
    {
        $menuDay = MenuDay::with(['menu', 'day'])->find($id);

        if (!$menuDay) {
            return response()->json(['error' => 'Menu day not found'], 404);
        }

        return response()->json($menuDay);
    }

    // Store a newly created menu day
    public function store(Request $request)
    {
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'day_id' => 'required|exists:days,id',
            'specific_date' => 'nullable|date',
        ]);

        $menuDay = MenuDay::create($validated);

        return response()->json($menuDay, 201);
    }

    // Update the specified menu day
    public function update(Request $request, $id)
    {
        $menuDay = MenuDay::find($id);

        if (!$menuDay) {
            return response()->json(['error' => 'Menu day not found'], 404);
        }

        $validated = $request->validate([
            'menu_id' => 'sometimes|exists:menus,id',
            'day_id' => 'sometimes|exists:days,id',
            'specific_date' => 'nullable|date',
        ]);

        $menuDay->update($validated);

        return response()->json($menuDay);
    }

    // Partially update the specified menu day
    public function patch(Request $request, $id)
    {
        return $this->update($request, $id);
    }

    // Remove the specified menu day
    public function destroy($id)
    {
        $menuDay = MenuDay::find($id);

        if (!$menuDay) {
            return response()->json(['error' => 'Menu day not found'], 404);
        }

        $menuDay->delete();

        return response()->json(['message' => 'Menu day deleted successfully']);
    }
}

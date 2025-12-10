<?php

namespace App\Http\Controllers;

use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyController extends Controller
{
    public function index()
    {
        return response()->json(Allergy::all());
    }

    public function updateUserAllergies(Request $request)
    {
        $request->validate([
            'allergies' => 'array',
            'allergies.*' => 'exists:allergies,id',
            'custom_allergies' => 'nullable|string',
        ]);

        $user = auth()->user();
        $user->allergies()->sync($request->allergies);
        $user->custom_allergies = $request->custom_allergies;
        $user->save();

        return response()->json(['message' => 'Allergies updated successfully', 'user' => $user->load('allergies')]);
    }
}

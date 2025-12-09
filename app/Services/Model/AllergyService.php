<?php

namespace App\Services\Model;

use App\Helpers\ApiResponse;
use App\Models\Allergy;
use Illuminate\Http\Request;

class AllergyService
{
    public function getAll(Request $request)
    {
        return ApiResponse::success(
            Allergy::select('id', 'name')->orderBy('name')->get()
        );
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

        return ApiResponse::success(['message' => 'Allergies updated successfully', 'user' => $user->load('allergies')]);
    }
}

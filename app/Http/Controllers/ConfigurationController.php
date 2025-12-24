<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConfigurationController extends Controller
{
    public function index()
    {
        $configurations = Configuration::all()->pluck('value', 'key');
        return response()->json([
            'status' => 'success',
            'message' => config('messages.configurations.list_retrieved'),
            'data' => $configurations
        ]);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'settings' => 'required|array',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.errors.invalid_data'),
                'errors' => $validator->errors()
            ], 422);
        }

        foreach ($request->settings as $key => $value) {
            Configuration::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => config('messages.configurations.updated_success')
        ]);
    }
}

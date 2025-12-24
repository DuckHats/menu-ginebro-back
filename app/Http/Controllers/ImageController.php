<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ImageController extends Controller
{
    public function index()
    {
        $images = Image::orderBy('created_at', 'desc')->get();

        $images->each(function ($image) {
            $image->url = asset('storage/' . $image->path);
        });

        return response()->json([
            'status' => 'success',
            'message' => config('messages.images.list_retrieved'),
            'data' => $images
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.errors.invalid_data'),
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->hasFile('image')) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.errors.no_image_uploaded')
            ], 400);
        }

        $path = $request->file('image')->store('menu_images', 'public');

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->startOfMonth();

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : $startDate->copy()->addMonth();

        $image = Image::create([
            'path' => $path,
            'start_date' => $startDate,
            'end_date' => $endDate,
            'month' => $startDate->format('F'),
            'year' => $startDate->year,
        ]);

        $image->url = asset('storage/' . $image->path);

        return response()->json([
            'status' => 'success',
            'message' => config('messages.images.created_success'),
            'data' => $image
        ]);
    }

    public function update(Request $request, $id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.images.not_found')
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.errors.invalid_data'),
                'errors' => $validator->errors()
            ], 422);
        }

        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);

        $image->update([
            'start_date' => $startDate,
            'end_date' => $endDate,
            'month' => $startDate->format('F'),
            'year' => $startDate->year,
        ]);

        $image->url = asset('storage/' . $image->path);

        return response()->json([
            'status' => 'success',
            'message' => config('messages.images.updated_success'),
            'data' => $image
        ]);
    }

    public function destroy($id)
    {
        $image = Image::find($id);

        if (!$image) {
            return response()->json([
                'status' => 'error',
                'message' => config('messages.images.not_found')
            ], 404);
        }

        Storage::disk('public')->delete($image->path);

        $image->delete();

        return response()->json([
            'status' => 'success',
            'message' => config('messages.images.deleted_success')
        ]);
    }
}

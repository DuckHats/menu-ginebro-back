<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SetupController extends Controller
{
    public function setup()
    {
        $user = User::where('email', 'AdminSetup@admin.com')->first();

        if (! $user) {
            $user = User::create([
                'name' => 'AdminSetup',
                'email' => 'AdminSetup@admin.com',
                'password' => Hash::make('password123'),
                'user_type_id' => 1,
            ]);

        }

        if (Auth::attempt(['email' => 'AdminSetup@admin.com', 'password' => 'password123'])) {
            return response('Setup OK', 200);
        }

        return response('Failed to login', 500);
    }
}

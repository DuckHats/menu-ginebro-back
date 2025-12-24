<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Log;

class GoogleAuthController extends Controller
{
    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\Response
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->stateless()->redirect();
    }

    /**
     * Obtain the user information from Google.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Validate domain
            if (!str_ends_with($googleUser->email, '@ginebro.cat')) {
                $frontendUrl = config('services.frontend.url');
                return redirect("{$frontendUrl}/login?error=invalid_domain");
            }

            // Find user by google_id or email
            $user = User::where('google_id', $googleUser->id)->orWhere('email', $googleUser->email)->first();

            if ($user) {
                // Update user with google info if linked or email matched
                $user->update([
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar
                ]);
            } else {
                // Create new user
                $user = User::create([
                    'name' => $googleUser->user['given_name'] ?? $googleUser->name,
                    'last_name' => $googleUser->user['family_name'] ?? '',
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'avatar' => $googleUser->avatar,
                    'password' => null, // Password is null for social login
                    'user_type_id' => User::ROLE_USER,
                    'status' => User::STATUS_ACTIVE,
                ]);
            }

            Auth::login($user);

            $frontendUrl = config('services.frontend.url');
            return redirect($frontendUrl);
        } catch (Exception $e) {
            // Redirect to login
            Log::error('Google Auth Error: ' . $e->getMessage());
            $frontendUrl = config('services.frontend.url');
            return redirect("{$frontendUrl}/login");
        }
    }
}

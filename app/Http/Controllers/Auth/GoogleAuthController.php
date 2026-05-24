<?php

namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
class GoogleAuthController extends Controller
{
    // redirect to google
    public function redirect()
    {
        return Socialite::driver(
            'google'
        )->stateless()->redirect();
    }

    // callback
    public function callback()
    {
        try {

            $googleUser =
                Socialite::driver(
                    'google'
                )
                ->stateless()
                ->user();

            // find existing user
            $user = User::where(
                'email',
                $googleUser->email
            )->first();

            // create user if not exists
            if (!$user) {

                $user = User::create([
                    'name' =>
                        $googleUser->name,

                    'email' =>
                        $googleUser->email,

                    'password' =>
                        bcrypt(
                            Str::random(24)
                        ),

                    'email_verified_at' =>
                        now(),
                ]);
            }

            // create sanctum token
            $token = $user
                ->createToken(
                    'auth_token'
                )
                ->plainTextToken;

            // redirect frontend
            return redirect(
                "http://localhost:3000/auth/google-success?token={$token}"
            );

        } catch (\Exception $e) {

            return response()->json([
                'message' =>
                    'Google login failed',
            ], 500);
        }
    }
}
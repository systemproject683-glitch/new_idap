<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    private function socialiteDriver(): \Laravel\Socialite\Two\GoogleProvider
    {
        $driver = Socialite::driver('google');
        if (config('app.env') !== 'production') {
            $driver->setHttpClient(new Client(['verify' => false]));
        }
        return $driver;
    }

    public function redirectToGoogle()
    {
        return $this->socialiteDriver()->stateless()->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = $this->socialiteDriver()->stateless()->user();

        // Restrict to @cvsu.edu.ph domain
        if (!str_ends_with($googleUser->getEmail(), '@cvsu.edu.ph')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Only @cvsu.edu.ph Google accounts are allowed.',
            ]);
        }

        // Find existing user by google_id or email
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'No account found for this email. Please contact the administrator.',
            ]);
        }

        // Link google_id if not yet linked
        if (!$user->google_id) {
            $user->update(['google_id' => $googleUser->getId()]);
        }

        Auth::login($user, true);
        request()->session()->regenerate();

        if ($user->role === 'faculty') {
            return redirect()->intended(route('development-objectives.index'));
        }

        if ($user->role === 'chairperson') {
            return redirect()->intended(route('chairperson.dashboard'));
        }

        return redirect('/');
    }
}

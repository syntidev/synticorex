<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Two\InvalidStateException;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    /**
     * Redirect to Google OAuth consent screen.
     */
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle Google OAuth callback.
     */
    public function handleGoogleCallback(): RedirectResponse
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (InvalidStateException $exception) {
            // In local/dev it is common to switch host between request and callback
            // (e.g. synticorex.test <-> 127.0.0.1), breaking session state.
            if (app()->environment('local')) {
                $googleUser = Socialite::driver('google')->stateless()->user();
            } else {
                report($exception);

                return redirect()->route('login')->withErrors([
                    'email' => 'No se pudo validar la sesión con Google. Intenta iniciar sesión nuevamente.',
                ]);
            }
        }

        $user = User::where('google_id', $googleUser->getId())->first();

        if (! $user) {
            // Check if email already exists (registered via email+password)
            $user = User::where('email', $googleUser->getEmail())->first();

            if ($user) {
                // Link Google account to existing user
                $user->update([
                    'google_id' => $googleUser->getId(),
                    'avatar'    => $googleUser->getAvatar(),
                ]);
            } else {
                // Create new user from Google
                $user = User::create([
                    'name'              => $googleUser->getName(),
                    'email'             => $googleUser->getEmail(),
                    'google_id'         => $googleUser->getId(),
                    'avatar'            => $googleUser->getAvatar(),
                    'email_verified_at' => now(),
                ]);
            }
        }

        Auth::login($user, remember: true);

        return $this->smartRedirect($user);
    }

    /**
     * Redirect user based on tenant ownership.
     */
    private function smartRedirect(User $user): RedirectResponse
    {
        if ($user->tenants()->exists()) {
            return redirect()->route('tenants.index');
        }

        return redirect()->route('onboarding.selector');
    }
}

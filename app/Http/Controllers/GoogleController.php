<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

/**
 * Class GoogleController
 * Handles Google authentication and user management.
 */
class GoogleController extends Controller {

    private const ROLE_ADMIN = 1;
    private const ROLE_TEACHER = 2;

    /**
     * Redirect the user to the Google authentication page.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Handle the Google callback and authenticate the user.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();
        $email = $googleUser->getEmail();

        if (!Str::endsWith($email, '@fundacionloyola.es')) {
            return redirect()->route('login')->withErrors([
                'email' => 'Solo se permiten cuentas de la fundacionloyola.es.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $email,
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?? '',
                'password' => bcrypt(uniqid()),
                'phone' => '',
                'dni' => substr(uniqid(), 0, 9),
                'role_id' => 2,
            ]);
        }

        Auth::login($user);

        if ($user->role_id === self::ROLE_ADMIN) {
            return redirect()->route('admin.admin');
        } elseif ($user->role_id === self::ROLE_TEACHER) {
            return redirect()->route('teacher.home');
        }

        return redirect()->route('home');
    }

}

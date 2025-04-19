<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class GoogleController extends Controller {

    private const ROLE_ADMIN = 1;
    private const ROLE_TEACHER = 2;

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar() ?? '',
                'password' => bcrypt(uniqid()),
                'phone' => '',
                'dni' => substr(uniqid(), 0, 9),
                'role_id' => 2,
            ]);
        }

        Auth::login($user);

        if ($user->role_id === self::ROLE_ADMIN)
            return redirect()->route('admin.admin');
        elseif ($user->role_id === self::ROLE_TEACHER)
            return redirect()->route('teacher.home');
    }
}

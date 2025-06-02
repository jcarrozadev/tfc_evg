<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

/**
 * PasswordController
 * Handles password reset functionality.
 */
class PasswordController extends Controller
{
    /**
     * Show the password reset form.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|confirmed|min:8',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user) use ($request) {
                $user->forceFill([
                    'password' => Hash::make($request->password),
                ])->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Contraseña restablecida correctamente.');
        } else {
            return back()->withErrors(['email' => [trans($status)]]);
        }
    }
}

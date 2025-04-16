<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class CustomLoginController extends Controller
{
    private const ROLE_ADMIN = 1;
    private const ROLE_TEACHER = 2;

    public function showLoginForm()
    {
        return view('login.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $user = User::where('email', $credentials['email'])->first();
    
        if ($user && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, $request->filled('remember'));
            $request->session()->regenerate();
    
            if ($user->role_id === self::ROLE_ADMIN) {
                return redirect()->route('admin.admin');
            } elseif ($user->role_id === self::ROLE_TEACHER) {
                return redirect()->route('teacher.home');
            }
        }
    
        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }
    

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}

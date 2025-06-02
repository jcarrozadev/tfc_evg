<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordService
{
    public function updatePassword(Request $request, $user): bool
    {
        $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
        ]);

        if (!is_null($user->google_id)) {
            return false;
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return true;
    }
}

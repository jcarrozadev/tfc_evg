<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

/**
 * PasswordService
 * Service for handling password updates for users.
 */
class PasswordService
{
    /**
     * Update the user's password.
     *
     * @param Request $request
     * @param mixed $user
     * @return bool
     */
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

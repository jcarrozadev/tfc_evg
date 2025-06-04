<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * ProfileService
 * Service for handling user profile updates, specifically avatar uploads.
 */
class ProfileService
{
    /**
     * Update the user's avatar.
     *
     * @param Request $request
     * @param mixed $user
     * @return void
     */
    public function updateAvatar(Request $request, $user): void
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:5000',
        ]);

        if ($user->image_profile && $user->image_profile !== 'default.png') {
            Storage::disk('public')->delete($user->image_profile);
        }

        $file = $request->file('avatar');

        $timestamp = now()->timestamp;
        $filename = 'avatar_' . $user->id . '_' . $timestamp . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        $directory = 'avatars';
        Storage::disk('public')->makeDirectory($directory);

        $file->storeAs($directory, $filename, 'public');

        $user->image_profile = $directory . '/' . $filename;
        $user->save();
    }
}

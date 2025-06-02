<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileService
{
    public function updateAvatar(Request $request, $user): void
    {
        $request->validate([
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($user->image_profile && $user->image_profile !== 'default.png') {
            Storage::disk('public')->delete($user->image_profile);
        }

        $file = $request->file('avatar');

        $filename = 'avatar_' . $user->id . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();

        $safeFolder = Str::slug($user->name);
        $directory = 'avatars/' . $safeFolder;
        Storage::disk('public')->makeDirectory($directory);

        $file->storeAs($directory, $filename, 'public');

        $user->image_profile = $directory . '/' . $filename;
        $user->save();
    }
}

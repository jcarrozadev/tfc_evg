<?php

namespace App\Actions\Fortify;

use App\Models\User;
use App\Rules\LoyolaEmail;
use App\Rules\ValidDni;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers {
    use PasswordValidationRules;

    public function create(array $input): User {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:50'],
            'email' => [
                'required',
                'string',
                'email',
                'max:100',
                'unique:users',
                new LoyolaEmail,
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'regex:/^(6|7|9)\d{8}$/'],
            'dni' => [
                'required',
                'string',
                'size:9',
                'unique:users',
                'regex:/^\d{8}[A-Z]$/',
                new ValidDni,
            ],
        ])->validate();

        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'phone' => $input['phone'],
            'dni' => strtoupper($input['dni']),
            'role_id' => 2,
        ]);
    }
}


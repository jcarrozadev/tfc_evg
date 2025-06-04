<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidDni implements Rule
{
    public function passes($attribute, $value): bool
    {
        $value = strtoupper($value);
        if (!preg_match('/^\d{8}[A-Z]$/', $value)) {
            return false;
        }

        $numbers = substr($value, 0, 8);
        $letter = substr($value, -1);
        $letters = "TRWAGMYFPDXBNJZSQVHLCKE";
        $expected = $letters[intval($numbers) % 23];

        return $letter === $expected;
    }

    public function message(): string
    {
        return 'El DNI no es válido. La letra no coincide con el número.';
    }
}

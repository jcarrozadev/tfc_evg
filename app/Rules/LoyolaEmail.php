<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class LoyolaEmail implements Rule
{
    public function passes($attribute, $value): bool
    {
        return str_ends_with(strtolower(trim($value)), '@fundacionloyola.es');
    }

    public function message(): string
    {
        return 'El correo debe pertenecer al dominio @fundacionloyola.es.';
    }
}

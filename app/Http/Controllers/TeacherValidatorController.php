<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

/**
 * TeacherValidatorController
 * Handles validation for teacher-related data.
 */
class TeacherValidatorController extends Controller
{
    /**
     * Validate teacher data.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public static function validateTeacherData(array $data): array
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|max:15',
            'dni' => 'required|string|max:10|unique:users,dni',
        ], [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no es válido.',
            'email.unique' => 'El correo electrónico ya está en uso.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'phone.required' => 'El teléfono es obligatorio.',
            'dni.required' => 'El DNI es obligatorio.',
            'dni.unique' => 'El DNI ya está registrado.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }

    /**
     * Validate notify absence data.
     *
     * @param array $data
     * @return array
     * @throws ValidationException
     */
    public static function validateNotifyAbsenceData(array $data): array
    {
        $validator = Validator::make($data, [
            'reason_id' => 'required|exists:reasons,id',
            'date' => 'required|date',
            'description' => 'nullable|string|max:500',
        ], [
            'reason_id.required' => 'El motivo es obligatorio.',
            'reason_id.exists' => 'El motivo seleccionado no es válido.',
            'date.required' => 'La fecha es obligatoria.',
            'date.date' => 'La fecha no es válida.',
            'description.max' => 'La descripción no puede superar los 500 caracteres.',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        return $validator->validated();
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TeacherController extends Controller {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $phone;
    public string $dni;
    public ?string $rol;

    public function __construct() {}

    public function index(): View {
        $teachers = User::getAllEnabledTeachers();

        foreach ($teachers as $teacher) {
            $teacher->available = $teacher->available === 1 ? 'Sí' : 'No';
        }

        return view('admin.teacher', compact('teachers'));
    }

    public function create(): RedirectResponse {
        try {
            $validatedData = $this->validateTeacherData(request()->all());

            return User::addTeacher($validatedData)
                ? redirect()->back()->with('success', 'Profesor creado correctamente.')
                : redirect()->back()->with('error', 'Error al crear el profesor.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->back()->withErrors([
                'error' => $e->validator->errors()->first()
            ]);
        }
    }    

    private function validateTeacherData(array $data): array {
        return \Illuminate\Support\Facades\Validator::validate($data, [
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
    }
    

    public function edit(Request $request, $id): JsonResponse {
        $teacher = User::getTeacherById($id);

        return !$teacher
            ? response()->json(['error' => 'Profesor no encontrado.'])
            : (User::editTeacher($teacher, $request->all())
                ? response()->json(['success' => 'Profesor editado correctamente.'])
                : response()->json(['error' => 'Error al editar profesor.']));
    }


    public function destroy($id): JsonResponse {
        if (User::deleteTeacher($id)) {
            return response()->json(['success' => 'Profesor eliminado correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar profesor.']);
        }
    }

    public function settings(): View {
        return view('user.setting');
    }

    public function home(): View {
        return view('admin.home');
    }

}

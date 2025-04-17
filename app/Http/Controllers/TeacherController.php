<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

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
        return view('admin.teacher', compact('teachers'));
    }

    public function create(): View {
        return view('admin.teacherCreate');
    }

    public function edit($id): View {
        $teacher = User::findOrFail($id);
        return view('admin.teacherEdit', compact('teacher'));
    }

    public function deleteTeacher($id): JsonResponse {
        if (User::deleteTeacher($id)) {
            return response()->json(['success' => 'Profesor eliminado correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar profesor.']);
        }
    }

    public function home(): View {
        return view('admin.home');
    }

}

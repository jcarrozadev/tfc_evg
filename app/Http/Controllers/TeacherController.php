<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
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

    public function create(): View {
        return view('admin.teacherCreate');
    }

    public function editTeacher(Request $request, $id): JsonResponse {
        $teacher = User::getTeacherById($id);

        return !$teacher
            ? response()->json(['error' => 'Profesor no encontrado.'])
            : (User::editTeacher($teacher, $request->all())
                ? response()->json(['success' => 'Profesor editado correctamente.'])
                : response()->json(['error' => 'Error al editar profesor.']));
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

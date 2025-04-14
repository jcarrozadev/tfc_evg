<?php

namespace App\Http\Controllers;

use Illuminate\Database\Eloquent\Collection;

use App\Models\User;

class TeacherController extends Controller {
    public int $id;
    public string $name;
    public string $email;
    public string $password;
    public string $phone;
    public string $dni;
    public ?string $rol;

    public function __construct()
    {
    }

    public function index()
    {
        $teachers = User::getAllTeachers();
        return view('admin.teacher', compact('teachers'));
    }

    public function create()
    {
        return view('admin.teacherCreate');
    }

    public function edit($id)
    {
        $teacher = User::findOrFail($id);
        return view('admin.teacherEdit', compact('teacher'));
    }

    public function destroy($id)
    {
        User::destroy($id);
        return redirect()->route('teacher.index')->with('success', 'Profesor eliminado');
    }

    public function home()
    {
        return view('admin.home');
    }

}

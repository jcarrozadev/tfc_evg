<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ClassesController extends Controller {
    public int $num_class;
    public string $course;
    public string $code;
    public int $bookguard_id;
    
    public function __construct() {
    }

    public function index() {
        $classes = Classes::getEnabledClasses();
        return view('admin.class', compact('classes'));
    }

    public function create(): RedirectResponse {
        $request = request();

        $request->validate([
            'num_class' => 'required|integer|unique:classes,num_class,NULL,id,course,' . $request->input('course'),
            'course' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);
    
        $created = Classes::addClass($request->only(['num_class', 'course', 'code']));
    
        return $created
            ? redirect()->back()->with('success', 'Clase creada correctamente.')
            : redirect()->back()->with('error', 'Error al crear la clase.');
    }

    public function edit(Request $request, $id): RedirectResponse {
        $class = Classes::getClassById($id);

        if (!$class) {
            return redirect()->back()->with('error', 'Clase no encontrada.');
        }

        return Classes::editClass($class, $request->all())
            ? redirect()->back()->with('success', 'Clase editada correctamente.')
            : redirect()->back()->with('error', 'Error al editar la clase.');
    }


    public function destroy($id): JsonResponse {
        if (Classes::destroyClass($id)) {
            return response()->json(['success' => 'Clase eliminada correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar la clase.']);
        }
    }
    
}

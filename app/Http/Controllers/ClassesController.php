<?php

namespace App\Http\Controllers;

use App\Models\Classes;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * ClassesController
 * Handles the management of classes in the application.
 */
class ClassesController extends Controller {
    public int $num_class;
    public string $course;
    public string $code;
    public int $bookguard_id;
    
    /**
     * ClassesController constructor.
     * Initializes the controller.
     */
    public function __construct() {}

    /**
     * Display the classes management page.
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index(): View {
        $classes = Classes::getEnabledClasses();
        return view('admin.class', compact('classes'));
    }

    /**
     * Display the classes management page with a specific class.
     *
     * @param int $id
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): RedirectResponse {
        $request = request();

        $request->validate([
            'num_class' => 'required|integer',
            'course' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);


        if(Classes::existClass($request->num_class, $request->course, $request->code)){
            return redirect()->back()->with('success', 'Clase creada correctamente.');
        }
    
        $created = Classes::addClass($request->only(['num_class', 'course', 'code']));
    
        return $created
            ? redirect()->back()->with('success', 'Clase creada correctamente.')
            : redirect()->back()->with('error', 'Error al crear la clase.');
    }

    /**
     * Edit a specific class.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function edit(Request $request, $id): RedirectResponse {
        $class = Classes::getClassById($id);

        if (!$class) {
            return redirect()->back()->with('error', 'Clase no encontrada.');
        }

        return Classes::editClass($class, $request->all())
            ? redirect()->back()->with('success', 'Clase editada correctamente.')
            : redirect()->back()->with('error', 'Error al editar la clase.');
    }

    /**
     * Delete a specific class.
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id): JsonResponse {
        if (Classes::destroyClass($id)) {
            return response()->json(['success' => 'Clase eliminada correctamente.']);
        } else {
            return response()->json(['error' => 'Error al eliminar la clase.']);
        }
    }
    
}

<?php

namespace App\Http\Controllers;

use App\Models\Classes;
use Illuminate\Http\RedirectResponse;

class ClassesController extends Controller {
    public int $num_class;
    public string $course;
    public string $code;
    public int $bookguard_id;
    
    public function __construct() {
    }

    public function index() {
        $classes = Classes::getAllClasses();
        return view('admin.class', compact('classes'));
    }

    public function create(): RedirectResponse {
        $request = request();

        $request->validate([
            'num_class' => 'required|integer',
            'course' => 'required|string|max:255',
            'code' => 'required|string|max:10',
        ]);
    
        $created = Classes::addClass($request->only(['num_class', 'course', 'code']));
    
        return $created
            ? redirect()->back()->with('success', 'Clase creada correctamente.')
            : redirect()->back()->with('error', 'Error al crear la clase.');
    }

    public function edit($id){
        // Logic to edit an existing class
    }

    public function destroy($id){
        // Logic to delete a class
    }
}

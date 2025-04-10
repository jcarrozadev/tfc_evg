<?php

namespace App\Http\Controllers;

use App\Models\Classes;

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

    public function create(){
        return view('admin.classCreate');
    }

    public function edit($id){
        // Logic to edit an existing class
    }

    public function destroy($id){
        // Logic to delete a class
    }
}

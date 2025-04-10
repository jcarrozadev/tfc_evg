<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;

Route::get('/', function () {
    return view('login.login');
});

Route::get('/admin', function () {
    return view('admin.adminPanel');
});

Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');

Route::post('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');

Route::get('/teacher/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');

Route::delete('/teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

Route::get('/class', [ClassesController::class, 'index'])->name('class.index');

Route::get('/class/create', [ClassesController::class, 'create'])->name('class.create');

Route::get('/class/{id}/edit', [ClassesController::class, 'edit'])->name('class.edit');

Route::delete('/class/{id}', [ClassesController::class, 'destroy'])->name('class.destroy');

<?php

use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('login.login');
});

Route::get('/admin', function () {
    return view('admin.adminPanel');
});

Route::get('/teacher', [TeacherController::class, 'index'])->name('teacher.index');

Route::get('/teacher/create', [TeacherController::class, 'create'])->name('teacher.create');

Route::get('/teacher/{id}/edit', [TeacherController::class, 'edit'])->name('teacher.edit');

Route::delete('/teacher/{id}', [TeacherController::class, 'destroy'])->name('teacher.destroy');

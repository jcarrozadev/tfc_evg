<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;

Route::get('/', function () {
    return view('login.login');
})->name('home');

Route::prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/create', [TeacherController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

Route::prefix('class')->name('class.')->group(function () {
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::get('/create', [ClassesController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [ClassesController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [ClassesController::class, 'destroy'])->name('destroy');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.adminPanel');
    });
});
<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\Auth\CustomLoginController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [CustomLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [CustomLoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('register');

Route::middleware(['auth', CheckRole::class.':Profesor'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/create', [TeacherController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [TeacherController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return view('admin.adminPanel');
    })->name('admin');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('class')->name('class.')->group(function () {
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::get('/create', [ClassesController::class, 'create'])->name('create');
    Route::get('/{id}/edit', [ClassesController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [ClassesController::class, 'destroy'])->name('destroy');
});

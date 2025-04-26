<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;

use App\Http\Controllers\TeacherController;
use App\Http\Controllers\ClassesController;
use App\Http\Controllers\Auth\CustomLoginController;
use App\Http\Controllers\GoogleController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

/* ROUTES GOOGLE */
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

/* ROUTES FORTIFY */
Route::get('/login', [CustomLoginController::class, 'showLoginForm'])->middleware('guest')->name('login');
Route::post('/login', [CustomLoginController::class, 'login'])->middleware('guest');
Route::post('/logout', [CustomLoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('register');


/* ROUTES TEACHERS */
Route::middleware(['auth', CheckRole::class.':Profesor'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');
    Route::get('/settings', [TeacherController::class, 'settings'])->name('settings');
});

/* ROUTES ADMINISTRATORS */
Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/create', [TeacherController::class, 'create'])->name('create');
    Route::put('/{id}', [TeacherController::class, 'edit'])->name('update');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        return AdminController::index();
    })->name('admin');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('class')->name('class.')->group(function () {
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::post('/create', [ClassesController::class, 'create'])->name('create');
    Route::put('/{id}', [ClassesController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [ClassesController::class, 'destroy'])->name('destroy');
});

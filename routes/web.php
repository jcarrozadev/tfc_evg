<?php

use App\Http\Controllers\BookGuardController;
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

Route::post('/register', [RegisteredUserController::class, 'store'])->middleware('guest')->name('custom.register');


/* ROUTES TEACHERS */
Route::middleware(['auth', CheckRole::class.':Profesor'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');
    Route::get('/settings', [TeacherController::class, 'settings'])->name('settings');
    Route::post('/settings', [TeacherController::class, 'updateSettings'])->name('updateSettings');
    Route::get('/personalGuard' , [TeacherController::class, 'personalGuard'])->name('personalGuard');
    Route::post('/teacher/updatePassword', [TeacherController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/upload-avatar', [TeacherController::class, 'uploadAvatar'])->name('uploadAvatar');

    Route::get('/notifyAbsence' , [TeacherController::class, 'notifyAbsence'])->name('notifyAbsence');
    Route::post('/notifyAbsence' , [TeacherController::class, 'storeNotifyAbsence'])->name('storeNotifyAbsence');
    Route::get('/consultAbsence' , [TeacherController::class, 'consultAbsence'])->name('consultAbsence');

    Route::get('/guardsToday', [TeacherController::class, 'guardsToday'])->name('guardsToday');
});

/* ROUTES ADMINISTRATORS */
Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/', [TeacherController::class, 'index'])->name('index');
    Route::post('/create', [TeacherController::class, 'create'])->name('create');
    Route::put('/{id}', [TeacherController::class, 'edit'])->name('update');
    Route::delete('/{id}', [TeacherController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/guards', [AdminController::class, 'guards'])->name('guards');

    Route::post('/guards/assign', [AdminController::class, 'assignGuard'])->name('guardsAssign');

});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('class')->name('class.')->group(function () {
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::post('/create', [ClassesController::class, 'create'])->name('create');
    Route::put('/{id}', [ClassesController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [ClassesController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('bookGuard')->name('bookGuard.')->group(function () {
    Route::get('/', [BookGuardController::class, 'index'])->name('index');

    Route::post('/store', [BookGuardController::class, 'store'])->name('store');

    Route::delete('/reset', [AdminController::class, 'resetBookGuard'])->name('reset');
});

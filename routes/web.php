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
use App\Http\Controllers\PasswordController;
use Illuminate\Support\Facades\Response;

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
Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update.fortify');

/* ROUTES TEACHERS */
Route::middleware(['auth', CheckRole::class.':Profesor'])->prefix('teacher')->name('teacher.')->group(function () {
    Route::get('/home', [TeacherController::class, 'home'])->name('home');
    Route::get('/settings', [TeacherController::class, 'settings'])->name('settings');
    Route::post('/settings', [TeacherController::class, 'updateSettings'])->name('updateSettings');
    Route::get('/personalGuard' , [TeacherController::class, 'personalGuard'])->name('personalGuard');
    Route::get('/personalSchedule' , [TeacherController::class, 'personalSchedule'])->name('personalSchedule');
    Route::post('/teacher/updatePassword', [TeacherController::class, 'updatePassword'])->name('updatePassword');
    Route::post('/upload-avatar', [TeacherController::class, 'uploadAvatar'])->name('uploadAvatar');

    Route::get('/notifyAbsence' , [TeacherController::class, 'notifyAbsence'])->name('notifyAbsence');
    Route::post('/notifyAbsence' , [TeacherController::class, 'storeNotifyAbsence'])->name('storeNotifyAbsence');
    
    Route::get('/consultAbsence' , [TeacherController::class, 'consultAbsence'])->name('consultAbsence');

    Route::match(['post', 'patch'], '/absences/{absence}/info',
    [TeacherController::class, 'updateInfo'])
    ->name('absences.updateInfo');

    Route::post('absences/sort',
    [TeacherController::class, 'sort'])
    ->name('absences.sort');

    Route::delete('/absences/files/{file}', [TeacherController::class, 'deleteFile'])->name('absences.deleteFile');

    Route::get('/absences/files/{file}', [TeacherController::class, 'showFile'])
    ->name('absences.files.show');

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
    Route::get('/', [AdminController::class, 'index'])->name('admin');
    Route::get('/guards', [AdminController::class, 'guards'])->name('guards');

    Route::post('/guards/assign', [AdminController::class, 'assignGuard'])->name('guardsAssign');
    Route::post('/guards/send-emails', [AdminController::class, 'sendEmails'])->name('guards.sendEmails');
    Route::post('/send-whatsapps', [AdminController::class, 'sendWhatsapps'])->name('admin.sendWhatsapps');

});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('class')->name('class.')->group(function () {
    Route::get('/', [ClassesController::class, 'index'])->name('index');
    Route::post('/create', [ClassesController::class, 'create'])->name('create');
    Route::put('/{id}', [ClassesController::class, 'edit'])->name('edit');
    Route::delete('/{id}', [ClassesController::class, 'destroy'])->name('destroy');
});

Route::middleware(['auth', CheckRole::class.':Administrador'])->prefix('bookGuard')->name('bookGuard.')->group(function () {
    Route::get('/', [BookGuardController::class, 'index'])->name('index');

    Route::get('/bookguard/download-pdf', [BookGuardController::class, 'downloadPdf'])->name('downloadPdf');


    Route::post('/store', [BookGuardController::class, 'store'])->name('store');

    Route::delete('/bookguards/reset/complete', [AdminController::class, 'resetBookGuard'])->name('reset.complete');
    Route::delete('/bookguards/reset/classes', [AdminController::class, 'resetBookGuardClasses'])->name('reset.classes');

});

/* ROUTE DOCS */
Route::get('/docs', function () {
    return view('docs.index');
})->name('docs.index');

/* ROUTE STORAGE */

Route::get('/avatars/{filename}', function ($filename) {
    $path = storage_path('app/public/avatars/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200)->header('Content-Type', $type);
});

Route::get('/proofs/{filename}', function ($filename) {
    $path = storage_path('app/public/proofs/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200)->header('Content-Type', $type);
});

Route::get('/substitute_files/{filename}', function ($filename) {
    $path = storage_path('app/public/substitute_files/' . $filename);
    if (!file_exists($path)) {
        abort(404);
    }

    $file = file_get_contents($path);
    $type = mime_content_type($path);

    return Response::make($file, 200)->header('Content-Type', $type);
});
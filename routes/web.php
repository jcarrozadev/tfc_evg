<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view('login.login');
});

Route::get('/admin', function () {
    return view('admin.adminPanel');
});

Route::get('/teacher', function () {
    return view('admin.teacher');
});

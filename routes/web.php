<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('test');
});

Route::get('/login', function () {
    return view('login.login');
});

Route::get('/admin', function () {
    return view('admin.adminPanel');
});

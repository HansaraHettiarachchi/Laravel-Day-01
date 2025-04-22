<?php

use App\Http\Controllers\NewUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', function () {
    return view('login');
});

Route::get('/userCreate', function () {
    return view('userCreate');
});


Route::controller(NewUserController::class)->group(function () {
    Route::get('user-list', 'userList');
    Route::get("edit-user", "editUser");
    Route::post('submit-login', 'submitLogin');
    Route::post('create-user', 'createUser');
    Route::post('update-user', 'updateUser');
    Route::get('delete-user', 'deleteUser');
    Route::post('user-data', 'userData');
});

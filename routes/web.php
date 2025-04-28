<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Auth
Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

// Students CRUD
Route::view('/students', 'students.index')->name('students.index');
Route::view('/students/create', 'students.create')->name('students.create');
Route::view('/students/{id}', 'students.show')->name('students.show');
Route::view('/students/{id}/edit', 'students.edit')->name('students.edit');

Route::get('/', function () {
    return redirect('/login');
});

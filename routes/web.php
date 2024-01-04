<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
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

Route::get('/',[UsersController::class,'index'])->name('home');

Route::get('/users-list',[UsersController::class,'fetchUserList'])->name('users-list');

Route::post('/user-save',[UsersController::class,'storeUser'])->name('user-form-save');

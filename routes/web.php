<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;

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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::controller(MenuController::class)->group(function () {
    Route::get('/menu', 'index')->name('menu');
    route::post('/menu', 'createMenu')->name('createMenu');


    // Menu Header part
    Route::get('/menu-header', 'menuHeaderIndex')->name('menu-header');
    Route::post('/menu-header', 'menuHeaderCreate')->name('menu-header.create');
    Route::put('/menu-header/{id}', 'menuHeaderUpdate')->name('menu-header.update');
});

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ItemCategoryController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
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
    // return view('welcome');
    return Redirect::to('/login');
});

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');


Route::controller(MenuController::class)->middleware('auth')->group(function () {
    Route::get('/menu', 'index')->name('menu');
    route::post('/menu', 'createMenu')->name('createMenu');
    route::put('/menu/{id}', 'updateMenu')->name('menu.update');


    // Menu Header part
    Route::get('/menu-header', 'menuHeaderIndex')->name('menu-header');
    Route::post('/menu-header', 'menuHeaderCreate')->name('menu-header.create');
    Route::put('/menu-header/{id}', 'menuHeaderUpdate')->name('menu-header.update');
});


Route::controller(UserController::class)->middleware('auth')->group(function () {

    Route::get('/user', 'userIndex')->name('menu.user');
    Route::post('/user', 'userCreate')->name('menu.user-create');
});



Route::controller(RoleController::class)->middleware('auth')->group(function () {

    Route::get('/role', 'roleIndex')->name('menu.role');
    Route::post('/role', 'roleCreation')->name('menu.role-creation');
    Route::put('/role/{id}', 'updateRole')->name('menu.role-update');
});


Route::controller(ItemController::class)->middleware('auth')->group(function () {
    Route::get('/items', 'index')->name('item.index');
    Route::post('/items', 'createItem')->name('item.create');
   
});




Route::controller(ItemCategoryController::class)->middleware('auth')->group(function () {
    Route::get('/item_category', 'index')->name('item_category.index');
    Route::post('/item_category', 'create_category')->name('item_category.create');
    Route::put('/item_category/{id}', 'update_category')->name('item_category.update');
});


Route::controller(StockController::class)->middleware('auth')->group(function(){
    Route::get('/stock', 'index')->name('stock.index');
    Route::post('/stock','StockIn')->name('stock.stockIn');
});
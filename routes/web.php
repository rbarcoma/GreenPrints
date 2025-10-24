<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Redirect;
use App\Http\Controllers\ManageInventoryController;

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
    Route::delete('/menu/{id}', 'destroy')->name('menu.destroy');


    // Menu Header part
    Route::get('/menu-header', 'menuHeaderIndex')->name('menu-header');
    Route::post('/menu-header', 'menuHeaderCreate')->name('menu-header.create');
    Route::put('/menu-header/{id}', 'menuHeaderUpdate')->name('menu-header.update');
    Route::delete('/menu-header/{id}', 'destroyMenuHeader')->name('menu-header.destroy');
});


Route::controller(UserController::class)->middleware('auth')->group(function () {

    Route::get('/user', 'userIndex')->name('menu.user');
    Route::post('/user', 'userCreate')->name('menu.user-create');
});


// Route::controller(RoleController::class)->middleware('auth')->group(function () {

//     Route::get('/role', 'roleIndex')->name('menu.role');
//     Route::post('/role', 'roleCreation')->name('menu.role-creation');
//     Route::put('/role/{id}', 'updateRole')->name('menu.role-update');
//     Route::delete('/role/{id}', 'destroy')->name('menu.role.destroy');
// });


// Route::controller(ItemController::class)->middleware('auth')->group(function () {
//     Route::get('/items', 'index')->name('item.index');
//     Route::post('/items', 'createItem')->name('item.create');
// });


// Route::controller(ItemCategoryController::class)->middleware('auth')->group(function () {
//     Route::get('/item_category', 'index')->name('item_category.index');
//     Route::post('/item_category', 'create_category')->name('item_category.create');
//     Route::put('/item_category/{id}', 'update_category')->name('item_category.update');
//     Route::delete('/item_category/{id}', 'destroy')->name('item_category.destroy');
// });


Route::controller(StockController::class)->middleware('auth')->group(function(){
    Route::get('/stock', 'index')->name('stock.index');
    Route::post('/stock','StockIn')->name('stock.stockIn');
});


// Route::controller(SaleController::class)->middleware('auth')->group(function () {
//     Route::get('/sales', 'index')->name('sales.index');
//     Route::post('/sales', 'store')->name('sales.store');
//     Route::post('/sales/add-item', 'addItem')->name('sales.addItem');
//     Route::get('/sales/{id}', 'show')->name('sales.show');
//     Route::delete('/sales/{id}', 'destroy')->name('sales.destroy');
// });

// Route::controller(ProductController::class)->middleware('auth')->group(function () {
//     Route::get('/products', 'index')->name('products.index');
//     Route::post('/products', 'store')->name('products.store');
//     Route::put('/products/{id}', 'update')->name('products.update');
//     Route::delete('/products/{id}', 'destroy')->name('products.destroy');
// });

// Route::controller(ProductRequirementController::class)->middleware('auth')->group(function () {
//     Route::post('/product-requirements', 'store')->name('product_requirements.store');

// });

// Route::controller(SaleController::class)->middleware('auth')->group(function () {
//     Route::get('/sales', 'index')->name('sales.index');
//     Route::post('/sales', 'store')->name('sales.store');
// });

// Route::controller(InventoryController::class)->middleware('auth')->group(function () {
//     Route::get('/inventory', 'index')->name('inventory.index');
//     Route::get('/inventory/items', 'items')->name('inventory.items');
//     Route::get('/inventory/batches', 'batches')->name('inventory.batches');
//     Route::get('/inventory/suppliers', 'suppliers')->name('inventory.suppliers');
//     Route::get('/inventory/logs', 'logs')->name('inventory.logs');
// });


Route::controller(ManageInventoryController::class)->middleware('auth')->group(function () {
    Route::get('/manage-inventory', 'index')->name('inventory.index');
    Route::post('/manage-inventory/item/add', 'addItem')->name('inventory.addItem');
    Route::put('/manage-inventory/item/update/{id}', 'updateItem')->name('inventory.updateItem');
    Route::delete('/manage-inventory/item/delete/{id}', 'deleteItem')->name('inventory.deleteItem');

    Route::post('/manage-inventory/batch/add', 'addBatch')->name('inventory.addBatch');
    Route::put('/manage-inventory/batch/update/{id}', 'updateBatch')->name('inventory.updateBatch');
    Route::delete('/manage-inventory/batch/delete/{id}', 'deleteBatch')->name('inventory.deleteBatch');

    Route::post('/manage-inventory/supplier/add', 'addSupplier')->name('inventory.addSupplier');
    Route::put('/manage-inventory/supplier/update/{id}', 'updateSupplier')->name('inventory.updateSupplier');
    Route::delete('/manage-inventory/supplier/delete/{id}', 'deleteSupplier')->name('inventory.deleteSupplier');

    Route::get('/manage-inventory/logs', 'viewLogs')->name('inventory.logs');
});

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\StockTransferController;
use App\Http\Controllers\ProductWarehouseController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
});



/**Supplier Routing Group Start*/
Route::middleware('auth:sanctum')->prefix('supplier')->group(function () {
    Route::get('/', [SupplierController::class, 'index']);
    Route::post('/', [SupplierController::class, 'store']);
    Route::put('/{id}', [SupplierController::class, 'update']);
    Route::get('/{id}', [SupplierController::class, 'show']);
    Route::delete('/{id}', [SupplierController::class, 'destroy']);
    
});
/**Supplier Routing Group End*/

/**Product Routing Group Start*/

Route::middleware('auth:sanctum')->prefix('products')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/', [ProductController::class, 'store']);
    Route::put('/{id}', [ProductController::class, 'update']);
    Route::get('/{id}', [ProductController::class, 'show']);
    Route::delete('/{id}', [ProductController::class, 'destroy']);

});
/**Product Routing Group End*/


/**Warehouse Routing Group Start*/

Route::middleware('auth:sanctum')->prefix('warehouse')->group(function () {
    Route::get('/', [WarehouseController::class, 'index']);
    Route::post('/', [WarehouseController::class, 'store']);
    Route::put('/{id}', [WarehouseController::class, 'update']);
    Route::get('/{id}', [WarehouseController::class, 'show']);
    Route::delete('/{id}', [WarehouseController::class, 'destroy']);

});


Route::middleware('auth:sanctum')->prefix('product-warehouse')->group(function () {
    Route::get('/', [ProductWarehouseController::class, 'index']);
    Route::post('/', [ProductWarehouseController::class, 'store']);
    Route::put('/{productId}/{warehouseId}', [ProductWarehouseController::class, 'update']);
    Route::get('/{productId}/{warehouseId}', [ProductWarehouseController::class, 'show']);
    Route::delete('/{productId}/{warehouseId}', [ProductWarehouseController::class, 'destroy']);

});

Route::post('/transfer-stock', [StockTransferController::class, 'transferStock'])->middleware('auth:sanctum');

/**Warehouse Routing Group End*/
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


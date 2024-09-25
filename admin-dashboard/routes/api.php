<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\API\ProductController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('/login', [AuthenticatedSessionController::class, 'store']);

Route::get('/products', [ProductController::class, 'index']);  // Fetch products with search, filter, and pagination
Route::delete('/products/{id}', [ProductController::class, 'destroy']);  // Delete a product by ID
Route::middleware('web')->post('/login', [AuthenticatedSessionController::class, 'store']);
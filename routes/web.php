<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Routes for the Product Management System.
| Each route maps to a controller method that handles the corresponding
| CRUD operation and returns JSON or a Blade view.
*/

// Main page view (loads the frontend)
Route::get('/', [ProductController::class, 'index'])->name('products.index');

// CRUD endpoints
Route::get('/products', [ProductController::class, 'list'])->name('products.list');   // Fetch all products
Route::post('/products', [ProductController::class, 'store'])->name('products.store'); // Create new product
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update'); // Update product
Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy'); // Delete product

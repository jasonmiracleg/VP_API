<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Route::middleware('auth:sanctum')->group(
//     function () {
Route::get('categories', [CategoryController::class, 'ListCategory']);
Route::post('create_category', [CategoryController::class, 'createCategory']);
Route::delete('delete_category', [CategoryController::class, 'deleteCategory']);
//     }
// );

Route::get('/all_user', [UserController::class, 'index']);

Route::post('/create_user', [UserController::class, 'createUser']);

Route::post('update_user', [UserController::class, 'updateUser']);

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GroupingController;

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
//
//GROUP & GROUPING
Route::get('groups', [GroupController::class, 'listGroup']);
Route::post('create_group', [GroupController::class, 'createGroup']);
Route::delete('delete_group', [GroupController::class, 'deleteGroup']);
Route::post('/groups/{groupId}/users/{userId}', [GroupingController::class, 'addUserToGroup']);


Route::get('/all_user', [UserController::class, 'index']);

Route::post('/create_user', [UserController::class, 'createUser']);

Route::post('update_user', [UserController::class, 'updateUser']);

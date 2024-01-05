<?php

use App\Http\Controllers\Api\AuthenticationController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ToDoListController;
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

Route::get('/all_user', [UserController::class, 'index']);
Route::post('/create_user', [UserController::class, 'createUser']);
Route::post('login', [AuthenticationController::class, 'logIn']);


Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('categories', [CategoryController::class, 'ListCategory']);
        Route::post('create_category', [CategoryController::class, 'createCategory']);
        Route::delete('delete_category', [CategoryController::class, 'deleteCategory']);

        Route::get('all_toDoList', [ToDoListController::class, 'allToDoList']);
        Route::get('today_toDoList', [ToDoListController::class, 'todayToDoList']);
        Route::post('create_toDoList', [ToDoListController::class, 'createToDoList']);
        Route::post('update_toDoList', [ToDoListController::class, 'editToDoList']);
        Route::delete('delete_toDoList', [ToDoListController::class, 'deleteToDoList']);

        Route::post('timer_start', [ToDoListController::class, 'startTimer']);
        Route::post('timer_stop', [ToDoListController::class, 'stopTimer']);
        Route::get('timer_state', [ToDoListController::class, 'getTimerState']);

        Route::post('update_user', [UserController::class, 'updateUser']);
        Route::delete('logout', [AuthenticationController::class, 'logOut']);
    }
);

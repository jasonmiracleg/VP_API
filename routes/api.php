<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\GroupingController;
use App\Http\Controllers\Api\ToDoListController;
use App\Http\Controllers\Api\AuthenticationController;

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

Route::get('all_user', [UserController::class, 'index']);
Route::post('create_user', [UserController::class, 'createUser']);
Route::post('login', [AuthenticationController::class, 'logIn']);


Route::middleware('auth:sanctum')->group(
    function () {
        Route::get('categories/{userId}', [CategoryController::class, 'ListCategory']);
        Route::post('create_category', [CategoryController::class, 'createCategory']);
        Route::delete('delete_category', [CategoryController::class, 'deleteCategory']);
        Route::post('categorizing/{toDoList}', [CategoryController::class, 'setCategory']);
        Route::get('toDoList-category/{category}', [CategoryController::class, 'categoryWithToDoList']);

        Route::get('all_toDoList/{userId}', [ToDoListController::class, 'allToDoList']);
        Route::get('today_toDoList/{userId}', [ToDoListController::class, 'todayToDoList']);
        Route::post('create_toDoList', [ToDoListController::class, 'createToDoList']); //ilangin parameter dlu aku pinjem
        Route::post('update_toDoList/{toDoList}', [ToDoListController::class, 'editToDoList']);
        Route::get('tasks-byDay', [ToDoListController::class, 'getTasksByDayName']);
        Route::delete('delete_toDoList', [ToDoListController::class, 'deleteToDoList']);
        Route::post('create-toDoList-group/', [ToDoListController::class, 'createToDoListGroup']);
        Route::post('group/{group}/toDoList/{toDoList}', [ToDoListController::class, 'editToDoListGroup']);
        Route::get('group-toDoList/{groupId}', [ToDoListController::class, 'getAllGroupTasks']);

        //GROUP & GROUPING
        Route::get('groups/{userId}', [GroupController::class, 'listGroup']);
        Route::post('create_group', [GroupController::class, 'createGroup']);
        Route::delete('delete_group', [GroupController::class, 'deleteGroup']);
        Route::post('groups/{groupId}/users/{userId}', [GroupingController::class, 'addUserToGroup']);
        Route::get('group-members/{group}', [GroupingController::class, 'getMembers']);

        Route::post('timer_start', [ToDoListController::class, 'startTimer']);
        Route::post('timer_stop', [ToDoListController::class, 'stopTimer']);
        Route::get('timer_state', [ToDoListController::class, 'getTimerState']);

        Route::post('update_user', [UserController::class, 'updateUser']);
        Route::delete('logout', [AuthenticationController::class, 'logOut']);
    }
);

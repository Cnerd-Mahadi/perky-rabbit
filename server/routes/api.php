<?php

use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
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

Route::post('/signup', [UserController::class, 'signup']);
Route::get('/login', [UserController::class, 'login']);
Route::middleware('auth:sanctum')->get('/logout', [UserController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/employee', [EmployeeController::class, 'get']);
Route::middleware('auth:sanctum')->get('/employee/getAll', [EmployeeController::class, 'getAll']);
Route::middleware('auth:sanctum')->get('/employee/getCategories', [EmployeeController::class, 'getCategories']);
Route::middleware('auth:sanctum')->post('/employee/create', [EmployeeController::class, 'create']);
Route::middleware('auth:sanctum')->post('/employee/update', [EmployeeController::class, 'update']);
Route::middleware('auth:sanctum')->get('/employee/filtered/department', [EmployeeController::class, 'filteredByDept']);
Route::middleware('auth:sanctum')->get('/employee/filtered/achievement', [EmployeeController::class, 'filteredByAchievement']);


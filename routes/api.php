<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/employees', 'App\Http\Controllers\EmployeeController@index');

Route::post('/employees', 'App\Http\Controllers\EmployeeController@add_node');

Route::get('/employees/get_children/{employee}', 'App\Http\Controllers\EmployeeController@get_children');

Route::post('/employees/update_parent/{employee}', 'App\Http\Controllers\EmployeeController@update_parent');

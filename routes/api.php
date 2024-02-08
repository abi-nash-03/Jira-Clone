<?php

use App\Http\Controllers\TaskController;
use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;

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


Route::middleware('auth')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'prefix'=> 'auth',
    'middleware'=> 'auth'
], function($router){
    Route::post('logout','AuthController@logout');
    Route::post('refresh',[AuthController::class,'refresh']);
    Route::post('me',[AuthController::class,'me']);
});

Route::post('login','AuthController@login');
Route::post('register','AuthController@register');


Route::group([  
    'middleware'=>'auth'
    ], function($router){
        Route::get('/tasks', 'TaskController@showAllTask');
        Route::post('/tasks', 'TaskController@store');
        Route::delete('/delete/{id}','TaskController@delete');
        Route::post('/update/{id}','TaskController@update');
        Route::get('/tasksByUser','TaskController@getTaskByUser');
        Route::get('/tasksByFilter','TaskController@getByStatus');
        Route::post('/taskAssign','TaskController@assign');
    });

    // Route::gro
Route::get('/getUserDetails/{id}','TaskController@getUserDetails');
// Route::get('/getTaskDetails/{id}','TaskController@getTaskDetails')->middleware('auth:api');
Route::get('/getTaskDetails/{id}','TaskController@getTaskDetails'); 
Route::get('/getTaskAssignees/{id}','TaskController@getAssigneeProfiles');
Route::get('/getTagName/{id}','TaskController@getTagName');
Route::get('/getAssigneesEmailOfTask/{id}','TaskController@getAssigneesEmailOfTask');
Route::get('/getUsers','TaskController@getUsers');
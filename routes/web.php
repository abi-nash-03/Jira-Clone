<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Auth::routes();

Route::get('/task/create','TaskControllerWeb@createTask');
Route::get('/home', 'TaskControllerWeb@getTaskDashboards')->name('home');
Route::get('/board', 'TaskControllerWeb@getBoard');
Route::post('/task/create','TaskControllerWeb@storeTask');
Route::get('/task/{id}/edit','TaskControllerWeb@editTask');
Route::post('/task/{id}','TaskControllerWeb@updateTask');
Route::delete('/task/destroy/{id}','TaskControllerWeb@destroyTask');
Route::post('/share', 'TaskControllerWeb@share');
Route::get('/search','TaskControllerWeb@search');
Route::post('/user/board', 'TaskControllerWeb@userBoard');
Route::get('/demo', 'TaskControllerWeb@demo');
<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', 'HomeController@index');
Route::get('/runProsses', 'HomeController@runProsses');
Route::get('/symfonyProcess', 'HomeController@symfonyProcess');
Route::get('/chromePdf', 'HomeController@chromePdf');
Route::get('/phpchromepdf', 'HomeController@phpchromepdf');
Route::get('/htmlTopdf', 'HomeController@htmlTopdf');//unix
Route::get('/httpCurl', 'HomeController@httpCurl');
Route::get('/test', 'HomeController@test');

/*Route::get('/', function () {
    return view('welcome');
});*/

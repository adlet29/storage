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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

Route::get('/store', 'App\Http\Controllers\MainController@index')->middleware(['auth']);
Route::post('/add_store', 'App\Http\Controllers\MainController@create')->middleware(['auth']);
Route::get('/send/{id}', 'App\Http\Controllers\MainController@send')->middleware(['auth']);
Route::get('/incoming', 'App\Http\Controllers\MainController@inlist')->middleware(['auth']);
Route::get('/move', 'App\Http\Controllers\MainController@move')->middleware(['auth']);
Route::get('/save/{id}', 'App\Http\Controllers\MainController@save')->middleware(['auth']);

require __DIR__.'/auth.php';

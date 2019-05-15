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
use Illuminate\Http\Request;

Route::get('/', function () {
    return view('welcome');
});

Route::post('one_piece','LineController@one_piece');
Route::post('pushMultiImage','LineController@pushMultiImage');
Route::post('push','LineController@push');
Route::post('pushImage','LineController@pushImage');
Route::post('horny_dragon','LineController@horny_dragon');

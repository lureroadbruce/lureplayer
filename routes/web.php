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
Route::get('/', 'ExcelsController@importView');

Route::post('/excel/import', 'ExcelsController@excelImport');


// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/pic2', function () {
    return view('pic2');
});
Route::get('/pic3', function () {
    return view('pic3');
});
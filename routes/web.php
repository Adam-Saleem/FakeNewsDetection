<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::get('/ar', function () {
    return view('welcome_ar');
});

Route::Post('/text-test', [\App\Http\Controllers\FNDController::class, 'Text_Test']);
Route::Post('/url-test', [\App\Http\Controllers\FNDController::class, 'Url_Test']);


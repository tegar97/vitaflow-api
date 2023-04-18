<?php

use App\Http\Controllers\FoodController;
use App\Http\Controllers\FoodServingController;
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


//food serving route resource
Route::resource('foodServing', FoodServingController::class);
Route::resource('food', FoodController::class);
Route::get('/', function () {
    return view('welcome');
});

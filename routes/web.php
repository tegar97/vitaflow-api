<?php

use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\ExerciseTypeController;
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
Route::resource('exercise', ExerciseController::class);
Route::resource('exerciseType', ExerciseTypeController::class);
Route::resource('workoutdays', Workout::class);

Route::get('exercise/{id}/workout', [ExerciseController::class, 'ExerciseWorkout'])->name('listExerciseWorkout');
Route::get('exercise/{id}/workout/create', [ExerciseController::class, 'createExerciseWorkout'])->name('createExerciseWorkout');
// store
Route::post('exercise/{id}/workout', [ExerciseController::class, 'storeExerciseWorkout'])->name('storeExerciseWorkout');




Route::get('/', function () {
    return view('welcome');
});

<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\MissionController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// best pratice endpoint
// genereate with version api

Route::group([
    'prefix' => 'v1'
], function () {

    // Vitamart
    // category
    Route::get("categories", [CategoryController::class, 'getCategoryData']);
    Route::get('products', [ProductController::class, 'getProductByCategory']);
    Route::get('product/{productId}', [ProductController::class, 'getProductDetail']);
    Route::post('product/search', [ProductController::class, 'searchProduct']);
    Route::get('articles', [ArticleController::class, 'getArticleData']);
    Route::get('article/{articleId}', [ArticleController::class, 'getArticleDetail']);


    // food
    Route::get("foods", [FoodController::class, 'getFoodData']);
    Route::get("food/search", [FoodController::class, 'searchFood']);
    // getFoodDetail
    Route::get("food/{foodId}", [FoodController::class, 'getFoodDetail']);
    Route::get('/food/{foodId}/unit', [FoodController::class, 'getFoodServingUnit']);


    Route::get("programs", [ProgramController::class, 'index']);
    Route::get("programs/{id}", [ProgramController::class, 'show']);


    // Missions
    Route::get("missions", [MissionController::class, 'index']);
    Route::get("missions/{id}", [MissionController::class, 'show']);
    Route::middleware('api.key')->group(function () {
        // route yang memerlukan key API
        Route::post("programs", [ProgramController::class, 'store']);
        Route::put("programs/{id}", [ProgramController::class, 'update']);
        Route::delete("programs/{id}", [ProgramController::class, 'destroy']);

        // Missions
        Route::post("missions", [MissionController::class, 'store']);
        Route::put("missions/{id}", [MissionController::class, 'update']);
        Route::delete("missions/{id}/{program_id}", [MissionController::class, 'destroy']);

    });





    Route::post('survey', [UserController::class, 'survey']);

    // getMy program
    Route::get('myprograms', [UserController::class, 'getMyPrograms']);
    // exit program
    Route::post('exitprogram', [UserController::class, 'exitProgram']);

    Route::get('/getDailyData', [UserController::class, 'getDailyUserData']);


    // drink mission
    Route::post('//drink/store', [UserController::class, 'storeDrink']);
    Route::get("/drink/history", [UserController::class, 'getUserDrinks']);

    // store weight track
    Route::post('/weight-track/store', [UserController::class, 'storeWeightTrackData']);
    Route::get('/weight-track/history', [UserController::class, 'getUserWeightTrackData']);

    // step track
    Route::post('/step-track/store', [UserController::class, 'storeStepTrackData']);
    Route::get('/step-track/history', [UserController::class, 'getUserStepTrackData']);

    // health track

    Route::post('/health-track/store', [UserController::class, 'storeHealthTrackData']);
    Route::get('/health-track/history', [UserController::class, 'getUserHealthTrackData']);


    // Exercise track data
    Route::post('/exercise-track/store', [UserController::class, 'storeExerciseTrackData']);
    Route::get('/exercise-track/calori-burn', [UserController::class, 'getSportCaloriBurn']);
    Route::get('/exercise-track/history', [UserController::class, 'getUseSportAcivityData']);

    // food
    Route::post('/foods-track/store', [UserController::class, 'storeFoodTrackData']);
    Route::get('/foods-track/history', [UserController::class, 'getUserFoodTrackData']);



    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('login', [AuthController::class, 'login']);
        Route::post('register', [AuthController::class, 'register']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('me', [AuthController::class, 'me']);


            // get daily users data



    });
});





// Route::group([
//     'middleware' => 'api',
//     'prefix' => 'auth'
// ], function ($router) {
//     Route::post('/login',  [AuthController::class, 'login'])->name('login');
//     //register
//     Route::post('/register', [AuthController::class, 'register']);
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::post('/refresh', [AuthController::class, 'refresh']);
//     Route::post('/me', [AuthController::class, 'me']);


// });


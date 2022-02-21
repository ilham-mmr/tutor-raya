<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TutorFavoritesController;
use App\Http\Controllers\Api\TutoringController;
use App\Http\Controllers\Api\UserProfileController;
use Illuminate\Support\Facades\Route;

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



Route::get('login/{provider}', [AuthController::class, 'redirectToProvider'])->name('providerRedirect');
// Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('providerCallback');





// book
Route::group(['middleware' => ['api', 'auth:sanctum']], function () {
    Route::post('/logout', [AuthController::class, 'logout']);


    Route::apiResource('tutorings', TutoringController::class)->except('store', 'update', 'delete');

    // favorite feature
    Route::controller(TutorFavoritesController::class)->group(function () {
        Route::get('/tutor-favorites', 'index');
        Route::post('/tutor-favorites', 'store');
        Route::put('/tutor-favorites', 'update');
        Route::delete('/tutor-favorites/{id}', 'destroy');
    });

    // get user profile
    Route::get('/users/{user}/profile', [UserProfileController::class, 'show']);
    // update profile
    Route::put('/users/{user}/profile', [UserProfileController::class, 'update']);

    //booking


    // middleware isTutor

});

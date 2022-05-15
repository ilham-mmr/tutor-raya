<?php

use App\Http\Controllers\Api\AuthMobileController;
use App\Http\Controllers\Api\TutorFavoritesController;
use App\Http\Controllers\Api\TutoringController;
use App\Http\Controllers\Api\UserProfileController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\TutorAPIController;
use App\Http\Resources\CategoryResource;
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



// Route::get('login/{provider}', [AuthController::class, 'redirectToProvider'])->name('providerRedirect');
// Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('providerCallback');


Route::post('login/mobile', [AuthMobileController::class, 'loginMobile']);
 //tutor
 Route::controller(TutorAPIController::class)->group(function () {
    Route::get('/tutors', 'getTutors');
    Route::get('/tutors/{tutor}', 'detailTutors');
});

// book
Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::post('/logout', [AuthMobileController::class, 'logout']);


    Route::apiResource('tutorings', TutoringController::class)->except('store', 'update', 'delete');

    Route::get('/categories', [CategoryController::class, 'index']);

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

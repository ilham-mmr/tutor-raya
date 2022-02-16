<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TutoringController;
use App\Http\Controllers\UserProfileController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::apiResource('tutorings', TutoringController::class)->except('store', 'update', 'delete');

// get user profile
// update profile

// add to favorite

// book
Route::group(['middleware' => ['api', 'auth:sanctum']], function () {
    // Route::resource('products', ProductController::class)->only(['store','update','destroy']);
    Route::post('/logout', [AuthController::class, 'logout']);
    // Route::apiResource('users/profile', UserProfileController::class)->except('store', 'update', 'delete';


    // middleware isTutor

});

Route::get('login/{provider}', [AuthController::class, 'redirectToProvider'])->name('providerRedirect');
Route::get('login/{provider}/callback', [AuthController::class, 'handleProviderCallback'])->name('providerCallback');

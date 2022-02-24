<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\AuthController as ApiAuth;
use App\Http\Controllers\HomeController;
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

Route::middleware('guest')->group(function () {
    Route::get('/sign-in', [AuthController::class, 'index'])->name('sign-in')->middleware('guest');
    Route::get('/sign-in/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.web');
    Route::get('/api/login/{provider}/callback', [ApiAuth::class, 'handleProviderCallback'])->name('providerCallback');
});


Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard.home');

    Route::get('/home/tutor/profile', [HomeController::class, 'profile']);
    Route::post('/home/tutor/profile', [HomeController::class, 'storeProfile'])->name('web-profile.store');

    Route::get('/home/tutor/add-tutoring', [HomeController::class, 'addTutoring'])->name('web-tutoring.create');
    Route::post('/home/tutor/add-tutoring', [HomeController::class, 'storeTutoring'])->name('web-tutoring.store');

    Route::get('/home/tutor/view-tutoring', [HomeController::class, 'viewTutoring']);

    Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');
});

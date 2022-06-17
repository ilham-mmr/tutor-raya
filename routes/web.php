<?php

use App\Http\Controllers\Api\TutoringController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\APIController;
use App\Http\Controllers\DropzoneController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use App\Models\Tutoring;
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

Route::get('/', function () {
    return redirect('/home');
});

Route::get('/book', [BookingController::class, 'book']);





Route::middleware('guest')->group(function () {

    Route::get('/sign-in', [AuthController::class, 'index'])->name('sign-in')->middleware('guest');
    Route::get('/sign-in/{provider}', [AuthController::class, 'redirectToProvider'])->name('auth.web');
    Route::get('/sign-in/{provider}/callback', [AuthController::class, 'handleProviderCallback']);
    // Route::get('/api/login/{provider}/callback', [ApiAuth::class, 'handleProviderCallback'])->name('providerCallback');
});


Route::middleware(['auth:web'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('dashboard.home');

    Route::get('/home/tutor/profile', [ProfileController::class, 'index'])->name('profile');
    Route::post('/home/tutor/profile', [ProfileController::class, 'store'])->name('web-profile.store');

    Route::get('/home/tutor/add-tutoring', [TutoringController::class, 'addTutoring'])->name('web-tutoring.create');
    Route::post('/home/tutor/add-tutoring', [TutoringController::class, 'storeTutoring'])->name('web-tutoring.store');

    Route::get('/home/tutor/edit-tutoring/{tutoring}', [TutoringController::class, 'editTutoring'])->name('web-tutoring.edit');
    Route::put('/home/tutor/edit-tutoring/{tutoring}', [TutoringController::class, 'updateTutoring'])->name('web-tutoring.update');
    Route::delete('/home/tutor/delete-tutoring/{tutoring}', [TutoringController::class, 'deleteTutoring'])->name('web-tutoring.delete');


    Route::get('/home/tutor/view-tutoring', [TutoringController::class, 'viewTutoring']);



    Route::post('/logout', [AuthController::class, 'logout'])->name('web.logout');

    Route::get('/home/tutor/booked-sessions', [BookingController::class, 'index'])->name('booked-session.index');
    Route::post('/home/tutor/booked-sessions/{booking}', [BookingController::class, 'action'])->name('booked-session.action');

    Route::post('/home/tutor/booked-sessions/{booking}/meeting-link', [BookingController::class, 'createMeetingLink'])->name('booked-session.meeting-link');


    Route::post('activities/upload', [DropzoneController::class, 'upload'])->name('dropzone.upload');
    Route::get('activities/fetch', [DropzoneController::class, 'fetch'])->name('dropzone.fetch');
    Route::get('activities/delete', [DropzoneController::class, 'delete'])->name('dropzone.delete');
});

Route::get('bookWithPaymentGateway', [BookingController::class, 'bookWithPaymentGateway']);

<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\VolunteerController;
use App\Http\Controllers\EvacuationPointController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('oauth/google', [\App\Http\Controllers\OauthController::class, 'redirectToProvider'])->name('oauth.google');
Route::get('oauth/google/callback', [\App\Http\Controllers\OauthController::class, 'handleProviderCallback'])->name('oauth.google.callback');



// Route::middleware(['auth', 'role:Admin'])->group(function () {
Route::middleware(['auth' ])->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->middleware(['role:Admin' ])->name('dashboard');
    Route::view('profile', 'profile')->name('profile');
    
    Route::prefix('volunteer')->name('volunteer.')->group(function () {
        Route::get('/', [VolunteerController::class, 'index'])->name('index');
        Route::post('verify/{id}', [VolunteerController::class, 'verify'])->name('verify');
    });
    Route::prefix('evacuation')->name('evacuation.')->group(function () {
        Route::get('/', [EvacuationPointController::class, 'index'])->name('index');
        Route::get('create', [EvacuationPointController::class, 'create'])->name('create');
        Route::post('store', [EvacuationPointController::class, 'store'])->name('store');
        Route::get('edit/{id}', [EvacuationPointController::class, 'edit'])->name('edit');
        Route::put('update/{id}', [EvacuationPointController::class, 'update'])->name('update');
        Route::get('delete/{id}', [EvacuationPointController::class, 'delete'])->name('delete');
    });
});
Route::middleware(['auth', 'role:Volunteer'])->group(function () {
    
});


require __DIR__ . '/auth.php';

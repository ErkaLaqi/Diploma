<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\AccountController;

Route::get('/', [HomeController::class,'index'])->name('home');


Route::group(['account'], function (){
    //Guest Route
    Route::group(['middleware'=> 'guest'], function (){
        Route::get('/register', [AccountController::class,'registration'])->name('account.registration');
        Route::post('/process-register', [AccountController::class,'processRegistration'])->name('account.processRegistration');
        Route::get('/login', [AccountController::class,'login'])->name('account.login');
        Route::post('/authenticate', [AccountController::class,'authenticate'])->name('account.authenticate');

    });
    //Authenticated Routes
    Route::group(['middleware'=> 'auth'], function (){
        Route::get('/profile', [AccountController::class,'profile'])->name('account.profile');
        Route::put('/profile-update', [AccountController::class,'profileUpdate'])->name('account.profileUpdate');
        Route::get('/logout', [AccountController::class,'logout'])->name('account.logout');
        Route::post('/profile-photo-update', [AccountController::class,'updateProfilePhoto'])->name('account.updateProfilePhoto');
        Route::get('/create-job', [AccountController::class,'createJob'])->name('account.createJob');
        Route::post('/save-job', [AccountController::class,'saveJob'])->name('account.saveJob');
        Route::get('/my-jobs', [AccountController::class,'myJobs'])->name('account.myJobs');
        Route::get('/my-jobs/edit/{jobId}', [AccountController::class,'editJobs'])->name('account.editJobs');
        Route::post('/update-job/{jobId}', [AccountController::class,'updateJob'])->name('account.updateJob');
        Route::post('/delete-job', [AccountController::class,'deleteJob'])->name('account.deleteJob');

    });
});

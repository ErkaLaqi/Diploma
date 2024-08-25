<?php

use App\Http\Controllers\JobsController;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\HomeController;
use \App\Http\Controllers\AccountController;

Route::get('/', [HomeController::class,'index'])->name('home');

Route::get('/jobs', [JobsController::class,'index'])->name('jobs');
Route::get('/jobs/detail/{id}', [JobsController::class,'detail'])->name('jobDetail');
Route::post('/apply-job', [JobsController::class,'applyJob'])->name('applyJob');
Route::post('/saved-job', [JobsController::class, 'savedJob'])->name('savedJob');



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
        Route::get('/my-job-applications', [AccountController::class,'myJobApplications'])->name('account.myJobApplications');
        Route::post('/remove-job-application', [AccountController::class,'removeJobs'])->name('account.removeJobs');
        Route::get('/fetch-saved-jobs', [AccountController::class,'fetchSavedJobs'])->name('account.fetchSavedJobs');
        Route::post('/remove-saved-job', [AccountController::class,'removeSavedJob'])->name('account.removeSavedJob');

    });
});

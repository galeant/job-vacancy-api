<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\JobVacancyController;
use App\Http\Controllers\AuthCompanyController;
use App\Http\Controllers\AuthApplicantController;
use App\Http\Middleware\NullAbleTokenMiddleware;

Route::group(['prefix' => 'company','as' => 'company.'], function () {
    Route::post('/register', [AuthCompanyController::class, 'register'])->name('register');
    Route::post('/login', [AuthCompanyController::class, 'login'])->name('login');

    Route::middleware('auth:company-api')->group(function () {
        Route::post('/logout', [AuthCompanyController::class, 'logout'])->name('logout');
        Route::get('/profile', [CompanyController::class, 'profile'])->name('profile');
        Route::post('/profile', [CompanyController::class, 'updateProfile'])->name('profile-update');
    });
});


Route::group(['prefix' => 'applicant','as' => 'applicant.'], function () {
    Route::post('/register', [AuthApplicantController::class, 'register'])->name('register');
    Route::post('/login', [AuthApplicantController::class, 'login'])->name('login');

    Route::middleware('auth:applicant-api')->group(function () {
        Route::post('/logout', [AuthApplicantController::class, 'logout'])->name('logout');
        Route::get('/profile', [ApplicantController::class, 'profile'])->name('profile');
        Route::post('/profile', [ApplicantController::class, 'updateProfile'])->name('profile-update');
    });
});

Route::group(['prefix' => 'vacancies','as' => 'vacancy.','middleware' => [NullAbleTokenMiddleware::class]], function () {

    Route::get('/', [JobVacancyController::class, 'getList'])->name('list');

    Route::middleware('auth:applicant-api')->group(function () {
        Route::post('/apply', [JobVacancyController::class, 'apply'])->name('apply');
        Route::get('/job-apply', [JobVacancyController::class, 'jobApply'])->name('job-apply');
    });

    Route::middleware('auth:company-api')->group(function () {
        Route::post('/', [JobVacancyController::class, 'create'])->name('create');
        Route::post('/{vacancy}', [JobVacancyController::class, 'update'])->name('update');
        Route::get('/{vacancy}/applied', [JobVacancyController::class, 'applied'])->name('applied');
        Route::post('/{vacancy}/publish', [JobVacancyController::class, 'publish'])->name('publish');
        Route::post('/{vacancy}/inactivate', [JobVacancyController::class, 'inactivate'])->name('inactivate');
    });
});


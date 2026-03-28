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
    Route::post('/register', [AuthCompanyController::class, 'register']);
    Route::post('/login', [AuthCompanyController::class, 'login']);

    Route::middleware('auth:company-api')->group(function () {
        Route::post('/logout', [AuthCompanyController::class, 'logout']);
        Route::get('/profile', [CompanyController::class, 'profile']);
        Route::post('/profile', [CompanyController::class, 'updateProfile']);
    });
});


Route::group(['prefix' => 'applicant','as' => 'applicant.'], function () {
    Route::post('/register', [AuthApplicantController::class, 'register']);
    Route::post('/login', [AuthApplicantController::class, 'login']);

    Route::middleware('auth:applicant-api')->group(function () {
        Route::post('/logout', [AuthApplicantController::class, 'logout']);
        Route::get('/profile', [ApplicantController::class, 'profile']);
        Route::post('/profile', [ApplicantController::class, 'updateProfile']);
    });
});

Route::group(['prefix' => 'vacancies','as' => 'vacancy.','middleware' => [NullAbleTokenMiddleware::class]], function () {

    Route::get('/', [JobVacancyController::class, 'getList']);

    Route::middleware('auth:applicant-api')->group(function () {
        Route::post('/apply', [JobVacancyController::class, 'apply']);
    });

    Route::middleware('auth:company-api')->group(function () {
        Route::post('/', [JobVacancyController::class, 'create']);
        Route::post('/{vacancy}', [JobVacancyController::class, 'update']);
        Route::post('/{vacancy}/publish', [JobVacancyController::class, 'publish']);
        Route::delete('/{vacancy}/inactivate', [JobVacancyController::class, 'inactivate']);
    });


});


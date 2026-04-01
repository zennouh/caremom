<?php

use App\Http\Controllers\EnterpriseJobController;
use App\Http\Controllers\MomJobController;
use Illuminate\Support\Facades\Route;

Route::prefix('mom')->group(function () {

    Route::post('/', [MomJobController::class, 'index']);
    Route::get('{id}', [MomJobController::class, 'show']);

    Route::post('{jobId}/apply', [MomJobController::class, 'apply']);

    Route::get('applications/{userId}', [MomJobController::class, 'myApplications']);
});

Route::prefix('enterprise')
    ->controller(EnterpriseJobController::class)
    ->group(function () {

        Route::post('/', 'store');
        Route::put('{id}', 'update');
        Route::delete('{id}', 'destroy');

        Route::get('{jobId}/applicants', 'applicants');
    });

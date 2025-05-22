<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\CompanyController;


Route::prefix('v1')->group(function () {
    
    Route::apiResource('companies', CompanyController::class);

    Route::get('/companies/by-nit/{nit}', [CompanyController::class, 'show']);

});
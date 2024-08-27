<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\AreaController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout']);

Route::middleware('check.jwt')->group(function () {
    Route::get('/kab', [AreaController::class, 'getKab']);
    Route::get('/kec/{idKab}', [AreaController::class, 'getKecByKab']);
    Route::get('/des/{idKec}', [AreaController::class, 'getDesByKec']);
    Route::get('/bs/{idDes}', [AreaController::class, 'getBsByDes']);

    Route::resource('companies', CompanyController::class);

});

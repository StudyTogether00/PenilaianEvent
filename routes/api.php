<?php

use App\Http\Controllers\BE\MstData\KriteriaController;
use App\Http\Controllers\BE\MstData\MstEventController;
use App\Http\Controllers\PenggunaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::prefix('MasterData')->group(function () {
    Route::prefix('Event')->group(function () {
        Route::post('List', [MstEventController::class, "Lists"]);
        Route::post('Save', [MstEventController::class, "Save"]);
        Route::post('Delete', [MstEventController::class, "Delete"]);
    });
    Route::prefix('Kriteria')->group(function () {
        Route::post('List', [KriteriaController::class, "Lists"]);
        Route::post('Save', [KriteriaController::class, "Save"]);
        Route::post('Delete', [KriteriaController::class, "Delete"]);
    });
});

Route::post("/test", [PenggunaController::class, "index"]);

Route::post("/List", [PenggunaController::class, "lists"]);

Route::prefix('Msseleksi')->group(function () {
    Route::post("/List", [PenggunaController::class, "lists"]);
    Route::post("/Save", [PenggunaController::class, "save"]);
    Route::post("/Delete", [PenggunaController::class, "delete"]);
});

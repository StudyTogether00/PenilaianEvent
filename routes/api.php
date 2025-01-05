<?php

use App\Http\Controllers\BE\MstData\MstBobotController;
use App\Http\Controllers\BE\MstData\MstEventController;
use App\Http\Controllers\BE\MstData\MstKriteriaController;
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
        Route::post('List', [MstKriteriaController::class, "Lists"]);
        Route::post('Save', [MstKriteriaController::class, "Save"]);
        Route::post('Delete', [MstKriteriaController::class, "Delete"]);
    });
    Route::prefix('Bobot')->group(function () {
        Route::post('List', [MstBobotController::class, "Lists"]);
        Route::post('DataBobot', [MstBobotController::class, "DataBobot"]);
        Route::post('KriteriaReady', [MstBobotController::class, "KriteriaReady"]);
        Route::post('Save', [MstBobotController::class, "Save"]);
        Route::post('Delete', [MstBobotController::class, "Delete"]);
    });
});

Route::post("/test", [PenggunaController::class, "index"]);

Route::post("/List", [PenggunaController::class, "lists"]);

Route::prefix('Msseleksi')->group(function () {
    Route::post("/List", [PenggunaController::class, "lists"]);
    Route::post("/Save", [PenggunaController::class, "save"]);
    Route::post("/Delete", [PenggunaController::class, "delete"]);
});

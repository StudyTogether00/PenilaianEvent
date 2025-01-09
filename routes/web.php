<?php

use App\Http\Controllers\FE\MasterDataController;
use App\Http\Controllers\FE\RouteController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Route::get("/", [RouteController::class, "Dashboard"])->middleware("websession");
Route::get("/Login", [RouteController::class, "Login"]);
Route::post("SignIn", [RouteController::class, "SignIn"]);
Route::post("DestroySession", [RouteController::class, "DestroySession"]);
Route::prefix("MasterData")->middleware("websession")->group(function () {
    Route::get("Event", [MasterDataController::class, "Event"]);
    Route::get("Kriteria", [MasterDataController::class, "Kriteria"]);
    Route::get("Bobot", [MasterDataController::class, "Bobot"]);
    Route::get("Peserta", [MasterDataController::class, "Peserta"]);
    Route::get("Nilai", [MasterDataController::class, "Nilai"]);
});

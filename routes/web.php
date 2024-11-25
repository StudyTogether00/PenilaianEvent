<?php

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

Route::get("/", [RouteController::class, "Dashboard"]);

Route::get('/Normalisasi', function () {
    return view('pages/Normalisasi');
});
Route::get('/laporan', function () {
    return view('pages/laporan');
});
Route::get('/datanilai', function () {
    return view('pages/datanilai');
});
Route::get('/datapeserta', function () {
    return view('pages/datapeserta');
});
Route::get('/databobot', function () {
    return view('pages/databobot');
});
Route::get('/datakriteria', function () {
    return view('pages/datakriteria');
});
Route::get('/dataevent', function () {
    return view('pages/dataevent');
});
Route::get('/datapengguna', function () {
    return view('pages/datapengguna');
});
Route::get('/ujicoba', function () {
    return view('pages/ujicoba');
});

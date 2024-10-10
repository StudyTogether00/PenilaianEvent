<?php

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


Route::get('/',function (){
    return view('pages/dashboard');
});

Route::get('/pages/Normalisasi',function (){
    return view('pages/Normalisasi');
});
Route::get('/pages/laporan',function (){
    return view('pages/laporan');
});
Route::get('/pages/datanilai',function (){
    return view('pages/datanilai');
});
Route::get('/pages/datapeserta',function (){
    return view('pages/datapeserta');
});
Route::get('/pages/databobot',function (){
    return view('pages/databobot');
});
Route::get('/pages/datakriteria',function (){
    return view('pages/datakriteria');
});
Route::get('/pages/dataevent',function (){
    return view('pages/dataevent');
});
Route::get('/pages/datapengguna',function (){
    return view('pages/datapengguna');
});
Route::get('/pages/ujicoba',function (){
    return view('pages/ujicoba');
});

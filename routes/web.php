<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/videos/{id}', function ($id) {
    $downloads = Redis::get("videos.{$id}.downloads");

    return view('welcome', compact('downloads'));
});

Route::get('/videos/{id}/download', function ($id) {
    // Prepare the download.
    Redis::incr("videos.{$id}.downloads");

    return back();
});

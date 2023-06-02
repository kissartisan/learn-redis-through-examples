<?php

use App\Models\Article;
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

Route::get('/articles/trending', function (Article $article) {
    $trending = Redis::zrevrange('trending_articles', 0, 2);

    $trending = Article::hydrate(
        array_map('json_decode', $trending)
    );

    return $trending;
});

Route::get('/articles/{article}', function (Article $article) {
    Redis::zincrby('trending_articles', 1, $article);

    // Remove the items that are not in the top 3
    // This code is the clean up Redis value if they got bloated.
    // This can be run on a CRON JOB to eventually remove items from Redis
    // Redis::zremrangebyrank('trending_articles', 0, -4);

    return $article;
});

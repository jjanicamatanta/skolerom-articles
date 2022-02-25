<?php

use App\Http\Controllers\ArticleController;
use App\Models\GrepGoalRelationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('filterarticle/v1/post/', [ArticleController::class, 'index'])->middleware('cors');

Route::get('post/{post}/childs/', [ArticleController::class, 'getChildArticles'])->middleware('cors');
<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\PostCommentController;

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

// URI: api/v1/..., name: api.v1....
Route::prefix('v1')->name('api.v1.')->group(function(){
    // ukozkova route bez kontrolera
    Route::get('/status', function (){
        return response()->json(['status' => 'OK']);
    })->name('status');
    // routa s kontrolerom
    Route::apiResource('posts.comments', PostCommentController::class);
});

// path potom vyzera api/v2/status
Route::prefix('v2')->name('api.v2.')->group(function(){
    Route::get('/status', function (){
        return response()->json(['status' => true]);
    })->name('status');
});

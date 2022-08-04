<?php

use App\Http\Controllers\FrontContoller;
use Illuminate\Http\Request;
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

require __DIR__ . '/auth.php';

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/get-all', [FrontContoller::class, 'getAll']);
    Route::get('/social/{id}', [FrontContoller::class, 'getSocial']);
    Route::post('/social', [FrontContoller::class, 'createOrEdit']);
    Route::post('/social/{id}', [FrontContoller::class, 'createOrEdit']);
    Route::delete('/social/delete/{id}', [FrontContoller::class, 'deleteItem']);
    Route::post('/generate/text', [FrontContoller::class, 'generateText']);
    Route::post('/generate/image', [FrontContoller::class, 'generateImage']);
});

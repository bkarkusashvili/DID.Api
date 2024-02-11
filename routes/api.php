<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\FrontContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\FetchUserData;
use App\Http\Controllers\MailController;
use App\Http\Controllers\PayzeController;


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
    // Route::post('/social', [FrontContoller::class, 'createOrEdit']);
    Route::post('/social/{id}', [FrontContoller::class, 'createOrEdit']);
    Route::delete('/social/delete/{id}', [FrontContoller::class, 'deleteItem']);
    Route::post('/generate/text', [FrontContoller::class, 'generateText']);
    Route::post('/generate/image', [FrontContoller::class, 'generateImage']);
    Route::post('/site', [FrontContoller::class, 'createSite']);
    Route::get('/site/{site}', [FrontContoller::class, 'getSite']);
    Route::post('/site/update/{site}', [FrontContoller::class, 'updateSite']);
    Route::delete('/site/{id}', [FrontContoller::class, 'deleteSite']);
    Route::post('/favorite', [FrontContoller::class, 'favorite']);
    Route::get('/get-all-site', [FrontContoller::class, 'getAllSite']);
    Route::get('/template', [FrontContoller::class, 'getTemplate']);
    Route::post('/fetch-user-data', [FetchUserData::class, 'fetchUserData']);
    Route::post('/save-card-info', [PayzeController::class, 'saveCardInfo']);
    
});
Route::get('payze-callback', [PayzeController::class, 'payzeCallback']);


Route::post('send-mail', [MailController::class, 'sendMail']);
Route::get('auth', [AuthController::class, 'redirectToAuth']);
Route::get('auth/callback', [AuthController::class, 'handleAuthCallback']);

Route::post('create-subscription-transactionUrl', [ProductController::class, 'createSubscriptionTransactionUrl']);
Route::post('justpay',[ProductController::class,'justPay']);
Route::post('justpay/callback/successful', [ProductController::class, 'justpayCallbackSuccessful']);
Route::post('justpay/callback/error', [ProductController::class, 'justpayCallbackError']);
Route::post('subscription/callback', [ProductController::class, 'subscriptionCallback']);
Route::post('products', [ProductController::class, 'create']);

Route::post('activatesite/{site}',[FrontContoller::class, 'updateSiteStatus']);
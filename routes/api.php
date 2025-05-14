<?php

use App\Http\Controllers\Api\V1\Author\AuthorController;
use App\Http\Controllers\Api\V1\Book\BookController;
use App\Http\Controllers\Api\V1\ContactUs\ContactUsController;
use App\Http\Controllers\Api\V1\LookUps\LookUpController;
use App\Http\Controllers\Api\V1\Payment\PaymentController;
use App\Http\Controllers\Api\V1\User\Auth\AuthenticationController;
use App\Http\Controllers\Api\V1\User\Auth\OtpController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::group(['prefix' => 'v1', 'middleware' => ['api', 'throttle']], function () {

    Route::group(['prefix' => 'auth'], function () {
        Route::post('/register', [AuthenticationController::class, 'register'])->middleware(["throttle:20,1"]);
        Route::post('/login', [AuthenticationController::class, 'login']);
        Route::post('/social-login', [AuthenticationController::class, 'socialLogin']);
        Route::post('/reset-password', [AuthenticationController::class, 'resetPassword']);
        Route::post('/request-reset-password', [AuthenticationController::class, 'requestResetPassword']);
        Route::post('/resend-otp', [OtpController::class, 'reSend'])->middleware(["throttle:10,1"]);
        Route::post('/verify-otp', [OtpController::class, 'verify'])->middleware("throttle:10,1");

        Route::post('/logout', [AuthenticationController::class, 'logout'])->middleware(["auth:api"]);
    });

    Route::group(['middleware' => ['auth:api']], function () {
        Route::group(['prefix' => 'books'], function () {
            Route::get('/favorites', [AuthenticationController::class, 'favBooks']);
            Route::get('/', [BookController::class, 'index']);
            Route::get('/{id}', [BookController::class, 'show']);
            Route::put('/favorites/{id}', [BookController::class, 'favToggle']);
        });
        Route::group(['prefix' => 'authors'], function () {
            Route::get('/', [AuthorController::class, 'index']);
        });
        Route::post('/contact-us', [ContactUsController::class, 'store']);
        Route::get('/lookups', [LookUpController::class, 'index']);
        Route::post('/update-profile', [AuthenticationController::class, 'updateProfile'])->middleware("throttle:10,1");
        Route::get('/faqs', [LookUpController::class, 'faqs']);


    });
    Route::group(['prefix' => 'paymob'], function () {
        Route::post('/create-intention', [PaymentController::class, 'createIntention'])->middleware('auth:api');
        Route::post('/webhook', [PaymentController::class, 'handleWebhook']);
    });

});





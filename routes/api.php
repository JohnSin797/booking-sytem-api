<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\PackageController;
use App\Http\Controllers\FeedbackController;
use App\Http\Controllers\RatingController;
use App\Http\Controllers\ReplyController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\PackageFeedbackController;
use App\Http\Controllers\PackageFeedbackReplyController;
use App\Http\Controllers\PackageRatingController;
use App\Http\Controllers\InquiryController;
use App\Http\Controllers\InquiryReplyController;
use App\Http\Controllers\AboutController;
use App\Models\Product;

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

Route::prefix('about')->controller(AboutController::class)->group(function() {
    Route::get('index', 'index');
    Route::post('store', 'store');
    Route::post('update', 'update');
});

Route::prefix('user')->controller(UserController::class)->group(function() {
    Route::post('/login', 'login');
    Route::post('/register', 'store');
    Route::get('/info', 'details');
    Route::post('/profile', 'profile');
    Route::post('/account/update', 'update');
    Route::post('/information/update', 'information');
});

Route::prefix('product')->controller(ProductController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
    Route::post('/delete', 'delete');
    Route::post('/update', 'update');
    Route::get('/archive', 'archive');
    Route::post('/restore', 'restore');
    Route::post('/destroy', 'destroy');
});

Route::prefix('package')->controller(PackageController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
    Route::post('/show', 'show');
    Route::post('/update', 'update');
    Route::get('/archive', 'archive');
    Route::post('/restore', 'restore');
    Route::post('/destroy', 'destroy');
    Route::post('/delete', 'delete');
});

Route::prefix('feedback')->controller(FeedbackController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
});

Route::prefix('rating')->controller(RatingController::class)->group(function() {
    Route::post('/store', 'store');
});

Route::prefix('reply')->controller(ReplyController::class)->group(function() {
    Route::post('/store', 'store');
});

Route::prefix('booking')->controller(BookingController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/show', 'show');
    Route::post('/store', 'store');
    Route::post('/update', 'update');
    Route::post('/customize', 'customize');
});

Route::prefix('package-feedback')->controller(PackageFeedbackController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
    Route::post('/show', 'show');
});

Route::prefix('package-feedback-reply')->controller(PackageFeedbackReplyController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/store', 'store');
});

Route::post('/package-rating/store', [PackageRatingController::class, 'store']);

Route::prefix('inquiries')->controller(InquiryController::class)->group(function() {
    Route::get('/index', 'index');
    Route::post('/show', 'show');
    Route::post('/store', 'store');
    Route::post('/view', 'view');
    Route::post('/delete', 'delete');
});

Route::prefix('inquiry')->controller(InquiryReplyController::class)->group(function() {
    Route::post('/reply', 'store');
    Route::post('/reply/show', 'show');
});
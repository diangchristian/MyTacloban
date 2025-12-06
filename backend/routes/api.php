<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\AnnouncementCategoryController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\EventCategoryController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\ReportCategoryController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ImageUploadController;

Route::get('/user', function (Request $request) {
    return response()->json([
        'user' => $request->user()
    ]);
})->middleware('auth:sanctum');


Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.reset');
Route::post('/forgot-password',[AuthController::class, 'forgotPassword'])->name('forgot-password'); 


Route::apiResource('announcement_categories', AnnouncementCategoryController::class );
Route::apiResource('announcements', AnnouncementController::class );
Route::apiResource('barangays', BarangayController::class );
Route::apiResource('event_categories', EventCategoryController::class );
Route::apiResource('event', EventController::class );
Route::apiResource('report_categories', ReportCategoryController::class );
Route::apiResource('reports', ReportController::class );



Route::get('/announcements/category/{id}', [AnnouncementController::class, 'getByCategory']);
Route::get('/announcements/date/{filter}', [AnnouncementController::class, 'getByCreatedAt']);
Route::get('/reports/user-reports/{id}', [ReportController::class, 'getUserReports']);


Route::post('/upload-image', [ImageUploadController::class, 'store']);
Route::post('/upload-images', [ImageUploadController::class, 'storeMultiple']);
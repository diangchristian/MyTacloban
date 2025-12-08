<?php

use App\Http\Controllers\AdminUserMgmtController;
use App\Http\Controllers\BarangayOfficialController;
use App\Http\Controllers\UserManagementController;
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
use App\Http\Controllers\ReportTimelineController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\UserController;



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
Route::apiResource('events', EventController::class );
Route::apiResource('report_categories', ReportCategoryController::class );
Route::apiResource('reports', ReportController::class );



Route::get('/announcements/category/{id}', [AnnouncementController::class, 'getByCategory']);
Route::get('/announcements/date/{filter}', [AnnouncementController::class, 'getByCreatedAt']);
Route::get('/announcements-search', [AnnouncementController::class, 'search']);
Route::get('/admin/announcements', [AnnouncementController::class, 'getAllAnnouncements']);
Route::get('/admin/announcements-stats', [AnnouncementController::class, 'getAllStats']);
Route::get('/announcement-details/{id}', [AnnouncementController::class, 'getAnnouncementById']);
Route::get('/reports/user-reports/admin     ', [ReportController::class, 'getReports']);
Route::get('/reports/user-reports/{id}', [ReportController::class, 'getUserReports']);
Route::get('/search/barangays', [BarangayController::class, 'searchFilter']);
Route::get('/reports/details/{id}', [ReportController::class, 'getReportDetail' ]);
Route::get('/report-timelines/{id}', [ReportTimelineController::class, 'getTimelines' ]);
    
Route::get('/users', [UserController::class, 'index']);

Route::post('/upload-image', [ImageUploadController::class, 'store']);
Route::post('/upload-images', [ImageUploadController::class, 'storeMultiple']);
Route::post('/report-timelines', [ReportTimelineController::class, 'store' ]);



Route::put('/user-profile/{id}', [UserController::class, 'update']);
Route::put('/user-profile/{id}', [UserManagementController::class, 'updateRoleStatus']);

Route::delete('/user-profile/{id}', [UserController::class, 'destroy']);


// Incase pangamiton nagpahimo na me daan sa ai
Route::prefix('barangay-officials')->group(function () {
    Route::get('/', [BarangayOfficialController::class, 'index']);
    Route::post('/', [BarangayOfficialController::class, 'store']);
    Route::get('/statistics', [BarangayOfficialController::class, 'statistics']);
    Route::get('/missing-positions', [BarangayOfficialController::class, 'missingPositions']);
    Route::get('/barangay/{barangayId}', [BarangayOfficialController::class, 'getByBarangay']);
    Route::get('/position/{position}', [BarangayOfficialController::class, 'getByPosition']);
    Route::get('/{id}', [BarangayOfficialController::class, 'show']);
    Route::put('/{id}', [BarangayOfficialController::class, 'update']);
    Route::delete('/{id}', [BarangayOfficialController::class, 'destroy']);
});

// FOR BARANGAY INFORMATIONS ADMIN
Route::get('/barangays', [BarangayController::class, 'index']);
Route::post('/barangays', [BarangayController::class, 'store']);
Route::put('/barangays/{id}', [BarangayController::class, 'update']);
Route::delete('/barangays/{id}', [BarangayController::class, 'destroy']);
<?php
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

// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
// return $request->user();
// });

use App\Http\Controllers\admin\Admindashboardcontroller;
use App\Http\Controllers\admin\AdminDashboardController as AdminAdminDashboardController;
use App\Http\Controllers\Admin\ApprovalController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::post(
    '/register/patient',
    [AuthController::class, 'registerPatient']
);

Route::post(
    '/register/doctor',
    [AuthController::class, 'registerDoctor']
);

Route::post(
    '/register/receptionist',
    [AuthController::class, 'registerReceptionist']
);

Route::post(
    '/login',
    [AuthController::class, 'login']
);
Route::middleware([
    'auth:sanctum',
    // 'approved'
])->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/me', [AuthController::class, 'me']);
});



Route::middleware([
    'auth:sanctum',
    'role:admin'
])
    ->prefix('admin')
    ->group(function () {

        Route::get(
            '/approval-requests',
            [ApprovalController::class, 'getpendingstatus']
        );

        Route::get(
            '/approval-request/{user}',
            [ApprovalController::class, 'show_one_approval']
        );

        Route::post(
            '/approval-request/update_status/{user}',
            [ApprovalController::class, 'updateStatus']
        );
        Route::get(
            '/stats/dashboard',
            [AdminAdminDashboardController::class, 'stats']
        );
    });

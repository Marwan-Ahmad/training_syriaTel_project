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

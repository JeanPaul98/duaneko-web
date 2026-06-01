<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\ResetPassword\CodeCheckController;
use App\Http\Controllers\Api\ResetPassword\ForgotPasswordController;
use App\Http\Controllers\Api\ResetPassword\ResetPasswordController;
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

Route::group([

    'middleware' => 'api',
    'prefix' => 'auth'

], function ($router) {

    Route::post('login', [AuthController::class, 'login']);
    Route::post('register', [AuthController::class, 'register']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::get('me', [AuthController::class, 'me']);
    Route::get('reports/all', [ReportController::class, 'showReports']);
    Route::get('users/all', [ReportController::class, 'showUsers']);
    Route::post('reports/{user_id}', [ReportController::class, 'reportStore']);
    Route::post('reports/public', [ReportController::class, 'reportPublicStore']);
});
Route::get('image/{filename}', [ReportController::class, 'getImage'])->where('path', '.*');
Route::post('password/email',  ForgotPasswordController::class);
Route::post('password/code/check', CodeCheckController::class);
Route::post('password/reset', ResetPasswordController::class);


// Route::get('/storage/{filename}', function ($filename) {
//     $path = storage_path('app/public/' . $filename);

//     if (file_exists($path)) {
//         return response()->file($path);
//     }

//     abort(404);
// });

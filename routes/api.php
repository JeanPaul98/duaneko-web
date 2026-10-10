<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CountryController;
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
    Route::put('me', [AuthController::class, 'updateMe']);
    Route::put('me/password', [AuthController::class, 'updatePassword']);
    Route::get('reports/all', [ReportController::class, 'showReports']);
    Route::get('reports/mine', [ReportController::class, 'myReports'])->middleware('auth:api');
    Route::get('reports/stats', [ReportController::class, 'stats'])->middleware('auth:api');
    Route::get('users/all', [ReportController::class, 'showUsers']);
    // 'public' doit être déclarée avant {user_id}, sinon elle est capturée par le paramètre.
    Route::post('reports/public', [ReportController::class, 'reportPublicStore']);
    Route::post('reports/{user_id}', [ReportController::class, 'reportStore'])->whereNumber('user_id')->middleware('auth:api');
});
Route::get('countries', [CountryController::class, 'index']);
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

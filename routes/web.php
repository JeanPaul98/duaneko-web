<?php

use App\Http\Controllers\AgentController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RamassageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ManagerController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return view('/pages/dashboard/admin_panel');
});

Auth::routes();

Route::get('/home', [DashboardController::class, 'index'])->name('home');

Route::get('/agent', [LoginController::class, 'showAgentLoginForm'])->name('agent.login-view');
Route::get('/manager', [LoginController::class, 'showManagerLoginForm'])->name('manager.login-view');
Route::get('/admin', [LoginController::class, 'showAdminLoginForm'])->name('admin.login-view');

Route::post('/agent', [LoginController::class, 'agentLogin'])->name('agent.login');
Route::post('/manager', [LoginController::class, 'managerLogin'])->name('manager.login');
Route::post('/admin', [LoginController::class, 'adminLogin'])->name('admin.login');

Route::resource('zones', ZoneController::class);
Route::resource('managers', ManagerController::class);
Route::resource('agents', AgentController::class);
Route::resource('companies', CompanyController::class);
Route::resource('reports', ReportController::class);
Route::resource('ramassages', RamassageController::class);


Route::get('/admin/home', function () {
    return view('pages.dashboard.home');
})->middleware('auth:admin');

Route::get('/manager/home', function () {
    return view('pages.dashboard.home');
})->middleware('auth:manager');

Route::get('/agent/home', function () {
    return view('pages.dashboard.home');
})->middleware('auth:agent');
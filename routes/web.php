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
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;

Route::get('/', function () {
    return redirect()->route('login');
});

Auth::routes(['register' => false]);

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('home');

    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    // Entreprises et manageurs : gérés exclusivement par l'admin. Création/édition/détail en modale
    // sur la page index, donc pas besoin des routes create/edit/show.
    Route::resource('companies', CompanyController::class)->except(['create', 'edit', 'show'])->middleware('role:admin');
    Route::resource('managers', ManagerController::class)->except(['create', 'edit', 'show'])->middleware('role:admin');
    Route::patch('managers/{manager}/validate', [ManagerController::class, 'validateAccount'])->name('managers.validate')->middleware('role:admin');
    Route::patch('managers/{manager}/reject', [ManagerController::class, 'reject'])->name('managers.reject')->middleware('role:admin');

    // Agents : consultés par l'admin et le manager, gérés (créer/modifier/supprimer) par le manager,
    // création/édition/détail en modale sur la page index.
    Route::resource('agents', AgentController::class)->except(['index', 'show', 'create', 'edit'])->middleware('role:manager');
    Route::resource('agents', AgentController::class)->only(['index'])->middleware('role:admin|manager');
    Route::patch('agents/{agent}/validate', [AgentController::class, 'validateAccount'])->name('agents.validate')->middleware('role:admin|manager');
    Route::patch('agents/{agent}/reject', [AgentController::class, 'reject'])->name('agents.reject')->middleware('role:admin|manager');

    // Zones : consultées par l'admin et le manager, gérées par le manager.
    Route::resource('zones', ZoneController::class)->except(['index', 'show'])->middleware('role:manager');
    Route::resource('zones', ZoneController::class)->only(['index', 'show'])->middleware('role:admin|manager');

    // Ramassages : consultés par l'admin, le manager (sa société) et l'agent (ses missions), gérés par le manager.
    Route::resource('ramassages', RamassageController::class)->except(['index', 'show'])->middleware('role:manager');
    Route::resource('ramassages', RamassageController::class)->only(['index', 'show'])->middleware('role:admin|manager|agent');

    Route::resource('reports', ReportController::class);
});

Route::get('/modal', function () {
    return view('components.ui.alert');
})->middleware(['auth', 'role:admin']);

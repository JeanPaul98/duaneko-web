<?php

namespace App\Providers;

use App\Http\Controllers\ReportController;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::defaultView('vendor.pagination.tailadmin');

        View::composer('components.header.notification-dropdown', function ($view) {
            $user = auth()->user();

            if (! $user) {
                $view->with([
                    'notificationType' => 'reports',
                    'pendingReportsCount' => 0,
                    'pendingReports' => collect(),
                    'assignedRamassages' => collect(),
                ]);
                return;
            }

            if ($user->hasRole('agent')) {
                $view->with([
                    'notificationType' => 'ramassages',
                    'pendingReportsCount' => $user->ramassages()->count(),
                    'pendingReports' => collect(),
                    'assignedRamassages' => $user->ramassages()->orderByDesc('created_at')->limit(5)->get(),
                ]);
                return;
            }

            $query = app(ReportController::class)->pendingReportsQuery($user);

            $view->with([
                'notificationType' => 'reports',
                'pendingReportsCount' => (clone $query)->count(),
                'pendingReports' => (clone $query)->with('user')->latest()->limit(5)->get(),
                'assignedRamassages' => collect(),
            ]);
        });
    }
}

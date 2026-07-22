<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Ramassage;
use App\Models\User;
use App\Models\Zone;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     */
    public function index(Request $request)
    {
        $authUser = auth()->user();

        if ($authUser->hasRole('agent')) {
            return $this->agentDashboard($authUser);
        }

        $isAdmin = $authUser->hasRole('admin');
        $companyId = $authUser->company_id;
        $now = now();

        $userScope = fn () => $isAdmin ? User::query() : User::where('company_id', $companyId);
        $ramassageScope = fn () => $isAdmin ? Ramassage::query() : Ramassage::where('company_id', $companyId);

        $metrics = [
            ['label' => 'Utilisateurs', 'value' => $userScope()->count(), 'icon' => 'users'],
            ['label' => 'Ramassages', 'value' => $ramassageScope()->count(), 'icon' => 'ramassages'],
        ];

        if ($isAdmin) {
            $metrics[] = ['label' => 'Entreprises', 'value' => Company::count(), 'icon' => 'building'];
            $metrics[] = ['label' => 'Zones actives', 'value' => Zone::count(), 'icon' => 'zone'];
        } else {
            $metrics[] = ['label' => 'Agents', 'value' => User::role('agent')->where('company_id', $companyId)->count(), 'icon' => 'building'];
            $metrics[] = ['label' => 'Zones actives', 'value' => Zone::where('company_id', $companyId)->count(), 'icon' => 'zone'];
        }

        $ramassagesByMonth = array_fill(0, 12, 0);
        $ramassageScope()->whereYear('date_de_ramassage', $now->year)
            ->get(['date_de_ramassage'])
            ->each(function ($ramassage) use (&$ramassagesByMonth) {
                $month = Carbon::parse($ramassage->date_de_ramassage)->month;
                $ramassagesByMonth[$month - 1]++;
            });

        $usersByMonth = array_fill(0, 12, 0);
        $userScope()->whereYear('created_at', $now->year)
            ->get(['created_at'])
            ->each(function ($user) use (&$usersByMonth) {
                $usersByMonth[$user->created_at->month - 1]++;
            });

        $ramassagesThisMonth = $ramassageScope()->whereYear('date_de_ramassage', $now->year)
            ->whereMonth('date_de_ramassage', $now->month)
            ->get(['date_de_ramassage']);
        $totalThisMonth = $ramassagesThisMonth->count();
        $completedThisMonth = $ramassagesThisMonth
            ->filter(fn ($r) => Carbon::parse($r->date_de_ramassage)->lte($now))
            ->count();
        $completionRate = $totalThisMonth > 0
            ? round($completedThisMonth / $totalThisMonth * 100, 1)
            : 0;

        $roleFilter = $request->query('role');
        $statusFilter = $request->query('status');
        $search = $request->query('search');

        $usersQuery = $userScope()->with('company')->latest();

        if ($roleFilter) {
            $usersQuery->role($roleFilter);
        }
        if ($statusFilter) {
            $usersQuery->where('status', $statusFilter);
        }
        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $usersQuery->paginate(10)->withQueryString();

        return view('pages.dashboard.dashboard', compact(
            'metrics',
            'ramassagesByMonth',
            'usersByMonth',
            'completionRate',
            'totalThisMonth',
            'completedThisMonth',
            'users',
            'roleFilter',
            'statusFilter',
            'search'
        ));
    }

    private function agentDashboard(User $agent): View
    {
        $now = now();
        $ramassages = $agent->ramassages()->orderBy('date_de_ramassage')->get();

        $totalAssigned = $ramassages->count();
        $completed = $ramassages->filter(fn ($r) => Carbon::parse($r->date_de_ramassage)->lte($now))->count();
        $upcoming = $totalAssigned - $completed;

        return view('pages.dashboard.agent-dashboard', compact('ramassages', 'totalAssigned', 'completed', 'upcoming'));
    }
}

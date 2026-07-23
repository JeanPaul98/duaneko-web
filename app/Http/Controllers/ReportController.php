<?php

namespace App\Http\Controllers;

use App\Models\Ramassage;
use App\Models\Report;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $isAdmin = $user->hasRole('admin');

        $zones = $isAdmin ? Zone::all() : Zone::where('company_id', $user->company_id)->get();

        $query = Report::query();

        if (!$isAdmin) {
            if ($zones->isEmpty()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where(function ($q) use ($zones) {
                    foreach ($zones as $zone) {
                        [$latMin, $latMax, $lngMin, $lngMax] = $this->zoneBounds($zone);
                        $q->orWhere(function ($qq) use ($latMin, $latMax, $lngMin, $lngMax) {
                            $qq->whereBetween('latitude', [$latMin, $latMax])
                                ->whereBetween('longitude', [$lngMin, $lngMax]);
                        });
                    }
                });
            }
        }

        $statusFilter = $request->query('status');
        if ($statusFilter) {
            $query->where('status', $statusFilter);
        }

        $zoneFilter = $request->query('zone');
        if ($zoneFilter && ($zone = $zones->firstWhere('id', $zoneFilter))) {
            [$latMin, $latMax, $lngMin, $lngMax] = $this->zoneBounds($zone);
            $query->whereBetween('latitude', [$latMin, $latMax])
                ->whereBetween('longitude', [$lngMin, $lngMax]);
        }

        $reports = $query->with('user')->latest()->paginate(10)->withQueryString();

        foreach ($reports as $report) {
            $matchedZone = $zones->first(fn ($zone) => $this->reportWithinZones($report, collect([$zone])));
            $report->zone_name = $matchedZone->name ?? null;
        }

        return view('pages.reports.index', compact('reports', 'zones', 'statusFilter', 'zoneFilter'));
    }

    public function show(Report $report)
    {
        abort_unless($this->canAccess($report), 403);

        $user = auth()->user();
        $zones = $user->hasRole('admin') ? Zone::all() : $this->companyZones($user);
        $matchedZone = $zones->first(fn ($zone) => $this->reportWithinZones($report, collect([$zone])));

        $agents = $matchedZone
            ? User::role('agent')->where('company_id', $matchedZone->company_id)->where('status', 'validated')
                ->withCount(['ramassages as active_ramassages_count' => fn ($q) => $q->whereNull('completed_at')])
                ->get()
            : collect();

        $message = $this->buildAgentMessage($report);

        foreach ($agents as $agent) {
            $phone = preg_replace('/\D/', '', $agent->phone_number ?? '');
            $agent->whatsapp_url = 'https://api.whatsapp.com/send/?phone=' . $phone . '&text=' . rawurlencode($message);
            $agent->mailto_url = 'mailto:' . $agent->email . '?subject=' . rawurlencode('Signalement à traiter') . '&body=' . rawurlencode($message);
        }

        return view('pages.reports.show', compact('report', 'agents'));
    }

    /**
     * Assigne un agent à un signalement : crée (ou met à jour) le ramassage
     * lié en reprenant directement les coordonnées GPS du signalement, sans
     * que le manager ait besoin de les ressaisir sur une carte.
     */
    public function assign(Request $request, Report $report): RedirectResponse
    {
        abort_unless($this->canModerate($report), 403);

        $request->validate([
            'agent_id' => ['required', 'exists:users,id'],
        ]);

        $typeLabels = ['wild_dumps' => 'Dépôt sauvage'];

        $ramassage = $report->ramassage ?: new Ramassage();

        $ramassage->fill([
            'report_id' => $report->id,
            'name' => ($typeLabels[$report->type] ?? $report->type) . ' - ' . $report->description,
            'latitude' => $report->latitude,
            'longitude' => $report->longitude,
            'description' => $report->description,
            'company_id' => auth()->user()->company_id,
            'agent_id' => $request->agent_id,
        ]);
        $ramassage->save();

        $report->update(['status' => 'in_progress']);

        return redirect()->route('reports.show', $report)->with('success', 'Agent assigné : le ramassage a été créé avec les coordonnées du signalement.');
    }

    private function buildAgentMessage(Report $report): string
    {
        $typeLabels = ['wild_dumps' => 'Dépôt sauvage'];
        $statusLabels = ['pending' => 'En attente', 'in_progress' => 'En cours', 'done' => 'Traité'];

        return implode("\r\n", [
            'Bonjour,',
            '',
            'Un signalement nécessite votre intervention :',
            'Type : ' . ($typeLabels[$report->type] ?? $report->type),
            'Description : ' . $report->description,
            'Statut : ' . ($statusLabels[$report->status] ?? $report->status),
            'Localisation : https://www.google.com/maps?q=' . $report->latitude . ',' . $report->longitude,
            '',
            'Merci de vous y rendre dès que possible.',
        ]);
    }

    public function update(Request $request, Report $report)
    {
        abort_unless($this->canModerate($report), 403);

        $request->validate([
            'status' => ['required', 'string', 'in:pending,in_progress,done'],
        ]);

        $report->update(['status' => $request->status]);

        return redirect()->route('reports.show', $report)->with('success', 'Statut du signalement mis à jour avec succès.');
    }

    public function pendingReportsQuery(User $user)
    {
        $isAdmin = $user->hasRole('admin');
        $zones = $isAdmin ? Zone::all() : Zone::where('company_id', $user->company_id)->get();

        $query = Report::where('status', 'pending');

        if (!$isAdmin) {
            if ($zones->isEmpty()) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where(function ($q) use ($zones) {
                    foreach ($zones as $zone) {
                        [$latMin, $latMax, $lngMin, $lngMax] = $this->zoneBounds($zone);
                        $q->orWhere(function ($qq) use ($latMin, $latMax, $lngMin, $lngMax) {
                            $qq->whereBetween('latitude', [$latMin, $latMax])
                                ->whereBetween('longitude', [$lngMin, $lngMax]);
                        });
                    }
                });
            }
        }

        return $query;
    }

    private function zoneBounds(Zone $zone): array
    {
        return [
            min($zone->northeast_latitude, $zone->southwest_latitude),
            max($zone->northeast_latitude, $zone->southwest_latitude),
            min($zone->northeast_longitude, $zone->southwest_longitude),
            max($zone->northeast_longitude, $zone->southwest_longitude),
        ];
    }

    private function reportWithinZones(Report $report, Collection $zones): bool
    {
        foreach ($zones as $zone) {
            [$latMin, $latMax, $lngMin, $lngMax] = $this->zoneBounds($zone);

            if ($report->latitude >= $latMin && $report->latitude <= $latMax
                && $report->longitude >= $lngMin && $report->longitude <= $lngMax) {
                return true;
            }
        }

        return false;
    }

    private function companyZones(User $user): Collection
    {
        return Zone::where('company_id', $user->company_id)->get();
    }

    private function canAccess(Report $report): bool
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return true;
        }

        return $this->reportWithinZones($report, $this->companyZones($user));
    }

    private function canModerate(Report $report): bool
    {
        $user = auth()->user();

        if ($user->hasRole('admin')) {
            return true;
        }

        return $user->hasRole('manager') && $this->reportWithinZones($report, $this->companyZones($user));
    }
}

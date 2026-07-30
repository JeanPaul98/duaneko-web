<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class AuditLogController extends Controller
{
    private array $actions = [
        'Connexion',
        'Déconnexion',
        'Manager créé',
        'Manager supprimé',
        'Manager validé',
        'Manager rejeté',
        'Agent créé',
        'Agent supprimé',
        'Agent validé',
        'Agent rejeté',
    ];

    public function index(Request $request)
    {
        $causers = User::whereDoesntHave('roles', fn ($q) => $q->where('name', 'citizen'))
            ->orderBy('first_name')
            ->get();

        $causerFilter = $request->query('causer');
        $actionFilter = $request->query('action');

        $query = Activity::with('causer')->latest();

        if ($causerFilter) {
            $query->where('causer_id', $causerFilter);
        }

        if ($actionFilter) {
            $query->where('description', $actionFilter);
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('pages.settings.audit.index', compact('logs', 'causers', 'causerFilter', 'actionFilter'))
            ->with('actions', $this->actions);
    }

    public function userActivities(User $user): JsonResponse
    {
        $activities = Activity::where('causer_type', User::class)
            ->where('causer_id', $user->id)
            ->latest()
            ->get(['description', 'properties', 'created_at'])
            ->map(fn ($activity) => [
                'description' => $activity->description,
                'subject_name' => $activity->getExtraProperty('subject_name'),
                'date' => $activity->created_at->format('d/m/Y H:i'),
            ]);

        $phone = preg_replace('/\D/', '', $user->phone_number ?? '');

        return response()->json([
            'user' => [
                'name' => $user->full_name(),
                'phone_number' => $user->phone_number,
                'email' => $user->email,
                'role' => $user->getRoleNames()->first(),
                'is_active' => $user->isValidated(),
                'status' => $user->status,
                'whatsapp_url' => $phone
                    ? 'https://api.whatsapp.com/send/?phone=' . $phone . '&text=' . rawurlencode('Bonjour ' . $user->first_name . ',')
                    : null,
            ],
            'activities' => $activities,
        ]);
    }
}

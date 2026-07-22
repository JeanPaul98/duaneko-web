<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\User;
use App\Models\Report;
use App\Models\Ramassage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class RamassageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        if ($user->hasRole('manager')) {
            $ramassages = Ramassage::with('agent')->where('company_id', $user->company_id)->paginate(5);
            $agents = User::role('agent')->where('company_id', $user->company_id)->get();
            $zones = Zone::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('agent')) {
            $ramassages = $user->ramassages()->with('agent')->paginate(5);
            $agents = collect();
            $zones = collect();
        } else {
            $ramassages = Ramassage::with('agent')->latest()->paginate(10);
            $agents = collect();
            $zones = collect();
        }

        $reports = Report::all();

        return view('pages.ramassages.index', compact('ramassages', 'agents', 'zones', 'reports'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'date_de_ramassage' => 'required',
            'heure_de_ramassage' => 'required',
            'description' => 'required',
            'agent_id' => ['nullable', 'exists:users,id'],
        ]);
        $request->merge(['company_id' => auth()->user()->company_id]);
        Ramassage::create($request->all());

        return redirect()->route('ramassages.index')
            ->with('success', 'Le ramassage a été créé avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ramassage $ramassage): View
    {
        return view('pages.ramassages.show', compact('ramassage'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ramassage $ramassage): RedirectResponse
    {
        $request->validate([
            'name' => 'required',
            'latitude' => 'required',
            'longitude' => 'required',
            'date_de_ramassage' => 'required',
            'heure_de_ramassage' => 'required',
            'description' => 'required',
            'agent_id' => ['nullable', 'exists:users,id'],
        ]);

        $request->merge(['company_id' => auth()->user()->company_id]);

        $ramassage->update($request->all());

        return redirect()->route('ramassages.index')
            ->with('success', 'Le ramassage a été modifié avec succès.');
    }

    /**
     * Marque le signalement à l'origine du ramassage comme traité, une fois
     * la collecte effectuée sur le terrain par l'agent assigné.
     */
    public function complete(Ramassage $ramassage): RedirectResponse
    {
        $user = auth()->user();

        abort_unless($user->hasRole(['admin', 'manager']) || $ramassage->agent_id === $user->id, 403);

        if ($ramassage->report) {
            $ramassage->report->update(['status' => 'done']);
        }

        return redirect()->route('ramassages.show', $ramassage)
            ->with('success', 'Ramassage marqué comme terminé.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ramassage $ramassage)
    {
        $ramassage->delete();

        return redirect()->route('ramassages.index')
            ->with('success', 'Ramassage supprimé avec succès');
    }
}

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
            $ramassages = Ramassage::where('company_id', $user->company_id)->paginate(5);
            $agents = User::role('agent')->where('company_id', $user->company_id)->get();
            $zones = Zone::where('company_id', $user->company_id)->get();
        } elseif ($user->hasRole('agent')) {
            $ramassages = $user->ramassages()->paginate(5);
            $agents = collect();
            $zones = collect();
        } else {
            $ramassages = Ramassage::latest()->paginate(10);
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

        ]);
        $request->merge(['company_id' => auth()->user()->company_id]);
        $ramassage = Ramassage::create($request->all());

        //Récupérer les agents sélectionnés dans la requête
        $agent_ids = $request->input('agents', []);

        //Associer les agents au ramassage
        $ramassage->agents()->attach($agent_ids);

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

        ]);

        $request->merge(['company_id' => auth()->user()->company_id]);

        $agent_ids = $request->input('agents', []);

        $ramassage->agents()->sync($agent_ids);

        $ramassage->update($request->all());

        return redirect()->route('ramassages.index')
            ->with('success', 'Le ramassage a été modifié avec succès.');
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

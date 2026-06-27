<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Agent;
use App\Models\Report;
use App\Models\Manager;
use App\Models\Ramassage;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class RamassageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $ramassages = [];

        if (Auth::guard('manager')->check()) {
            $id_manager = Auth::guard('manager')->id();
            $manager = Manager::find($id_manager);
            $ramassages = Ramassage::where('company_id', $manager->company_id)->paginate(5);
            
        } elseif(Auth::guard('agent')->check()) {
            $agent = Agent::find(Auth::guard('agent')->id());
            $ramassages = $agent->ramassages()->paginate(5);
           
             
           
        } else{
            $ramassages = Ramassage::latest()->paginate(10); 
            
        }
        return view('pages.ramassages.index', compact('ramassages'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(Auth::guard('manager')->check()){
            $id_manager = Auth::guard('manager')->id();
            $manager = Manager::find($id_manager);
            $agents = Agent::where('company_id',$manager->company_id)->get();
            $zones = Zone::where('company_id', $manager->company_id)->get();
            // dd($zones);
        }
        $reports = Report::all();
        return view('pages.ramassages.create', compact('agents', 'reports', 'zones'));
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
        $id_manager = Auth::guard('manager')->id();
        $manager = Manager::find($id_manager);
        $request->merge(['company_id' => $manager->company_id]);
        // dd($request);
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
     * Show the form for editing the specified resource.
     */
    public function edit(Ramassage $ramassage): View
    {

        // Récupérer tous les agents disponibles
        $id_manager = Auth::guard('manager')->id();
            $manager = Manager::find($id_manager);
            $agents = Agent::where('company_id',$manager->company_id)->get();
        // $agents = Agent::where('company_id', Auth::user()->company_id)->get();;

        return view('pages.ramassages.edit', compact('ramassage', 'agents'));
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

        $id_manager = Auth::guard('manager')->id();
        $manager = Manager::find($id_manager);
        $request->merge(['company_id' => $manager->company_id]);

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

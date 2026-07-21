<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ZoneController extends Controller
{

    public function index()
    {
        if (auth()->user()->hasRole('manager')) {
            $zones = Zone::where('company_id', auth()->user()->company_id)->paginate(5);
        } else {
            $zones = Zone::latest()->paginate(10);
        }

        return view('pages.zones.index', compact('zones'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        return view('pages.zones.create');
    }

    public function store(Request $request): RedirectResponse
    {

        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'google_map_name' => 'required',
            'northeast_latitude' => 'required',
            'northeast_longitude' => 'required',
            'southwest_latitude' => 'required',
            'southwest_longitude' => 'required',
        ]);
        $request->merge(['company_id' => auth()->user()->company_id]);
        Zone::create($request->all());

        return redirect()->route('zones.index')
            ->with('success', 'La zone a été créée avec succès.');
    }

    public function show(Zone $zone)
    {
        return view('pages.zones.show', compact('zone'));
    }


    public function edit(Zone $zone)
    {
        return view('pages.zones.edit',compact('zone'));
    }

    public function update(Request $request, Zone $zone)
    {
        $request->validate([
            'name' => 'required',
            'color' => 'required',
            'google_map_name' => 'required',
            'northeast_latitude' => 'required',
            'northeast_longitude' => 'required',
            'southwest_latitude' => 'required',
            'southwest_longitude' => 'required',

        ]);

        $zone->update($request->all());
        return redirect()->route('zones.show',compact('zone'))->with('success','Zone modifier avec success');
    }

    public function destroy(Zone $zone)
    {

        $zone->delete();
        return redirect()->route('zones.index')->with('success','La zone a été supprimé avec success');
    }



}

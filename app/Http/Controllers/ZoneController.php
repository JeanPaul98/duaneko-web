<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class ZoneController extends Controller
{

    public function index()
    {
        if (auth()->user()->hasRole('manager')) {
            $zones = Zone::with('administrativeDivision')->where('company_id', auth()->user()->company_id)->paginate(5);
        } else {
            $zones = Zone::with('administrativeDivision')->latest()->paginate(10);
        }

        $countries = Country::orderBy('name')->get();

        return view('pages.zones.index', compact('zones', 'countries'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
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
            'administrative_division_id' => ['nullable', 'exists:administrative_divisions,id'],
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
            'administrative_division_id' => ['nullable', 'exists:administrative_divisions,id'],

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

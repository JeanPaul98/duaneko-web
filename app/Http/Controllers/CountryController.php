<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class CountryController extends Controller
{
    public function index()
    {
        $countries = Country::withCount(['divisionLevels', 'administrativeDivisions', 'companies'])
            ->orderBy('name')
            ->paginate(10);

        return view('pages.settings.countries.index', compact('countries'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iso_code' => ['required', 'string', 'max:5', Rule::unique('countries')],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'currency' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        Country::create($validated);

        return redirect()->route('parametres.pays.index')->with('success', 'Pays créé avec succès.');
    }

    public function update(Request $request, Country $pays): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'iso_code' => ['required', 'string', 'max:5', Rule::unique('countries')->ignore($pays->id)],
            'phone_code' => ['nullable', 'string', 'max:10'],
            'currency' => ['nullable', 'string', 'max:10'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $pays->update($validated);

        return redirect()->route('parametres.pays.index')->with('success', 'Pays modifié avec succès.');
    }

    public function destroy(Country $pays): RedirectResponse
    {
        if ($pays->companies()->exists() || $pays->administrativeDivisions()->exists() || $pays->divisionLevels()->exists()) {
            return redirect()->route('parametres.pays.index')
                ->with('error', 'Impossible de supprimer ce pays : des entreprises, niveaux ou subdivisions en dépendent.');
        }

        $pays->delete();

        return redirect()->route('parametres.pays.index')->with('success', 'Pays supprimé avec succès.');
    }
}

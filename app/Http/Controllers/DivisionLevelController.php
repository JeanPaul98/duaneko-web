<?php

namespace App\Http\Controllers;

use App\Models\DivisionLevel;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class DivisionLevelController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'name' => ['required', 'string', 'max:255'],
        ]);

        $validated['depth'] = DivisionLevel::where('country_id', $validated['country_id'])->count();

        DivisionLevel::create($validated);

        return redirect()->route('parametres.hierarchie.index', ['country' => $validated['country_id']])
            ->with('success', 'Niveau administratif ajouté avec succès.');
    }

    public function update(Request $request, DivisionLevel $niveau): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $niveau->update($validated);

        return redirect()->route('parametres.hierarchie.index', ['country' => $niveau->country_id])
            ->with('success', 'Niveau administratif modifié avec succès.');
    }

    public function destroy(DivisionLevel $niveau): RedirectResponse
    {
        if ($niveau->administrativeDivisions()->exists()) {
            return redirect()->route('parametres.hierarchie.index', ['country' => $niveau->country_id])
                ->with('error', 'Impossible de supprimer ce niveau : des subdivisions l\'utilisent.');
        }

        $countryId = $niveau->country_id;
        $niveau->delete();

        return redirect()->route('parametres.hierarchie.index', ['country' => $countryId])
            ->with('success', 'Niveau administratif supprimé avec succès.');
    }
}

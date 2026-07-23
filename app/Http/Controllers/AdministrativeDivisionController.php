<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeDivision;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class AdministrativeDivisionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'country_id' => ['required', 'exists:countries,id'],
            'division_level_id' => ['required', 'exists:division_levels,id'],
            'parent_id' => ['nullable', 'exists:administrative_divisions,id'],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
        ]);

        AdministrativeDivision::create($validated);

        return redirect()->route('parametres.hierarchie.index', ['country' => $validated['country_id']])
            ->with('success', 'Subdivision créée avec succès.');
    }

    public function update(Request $request, AdministrativeDivision $subdivision): RedirectResponse
    {
        $validated = $request->validate([
            'division_level_id' => ['required', 'exists:division_levels,id'],
            'parent_id' => ['nullable', 'exists:administrative_divisions,id', function ($attribute, $value, $fail) use ($subdivision) {
                if ((int) $value === $subdivision->id) {
                    $fail('Une subdivision ne peut pas être son propre parent.');
                }
            }],
            'name' => ['required', 'string', 'max:255'],
            'code' => ['nullable', 'string', 'max:255'],
        ]);

        $subdivision->update($validated);

        return redirect()->route('parametres.hierarchie.index', ['country' => $subdivision->country_id])
            ->with('success', 'Subdivision modifiée avec succès.');
    }

    public function destroy(AdministrativeDivision $subdivision): RedirectResponse
    {
        if ($subdivision->children()->exists() || $subdivision->companies()->exists() || $subdivision->zones()->exists()) {
            return redirect()->route('parametres.hierarchie.index', ['country' => $subdivision->country_id])
                ->with('error', 'Impossible de supprimer cette subdivision : des sous-subdivisions, entreprises ou zones en dépendent.');
        }

        $countryId = $subdivision->country_id;
        $subdivision->delete();

        return redirect()->route('parametres.hierarchie.index', ['country' => $countryId])
            ->with('success', 'Subdivision supprimée avec succès.');
    }

    /**
     * Returns divisions for a country, either restricted to one level (used to populate
     * the "division parente" select, since a parent must sit one level up) or, when no
     * level is given, every division of that country (used by Entreprise/Zone forms to
     * attach a record to any depth of the hierarchy).
     */
    public function children(Request $request): JsonResponse
    {
        if (!$request->filled('country_id')) {
            return response()->json([]);
        }

        $query = AdministrativeDivision::with('parent.parent.parent.parent')
            ->where('country_id', $request->query('country_id'));

        if ($request->filled('division_level_id')) {
            $query->where('division_level_id', $request->query('division_level_id'));
        }

        $divisions = $query->orderBy('name')->get();

        return response()->json($divisions->map(fn ($division) => [
            'id' => $division->id,
            'path' => $division->fullPath(),
        ]));
    }
}

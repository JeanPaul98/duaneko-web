<?php

namespace App\Http\Controllers;

use App\Models\AdministrativeDivision;
use App\Models\Country;
use Illuminate\Http\Request;

class HierarchyController extends Controller
{
    public function index(Request $request)
    {
        $countries = Country::orderBy('name')->get();

        $selectedCountryId = $request->query('country', $countries->first()?->id);
        $country = $countries->firstWhere('id', (int) $selectedCountryId);

        $levels = $country ? $country->divisionLevels()->get(['id', 'name', 'depth']) : collect();

        $divisions = $country
            ? AdministrativeDivision::withCount(['children', 'companies', 'zones'])
                ->where('country_id', $country->id)
                ->get(['id', 'name', 'code', 'division_level_id', 'parent_id'])
            : collect();

        return view('pages.settings.hierarchy.index', compact('countries', 'country', 'levels', 'divisions'));
    }
}

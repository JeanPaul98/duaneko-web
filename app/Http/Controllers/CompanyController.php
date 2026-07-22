<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\User;
use App\Models\Company;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    private array $types = ['mairie', 'ong', 'recycleur'];

    public function index()
    {
        $companies = Company::with(['administrativeDivision', 'country'])->latest()->paginate(5);
        $countries = Country::orderBy('name')->get();

        foreach ($companies as $company) {
            $company->managers_count = User::role('manager')->where('company_id', $company->id)->count();
            $company->agents_count = User::role('agent')->where('company_id', $company->id)->count();
            $company->zones_count = Zone::where('company_id', $company->id)->count();
        }

        return view('pages.companies.index', compact('companies', 'countries'))
            ->with('i', (request()->input('page', 1) - 1) * 5);

    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Le nom est requis',
            'type.required' => 'Le type est requis',
            'logo.image' => 'Le logo doit être une image',
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', $this->types)],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'administrative_division_id' => ['nullable', 'exists:administrative_divisions,id'],
        ], $messages);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($file));
            $validated['logo'] = $filename;
        }

        Company::create($validated);

        return redirect()->route('companies.index')->with('success', 'Entreprise créée avec succès.');
    }


    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();

        return redirect()->route('companies.index')
                        ->with('success','Companies deleted successfully');
    }

    public function update(Request $request, Company $company)
    {
        $messages = [
            'name.required' => 'le champs ne peut par etre vide',
            'slug.required' => 'le champs ne peut par etre vide',
        ];

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            'type' => ['required', 'string', 'in:' . implode(',', $this->types)],
            'description' => ['nullable', 'string'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:255'],
            'district' => ['nullable', 'string', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'email' => ['nullable', 'string', 'email', 'max:255'],
            'logo' => ['nullable', 'image', 'max:2048'],
            'country_id' => ['nullable', 'exists:countries,id'],
            'administrative_division_id' => ['nullable', 'exists:administrative_divisions,id'],
        ], $messages);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();
            Storage::disk('public')->put($filename, file_get_contents($file));
            $validated['logo'] = $filename;
        } else {
            unset($validated['logo']);
        }

        $company->update($validated);

        return redirect()->route('companies.index')->with('success', 'Entreprise modifiée avec succès.');
    }

    public function getLogo($filename)
    {
        $path = storage_path('app/public/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }

}

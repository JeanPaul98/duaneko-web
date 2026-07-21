<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\User;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class CompanyController extends Controller
{
    
    public function create()
    {
       
        return view('pages.companies.create');
    }
    
    public function index()
    {
        $companies = Company::latest()->paginate(5); 

        return view('pages.companies.index', compact('companies'))
            ->with('i', (request()->input('page', 1) - 1) * 5);
        
    }

    public function store(Request $request)
    {
        $messages = [
            'name.required' => 'Le nom est requis',
            
        ];

        $request = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            
        ], $messages);
       
        Company::create([
            'name' => $request['name'],
           
        ]);
        return redirect()->route('companies.index')->with('success', 'Company créée!');
    }

    
    public function destroy(Company $company): RedirectResponse
    {
        $company->delete();
         
        return redirect()->route('companies.index')
                        ->with('success','Companies deleted successfully');
    }
    public function edit(Company $company)
    {
        
        return view('pages.companies.edit', compact('company'));
    }

    public function show(Company $company)
    {
        $agents = User::role('agent')->where('company_id',$company->id)->paginate(2);
        $managers = User::role('manager')->where('company_id',$company->id)->paginate(2);
        $zones = Zone::where('company_id',$company->id)->get();
        $compt_agent = count($agents);
        $compt_manager = count($managers);
        $compt_zone = count($zones);
        
        return view('pages.companies.show',compact('company','agents','zones','managers','compt_agent','compt_manager','compt_zone'));
    }

    public function update(Request $request, Company $company)
    {
        
        $messages = [
            'name.required' => 'le champs ne peut par etre vide',
            'slug.required' => 'le champs ne peut par etre vide',
             
        ];
        $request = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255'],
            
        ],
         $messages
        );
       
        $company->update([
            'name' => $request['name'],
            'slug' => $request['slug'],
            
        ]);
           return redirect()->route('companies.index')->with('success', 'Agent modifié avec succès.');
    }
    
}

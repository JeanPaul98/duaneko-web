<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Report;
use App\Models\Company;
use App\Models\Manager;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class ManagerController extends Controller
{
    
    public function create()
    {
        $companies=Company::all();
        return view('pages.managers.create',compact('companies'));
    }

    public function index(){
       
      
        $managers=Manager::latest()->paginate(5);
        return view('pages.managers.index',compact('managers'))
        ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function store(Request $request): RedirectResponse
    {
        $messages = [
            'email.required' => 'L\'email est requis',
            'password.required' => 'Le mot de passe est requis',
            'first_name.required' => 'Le prénom est requis',
            'last_name.required' => 'Le nom est requis',
            'phone_number.required' => 'Le numéro de téléphone est requis',
            'company_id.required' => 'La société est requise',
        ];

        $request = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'company_id' => ['required', 'string',],
        ], $messages);

        //  dd($request);
        Manager::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number'],
            'email' => $request['email'],
            'company_id' => $request['company_id'],
            'password' => Hash::make($request['password']),
        ]);

        return redirect()->route('managers.index')
            ->with('success', 'Le manager a été créée avec succès.');
    }


    
    public function edit(Manager $manager)
    {
        return view('pages.managers.edit',compact('manager'));
    }



    public function update(Request $request, Manager $manager)
    {
        $messages = [
            'email.required' => 'L\'email est requis',
            
            'first_name.required' => 'Le prénom est requis',
            'last_name.required' => 'Le nom est requis',
            
            
            'phone_number.required' => 'Le numéro de téléphone est requis',
        ];

        $request = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string',],
        ],
         $messages
        );
       
        $manager->update([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'email' => $request['email'],
            'phone_number' => $request['phone_number']
        ]);
           return redirect()->route('managers.index')->with('success', 'Manager modifié avec succès.');

    }

    public function destroy(Manager $manager)
    {
        $manager->delete();
  
        return redirect()->route('managers.index')->with('success','Manager deleted successfully');
    }

    public function show(Manager $manager){
        return view('managers.show',compact('manager'));
    }


}

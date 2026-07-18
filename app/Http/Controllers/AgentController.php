<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use App\Models\Company;
use App\Models\Manager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    
     public function create()
     {
         return view('pages.agents.create');
     }
 
     public function index(){
        
        $agents = [];

        if (Auth::guard('manager')->check()) {
            $id_manager = Auth::guard('manager')->id();
            $id_company = Manager::find($id_manager);
            $agents = Agent::where('company_id', $id_company->company_id)->paginate(5);
        } else {
            $agents = Agent::latest()->paginate(5);  
        } 
        
        return view('pages.agents.index', compact('agents'))
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
             
         ];
 
         $request = $request->validate([
             'first_name' => ['required', 'string', 'max:255'],
             'last_name' => ['required', 'string', 'max:255'],
             'phone_number' => ['required', 'string'],
             'email' => ['required', 'string', 'email', 'max:255', Rule::unique('agents')],
             'password' => ['required', 'string', 'min:4', 'confirmed'],
             
         ], $messages);
 
       
         $id_manager = Auth::guard('manager')->id();
         $id_company = Manager::find($id_manager);
        //  $company_id=$id_company->company_id;
         Agent::create([
             'first_name' => $request['first_name'],
             'last_name' => $request['last_name'],
             'phone_number' => $request['phone_number'],
             'email' => $request['email'],
             'password' => Hash::make($request['password']),
             'company_id'=> $id_company->company_id
         ]);

         return redirect()->route('agents.index')
             ->with('success', 'Agent a été créée avec succès.');
     }
 
 

     public function edit(Agent $agent)
     {
         return view('pages.agents.edit',compact('agent'));
     }
 
 
 
     public function update(Request $request, Agent $agent)
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
             'email' => ['required', 'string', 'email', 'max:255', Rule::unique('agents')->ignore($agent->id)],
             'phone_number' => ['required', 'string',],
         ],
          $messages
         );
        
         $agent->update([
             'first_name' => $request['first_name'],
             'last_name' => $request['last_name'],
             'email' => $request['email'],
             'phone_number' => $request['phone_number']
         ]);
            return redirect()->route('agents.index')->with('success', 'Agent modifié avec succès.');
 
     }
 
     public function destroy(Agent $agent)
     {
         $agent->delete();
   
         return redirect()->route('agents.index')->with('success','Agent deleted successfully');
     }

     public function show(Agent $agent){
         $agent->load('ramassages');
         $ramassages = $agent->ramassages()->paginate(5);
         $compt_ramassage = $agent->ramassages()->count();
         return view('pages.agents.show', compact('agent', 'ramassages', 'compt_ramassage'));
    }
 
}

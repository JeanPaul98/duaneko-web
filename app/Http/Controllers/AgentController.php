<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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

        if (auth()->user()->hasRole('manager')) {
            $agents = User::role('agent')->where('company_id', auth()->user()->company_id)->paginate(5);
        } else {
            $agents = User::role('agent')->latest()->paginate(5);
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
             'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
             'password' => ['required', 'string', 'min:4', 'confirmed'],

         ], $messages);

         $agent = User::create([
             'first_name' => $request['first_name'],
             'last_name' => $request['last_name'],
             'phone_number' => $request['phone_number'],
             'email' => $request['email'],
             'password' => Hash::make($request['password']),
             'company_id'=> auth()->user()->company_id,
             'status' => 'pending',
         ]);
         $agent->assignRole('agent');

         return redirect()->route('agents.index')
             ->with('success', 'Agent a été créée avec succès. Il doit être validé avant de pouvoir se connecter.');
     }

     private function canModerate(User $agent): bool
     {
         $user = auth()->user();

         if ($user->hasRole('admin')) {
             return true;
         }

         return $user->hasRole('manager') && $user->company_id === $agent->company_id;
     }

     public function validateAccount(User $agent)
     {
         abort_unless($this->canModerate($agent), 403);

         $agent->update(['status' => 'validated']);

         return redirect()->route('agents.index')->with('success', 'Agent validé avec succès.');
     }

     public function reject(User $agent)
     {
         abort_unless($this->canModerate($agent), 403);

         $agent->update(['status' => 'rejected']);

         return redirect()->route('agents.index')->with('success', 'Agent rejeté.');
     }



     public function edit(User $agent)
     {
         return view('pages.agents.edit',compact('agent'));
     }



     public function update(Request $request, User $agent)
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
             'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($agent->id)],
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

     public function destroy(User $agent)
     {
         $agent->delete();

         return redirect()->route('agents.index')->with('success','Agent deleted successfully');
     }

     public function show(User $agent){
         $ramassages = $agent->ramassages()->paginate(5);
         $compt_ramassage = $agent->ramassages()->count();
         return view('pages.agents.show', compact('agent', 'ramassages', 'compt_ramassage'));
    }

}

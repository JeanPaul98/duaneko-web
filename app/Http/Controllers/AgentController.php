<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class AgentController extends Controller
{
    private array $genders = ['homme', 'femme', 'autre'];
    private array $idDocumentTypes = ['cni', 'passeport'];

     public function index(){

        if (auth()->user()->hasRole('manager')) {
            $agents = User::role('agent')->where('company_id', auth()->user()->company_id)->paginate(5);
        } else {
            $agents = User::role('agent')->latest()->paginate(5);
        }

        foreach ($agents as $agent) {
            $agent->ramassages_count = $agent->ramassages()->count();
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
             'date_of_birth' => ['nullable', 'date'],
             'gender' => ['nullable', 'string', 'in:' . implode(',', $this->genders)],
             'address' => ['nullable', 'string', 'max:255'],
             'city' => ['nullable', 'string', 'max:255'],
             'district' => ['nullable', 'string', 'max:255'],
             'id_document_type' => ['nullable', 'string', 'in:' . implode(',', $this->idDocumentTypes)],
             'id_document_number' => ['nullable', 'string', 'max:100'],
             'photo' => ['nullable', 'image', 'max:2048'],

         ], $messages);

         if (request()->hasFile('photo')) {
             $file = request()->file('photo');
             $filename = 'photo_' . time() . '.' . $file->getClientOriginalExtension();
             Storage::disk('public')->put($filename, file_get_contents($file));
             $request['photo'] = $filename;
         }

         $agent = User::create([
             'first_name' => $request['first_name'],
             'last_name' => $request['last_name'],
             'phone_number' => $request['phone_number'],
             'email' => $request['email'],
             'password' => Hash::make($request['password']),
             'company_id'=> auth()->user()->company_id,
             'status' => 'pending',
             'date_of_birth' => $request['date_of_birth'] ?? null,
             'gender' => $request['gender'] ?? null,
             'address' => $request['address'] ?? null,
             'city' => $request['city'] ?? null,
             'district' => $request['district'] ?? null,
             'id_document_type' => $request['id_document_type'] ?? null,
             'id_document_number' => $request['id_document_number'] ?? null,
             'photo' => $request['photo'] ?? null,
         ]);
         $agent->assignRole('agent');

         activity()->causedBy(auth()->user())->performedOn($agent)
             ->withProperties(['subject_name' => $agent->full_name()])
             ->log('Agent créé');

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

         activity()->causedBy(auth()->user())->performedOn($agent)
             ->withProperties(['subject_name' => $agent->full_name()])
             ->log('Agent validé');

         return redirect()->route('agents.index')->with('success', 'Agent validé avec succès.');
     }

     public function reject(User $agent)
     {
         abort_unless($this->canModerate($agent), 403);

         $agent->update(['status' => 'rejected']);

         activity()->causedBy(auth()->user())->performedOn($agent)
             ->withProperties(['subject_name' => $agent->full_name()])
             ->log('Agent rejeté');

         return redirect()->route('agents.index')->with('success', 'Agent rejeté.');
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
             'date_of_birth' => ['nullable', 'date'],
             'gender' => ['nullable', 'string', 'in:' . implode(',', $this->genders)],
             'address' => ['nullable', 'string', 'max:255'],
             'city' => ['nullable', 'string', 'max:255'],
             'district' => ['nullable', 'string', 'max:255'],
             'id_document_type' => ['nullable', 'string', 'in:' . implode(',', $this->idDocumentTypes)],
             'id_document_number' => ['nullable', 'string', 'max:100'],
             'photo' => ['nullable', 'image', 'max:2048'],
         ],
          $messages
         );

         if (request()->hasFile('photo')) {
             $file = request()->file('photo');
             $filename = 'photo_' . time() . '.' . $file->getClientOriginalExtension();
             Storage::disk('public')->put($filename, file_get_contents($file));
             $request['photo'] = $filename;
         } else {
             unset($request['photo']);
         }

         $agent->update([
             'first_name' => $request['first_name'],
             'last_name' => $request['last_name'],
             'email' => $request['email'],
             'phone_number' => $request['phone_number'],
             'date_of_birth' => $request['date_of_birth'] ?? null,
             'gender' => $request['gender'] ?? null,
             'address' => $request['address'] ?? null,
             'city' => $request['city'] ?? null,
             'district' => $request['district'] ?? null,
             'id_document_type' => $request['id_document_type'] ?? null,
             'id_document_number' => $request['id_document_number'] ?? null,
             ...(isset($request['photo']) ? ['photo' => $request['photo']] : []),
         ]);
            return redirect()->route('agents.index')->with('success', 'Agent modifié avec succès.');

     }

     public function destroy(User $agent)
     {
         activity()->causedBy(auth()->user())->performedOn($agent)
             ->withProperties(['subject_name' => $agent->full_name()])
             ->log('Agent supprimé');

         $agent->delete();

         return redirect()->route('agents.index')->with('success','Agent deleted successfully');
     }

}

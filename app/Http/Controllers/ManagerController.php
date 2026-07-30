<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\User;
use App\Models\Zone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Validation\Rule;

class ManagerController extends Controller
{
    private array $genders = ['homme', 'femme', 'autre'];
    private array $idDocumentTypes = ['cni', 'passeport'];

    public function index(){

        $managers = User::role('manager')->with('company')->latest()->paginate(5);
        $companies = Company::all();

        foreach ($managers as $manager) {
            $manager->agents_count = User::role('agent')->where('company_id', $manager->company_id)->count();
            $manager->zones_count = Zone::where('company_id', $manager->company_id)->count();
        }

        return view('pages.managers.index',compact('managers', 'companies'))
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => ['required', 'string', 'min:4', 'confirmed'],
            'company_id' => ['required', 'string',],
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

        $manager = User::create([
            'first_name' => $request['first_name'],
            'last_name' => $request['last_name'],
            'phone_number' => $request['phone_number'],
            'email' => $request['email'],
            'company_id' => $request['company_id'],
            'password' => Hash::make($request['password']),
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
        $manager->assignRole('manager');

        activity()->causedBy(auth()->user())->performedOn($manager)
            ->withProperties(['subject_name' => $manager->full_name()])
            ->log('Manager créé');

        return redirect()->route('managers.index')
            ->with('success', 'Le manager a été créée avec succès. Il doit être validé avant de pouvoir se connecter.');
    }

    public function validateAccount(User $manager)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $manager->update(['status' => 'validated']);

        activity()->causedBy(auth()->user())->performedOn($manager)
            ->withProperties(['subject_name' => $manager->full_name()])
            ->log('Manager validé');

        return redirect()->route('managers.index')->with('success', 'Manager validé avec succès.');
    }

    public function reject(User $manager)
    {
        abort_unless(auth()->user()->hasRole('admin'), 403);

        $manager->update(['status' => 'rejected']);

        activity()->causedBy(auth()->user())->performedOn($manager)
            ->withProperties(['subject_name' => $manager->full_name()])
            ->log('Manager rejeté');

        return redirect()->route('managers.index')->with('success', 'Manager rejeté.');
    }

    public function update(Request $request, User $manager)
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
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($manager->id)],
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

        $manager->update([
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
           return redirect()->route('managers.index')->with('success', 'Manager modifié avec succès.');

    }

    public function destroy(User $manager)
    {
        activity()->causedBy(auth()->user())->performedOn($manager)
            ->withProperties(['subject_name' => $manager->full_name()])
            ->log('Manager supprimé');

        $manager->delete();

        return redirect()->route('managers.index')->with('success','Manager deleted successfully');
    }

}

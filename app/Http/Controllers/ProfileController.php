<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function getPhoto($filename)
    {
        $path = storage_path('app/public/' . $filename);

        if (file_exists($path)) {
            return response()->file($path);
        }

        abort(404);
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $messages = [
            'first_name.required' => 'Le prénom est requis',
            'last_name.required' => 'Le nom est requis',
            'email.required' => 'L\'email est requis',
            'current_password.required_with' => 'Le mot de passe actuel est requis pour le modifier',
            'current_password.current_password' => 'Le mot de passe actuel est incorrect',
            'new_password.confirmed' => 'La confirmation du mot de passe ne correspond pas',
        ];

        $validated = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'phone_number' => ['nullable', 'string'],
            'current_password' => ['nullable', 'required_with:new_password', 'current_password'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], $messages);

        $user->update([
            'first_name' => $validated['first_name'],
            'last_name' => $validated['last_name'],
            'email' => $validated['email'],
            'phone_number' => $validated['phone_number'] ?? $user->phone_number,
        ]);

        if (!empty($validated['new_password'])) {
            $user->update(['password' => Hash::make($validated['new_password'])]);
        }

        return redirect()->back()->with('success', 'Profil mis à jour avec succès.');
    }
}

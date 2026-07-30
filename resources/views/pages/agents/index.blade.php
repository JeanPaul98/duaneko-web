@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    $genderLabels = ['homme' => 'Homme', 'femme' => 'Femme', 'autre' => 'Autre'];
    $idDocLabels = ['cni' => 'CNI', 'passeport' => 'Passeport'];
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Agents" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Agents</h3>
                @if (auth()->user()->hasRole('manager'))
                    <button type="button" @click="$dispatch('open-create-agent-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                        Ajouter un agent
                    </button>
                @endif
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Prénom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Téléphone</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($agents as $agent)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">
                                    <div class="flex items-center gap-3">
                                        @if ($agent->photo)
                                            <img src="{{ route('users.photo', $agent->photo) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                        @endif
                                        {{ $agent->first_name }}
                                    </div>
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $agent->last_name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $agent->phone_number }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $agent->email }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($agent->status === 'validated')
                                        <x-ui.badge color="success">Validé</x-ui.badge>
                                    @elseif ($agent->status === 'rejected')
                                        <x-ui.badge color="error">Rejeté</x-ui.badge>
                                    @else
                                        <x-ui.badge color="warning">En attente</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" @click="$dispatch('open-detail-agent-modal', { id: {{ $agent->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</button>

                                        @if (auth()->user()->hasRole('manager'))
                                            <button type="button" @click="$dispatch('open-edit-agent-modal', { id: {{ $agent->id }} })"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                        @endif

                                        @if ($agent->status === 'pending' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager')))
                                            <form action="{{ route('agents.validate', $agent) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-50 px-3 py-1.5 text-theme-xs font-medium text-green-700 hover:bg-green-100 dark:bg-green-500/15 dark:text-green-500">Valider</button>
                                            </form>
                                            <form action="{{ route('agents.reject', $agent) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Rejeter</button>
                                            </form>
                                        @endif

                                        @if (auth()->user()->hasRole('manager'))
                                            <form action="{{ route('agents.destroy', $agent) }}" method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer cet agent ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if (auth()->user()->hasRole('manager'))
                                <!-- Modale de modification de l'agent #{{ $agent->id }} -->
                                <x-ui.modal
                                    @open-edit-agent-modal.window="if ($event.detail.id === {{ $agent->id }}) open = true" :isOpen="old('_editing_agent') == $agent->id" class="max-w-[600px]">
                                    <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                        <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier l'agent</h4>
                                        <form method="POST" action="{{ route('agents.update', $agent) }}" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="_editing_agent" value="{{ $agent->id }}">
                                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                <div>
                                                    <label class="{{ $labelClass }}">Prénom</label>
                                                    <input type="text" name="first_name" value="{{ old('_editing_agent') == $agent->id ? old('first_name') : $agent->first_name }}" class="{{ $inputClass }}">
                                                    @error('first_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Nom</label>
                                                    <input type="text" name="last_name" value="{{ old('_editing_agent') == $agent->id ? old('last_name') : $agent->last_name }}" class="{{ $inputClass }}">
                                                    @error('last_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Téléphone</label>
                                                    <input type="tel" name="phone_number" value="{{ old('_editing_agent') == $agent->id ? old('phone_number') : $agent->phone_number }}" class="{{ $inputClass }}">
                                                    @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Email</label>
                                                    <input type="email" name="email" value="{{ old('_editing_agent') == $agent->id ? old('email') : $agent->email }}" class="{{ $inputClass }}">
                                                    @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Date de naissance</label>
                                                    <input type="date" name="date_of_birth" value="{{ old('_editing_agent') == $agent->id ? old('date_of_birth') : optional($agent->date_of_birth)->format('Y-m-d') }}" class="{{ $inputClass }}">
                                                    @error('date_of_birth') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Genre</label>
                                                    <select name="gender" class="{{ $inputClass }}">
                                                        <option value="">—</option>
                                                        @foreach ($genderLabels as $value => $label)
                                                            <option value="{{ $value }}" @selected((old('_editing_agent') == $agent->id ? old('gender') : $agent->gender) === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('gender') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Ville</label>
                                                    <input type="text" name="city" value="{{ old('_editing_agent') == $agent->id ? old('city') : $agent->city }}" class="{{ $inputClass }}">
                                                    @error('city') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Quartier</label>
                                                    <input type="text" name="district" value="{{ old('_editing_agent') == $agent->id ? old('district') : $agent->district }}" class="{{ $inputClass }}">
                                                    @error('district') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="{{ $labelClass }}">Adresse</label>
                                                    <input type="text" name="address" value="{{ old('_editing_agent') == $agent->id ? old('address') : $agent->address }}" class="{{ $inputClass }}">
                                                    @error('address') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Type de pièce d'identité</label>
                                                    <select name="id_document_type" class="{{ $inputClass }}">
                                                        <option value="">—</option>
                                                        @foreach ($idDocLabels as $value => $label)
                                                            <option value="{{ $value }}" @selected((old('_editing_agent') == $agent->id ? old('id_document_type') : $agent->id_document_type) === $value)>{{ $label }}</option>
                                                        @endforeach
                                                    </select>
                                                    @error('id_document_type') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Numéro de pièce d'identité</label>
                                                    <input type="text" name="id_document_number" value="{{ old('_editing_agent') == $agent->id ? old('id_document_number') : $agent->id_document_number }}" class="{{ $inputClass }}">
                                                    @error('id_document_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="{{ $labelClass }}">Photo</label>
                                                    @if ($agent->photo)
                                                        <img src="{{ route('users.photo', $agent->photo) }}" alt="" class="mb-2 h-12 w-12 rounded-full object-cover">
                                                    @endif
                                                    <input type="file" name="photo" accept="image/*" class="{{ $inputClass }}">
                                                    @error('photo') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                            </div>
                                            <div class="mt-6 flex items-center justify-end gap-3">
                                                <button @click="open = false" type="button"
                                                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                                                <button type="submit"
                                                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                                            </div>
                                        </form>
                                    </div>
                                </x-ui.modal>
                            @endif

                            <!-- Modale de détail de l'agent #{{ $agent->id }} -->
                            <x-ui.modal
                                @open-detail-agent-modal.window="if ($event.detail.id === {{ $agent->id }}) open = true" :isOpen="false" class="max-w-[500px]">
                                <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <div class="mb-6 flex items-center gap-4">
                                        @if ($agent->photo)
                                            <img src="{{ route('users.photo', $agent->photo) }}" alt="" class="h-14 w-14 rounded-full object-cover">
                                        @endif
                                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $agent->first_name }} {{ $agent->last_name }}</h4>
                                    </div>
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Téléphone</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->phone_number }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Date de naissance</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ optional($agent->date_of_birth)->format('d/m/Y') ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Genre</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $genderLabels[$agent->gender] ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Localisation</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ collect([$agent->address, $agent->district, $agent->city])->filter()->implode(', ') ?: '—' }}</p>
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Pièce d'identité</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $idDocLabels[$agent->id_document_type] ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Numéro</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->id_document_number ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Statut</p>
                                            @if ($agent->status === 'validated')
                                                <x-ui.badge color="success">Validé</x-ui.badge>
                                            @elseif ($agent->status === 'rejected')
                                                <x-ui.badge color="error">Rejeté</x-ui.badge>
                                            @else
                                                <x-ui.badge color="warning">En attente</x-ui.badge>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Ramassages assignés</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $agent->ramassages_count }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex items-center justify-end gap-3">
                                        @if ($agent->status === 'pending' && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('manager')))
                                            <form action="{{ route('agents.validate', $agent) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-50 px-3 py-1.5 text-theme-xs font-medium text-green-700 hover:bg-green-100 dark:bg-green-500/15 dark:text-green-500">Valider</button>
                                            </form>
                                            <form action="{{ route('agents.reject', $agent) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Rejeter</button>
                                            </form>
                                        @endif
                                        <button @click="open = false" type="button"
                                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Fermer</button>
                                    </div>
                                </div>
                            </x-ui.modal>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $agents->links() !!}
            </div>
        </div>
    </div>

    @if (auth()->user()->hasRole('manager'))
        <!-- Modale de création d'un agent -->
        <x-ui.modal
            @open-create-agent-modal.window="open = true" :isOpen="old('_creating_agent') ? true : false" class="max-w-[600px]">
            <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un agent</h4>
                <form method="POST" action="{{ route('agents.store') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="_creating_agent" value="1">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div>
                            <label class="{{ $labelClass }}">Prénom</label>
                            <input type="text" name="first_name" value="{{ old('_creating_agent') ? old('first_name') : '' }}" class="{{ $inputClass }}">
                            @error('first_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Nom</label>
                            <input type="text" name="last_name" value="{{ old('_creating_agent') ? old('last_name') : '' }}" class="{{ $inputClass }}">
                            @error('last_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Téléphone</label>
                            <input type="tel" name="phone_number" value="{{ old('_creating_agent') ? old('phone_number') : '' }}" class="{{ $inputClass }}">
                            @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Email</label>
                            <input type="email" name="email" value="{{ old('_creating_agent') ? old('email') : '' }}" class="{{ $inputClass }}">
                            @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Mot de passe</label>
                            <input type="password" name="password" class="{{ $inputClass }}">
                            @error('password') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Confirmation du mot de passe</label>
                            <input type="password" name="password_confirmation" class="{{ $inputClass }}">
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Date de naissance</label>
                            <input type="date" name="date_of_birth" value="{{ old('_creating_agent') ? old('date_of_birth') : '' }}" class="{{ $inputClass }}">
                            @error('date_of_birth') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Genre</label>
                            <select name="gender" class="{{ $inputClass }}">
                                <option value="">—</option>
                                @foreach ($genderLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('gender') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('gender') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Ville</label>
                            <input type="text" name="city" value="{{ old('_creating_agent') ? old('city') : '' }}" class="{{ $inputClass }}">
                            @error('city') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Quartier</label>
                            <input type="text" name="district" value="{{ old('_creating_agent') ? old('district') : '' }}" class="{{ $inputClass }}">
                            @error('district') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Adresse</label>
                            <input type="text" name="address" value="{{ old('_creating_agent') ? old('address') : '' }}" class="{{ $inputClass }}">
                            @error('address') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Type de pièce d'identité</label>
                            <select name="id_document_type" class="{{ $inputClass }}">
                                <option value="">—</option>
                                @foreach ($idDocLabels as $value => $label)
                                    <option value="{{ $value }}" @selected(old('id_document_type') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('id_document_type') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Numéro de pièce d'identité</label>
                            <input type="text" name="id_document_number" value="{{ old('_creating_agent') ? old('id_document_number') : '' }}" class="{{ $inputClass }}">
                            @error('id_document_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Photo</label>
                            <input type="file" name="photo" accept="image/*" class="{{ $inputClass }}">
                            @error('photo') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end gap-3">
                        <button @click="open = false" type="button"
                            class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                        <button type="submit"
                            class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                    </div>
                </form>
            </div>
        </x-ui.modal>
    @endif
@endsection

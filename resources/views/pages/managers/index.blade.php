@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Manageurs" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Manageurs</h3>
                <button type="button" @click="$dispatch('open-create-manager-modal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter un manager
                </button>
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
                        @foreach ($managers as $manager)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->first_name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->last_name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->phone_number }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->email }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($manager->status === 'validated')
                                        <x-ui.badge color="success">Validé</x-ui.badge>
                                    @elseif ($manager->status === 'rejected')
                                        <x-ui.badge color="error">Rejeté</x-ui.badge>
                                    @else
                                        <x-ui.badge color="warning">En attente</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" @click="$dispatch('open-detail-manager-modal', { id: {{ $manager->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</button>
                                        <button type="button" @click="$dispatch('open-edit-manager-modal', { id: {{ $manager->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>

                                        @if ($manager->status === 'pending' && auth()->user()->hasRole('admin'))
                                            <form action="{{ route('managers.validate', $manager) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-50 px-3 py-1.5 text-theme-xs font-medium text-green-700 hover:bg-green-100 dark:bg-green-500/15 dark:text-green-500">Valider</button>
                                            </form>
                                            <form action="{{ route('managers.reject', $manager) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Rejeter</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('managers.destroy', $manager) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce manager ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modale de modification du manager #{{ $manager->id }} -->
                            <x-ui.modal x-data="{ open: {{ old('_editing_manager') == $manager->id ? 'true' : 'false' }} }"
                                @open-edit-manager-modal.window="if ($event.detail.id === {{ $manager->id }}) open = true" :isOpen="false" class="max-w-[600px]">
                                <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier le manager</h4>
                                    <form method="POST" action="{{ route('managers.update', $manager) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_editing_manager" value="{{ $manager->id }}">
                                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <div>
                                                <label class="{{ $labelClass }}">Prénom</label>
                                                <input type="text" name="first_name" value="{{ old('_editing_manager') == $manager->id ? old('first_name') : $manager->first_name }}" class="{{ $inputClass }}">
                                                @error('first_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Nom</label>
                                                <input type="text" name="last_name" value="{{ old('_editing_manager') == $manager->id ? old('last_name') : $manager->last_name }}" class="{{ $inputClass }}">
                                                @error('last_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Téléphone</label>
                                                <input type="tel" name="phone_number" value="{{ old('_editing_manager') == $manager->id ? old('phone_number') : $manager->phone_number }}" class="{{ $inputClass }}">
                                                @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Email</label>
                                                <input type="email" name="email" value="{{ old('_editing_manager') == $manager->id ? old('email') : $manager->email }}" class="{{ $inputClass }}">
                                                @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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

                            <!-- Modale de détail du manager #{{ $manager->id }} -->
                            <x-ui.modal x-data="{ open: false }"
                                @open-detail-manager-modal.window="if ($event.detail.id === {{ $manager->id }}) open = true" :isOpen="false" class="max-w-[500px]">
                                <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $manager->first_name }} {{ $manager->last_name }}</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $manager->email }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Téléphone</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $manager->phone_number }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Entreprise</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $manager->company->name ?? '—' }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Statut</p>
                                            @if ($manager->status === 'validated')
                                                <x-ui.badge color="success">Validé</x-ui.badge>
                                            @elseif ($manager->status === 'rejected')
                                                <x-ui.badge color="error">Rejeté</x-ui.badge>
                                            @else
                                                <x-ui.badge color="warning">En attente</x-ui.badge>
                                            @endif
                                        </div>
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Agents</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $manager->agents_count }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Zones</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $manager->zones_count }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mt-6 flex items-center justify-end">
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
                {!! $managers->links() !!}
            </div>
        </div>
    </div>

    <!-- Modale de création d'un manager -->
    <x-ui.modal x-data="{ open: {{ old('_creating_manager') ? 'true' : 'false' }} }"
        @open-create-manager-modal.window="open = true" :isOpen="false" class="max-w-[600px]">
        <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un manager</h4>
            <form method="POST" action="{{ route('managers.store') }}">
                @csrf
                <input type="hidden" name="_creating_manager" value="1">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Prénom</label>
                        <input type="text" name="first_name" value="{{ old('_creating_manager') ? old('first_name') : '' }}" class="{{ $inputClass }}">
                        @error('first_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Nom</label>
                        <input type="text" name="last_name" value="{{ old('_creating_manager') ? old('last_name') : '' }}" class="{{ $inputClass }}">
                        @error('last_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Téléphone</label>
                        <input type="tel" name="phone_number" value="{{ old('_creating_manager') ? old('phone_number') : '' }}" class="{{ $inputClass }}">
                        @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Email</label>
                        <input type="email" name="email" value="{{ old('_creating_manager') ? old('email') : '' }}" class="{{ $inputClass }}">
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
                    <div class="sm:col-span-2">
                        <label class="{{ $labelClass }}">Société</label>
                        <select name="company_id" class="{{ $inputClass }}">
                            @foreach ($companies as $company)
                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                            @endforeach
                        </select>
                        @error('company_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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
@endsection

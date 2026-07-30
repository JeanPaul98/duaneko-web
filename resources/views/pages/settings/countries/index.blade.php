@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Pays" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif
        @if ($message = Session::get('error'))
            <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700 dark:border-red-800 dark:bg-red-900/20 dark:text-red-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pays</h3>
                <button type="button" @click="$dispatch('open-create-country-modal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter un pays
                </button>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Code ISO</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Devise</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Niveaux / Subdivisions / Entreprises</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($countries as $country)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $country->name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $country->iso_code }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $country->currency ?? '—' }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($country->is_active)
                                        <x-ui.badge color="success">Actif</x-ui.badge>
                                    @else
                                        <x-ui.badge color="light">Inactif</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">
                                    {{ $country->division_levels_count }} / {{ $country->administrative_divisions_count }} / {{ $country->companies_count }}
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" @click="$dispatch('open-edit-country-modal', { id: {{ $country->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                        <form action="{{ route('parametres.pays.destroy', $country) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce pays ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modale de modification du pays #{{ $country->id }} -->
                            <x-ui.modal
                                @open-edit-country-modal.window="if ($event.detail.id === {{ $country->id }}) open = true" :isOpen="old('_editing_country') == $country->id" class="max-w-[600px]">
                                <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier le pays</h4>
                                    <form method="POST" action="{{ route('parametres.pays.update', $country) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_editing_country" value="{{ $country->id }}">
                                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <div>
                                                <label class="{{ $labelClass }}">Nom</label>
                                                <input type="text" name="name" value="{{ old('_editing_country') == $country->id ? old('name') : $country->name }}" class="{{ $inputClass }}">
                                                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Code ISO</label>
                                                <input type="text" name="iso_code" value="{{ old('_editing_country') == $country->id ? old('iso_code') : $country->iso_code }}" class="{{ $inputClass }}">
                                                @error('iso_code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Indicatif téléphonique</label>
                                                <input type="text" name="phone_code" value="{{ old('_editing_country') == $country->id ? old('phone_code') : $country->phone_code }}" class="{{ $inputClass }}">
                                                @error('phone_code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Devise</label>
                                                <input type="text" name="currency" value="{{ old('_editing_country') == $country->id ? old('currency') : $country->currency }}" class="{{ $inputClass }}">
                                                @error('currency') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <input type="checkbox" name="is_active" value="1" id="active-{{ $country->id }}" @checked($country->is_active) class="h-5 w-5 rounded border-gray-300">
                                                <label for="active-{{ $country->id }}" class="text-sm text-gray-700 dark:text-gray-400">Pays actif</label>
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
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Aucun pays configuré.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $countries->links() !!}
            </div>
        </div>
    </div>

    <!-- Modale de création d'un pays -->
    <x-ui.modal
        @open-create-country-modal.window="open = true" :isOpen="old('_creating_country') ? true : false" class="max-w-[600px]">
        <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un pays</h4>
            <form method="POST" action="{{ route('parametres.pays.store') }}">
                @csrf
                <input type="hidden" name="_creating_country" value="1">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Nom</label>
                        <input type="text" name="name" value="{{ old('_creating_country') ? old('name') : '' }}" class="{{ $inputClass }}">
                        @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Code ISO</label>
                        <input type="text" name="iso_code" placeholder="TG, CA, FR..." value="{{ old('_creating_country') ? old('iso_code') : '' }}" class="{{ $inputClass }}">
                        @error('iso_code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Indicatif téléphonique</label>
                        <input type="text" name="phone_code" placeholder="+228" value="{{ old('_creating_country') ? old('phone_code') : '' }}" class="{{ $inputClass }}">
                        @error('phone_code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Devise</label>
                        <input type="text" name="currency" placeholder="XOF, CAD, EUR..." value="{{ old('_creating_country') ? old('currency') : '' }}" class="{{ $inputClass }}">
                        @error('currency') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="is_active" value="1" id="active-new" checked class="h-5 w-5 rounded border-gray-300">
                        <label for="active-new" class="text-sm text-gray-700 dark:text-gray-400">Pays actif</label>
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

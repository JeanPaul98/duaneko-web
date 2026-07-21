@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Entreprises" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Entreprises</h3>
                <button type="button" @click="$dispatch('open-create-company-modal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter une entreprise
                </button>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Slug</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $company->name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $company->slug }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <button type="button" @click="$dispatch('open-detail-company-modal', { id: {{ $company->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</button>
                                        <button type="button" @click="$dispatch('open-edit-company-modal', { id: {{ $company->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette entreprise ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modale de modification de l'entreprise #{{ $company->id }} -->
                            <x-ui.modal x-data="{ open: {{ old('_editing_company') == $company->id ? 'true' : 'false' }} }"
                                @open-edit-company-modal.window="if ($event.detail.id === {{ $company->id }}) open = true" :isOpen="false" class="max-w-[500px]">
                                <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier l'entreprise</h4>
                                    <form method="POST" action="{{ route('companies.update', $company) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_editing_company" value="{{ $company->id }}">
                                        <div class="space-y-5">
                                            <div>
                                                <label class="{{ $labelClass }}">Nom</label>
                                                <input type="text" name="name" value="{{ old('_editing_company') == $company->id ? old('name') : $company->name }}" class="{{ $inputClass }}">
                                                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Slug</label>
                                                <input type="text" name="slug" value="{{ old('_editing_company') == $company->id ? old('slug') : $company->slug }}" class="{{ $inputClass }}">
                                                @error('slug') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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

                            <!-- Modale de détail de l'entreprise #{{ $company->id }} -->
                            <x-ui.modal x-data="{ open: false }"
                                @open-detail-company-modal.window="if ($event.detail.id === {{ $company->id }}) open = true" :isOpen="false" class="max-w-[500px]">
                                <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $company->name }}</h4>
                                    <div class="space-y-4">
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Slug</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->slug }}</p>
                                        </div>
                                        <div class="grid grid-cols-3 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Managers</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->managers_count }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Agents</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->agents_count }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Zones</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->zones_count }}</p>
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
                {!! $companies->links() !!}
            </div>
        </div>
    </div>

    <!-- Modale de création d'une entreprise -->
    <x-ui.modal x-data="{ open: {{ old('_creating_company') ? 'true' : 'false' }} }"
        @open-create-company-modal.window="open = true" :isOpen="false" class="max-w-[500px]">
        <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter une entreprise</h4>
            <form method="POST" action="{{ route('companies.store') }}">
                @csrf
                <input type="hidden" name="_creating_company" value="1">
                <div>
                    <label class="{{ $labelClass }}">Nom</label>
                    <input type="text" name="name" value="{{ old('_creating_company') ? old('name') : '' }}" class="{{ $inputClass }}">
                    @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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

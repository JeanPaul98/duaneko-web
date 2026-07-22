@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Hiérarchie administrative" />

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

        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <form method="GET" action="{{ route('parametres.hierarchie.index') }}" class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-400">Pays</label>
                <select name="country" class="{{ $inputClass }} max-w-xs" onchange="this.form.submit()">
                    @forelse ($countries as $c)
                        <option value="{{ $c->id }}" @selected($country && $country->id === $c->id)>{{ $c->name }}</option>
                    @empty
                        <option value="">Aucun pays</option>
                    @endforelse
                </select>
            </form>
            <a href="{{ route('parametres.pays.index') }}"
                class="inline-flex items-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                Gérer les pays
            </a>
        </div>

        @if (!$country)
            <div class="rounded-2xl border border-gray-200 bg-white p-10 text-center dark:border-white/[0.05] dark:bg-white/[0.03]">
                <p class="text-theme-sm text-gray-500 dark:text-gray-400">Aucun pays configuré pour le moment.</p>
                <a href="{{ route('parametres.pays.index') }}" class="mt-4 inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">Créer un pays</a>
            </div>
        @else
            <div x-data="hierarchyExplorer(@json($levels), @json($divisions))" class="grid grid-cols-1 gap-4 lg:grid-cols-4">
                <div class="lg:col-span-3">
                    <div class="flex gap-4 overflow-x-auto pb-2">
                        <template x-for="(level, depth) in levels" :key="level.id">
                            <div class="w-64 shrink-0 rounded-2xl border border-gray-200 bg-white dark:border-white/[0.05] dark:bg-white/[0.03]">
                                <div class="flex items-center justify-between border-b border-gray-100 px-4 py-3 dark:border-white/[0.05]">
                                    <span class="text-theme-xs font-semibold uppercase tracking-wide text-gray-500 dark:text-gray-400" x-text="level.name"></span>
                                    <div class="flex items-center gap-1">
                                        <button type="button" class="text-gray-400 hover:text-gray-700 dark:hover:text-gray-200" @click="openEditLevelModal(level)" title="Renommer le niveau">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </button>
                                        <button type="button" class="text-gray-400 hover:text-red-600" @click="confirmDeleteLevel(level)" title="Supprimer le niveau">
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/></svg>
                                        </button>
                                    </div>
                                </div>

                                <template x-if="depth > 0 && !selected[depth - 1]">
                                    <p class="px-4 py-6 text-center text-theme-xs text-gray-400 dark:text-gray-500">
                                        Sélectionnez d'abord un élément dans « <span x-text="levels[depth - 1].name"></span> ».
                                    </p>
                                </template>

                                <template x-if="depth === 0 || selected[depth - 1]">
                                    <div>
                                        <ul class="max-h-80 overflow-y-auto py-1">
                                            <template x-for="item in columnItems(depth)" :key="item.id">
                                                <li>
                                                    <button type="button" @click="select(depth, item.id)"
                                                        class="flex w-full items-center justify-between px-4 py-2.5 text-left text-theme-sm hover:bg-gray-50 dark:hover:bg-white/[0.03]"
                                                        :class="selected[depth] === item.id ? 'bg-brand-50 text-brand-600 dark:bg-brand-500/10 dark:text-brand-400 font-medium' : 'text-gray-700 dark:text-gray-300'">
                                                        <span x-text="item.name"></span>
                                                        <span x-show="item.children_count > 0" class="text-gray-300 dark:text-gray-600">›</span>
                                                    </button>
                                                </li>
                                            </template>
                                            <template x-if="columnItems(depth).length === 0">
                                                <li class="px-4 py-6 text-center text-theme-xs text-gray-400 dark:text-gray-500">Aucun élément.</li>
                                            </template>
                                        </ul>
                                        <div class="border-t border-gray-100 p-2 dark:border-white/[0.05]">
                                            <button type="button" @click="openCreateDivisionModal(level, depth > 0 ? selected[depth - 1] : null)"
                                                class="w-full rounded-lg px-3 py-2 text-center text-theme-xs font-medium text-brand-500 hover:bg-brand-50 dark:hover:bg-brand-500/10">
                                                + Ajouter
                                            </button>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </template>

                        <div class="w-64 shrink-0 rounded-2xl border border-dashed border-gray-300 dark:border-gray-700">
                            <button type="button" @click="openCreateLevelModal()"
                                class="flex h-full min-h-[120px] w-full flex-col items-center justify-center gap-2 px-4 py-6 text-center text-theme-sm font-medium text-gray-500 hover:text-brand-500 dark:text-gray-400">
                                <span class="text-2xl">+</span>
                                Ajouter un niveau
                            </button>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                        <template x-if="!selectedDivision">
                            <p class="text-theme-sm text-gray-400 dark:text-gray-500">Sélectionnez un élément pour voir son détail.</p>
                        </template>
                        <template x-if="selectedDivision">
                            <div class="space-y-4">
                                <h4 class="text-lg font-semibold text-gray-800 dark:text-white/90" x-text="selectedDivision.name"></h4>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Code</p>
                                    <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="selectedDivision.code || '—'"></p>
                                </div>
                                <div class="grid grid-cols-3 gap-2 text-center">
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Enfants</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="selectedDivision.children_count"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Entreprises</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="selectedDivision.companies_count"></p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-gray-500 dark:text-gray-400">Zones</p>
                                        <p class="text-sm font-medium text-gray-800 dark:text-white/90" x-text="selectedDivision.zones_count"></p>
                                    </div>
                                </div>
                                <div class="flex flex-col gap-2 pt-2">
                                    <button type="button" @click="openEditDivisionModal(selectedDivision)"
                                        class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                    <button type="button" @click="confirmDeleteDivision(selectedDivision)"
                                        class="w-full rounded-lg bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        @endif
    </div>

    <!-- Modale : ajouter un niveau -->
    <x-ui.modal @open-create-level-modal.window="open = true" :isOpen="false" class="max-w-[500px]">
        <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un niveau administratif</h4>
            <form method="POST" action="{{ route('parametres.niveaux.store') }}">
                @csrf
                <input type="hidden" name="_form" value="create_level">
                <input type="hidden" name="country_id" value="{{ $country?->id }}">
                <label class="{{ $labelClass }}">Nom du niveau</label>
                <input type="text" name="name" value="{{ old('_form') === 'create_level' ? old('name') : '' }}" placeholder="Canton, District, Quartier..." class="{{ $inputClass }}">
                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="open = false" type="button" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Ajouter</button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    <!-- Modale : modifier un niveau -->
    <x-ui.modal @open-edit-level-modal.window="open = true" :isOpen="false" class="max-w-[500px]">
        <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Renommer le niveau</h4>
            <form method="POST" id="level-edit-form" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit_level">
                <input type="hidden" id="level-edit-id" name="_id" value="{{ old('_form') === 'edit_level' ? old('_id') : '' }}">
                <label class="{{ $labelClass }}">Nom du niveau</label>
                <input type="text" id="level-edit-name" name="name" value="{{ old('_form') === 'edit_level' ? old('name') : '' }}" class="{{ $inputClass }}">
                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="open = false" type="button" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    <!-- Modale : ajouter une division -->
    <x-ui.modal @open-create-division-modal.window="open = true" :isOpen="false" class="max-w-[500px]">
        <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90" id="division-create-title">Ajouter une subdivision</h4>
            <form method="POST" action="{{ route('parametres.subdivisions.store') }}">
                @csrf
                <input type="hidden" name="_form" value="create_division">
                <input type="hidden" name="country_id" value="{{ $country?->id }}">
                <input type="hidden" id="division-create-division_level_id" name="division_level_id" value="{{ old('_form') === 'create_division' ? old('division_level_id') : '' }}">
                <input type="hidden" id="division-create-parent_id" name="parent_id" value="{{ old('_form') === 'create_division' ? old('parent_id') : '' }}">
                <label class="{{ $labelClass }}">Nom</label>
                <input type="text" id="division-create-name" name="name" value="{{ old('_form') === 'create_division' ? old('name') : '' }}" class="{{ $inputClass }}">
                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <label class="{{ $labelClass }} mt-4">Code (optionnel)</label>
                <input type="text" name="code" value="{{ old('_form') === 'create_division' ? old('code') : '' }}" class="{{ $inputClass }}">
                @error('code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="open = false" type="button" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Ajouter</button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    <!-- Modale : modifier une division -->
    <x-ui.modal @open-edit-division-modal.window="open = true" :isOpen="false" class="max-w-[500px]">
        <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier la subdivision</h4>
            <form method="POST" id="division-edit-form" action="">
                @csrf
                @method('PUT')
                <input type="hidden" name="_form" value="edit_division">
                <input type="hidden" id="division-edit-id" name="_id" value="{{ old('_form') === 'edit_division' ? old('_id') : '' }}">
                <input type="hidden" id="division-edit-division_level_id" name="division_level_id" value="{{ old('_form') === 'edit_division' ? old('division_level_id') : '' }}">
                <input type="hidden" id="division-edit-parent_id" name="parent_id" value="{{ old('_form') === 'edit_division' ? old('parent_id') : '' }}">
                <label class="{{ $labelClass }}">Nom</label>
                <input type="text" id="division-edit-name" name="name" value="{{ old('_form') === 'edit_division' ? old('name') : '' }}" class="{{ $inputClass }}">
                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <label class="{{ $labelClass }} mt-4">Code (optionnel)</label>
                <input type="text" id="division-edit-code" name="code" value="{{ old('_form') === 'edit_division' ? old('code') : '' }}" class="{{ $inputClass }}">
                @error('code') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                <div class="mt-6 flex items-center justify-end gap-3">
                    <button @click="open = false" type="button" class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                    <button type="submit" class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                </div>
            </form>
        </div>
    </x-ui.modal>

    <form method="POST" id="division-delete-form" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    <form method="POST" id="level-delete-form" action="" class="hidden">
        @csrf
        @method('DELETE')
    </form>

    @push('scripts')
        <script>
            function hierarchyExplorer(levels, divisions) {
                return {
                    levels: levels,
                    divisions: divisions,
                    selected: [],

                    columnItems(depth) {
                        const level = this.levels[depth];
                        if (!level) return [];
                        const parentId = depth === 0 ? null : this.selected[depth - 1];
                        if (depth > 0 && !parentId) return [];

                        return this.divisions.filter(function (d) {
                            return d.division_level_id === level.id && (depth === 0 ? d.parent_id === null : d.parent_id === parentId);
                        });
                    },

                    select(depth, id) {
                        this.selected = this.selected.slice(0, depth);
                        this.selected[depth] = (this.selected[depth] === id) ? null : id;
                    },

                    get selectedDivision() {
                        for (let i = this.selected.length - 1; i >= 0; i--) {
                            if (this.selected[i]) {
                                return this.divisions.find(function (d) { return d.id === this.selected[i]; }.bind(this));
                            }
                        }
                        return null;
                    },

                    openCreateLevelModal() {
                        window.dispatchEvent(new Event('open-create-level-modal'));
                    },

                    openEditLevelModal(level) {
                        document.getElementById('level-edit-form').action = window.levelUpdateUrlTemplate.replace('__ID__', level.id);
                        document.getElementById('level-edit-id').value = level.id;
                        document.getElementById('level-edit-name').value = level.name;
                        window.dispatchEvent(new Event('open-edit-level-modal'));
                    },

                    confirmDeleteLevel(level) {
                        if (!confirm('Supprimer le niveau "' + level.name + '" ? Impossible si des subdivisions l\'utilisent.')) return;
                        const form = document.getElementById('level-delete-form');
                        form.action = window.levelDeleteUrlTemplate.replace('__ID__', level.id);
                        form.submit();
                    },

                    openCreateDivisionModal(level, parentId) {
                        document.getElementById('division-create-title').textContent = 'Ajouter : ' + level.name;
                        document.getElementById('division-create-division_level_id').value = level.id;
                        document.getElementById('division-create-parent_id').value = parentId || '';
                        document.getElementById('division-create-name').value = '';
                        window.dispatchEvent(new Event('open-create-division-modal'));
                    },

                    openEditDivisionModal(division) {
                        document.getElementById('division-edit-form').action = window.divisionUpdateUrlTemplate.replace('__ID__', division.id);
                        document.getElementById('division-edit-id').value = division.id;
                        document.getElementById('division-edit-division_level_id').value = division.division_level_id;
                        document.getElementById('division-edit-parent_id').value = division.parent_id || '';
                        document.getElementById('division-edit-name').value = division.name;
                        document.getElementById('division-edit-code').value = division.code || '';
                        window.dispatchEvent(new Event('open-edit-division-modal'));
                    },

                    confirmDeleteDivision(division) {
                        if (!confirm('Supprimer "' + division.name + '" ? Impossible si des enfants, entreprises ou zones en dépendent.')) return;
                        const form = document.getElementById('division-delete-form');
                        form.action = window.divisionDeleteUrlTemplate.replace('__ID__', division.id);
                        form.submit();
                    },
                };
            }

            window.levelUpdateUrlTemplate = "{{ route('parametres.niveaux.update', ['niveau' => '__ID__']) }}";
            window.levelDeleteUrlTemplate = "{{ route('parametres.niveaux.destroy', ['niveau' => '__ID__']) }}";
            window.divisionUpdateUrlTemplate = "{{ route('parametres.subdivisions.update', ['subdivision' => '__ID__']) }}";
            window.divisionDeleteUrlTemplate = "{{ route('parametres.subdivisions.destroy', ['subdivision' => '__ID__']) }}";

            @if (old('_form') === 'create_level')
                window.addEventListener('DOMContentLoaded', function () {
                    window.dispatchEvent(new Event('open-create-level-modal'));
                });
            @endif

            @if (old('_form') === 'edit_level')
                window.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('level-edit-form').action = window.levelUpdateUrlTemplate.replace('__ID__', '{{ old('_id') }}');
                    window.dispatchEvent(new Event('open-edit-level-modal'));
                });
            @endif

            @if (old('_form') === 'create_division')
                window.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('division-create-title').textContent = 'Ajouter une subdivision';
                    window.dispatchEvent(new Event('open-create-division-modal'));
                });
            @endif

            @if (old('_form') === 'edit_division')
                window.addEventListener('DOMContentLoaded', function () {
                    document.getElementById('division-edit-form').action = window.divisionUpdateUrlTemplate.replace('__ID__', '{{ old('_id') }}');
                    window.dispatchEvent(new Event('open-edit-division-modal'));
                });
            @endif
        </script>
    @endpush
@endsection

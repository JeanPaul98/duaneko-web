@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
    $typeLabels = [
        'mairie' => 'Mairie / Commune',
        'ong' => 'ONG / Partenaire',
        'recycleur' => 'Recycleur / Vendeur',
    ];
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
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Type</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Ville</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">
                                    <div class="flex items-center gap-3">
                                        @if ($company->logo)
                                            <img src="{{ route('companies.logo', $company->logo) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                        @endif
                                        {{ $company->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-3.5">
                                    <x-ui.badge color="primary">{{ $typeLabels[$company->type] ?? $company->type }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $company->city ?? '—' }}</td>
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
                            <x-ui.modal
                                @open-edit-company-modal.window="if ($event.detail.id === {{ $company->id }}) open = true" :isOpen="old('_editing_company') == $company->id" class="max-w-[700px]">
                                <div class="relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier l'entreprise</h4>
                                    <form method="POST" action="{{ route('companies.update', $company) }}" enctype="multipart/form-data">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_editing_company" value="{{ $company->id }}">
                                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
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
                                            <div>
                                                <label class="{{ $labelClass }}">Type d'organisation</label>
                                                <select name="type" class="{{ $inputClass }}">
                                                    @foreach ($typeLabels as $value => $label)
                                                        <option value="{{ $value }}" {{ (old('_editing_company') == $company->id ? old('type') : $company->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                                @error('type') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Téléphone</label>
                                                <input type="tel" name="phone_number" value="{{ old('_editing_company') == $company->id ? old('phone_number') : $company->phone_number }}" class="{{ $inputClass }}">
                                                @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Email</label>
                                                <input type="email" name="email" value="{{ old('_editing_company') == $company->id ? old('email') : $company->email }}" class="{{ $inputClass }}">
                                                @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Ville</label>
                                                <input type="text" name="city" value="{{ old('_editing_company') == $company->id ? old('city') : $company->city }}" class="{{ $inputClass }}">
                                                @error('city') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Quartier</label>
                                                <input type="text" name="district" value="{{ old('_editing_company') == $company->id ? old('district') : $company->district }}" class="{{ $inputClass }}">
                                                @error('district') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="{{ $labelClass }}">Adresse</label>
                                                <input type="text" name="address" value="{{ old('_editing_company') == $company->id ? old('address') : $company->address }}" class="{{ $inputClass }}">
                                                @error('address') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Pays</label>
                                                <select id="company-edit-{{ $company->id }}-country_id" name="country_id" class="{{ $inputClass }}" onchange="onCompanyCountryChange('company-edit-{{ $company->id }}')">
                                                    <option value="">—</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" @selected((old('_editing_company') == $company->id ? old('country_id') : $company->country_id) == $country->id)>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                                @error('country_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Subdivision administrative</label>
                                                <select id="company-edit-{{ $company->id }}-administrative_division_id" name="administrative_division_id" class="{{ $inputClass }}" data-selected="{{ old('_editing_company') == $company->id ? old('administrative_division_id') : $company->administrative_division_id }}">
                                                    @if ($company->administrativeDivision)
                                                        <option value="{{ $company->administrativeDivision->id }}" selected>{{ $company->administrativeDivision->fullPath() }}</option>
                                                    @endif
                                                </select>
                                                @error('administrative_division_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="{{ $labelClass }}">Description</label>
                                                <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('_editing_company') == $company->id ? old('description') : $company->description }}</textarea>
                                                @error('description') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div class="sm:col-span-2">
                                                <label class="{{ $labelClass }}">Logo</label>
                                                @if ($company->logo)
                                                    <img src="{{ route('companies.logo', $company->logo) }}" alt="" class="mb-2 h-12 w-12 rounded-full object-cover">
                                                @endif
                                                <input type="file" name="logo" accept="image/*" class="{{ $inputClass }}">
                                                @error('logo') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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
                            <x-ui.modal
                                @open-detail-company-modal.window="if ($event.detail.id === {{ $company->id }}) open = true" :isOpen="false" class="max-w-[500px]">
                                <div class="relative w-full max-w-[500px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <div class="mb-6 flex items-center gap-4">
                                        @if ($company->logo)
                                            <img src="{{ route('companies.logo', $company->logo) }}" alt="" class="h-14 w-14 rounded-full object-cover">
                                        @endif
                                        <div>
                                            <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $company->name }}</h4>
                                            <x-ui.badge color="primary">{{ $typeLabels[$company->type] ?? $company->type }}</x-ui.badge>
                                        </div>
                                    </div>
                                    <div class="space-y-4">
                                        @if ($company->description)
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Description</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->description }}</p>
                                            </div>
                                        @endif
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Téléphone</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->phone_number ?? '—' }}</p>
                                            </div>
                                            <div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $company->email ?? '—' }}</p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Adresse</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ collect([$company->address, $company->district, $company->city])->filter()->implode(', ') ?: '—' }}
                                            </p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Pays / Subdivision</p>
                                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">
                                                {{ $company->country->name ?? '—' }}{{ $company->administrativeDivision ? ' — ' . $company->administrativeDivision->fullPath() : '' }}
                                            </p>
                                        </div>
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
    <x-ui.modal
        @open-create-company-modal.window="open = true" :isOpen="old('_creating_company') ? true : false" class="max-w-[700px]">
        <div class="relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter une entreprise</h4>
            <form method="POST" action="{{ route('companies.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="_creating_company" value="1">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Nom</label>
                        <input type="text" name="name" value="{{ old('_creating_company') ? old('name') : '' }}" class="{{ $inputClass }}">
                        @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Type d'organisation</label>
                        <select name="type" class="{{ $inputClass }}">
                            @foreach ($typeLabels as $value => $label)
                                <option value="{{ $value }}" {{ old('type') === $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('type') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Téléphone</label>
                        <input type="tel" name="phone_number" value="{{ old('_creating_company') ? old('phone_number') : '' }}" class="{{ $inputClass }}">
                        @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Email</label>
                        <input type="email" name="email" value="{{ old('_creating_company') ? old('email') : '' }}" class="{{ $inputClass }}">
                        @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Ville</label>
                        <input type="text" name="city" value="{{ old('_creating_company') ? old('city') : '' }}" class="{{ $inputClass }}">
                        @error('city') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Quartier</label>
                        <input type="text" name="district" value="{{ old('_creating_company') ? old('district') : '' }}" class="{{ $inputClass }}">
                        @error('district') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="{{ $labelClass }}">Adresse</label>
                        <input type="text" name="address" value="{{ old('_creating_company') ? old('address') : '' }}" class="{{ $inputClass }}">
                        @error('address') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Pays</label>
                        <select id="company-create-country_id" name="country_id" class="{{ $inputClass }}" onchange="onCompanyCountryChange('company-create')">
                            <option value="">—</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>{{ $country->name }}</option>
                            @endforeach
                        </select>
                        @error('country_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Subdivision administrative</label>
                        <select id="company-create-administrative_division_id" name="administrative_division_id" class="{{ $inputClass }}" data-selected="{{ old('administrative_division_id') }}">
                        </select>
                        @error('administrative_division_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="{{ $labelClass }}">Description</label>
                        <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('_creating_company') ? old('description') : '' }}</textarea>
                        @error('description') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label class="{{ $labelClass }}">Logo</label>
                        <input type="file" name="logo" accept="image/*" class="{{ $inputClass }}">
                        @error('logo') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
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

    @push('scripts')
        <script>
            window.companyDivisionsUrl = "{{ route('parametres.divisions.children') }}";

            function updateCompanyDivisionSelect(prefix) {
                const countrySelect = document.getElementById(prefix + '-country_id');
                const divisionSelect = document.getElementById(prefix + '-administrative_division_id');
                if (!countrySelect || !divisionSelect) return;

                const countryId = countrySelect.value;
                const wanted = divisionSelect.dataset.selected || '';

                if (!countryId) {
                    divisionSelect.innerHTML = '';
                    return;
                }

                fetch(window.companyDivisionsUrl + '?country_id=' + countryId)
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        divisionSelect.innerHTML = '<option value="">—</option>';
                        data.forEach(function (d) {
                            const opt = document.createElement('option');
                            opt.value = d.id;
                            opt.textContent = d.path;
                            if (String(d.id) === String(wanted)) opt.selected = true;
                            divisionSelect.appendChild(opt);
                        });
                    });
            }

            function onCompanyCountryChange(prefix) {
                updateCompanyDivisionSelect(prefix);
            }

            window.addEventListener('open-create-company-modal', function () {
                setTimeout(function () { updateCompanyDivisionSelect('company-create'); }, 30);
            });

            @foreach ($companies as $company)
                window.addEventListener('open-edit-company-modal', function (e) {
                    if (e.detail.id === {{ $company->id }}) {
                        setTimeout(function () { updateCompanyDivisionSelect('company-edit-{{ $company->id }}'); }, 30);
                    }
                });
            @endforeach

            @if (old('_creating_company'))
                window.addEventListener('DOMContentLoaded', function () { updateCompanyDivisionSelect('company-create'); });
            @endif

            @if (old('_editing_company'))
                window.addEventListener('DOMContentLoaded', function () { updateCompanyDivisionSelect('company-edit-{{ old('_editing_company') }}'); });
            @endif
        </script>
    @endpush
@endsection

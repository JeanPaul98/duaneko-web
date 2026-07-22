@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Zones" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Zones</h3>
                <button type="button" @click="$dispatch('open-create-zone-modal')"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter une zone
                </button>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Carte Google</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Couleur</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($zones as $zone)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $zone->name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $zone->google_map_name }}</td>
                                <td class="px-6 py-3.5">
                                    <span class="inline-flex items-center gap-2">
                                        <span class="h-3 w-3 rounded-full" style="background-color: {{ $zone->color }}"></span>
                                        {{ $zone->color }}
                                    </span>
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('zones.show', $zone) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</a>
                                        <button type="button" @click="$dispatch('open-edit-zone-modal', { id: {{ $zone->id }} })"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                        <form action="{{ route('zones.destroy', $zone) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette zone ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>

                            <!-- Modale de modification de la zone #{{ $zone->id }} -->
                            <x-ui.modal
                                @open-edit-zone-modal.window="if ($event.detail.id === {{ $zone->id }}) open = true" :isOpen="old('_editing_zone') == $zone->id" class="max-w-[1100px]">
                                <div class="relative w-full max-w-[1100px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                    <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier la zone</h4>
                                    <form method="POST" action="{{ route('zones.update', $zone) }}">
                                        @csrf
                                        @method('PUT')
                                        <input type="hidden" name="_editing_zone" value="{{ $zone->id }}">
                                        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                            <div>
                                                <label class="{{ $labelClass }}">Nom</label>
                                                <input type="text" name="name" value="{{ old('_editing_zone') == $zone->id ? old('name') : $zone->name }}" class="{{ $inputClass }}">
                                                @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Couleur</label>
                                                <input type="color" name="color" value="{{ old('_editing_zone') == $zone->id ? old('color') : $zone->color }}" class="{{ $inputClass }} h-11">
                                                @error('color') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Pays</label>
                                                <select id="zone-edit-{{ $zone->id }}-country_id" class="{{ $inputClass }}" onchange="onZoneCountryChange('zone-edit-{{ $zone->id }}')">
                                                    <option value="">—</option>
                                                    @foreach ($countries as $country)
                                                        <option value="{{ $country->id }}" @selected($zone->administrativeDivision && $zone->administrativeDivision->country_id == $country->id)>{{ $country->name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="{{ $labelClass }}">Subdivision administrative</label>
                                                <select id="zone-edit-{{ $zone->id }}-administrative_division_id" name="administrative_division_id" class="{{ $inputClass }}" data-selected="{{ old('_editing_zone') == $zone->id ? old('administrative_division_id') : $zone->administrative_division_id }}">
                                                    @if ($zone->administrativeDivision)
                                                        <option value="{{ $zone->administrativeDivision->id }}" selected>{{ $zone->administrativeDivision->fullPath() }}</option>
                                                    @endif
                                                </select>
                                                @error('administrative_division_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                            </div>
                                        </div>
                                        <p class="mt-4 mb-1.5 text-sm text-gray-500 dark:text-gray-400">Recherchez une adresse pour délimiter la zone sur la carte (facultatif — laissez tel quel pour garder les limites actuelles).</p>
                                        <div id="map-zone-edit-{{ $zone->id }}" class="rounded-lg" style="height: 60vh;"></div>
                                        <input type="hidden" id="zone-edit-{{ $zone->id }}-northeast_latitude" name="northeast_latitude" value="{{ $zone->northeast_latitude }}">
                                        <input type="hidden" id="zone-edit-{{ $zone->id }}-northeast_longitude" name="northeast_longitude" value="{{ $zone->northeast_longitude }}">
                                        <input type="hidden" id="zone-edit-{{ $zone->id }}-southwest_latitude" name="southwest_latitude" value="{{ $zone->southwest_latitude }}">
                                        <input type="hidden" id="zone-edit-{{ $zone->id }}-southwest_longitude" name="southwest_longitude" value="{{ $zone->southwest_longitude }}">
                                        <input type="hidden" id="zone-edit-{{ $zone->id }}-google_map_name" name="google_map_name" value="{{ $zone->google_map_name }}">
                                        @error('northeast_latitude') <p class="mt-1.5 text-sm text-red-500">Veuillez délimiter la zone sur la carte.</p> @enderror
                                        <div class="mt-6 flex items-center justify-end gap-3">
                                            <button @click="open = false" type="button"
                                                class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                                            <button type="submit"
                                                class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                                        </div>
                                    </form>
                                </div>
                            </x-ui.modal>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $zones->links() !!}
            </div>
        </div>
    </div>

    <!-- Modale de création d'une zone -->
    <x-ui.modal
        @open-create-zone-modal.window="open = true" :isOpen="old('_creating_zone') ? true : false" class="max-w-[1100px]">
        <div class="relative w-full max-w-[1100px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
            <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter une zone</h4>
            <form method="POST" action="{{ route('zones.store') }}">
                @csrf
                <input type="hidden" name="_creating_zone" value="1">
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div>
                        <label class="{{ $labelClass }}">Nom</label>
                        <input type="text" name="name" value="{{ old('_creating_zone') ? old('name') : '' }}" class="{{ $inputClass }}">
                        @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Couleur</label>
                        <input type="color" name="color" value="{{ old('_creating_zone') ? old('color') : '#3b82f6' }}" class="{{ $inputClass }} h-11">
                        @error('color') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Pays</label>
                        <select id="zone-create-country_id" class="{{ $inputClass }}" onchange="onZoneCountryChange('zone-create')">
                            <option value="">—</option>
                            @foreach ($countries as $country)
                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Subdivision administrative</label>
                        <select id="zone-create-administrative_division_id" name="administrative_division_id" class="{{ $inputClass }}" data-selected="{{ old('administrative_division_id') }}">
                        </select>
                        @error('administrative_division_id') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                </div>
                <p class="mt-4 mb-1.5 text-sm text-gray-500 dark:text-gray-400">Recherchez une adresse pour délimiter la zone sur la carte.</p>
                <div id="map-zone-create" class="rounded-lg" style="height: 60vh;"></div>
                <input type="hidden" id="zone-create-northeast_latitude" name="northeast_latitude">
                <input type="hidden" id="zone-create-northeast_longitude" name="northeast_longitude">
                <input type="hidden" id="zone-create-southwest_latitude" name="southwest_latitude">
                <input type="hidden" id="zone-create-southwest_longitude" name="southwest_longitude">
                
                <input type="hidden" id="zone-create-google_map_name" name="google_map_name">
                @error('northeast_latitude') <p class="mt-1.5 text-sm text-red-500">Veuillez délimiter la zone sur la carte.</p> @enderror
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
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
        <script>
            window.zoneMaps = window.zoneMaps || {};

            function initZoneMapOnce(mapId, prefix, initialBounds) {
                if (window.zoneMaps[mapId]) {
                    window.zoneMaps[mapId].invalidateSize();
                    return;
                }

                const mapEl = document.getElementById(mapId);
                if (!mapEl) return;

                let currentPolygon = null;
                const map = L.map(mapId, { scrollWheelZoom: true }).setView([6.1319, 1.2228], 12);

                L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                    maxZoom: 19,
                    attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
                }).addTo(map);

                if (initialBounds) {
                    currentPolygon = L.polygon([
                        [initialBounds.ne_lat, initialBounds.ne_lng],
                        [initialBounds.sw_lat, initialBounds.ne_lng],
                        [initialBounds.sw_lat, initialBounds.sw_lng],
                        [initialBounds.ne_lat, initialBounds.sw_lng]
                    ], { color: 'blue' }).addTo(map);
                    map.fitBounds(currentPolygon.getBounds());
                } else if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        map.setView([position.coords.latitude, position.coords.longitude], 12);
                    });
                }

                L.Control.geocoder({ defaultMarkGeocode: false })
                    .on('markgeocode', function(e) {
                        if (currentPolygon) map.removeLayer(currentPolygon);

                        const bbox = e.geocode.bbox;
                        currentPolygon = L.polygon([
                            bbox.getSouthEast(), bbox.getNorthEast(), bbox.getNorthWest(), bbox.getSouthWest()
                        ]).addTo(map);
                        map.fitBounds(currentPolygon.getBounds());

                        document.getElementById(prefix + '-northeast_latitude').value = bbox._northEast.lat;
                        document.getElementById(prefix + '-northeast_longitude').value = bbox._northEast.lng;
                        document.getElementById(prefix + '-southwest_latitude').value = bbox._southWest.lat;
                        document.getElementById(prefix + '-southwest_longitude').value = bbox._southWest.lng;
                        document.getElementById(prefix + '-google_map_name').value = e.geocode.name;
                    })
                    .addTo(map);

                window.zoneMaps[mapId] = map;
            }

            window.zoneDivisionsUrl = "{{ route('parametres.divisions.children') }}";

            function updateZoneDivisionSelect(prefix) {
                const countrySelect = document.getElementById(prefix + '-country_id');
                const divisionSelect = document.getElementById(prefix + '-administrative_division_id');
                if (!countrySelect || !divisionSelect) return;

                const countryId = countrySelect.value;
                const wanted = divisionSelect.dataset.selected || '';

                if (!countryId) {
                    divisionSelect.innerHTML = '';
                    return;
                }

                fetch(window.zoneDivisionsUrl + '?country_id=' + countryId)
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

            function onZoneCountryChange(prefix) {
                updateZoneDivisionSelect(prefix);
            }

            window.addEventListener('open-create-zone-modal', function() {
                setTimeout(function() {
                    initZoneMapOnce('map-zone-create', 'zone-create', null);
                    updateZoneDivisionSelect('zone-create');
                }, 50);
            });

            @foreach ($zones as $zone)
                window.addEventListener('open-edit-zone-modal', function(e) {
                    if (e.detail.id === {{ $zone->id }}) {
                        setTimeout(function() {
                            initZoneMapOnce('map-zone-edit-{{ $zone->id }}', 'zone-edit-{{ $zone->id }}', {
                                ne_lat: {{ $zone->northeast_latitude }},
                                ne_lng: {{ $zone->northeast_longitude }},
                                sw_lat: {{ $zone->southwest_latitude }},
                                sw_lng: {{ $zone->southwest_longitude }}
                            });
                            updateZoneDivisionSelect('zone-edit-{{ $zone->id }}');
                        }, 50);
                    }
                });
            @endforeach

            @if (old('_creating_zone'))
                window.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        initZoneMapOnce('map-zone-create', 'zone-create', null);
                        updateZoneDivisionSelect('zone-create');
                    }, 50);
                });
            @endif

            @if (old('_editing_zone'))
                window.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        initZoneMapOnce('map-zone-edit-{{ old('_editing_zone') }}', 'zone-edit-{{ old('_editing_zone') }}', null);
                        updateZoneDivisionSelect('zone-edit-{{ old('_editing_zone') }}');
                    }, 50);
                });
            @endif
        </script>
    @endpush
@endsection

@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Ramassages" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Ramassages</h3>
                @if (auth()->user()->hasRole('manager'))
                    <button type="button" @click="$dispatch('open-create-ramassage-modal')"
                        class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                        Ajouter un ramassage
                    </button>
                @endif
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Date</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Heure</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Agents</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ramassages as $ramassage)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $ramassage->name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $ramassage->date_de_ramassage }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $ramassage->heure_de_ramassage }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">
                                    @foreach ($ramassage->agents as $agent)
                                        <div>{{ $agent->full_name() }}</div>
                                    @endforeach
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('ramassages.show', $ramassage) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</a>
                                        @if (auth()->user()->hasRole('manager'))
                                            <button type="button" @click="$dispatch('open-edit-ramassage-modal', { id: {{ $ramassage->id }} })"
                                                class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</button>
                                            <form action="{{ route('ramassages.destroy', $ramassage) }}" method="POST"
                                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce ramassage ?');">
                                                @csrf
                                                @method('DELETE')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>

                            @if (auth()->user()->hasRole('manager'))
                                <!-- Modale de modification du ramassage #{{ $ramassage->id }} -->
                                <x-ui.modal
                                    @open-edit-ramassage-modal.window="if ($event.detail.id === {{ $ramassage->id }}) open = true" :isOpen="old('_editing_ramassage') == $ramassage->id" class="max-w-[700px]">
                                    <div class="relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                                        <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier le ramassage</h4>
                                        <form method="POST" action="{{ route('ramassages.update', $ramassage) }}">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="_editing_ramassage" value="{{ $ramassage->id }}">
                                            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                                                <div class="sm:col-span-2">
                                                    <label class="{{ $labelClass }}">Nom</label>
                                                    <input type="text" name="name" value="{{ old('_editing_ramassage') == $ramassage->id ? old('name') : $ramassage->name }}" class="{{ $inputClass }}">
                                                    @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Date</label>
                                                    <input type="date" name="date_de_ramassage" value="{{ old('_editing_ramassage') == $ramassage->id ? old('date_de_ramassage') : $ramassage->date_de_ramassage }}" class="{{ $inputClass }}">
                                                    @error('date_de_ramassage') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div>
                                                    <label class="{{ $labelClass }}">Heure</label>
                                                    <input type="time" name="heure_de_ramassage" value="{{ old('_editing_ramassage') == $ramassage->id ? old('heure_de_ramassage') : $ramassage->heure_de_ramassage }}" class="{{ $inputClass }}">
                                                    @error('heure_de_ramassage') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="{{ $labelClass }}">Description</label>
                                                    <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('_editing_ramassage') == $ramassage->id ? old('description') : $ramassage->description }}</textarea>
                                                    @error('description') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                                                </div>
                                                <div class="sm:col-span-2">
                                                    <label class="{{ $labelClass }}">Agents à affecter</label>
                                                    <div class="flex flex-wrap gap-4">
                                                        @foreach ($agents as $agent)
                                                            <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-400">
                                                                <input type="checkbox" name="agents[]" value="{{ $agent->id }}" {{ $ramassage->agents->contains($agent->id) ? 'checked' : '' }}>
                                                                {{ $agent->full_name() }}
                                                            </label>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="mt-4 mb-1.5 text-sm text-gray-500 dark:text-gray-400">Cliquez sur la carte pour repositionner le point de ramassage (facultatif).</p>
                                            <div id="map-ramassage-edit-{{ $ramassage->id }}" class="rounded-lg" style="height: 40vh;"></div>
                                            <input type="hidden" id="ramassage-edit-{{ $ramassage->id }}-latitude" name="latitude" value="{{ $ramassage->latitude }}">
                                            <input type="hidden" id="ramassage-edit-{{ $ramassage->id }}-longitude" name="longitude" value="{{ $ramassage->longitude }}">
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
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $ramassages->links() !!}
            </div>
        </div>
    </div>

    @if (auth()->user()->hasRole('manager'))
        <!-- Modale de création d'un ramassage -->
        <x-ui.modal
            @open-create-ramassage-modal.window="open = true" :isOpen="old('_creating_ramassage') ? true : false" class="max-w-[700px]">
            <div class="relative w-full max-w-[700px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
                <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Ajouter un ramassage</h4>
                <form method="POST" action="{{ route('ramassages.store') }}">
                    @csrf
                    <input type="hidden" name="_creating_ramassage" value="1">
                    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Nom</label>
                            <input type="text" name="name" value="{{ old('_creating_ramassage') ? old('name') : '' }}" class="{{ $inputClass }}">
                            @error('name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Date</label>
                            <input type="date" name="date_de_ramassage" value="{{ old('_creating_ramassage') ? old('date_de_ramassage') : '' }}" class="{{ $inputClass }}">
                            @error('date_de_ramassage') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="{{ $labelClass }}">Heure</label>
                            <input type="time" name="heure_de_ramassage" value="{{ old('_creating_ramassage') ? old('heure_de_ramassage') : '' }}" class="{{ $inputClass }}">
                            @error('heure_de_ramassage') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Description</label>
                            <textarea name="description" rows="3" class="{{ $inputClass }}">{{ old('_creating_ramassage') ? old('description') : '' }}</textarea>
                            @error('description') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="{{ $labelClass }}">Agents à affecter</label>
                            <div class="flex flex-wrap gap-4">
                                @foreach ($agents as $agent)
                                    <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-400">
                                        <input type="checkbox" name="agents[]" value="{{ $agent->id }}">
                                        {{ $agent->full_name() }}
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <p class="mt-4 mb-1.5 text-sm text-gray-500 dark:text-gray-400">Cliquez sur la carte pour placer le point de ramassage.</p>
                    <div id="map-ramassage-create" class="rounded-lg" style="height: 40vh;"></div>
                    <input type="hidden" id="ramassage-create-latitude" name="latitude">
                    <input type="hidden" id="ramassage-create-longitude" name="longitude">
                    @error('latitude') <p class="mt-1.5 text-sm text-red-500">Veuillez cliquer sur la carte pour placer le point.</p> @enderror
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

    @push('scripts')
        <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
        <script>
            window.ramassageMaps = window.ramassageMaps || {};

            function initRamassageMapOnce(mapId, prefix, initialLatLng, overlay) {
                if (window.ramassageMaps[mapId]) {
                    window.ramassageMaps[mapId].invalidateSize();
                    return;
                }

                const mapEl = document.getElementById(mapId);
                if (!mapEl) return;

                const map = L.map(mapId, { scrollWheelZoom: false }).setView(
                    initialLatLng ? [initialLatLng.lat, initialLatLng.lng] : [6.1319, 1.2228],
                    initialLatLng ? 15 : 12
                );

                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 19,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                let marker = null;
                if (initialLatLng) {
                    marker = L.marker([initialLatLng.lat, initialLatLng.lng]).addTo(map);
                } else if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        map.setView([position.coords.latitude, position.coords.longitude], 12);
                    });
                }

                if (overlay) {
                    const icon = L.divIcon({
                        className: 'custom-div-icon',
                        html: "<div style='background-color:#4838cc;width:14px;height:14px;border-radius:50%;'></div>",
                        iconSize: [14, 14]
                    });

                    overlay.zones.forEach(function(zone) {
                        L.polygon([
                            [zone.northeast_latitude, zone.northeast_longitude],
                            [zone.southwest_latitude, zone.northeast_longitude],
                            [zone.southwest_latitude, zone.southwest_longitude],
                            [zone.northeast_latitude, zone.southwest_longitude]
                        ], { color: zone.color || 'blue' }).addTo(map);
                    });

                    overlay.reports.forEach(function(report) {
                        L.marker([report.latitude, report.longitude], { icon: icon }).addTo(map);
                    });
                }

                map.on('click', function(e) {
                    if (marker) map.removeLayer(marker);
                    marker = L.marker(e.latlng).addTo(map);
                    document.getElementById(prefix + '-latitude').value = e.latlng.lat;
                    document.getElementById(prefix + '-longitude').value = e.latlng.lng;
                });

                window.ramassageMaps[mapId] = map;
            }

            const ramassageOverlay = {
                zones: @json($zones),
                reports: @json($reports)
            };

            window.addEventListener('open-create-ramassage-modal', function() {
                setTimeout(function() {
                    initRamassageMapOnce('map-ramassage-create', 'ramassage-create', null, ramassageOverlay);
                }, 50);
            });

            @foreach ($ramassages as $ramassage)
                window.addEventListener('open-edit-ramassage-modal', function(e) {
                    if (e.detail.id === {{ $ramassage->id }}) {
                        setTimeout(function() {
                            initRamassageMapOnce('map-ramassage-edit-{{ $ramassage->id }}', 'ramassage-edit-{{ $ramassage->id }}', {
                                lat: {{ $ramassage->latitude }},
                                lng: {{ $ramassage->longitude }}
                            }, null);
                        }, 50);
                    }
                });
            @endforeach

            @if (old('_creating_ramassage'))
                window.addEventListener('DOMContentLoaded', function() {
                    setTimeout(function() {
                        initRamassageMapOnce('map-ramassage-create', 'ramassage-create', null, ramassageOverlay);
                    }, 50);
                });
            @endif
        </script>
    @endpush
@endsection

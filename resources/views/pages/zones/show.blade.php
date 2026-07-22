@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #zone-map { height: 400px; }
    </style>
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Détail de la zone" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Délimitation géographique</h3>
                    <div id="zone-map" class="rounded-lg"></div>
                </div>
            </div>

            <div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="h-6 w-6 rounded-full border border-gray-200 dark:border-gray-700" style="background-color: {{ $zone->color }}"></span>
                        <h4 class="text-xl font-semibold text-gray-800 dark:text-white/90">{{ $zone->name }}</h4>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Lieu recherché</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $zone->google_map_name }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Entreprise</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $zone->company->name ?? '—' }}</p>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Nord-est</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $zone->northeast_latitude }}, {{ $zone->northeast_longitude }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Sud-ouest</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $zone->southwest_latitude }}, {{ $zone->southwest_longitude }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex flex-col gap-2">
                        <a href="{{ route('zones.index') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Retour à la liste</a>

                        @if (auth()->user()->hasRole('manager'))
                            <form action="{{ route('zones.destroy', $zone) }}" method="POST"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer cette zone ?');">
                                @csrf
                                @method('DELETE')
                                <button class="w-full rounded-lg bg-red-50 px-4 py-2.5 text-sm font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const bounds = {
                neLat: {{ $zone->northeast_latitude }},
                neLng: {{ $zone->northeast_longitude }},
                swLat: {{ $zone->southwest_latitude }},
                swLng: {{ $zone->southwest_longitude }}
            };

            const map = L.map('zone-map');

            L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            }).addTo(map);

            const polygon = L.polygon([
                [bounds.neLat, bounds.neLng],
                [bounds.swLat, bounds.neLng],
                [bounds.swLat, bounds.swLng],
                [bounds.neLat, bounds.swLng]
            ], { color: '{{ $zone->color }}' }).addTo(map);

            map.fitBounds(polygon.getBounds());
        });
    </script>
@endpush

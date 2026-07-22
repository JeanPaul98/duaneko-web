@extends('layouts.app')

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #ramassage-map { height: 400px; }
    </style>
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Détail du ramassage" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                    <h3 class="mb-4 text-lg font-semibold text-gray-800 dark:text-white/90">Localisation</h3>
                    <div id="ramassage-map" class="rounded-lg"></div>
                </div>
            </div>

            <div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                    <h4 class="mb-4 text-xl font-semibold text-gray-800 dark:text-white/90">{{ $ramassage->name }}</h4>

                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Date</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $ramassage->date_de_ramassage }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Heure</p>
                                <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $ramassage->heure_de_ramassage }}</p>
                            </div>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $ramassage->description }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Agent affecté</p>
                            @if ($ramassage->agent)
                                <x-ui.badge color="success">{{ $ramassage->agent->full_name() }}</x-ui.badge>
                            @else
                                <x-ui.badge color="light">Non assigné</x-ui.badge>
                            @endif
                        </div>
                        @if ($ramassage->report)
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Issu du signalement</p>
                                <a href="{{ route('reports.show', $ramassage->report) }}" class="text-sm font-medium text-brand-500 hover:underline">Voir le signalement d'origine</a>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Statut du signalement</p>
                                @if ($ramassage->report->status === 'done')
                                    <x-ui.badge color="success">Traité</x-ui.badge>
                                @elseif ($ramassage->report->status === 'in_progress')
                                    <x-ui.badge color="warning">En cours</x-ui.badge>
                                @else
                                    <x-ui.badge color="light">En attente</x-ui.badge>
                                @endif
                            </div>
                        @endif
                    </div>

                    @php
                        $canCompleteRamassage = $ramassage->report
                            && $ramassage->report->status !== 'done'
                            && (auth()->user()->hasRole(['admin', 'manager']) || $ramassage->agent_id === auth()->id());
                    @endphp

                    @if ($canCompleteRamassage)
                        <form action="{{ route('ramassages.complete', $ramassage) }}" method="POST" class="mt-6"
                            onsubmit="return confirm('Confirmer que le ramassage a été effectué ? Le signalement sera marqué comme traité.');">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-green-600 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-green-700">
                                Marquer le ramassage comme effectué
                            </button>
                        </form>
                    @endif

                    <div class="mt-6 flex flex-col gap-2">
                        <a href="https://www.google.com/maps/dir/?api=1&destination={{ $ramassage->latitude }},{{ $ramassage->longitude }}"
                            target="_blank" rel="noopener"
                            class="w-full rounded-lg bg-brand-500 px-4 py-2.5 text-center text-sm font-medium text-white hover:bg-brand-600">
                            Itinéraire vers le lieu de ramassage
                        </a>
                        <a href="{{ route('ramassages.index') }}"
                            class="w-full rounded-lg border border-gray-300 px-4 py-2.5 text-center text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Retour à la liste</a>

                        @if (auth()->user()->hasRole('manager'))
                            <form action="{{ route('ramassages.destroy', $ramassage) }}" method="POST"
                                onsubmit="return confirm('Voulez-vous vraiment supprimer ce ramassage ?');">
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
            const lat = {{ $ramassage->latitude }};
            const lng = {{ $ramassage->longitude }};
            const map = L.map('ramassage-map').setView([lat, lng], 17);

            L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
            }).addTo(map);

            L.marker([lat, lng]).addTo(map);
        });
    </script>
@endpush

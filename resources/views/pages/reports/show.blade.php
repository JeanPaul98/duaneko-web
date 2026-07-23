@extends('layouts.app')

@php
    $typeLabels = ['wild_dumps' => 'Dépôt sauvage'];
    $canModerate = auth()->user()->hasRole(['admin', 'manager']);
@endphp

@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <style>
        #report-map { height: 400px; }
    </style>
@endpush

@section('content')
    <x-common.page-breadcrumb pageTitle="Détail du signalement" />

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
                    <div id="report-map" class="rounded-lg"></div>
                </div>
            </div>

            <div>
                <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-white/[0.05] dark:bg-white/[0.03]">
                    <img src="{{ url('/api/image/' . $report->image) }}" alt="" class="mb-4 h-48 w-full rounded-lg object-cover">

                    <div class="space-y-4">
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Type</p>
                            <x-ui.badge color="primary">{{ $typeLabels[$report->type] ?? $report->type }}</x-ui.badge>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Description</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $report->description }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Signalé par</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $report->user?->full_name() ?? 'Anonyme' }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Date</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $report->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-xs text-gray-500 dark:text-gray-400">Statut actuel</p>
                            @if ($report->status === 'done')
                                <x-ui.badge color="success">Traité</x-ui.badge>
                            @elseif ($report->status === 'in_progress')
                                <x-ui.badge color="warning">En cours</x-ui.badge>
                            @else
                                <x-ui.badge color="light">En attente</x-ui.badge>
                            @endif
                        </div>

                    </div>

                    @if ($report->ramassage)
                        <div class="mt-6 border-t border-gray-100 pt-5 dark:border-white/[0.05]">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Agent assigné</p>
                            <p class="text-sm font-medium text-gray-800 dark:text-white/90">{{ $report->ramassage->agent?->full_name() ?? '—' }}</p>

                            <div class="mt-3 grid grid-cols-1 gap-2">
                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $report->latitude }},{{ $report->longitude }}"
                                    target="_blank" rel="noopener"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                                    Itinéraire vers le lieu
                                </a>
                                <a href="{{ route('ramassages.show', $report->ramassage) }}"
                                    class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">
                                    Voir le ramassage
                                </a>
                            </div>

                            @if ($canModerate && $agents->isNotEmpty())
                                <form method="POST" action="{{ route('reports.assign', $report) }}" class="mt-4">
                                    @csrf
                                    <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Réaffecter à un autre agent</label>
                                    <select name="agent_id" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" @selected($report->ramassage->agent_id == $agent->id)>{{ $agent->full_name() }} — {{ $agent->active_ramassages_count }} ramassage(s) en cours — {{ ucfirst($agent->status) }}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit" class="mt-3 w-full rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Réaffecter</button>
                                </form>
                            @endif
                        </div>
                    @elseif ($canModerate)
                        <div class="mt-6 border-t border-gray-100 pt-5 dark:border-white/[0.05]">
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Assigner un agent</label>

                            @if ($agents->isEmpty())
                                <p class="text-theme-xs text-gray-400 dark:text-gray-500">Aucun agent disponible pour ce signalement (zone non couverte ou aucun agent enregistré).</p>
                            @else
                                <form method="POST" action="{{ route('reports.assign', $report) }}">
                                    @csrf
                                    <select id="report-agent-select" name="agent_id" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                        <option value="">Choisir un agent…</option>
                                        @foreach ($agents as $agent)
                                            <option value="{{ $agent->id }}" data-whatsapp="{{ $agent->whatsapp_url }}" data-mailto="{{ $agent->mailto_url }}">{{ $agent->full_name() }}</option>
                                        @endforeach
                                    </select>

                                    <button type="submit" class="mt-3 w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">
                                        Assigner (crée le ramassage avec les coordonnées GPS du signalement)
                                    </button>
                                </form>

                                <p class="mt-3 mb-1.5 text-xs text-gray-500 dark:text-gray-400">Ou contacter directement l'agent avant assignation :</p>
                                <div class="grid grid-cols-2 gap-2">
                                    <button type="button" id="report-send-whatsapp" disabled
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-400 dark:border-gray-700">
                                        WhatsApp
                                    </button>
                                    <button type="button" id="report-send-email" disabled
                                        class="inline-flex items-center justify-center gap-2 rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-400 dark:border-gray-700">
                                        Email
                                    </button>
                                </div>
                            @endif
                        </div>
                    @endif

                    @if ($canModerate)
                        <form method="POST" action="{{ route('reports.update', $report) }}" class="mt-6 border-t border-gray-100 pt-5 dark:border-white/[0.05]">
                            @csrf
                            @method('PUT')
                            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Changer le statut</label>
                            <select name="status" class="dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs dark:border-gray-700 dark:bg-gray-900 dark:text-white/90">
                                <option value="pending" @selected($report->status === 'pending')>En attente</option>
                                <option value="in_progress" @selected($report->status === 'in_progress')>En cours</option>
                                <option value="done" @selected($report->status === 'done')>Traité</option>
                            </select>
                            @error('status') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                            <button type="submit" class="mt-4 w-full rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const lat = {{ $report->latitude }};
            const lng = {{ $report->longitude }};
            const map = L.map('report-map').setView([lat, lng], 15);

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                maxZoom: 19,
                attribution: 'Tiles &copy; Esri &mdash; Source: Esri, Maxar, Earthstar Geographics, and the GIS User Community'
            }).addTo(map);

            const standardLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                maxZoom: 19,
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
            });

            L.control.layers({ 'Satellite': satelliteLayer, 'Standard': standardLayer }).addTo(map);

            L.marker([lat, lng]).addTo(map);

            const agentSelect = document.getElementById('report-agent-select');
            const whatsappBtn = document.getElementById('report-send-whatsapp');
            const emailBtn = document.getElementById('report-send-email');

            if (agentSelect) {
                const activeClasses = ['text-brand-500', 'border-brand-300', 'hover:bg-brand-50'];
                const inactiveClasses = ['text-gray-400'];

                agentSelect.addEventListener('change', function () {
                    const option = agentSelect.selectedOptions[0];
                    const hasAgent = option && option.value;

                    [whatsappBtn, emailBtn].forEach(function (btn) {
                        btn.disabled = !hasAgent;
                        btn.classList.toggle('cursor-not-allowed', !hasAgent);
                        activeClasses.forEach(function (c) { btn.classList.toggle(c, hasAgent); });
                        inactiveClasses.forEach(function (c) { btn.classList.toggle(c, !hasAgent); });
                    });
                });

                whatsappBtn.addEventListener('click', function () {
                    const option = agentSelect.selectedOptions[0];
                    if (option && option.dataset.whatsapp) window.open(option.dataset.whatsapp, '_blank');
                });

                emailBtn.addEventListener('click', function () {
                    const option = agentSelect.selectedOptions[0];
                    if (option && option.dataset.mailto) window.location.href = option.dataset.mailto;
                });
            }
        });
    </script>
@endpush

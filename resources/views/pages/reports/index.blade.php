@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $typeLabels = ['wild_dumps' => 'Dépôt sauvage'];
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Signalements" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Signalements</h3>

                <form method="GET" action="{{ route('reports.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
                    <select name="status" class="{{ $inputClass }}">
                        <option value="">Tous les statuts</option>
                        <option value="pending" @selected($statusFilter === 'pending')>En attente</option>
                        <option value="in_progress" @selected($statusFilter === 'in_progress')>En cours</option>
                        <option value="done" @selected($statusFilter === 'done')>Traité</option>
                    </select>

                    <select name="zone" class="{{ $inputClass }}">
                        <option value="">Toutes les zones</option>
                        @foreach ($zones as $zone)
                            <option value="{{ $zone->id }}" @selected((string) $zoneFilter === (string) $zone->id)>{{ $zone->name }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2 sm:col-span-2">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">Filtrer</button>
                        @if ($statusFilter || $zoneFilter)
                            <a href="{{ route('reports.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Réinitialiser</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Photo</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Type</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Zone</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Signalé par</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Date</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reports as $report)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5">
                                    <img src="{{ url('/api/image/' . $report->image) }}" alt="" class="h-10 w-10 rounded-lg object-cover">
                                </td>
                                <td class="px-6 py-3.5">
                                    <x-ui.badge color="primary">{{ $typeLabels[$report->type] ?? $report->type }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $report->zone_name ?? 'Hors zone' }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $report->user?->full_name() ?? 'Anonyme' }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($report->status === 'done')
                                        <x-ui.badge color="success">Traité</x-ui.badge>
                                    @elseif ($report->status === 'in_progress')
                                        <x-ui.badge color="warning">En cours</x-ui.badge>
                                    @else
                                        <x-ui.badge color="light">En attente</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $report->created_at->format('d/m/Y') }}</td>
                                <td class="px-6 py-3.5">
                                    <a href="{{ route('reports.show', $report) }}"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Aucun signalement trouvé.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $reports->links() !!}
            </div>
        </div>
    </div>
@endsection

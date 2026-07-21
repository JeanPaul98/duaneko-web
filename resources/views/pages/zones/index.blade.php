@extends('layouts.app')

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
                <a href="{{ route('zones.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter une zone
                </a>
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
                                        <a href="{{ route('zones.edit', $zone) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</a>
                                        <form action="{{ route('zones.destroy', $zone) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette zone ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Supprimer</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $zones->links() !!}
            </div>
        </div>
    </div>
@endsection

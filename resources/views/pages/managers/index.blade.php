@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Manageurs" />

    <div class="space-y-6">
        @if ($message = Session::get('success'))
            <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ $message }}
            </div>
        @endif

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Manageurs</h3>
                <a href="{{ route('managers.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter un manager
                </a>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Prénom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Téléphone</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($managers as $manager)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->first_name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->last_name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->phone_number }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $manager->email }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($manager->status === 'validated')
                                        <x-ui.badge color="success">Validé</x-ui.badge>
                                    @elseif ($manager->status === 'rejected')
                                        <x-ui.badge color="error">Rejeté</x-ui.badge>
                                    @else
                                        <x-ui.badge color="warning">En attente</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('managers.show', $manager) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</a>
                                        <a href="{{ route('managers.edit', $manager) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</a>

                                        @if ($manager->status === 'pending' && auth()->user()->hasRole('admin'))
                                            <form action="{{ route('managers.validate', $manager) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-green-50 px-3 py-1.5 text-theme-xs font-medium text-green-700 hover:bg-green-100 dark:bg-green-500/15 dark:text-green-500">Valider</button>
                                            </form>
                                            <form action="{{ route('managers.reject', $manager) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <button class="rounded-lg bg-red-50 px-3 py-1.5 text-theme-xs font-medium text-red-700 hover:bg-red-100 dark:bg-red-500/15 dark:text-red-500">Rejeter</button>
                                            </form>
                                        @endif

                                        <form action="{{ route('managers.destroy', $manager) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer ce manager ?');">
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
                {!! $managers->links() !!}
            </div>
        </div>
    </div>
@endsection

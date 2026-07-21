@extends('layouts.app')

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
                <a href="{{ route('companies.create') }}"
                    class="inline-flex items-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">
                    Ajouter une entreprise
                </a>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Slug</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $company->name }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $company->slug }}</td>
                                <td class="px-6 py-3.5">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <a href="{{ route('companies.show', $company) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</a>
                                        <a href="{{ route('companies.edit', $company) }}"
                                            class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Modifier</a>
                                        <form action="{{ route('companies.destroy', $company) }}" method="POST"
                                            onsubmit="return confirm('Voulez-vous vraiment supprimer cette entreprise ?');">
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
                {!! $companies->links() !!}
            </div>
        </div>
    </div>
@endsection

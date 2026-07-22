@props(['users', 'roleFilter' => null, 'statusFilter' => null, 'search' => null])

@php
    $roleLabels = ['admin' => 'Administrateur', 'manager' => 'Manager (Mairie)', 'agent' => 'Collecteur', 'citizen' => 'Citoyen'];
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
@endphp

<div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
    <div class="flex flex-col gap-4 px-6 mb-4">
        <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Tous les utilisateurs</h3>

        <form method="GET" action="{{ route('home') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-4">
            <input type="text" name="search" value="{{ $search }}" placeholder="Nom, prénom ou email..." class="{{ $inputClass }}">

            <select name="role" class="{{ $inputClass }}">
                <option value="">Tous les rôles</option>
                @foreach ($roleLabels as $value => $label)
                    <option value="{{ $value }}" @selected($roleFilter === $value)>{{ $label }}</option>
                @endforeach
            </select>

            <select name="status" class="{{ $inputClass }}">
                <option value="">Tous les statuts</option>
                <option value="validated" @selected($statusFilter === 'validated')>Validé</option>
                <option value="pending" @selected($statusFilter === 'pending')>En attente</option>
                <option value="rejected" @selected($statusFilter === 'rejected')>Rejeté</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">Filtrer</button>
                @if ($roleFilter || $statusFilter || $search)
                    <a href="{{ route('home') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Réinitialiser</a>
                @endif
            </div>
        </form>
    </div>

    <div class="max-w-full overflow-x-auto">
        <table class="w-full">
            <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                <tr>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Email</th>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Rôle</th>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Entreprise</th>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                    <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Inscrit le</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                        <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">
                            <div class="flex items-center gap-3">
                                @if ($user->photo)
                                    <img src="{{ route('users.photo', $user->photo) }}" alt="" class="h-8 w-8 rounded-full object-cover">
                                @endif
                                {{ $user->first_name }} {{ $user->last_name }}
                            </div>
                        </td>
                        <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $user->email }}</td>
                        <td class="px-6 py-3.5">
                            <x-ui.badge color="primary">{{ $roleLabels[$user->getRoleNames()->first()] ?? $user->getRoleNames()->first() ?? '—' }}</x-ui.badge>
                        </td>
                        <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $user->company->name ?? '—' }}</td>
                        <td class="px-6 py-3.5">
                            @if ($user->status === 'validated')
                                <x-ui.badge color="success">Validé</x-ui.badge>
                            @elseif ($user->status === 'rejected')
                                <x-ui.badge color="error">Rejeté</x-ui.badge>
                            @elseif ($user->status === 'pending')
                                <x-ui.badge color="warning">En attente</x-ui.badge>
                            @else
                                <x-ui.badge color="light">—</x-ui.badge>
                            @endif
                        </td>
                        <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ optional($user->created_at)->format('d/m/Y') ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Aucun utilisateur trouvé.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="px-6 py-4">
        {!! $users->links() !!}
    </div>
</div>

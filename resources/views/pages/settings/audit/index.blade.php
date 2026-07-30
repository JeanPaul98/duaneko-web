@extends('layouts.app')

@php
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $actionColors = [
        'Connexion' => 'success',
        'Déconnexion' => 'light',
        'Manager créé' => 'primary',
        'Agent créé' => 'primary',
        'Manager validé' => 'success',
        'Agent validé' => 'success',
        'Manager rejeté' => 'error',
        'Agent rejeté' => 'error',
        'Manager supprimé' => 'error',
        'Agent supprimé' => 'error',
    ];
    $roleLabels = ['admin' => 'Administrateur', 'manager' => 'Manager', 'agent' => 'Agent'];
@endphp

@section('content')
    <x-common.page-breadcrumb pageTitle="Journal d'activité" />

    <div class="space-y-6">
        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Utilisateurs suivis</h3>
                <p class="text-theme-xs text-gray-500 dark:text-gray-400">Managers, agents et administrateurs — les citoyens ne sont pas suivis ici.</p>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Nom</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Rôle</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Téléphone</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Email</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Statut</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($causers as $causer)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $causer->full_name() }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $roleLabels[$causer->getRoleNames()->first()] ?? $causer->getRoleNames()->first() }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $causer->phone_number ?? '—' }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $causer->email ?? '—' }}</td>
                                <td class="px-6 py-3.5">
                                    @if ($causer->status === 'validated')
                                        <x-ui.badge color="success">Actif</x-ui.badge>
                                    @elseif ($causer->status === 'rejected')
                                        <x-ui.badge color="error">Rejeté</x-ui.badge>
                                    @else
                                        <x-ui.badge color="warning">En attente</x-ui.badge>
                                    @endif
                                </td>
                                <td class="px-6 py-3.5">
                                    <button type="button" onclick="openUserDetailModal({{ $causer->id }})"
                                        class="rounded-lg border border-gray-300 px-3 py-1.5 text-theme-xs font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Détail</button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Aucun utilisateur.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="overflow-hidden rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex flex-col gap-4 px-6 mb-4">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Historique des actions</h3>

                <form method="GET" action="{{ route('parametres.audit.index') }}" class="grid grid-cols-1 gap-3 sm:grid-cols-3">
                    <select name="causer" class="{{ $inputClass }}">
                        <option value="">Tous les utilisateurs</option>
                        @foreach ($causers as $causer)
                            <option value="{{ $causer->id }}" @selected((string) $causerFilter === (string) $causer->id)>{{ $causer->full_name() }}</option>
                        @endforeach
                    </select>

                    <select name="action" class="{{ $inputClass }}">
                        <option value="">Toutes les actions</option>
                        @foreach ($actions as $action)
                            <option value="{{ $action }}" @selected($actionFilter === $action)>{{ $action }}</option>
                        @endforeach
                    </select>

                    <div class="flex gap-2">
                        <button type="submit" class="inline-flex flex-1 items-center justify-center gap-2 rounded-lg bg-brand-500 px-4 py-2.5 text-theme-sm font-medium text-white hover:bg-brand-600">Filtrer</button>
                        @if ($causerFilter || $actionFilter)
                            <a href="{{ route('parametres.audit.index') }}" class="inline-flex items-center justify-center rounded-lg border border-gray-300 px-4 py-2.5 text-theme-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Réinitialiser</a>
                        @endif
                    </div>
                </form>
            </div>

            <div class="max-w-full overflow-x-auto">
                <table class="w-full">
                    <thead class="border-t border-y border-gray-100 bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Date</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Utilisateur</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                            <th class="px-6 py-3 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Concerne</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $log->created_at->format('d/m/Y H:i') }}</td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $log->causer?->full_name() ?? '—' }}</td>
                                <td class="px-6 py-3.5">
                                    <x-ui.badge color="{{ $actionColors[$log->description] ?? 'light' }}">{{ $log->description }}</x-ui.badge>
                                </td>
                                <td class="px-6 py-3.5 text-theme-sm text-gray-700 dark:text-gray-400">{{ $log->getExtraProperty('subject_name') ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-6 text-center text-theme-sm text-gray-500 dark:text-gray-400">Aucune activité enregistrée.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4">
                {!! $logs->links() !!}
            </div>
        </div>
    </div>

    <!-- Modale : détail d'un utilisateur -->
    <x-ui.modal @open-user-detail-modal.window="open = true" :isOpen="false" class="max-w-[600px]">
        <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8" style="max-height: 85vh;">
            <div class="mb-6 flex items-center justify-between">
                <div>
                    <h4 id="user-detail-name" class="text-xl font-semibold text-gray-800 dark:text-white/90"></h4>
                    <span id="user-detail-status" class="mt-1 inline-block"></span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Téléphone</p>
                    <p id="user-detail-phone" class="text-sm font-medium text-gray-800 dark:text-white/90"></p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 dark:text-gray-400">Email</p>
                    <p id="user-detail-email" class="text-sm font-medium text-gray-800 dark:text-white/90"></p>
                </div>
            </div>

            <a id="user-detail-whatsapp" href="#" target="_blank"
                class="mt-4 inline-flex w-full items-center justify-center gap-2 rounded-lg bg-green-50 px-4 py-2.5 text-sm font-medium text-green-700 hover:bg-green-100 dark:bg-green-500/15 dark:text-green-500">
                Écrire sur WhatsApp
            </a>

            <h5 class="mb-3 mt-6 text-sm font-semibold text-gray-700 dark:text-gray-300">Activité</h5>
            <div class="max-h-72 overflow-y-auto rounded-lg border border-gray-100 dark:border-white/[0.05]">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-900">
                        <tr>
                            <th class="px-4 py-2 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Date</th>
                            <th class="px-4 py-2 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Action</th>
                            <th class="px-4 py-2 text-start text-theme-xs font-medium text-gray-500 dark:text-gray-400">Concerne</th>
                        </tr>
                    </thead>
                    <tbody id="user-detail-activities"></tbody>
                </table>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <button @click="open = false" type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Fermer</button>
            </div>
        </div>
    </x-ui.modal>

    @push('scripts')
        <script>
            window.auditUserUrlTemplate = "{{ route('parametres.audit.user', ['user' => '__ID__']) }}";

            function openUserDetailModal(userId) {
                fetch(window.auditUserUrlTemplate.replace('__ID__', userId))
                    .then(function (r) { return r.json(); })
                    .then(function (data) {
                        document.getElementById('user-detail-name').textContent = data.user.name;

                        const statusEl = document.getElementById('user-detail-status');
                        if (data.user.status === 'validated') {
                            statusEl.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-500">Actif</span>';
                        } else if (data.user.status === 'rejected') {
                            statusEl.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500">Rejeté</span>';
                        } else {
                            statusEl.innerHTML = '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm font-medium bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-orange-400">En attente</span>';
                        }

                        document.getElementById('user-detail-phone').textContent = data.user.phone_number || '—';
                        document.getElementById('user-detail-email').textContent = data.user.email || '—';

                        const whatsappBtn = document.getElementById('user-detail-whatsapp');
                        if (data.user.whatsapp_url) {
                            whatsappBtn.href = data.user.whatsapp_url;
                            whatsappBtn.classList.remove('hidden');
                        } else {
                            whatsappBtn.classList.add('hidden');
                        }

                        const tbody = document.getElementById('user-detail-activities');
                        tbody.innerHTML = '';
                        if (data.activities.length === 0) {
                            const emptyRow = document.createElement('tr');
                            const emptyCell = document.createElement('td');
                            emptyCell.colSpan = 3;
                            emptyCell.className = 'px-4 py-4 text-center text-theme-xs text-gray-400';
                            emptyCell.textContent = 'Aucune activité.';
                            emptyRow.appendChild(emptyCell);
                            tbody.appendChild(emptyRow);
                        } else {
                            data.activities.forEach(function (activity) {
                                const tr = document.createElement('tr');
                                tr.className = 'border-t border-gray-100 dark:border-white/[0.05]';
                                [activity.date, activity.description, activity.subject_name || '—'].forEach(function (text) {
                                    const td = document.createElement('td');
                                    td.className = 'px-4 py-2 text-theme-xs text-gray-700 dark:text-gray-400';
                                    td.textContent = text;
                                    tr.appendChild(td);
                                });
                                tbody.appendChild(tr);
                            });
                        }

                        window.dispatchEvent(new Event('open-user-detail-modal'));
                    });
            }
        </script>
    @endpush
@endsection

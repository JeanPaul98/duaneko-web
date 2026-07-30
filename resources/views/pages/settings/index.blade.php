@extends('layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Paramètres" />

    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 md:gap-6">
        <a href="{{ route('parametres.pays.index') }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Pays</h3>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Pays supportés par la plateforme (nom, code ISO, devise).</p>
            <p class="mt-4 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $countriesCount }}</p>
        </a>

        <a href="{{ route('parametres.hierarchie.index') }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Hiérarchie administrative</h3>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Explorez et construisez l'arbre réel des lieux par pays (région, préfecture, commune...).</p>
            <p class="mt-4 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $administrativeDivisionsCount }}</p>
        </a>

        <a href="{{ route('parametres.audit.index') }}"
            class="rounded-2xl border border-gray-200 bg-white p-5 hover:border-brand-300 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Journal d'activité</h3>
            <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">Connexions, déconnexions, créations/suppressions/validations des managers et agents.</p>
            <p class="mt-4 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $activityCount }}</p>
        </a>
    </div>
@endsection

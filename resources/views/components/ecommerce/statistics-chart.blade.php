@props(['ramassages' => [], 'users' => []])

<div
    class="rounded-2xl border border-gray-200 bg-white px-5 pb-5 pt-5 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 sm:pt-6">
    <div class="flex flex-col gap-5 mb-6 sm:flex-row sm:justify-between">
        <div class="w-full">
            <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                Statistiques annuelles
            </h3>
            <p class="mt-1 text-gray-500 text-theme-sm dark:text-gray-400">
                Ramassages et nouveaux utilisateurs par mois ({{ now()->year }})
            </p>
        </div>
    </div>
    <div class="max-w-full overflow-x-auto custom-scrollbar">
        <div id="chartThree" data-ramassages="{{ json_encode($ramassages) }}" data-users="{{ json_encode($users) }}" class="-ml-4 min-w-[700px] pl-2 xl:min-w-full"></div>
    </div>
</div>


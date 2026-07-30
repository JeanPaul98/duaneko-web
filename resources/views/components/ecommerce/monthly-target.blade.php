@props(['rate' => 0, 'total' => 0, 'completed' => 0])

@php
    $remaining = max($total - $completed, 0);
@endphp

<div class="rounded-2xl border border-gray-200 bg-gray-100 dark:border-gray-800 dark:bg-white/[0.03]">
    <div class="shadow-default rounded-2xl bg-white px-5 pb-11 pt-5 dark:bg-gray-900 sm:px-6 sm:pt-6">
        <div class="flex justify-between">
            <div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">
                    Taux de ramassages effectués
                </h3>
                <p class="mt-1 text-theme-sm text-gray-500 dark:text-gray-400">
                    Ramassages du mois déjà passés / programmés ce mois
                </p>
            </div>
        </div>
        <div class="relative max-h-[195px]">
            {{-- Chart --}}
            <div id="chartTwo" data-rate="{{ $rate }}" class="h-full"></div>
        </div>
        <p class="mx-auto mt-1.5 w-full max-w-[380px] text-center text-sm text-gray-500 sm:text-base">
            @if ($total > 0)
                {{ $completed }} ramassage(s) effectué(s) sur {{ $total }} programmé(s) ce mois-ci.
            @else
                Aucun ramassage programmé ce mois-ci.
            @endif
        </p>
    </div>

    <div class="flex items-center justify-center gap-5 px-6 py-3.5 sm:gap-8 sm:py-5">
        <div>
            <p class="mb-1 text-center text-theme-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                Programmés
            </p>
            <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                {{ $total }}
            </p>
        </div>

        <div class="h-7 w-px bg-gray-200 dark:bg-gray-800"></div>

        <div>
            <p class="mb-1 text-center text-theme-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                Effectués
            </p>
            <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                {{ $completed }}
            </p>
        </div>

        <div class="h-7 w-px bg-gray-200 dark:bg-gray-800"></div>

        <div>
            <p class="mb-1 text-center text-theme-xs text-gray-500 dark:text-gray-400 sm:text-sm">
                Restants
            </p>
            <p class="flex items-center justify-center gap-1 text-base font-semibold text-gray-800 dark:text-white/90 sm:text-lg">
                {{ $remaining }}
            </p>
        </div>
    </div>
</div>


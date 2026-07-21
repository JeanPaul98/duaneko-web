@php
    $roleLabels = [
        'admin' => 'Administrateur',
        'manager' => 'Manager (Mairie)',
        'agent' => 'Collecteur',
        'citizen' => 'Citoyen',
    ];
    $currentRole = auth()->user()->getRoleNames()->first();
    $inputClass = 'dark:bg-dark-900 h-11 w-full rounded-lg border border-gray-300 bg-transparent px-4 py-2.5 text-sm text-gray-800 shadow-theme-xs placeholder:text-gray-400 focus:border-brand-300 focus:outline-hidden focus:ring-3 focus:ring-brand-500/10 dark:border-gray-700 dark:bg-gray-900 dark:text-white/90 dark:placeholder:text-white/30 dark:focus:border-brand-800';
    $labelClass = 'mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400';
@endphp

<div class="relative" x-data="{
    dropdownOpen: false,
    toggleDropdown() {
        this.dropdownOpen = !this.dropdownOpen;
    },
    closeDropdown() {
        this.dropdownOpen = false;
    }
}" @click.away="closeDropdown()">
    <!-- User Button -->
    <button
        class="flex items-center text-gray-700 dark:text-gray-400"
        @click.prevent="toggleDropdown()"
        type="button"
    >
        <span class="mr-3 overflow-hidden rounded-full h-11 w-11">
            <img src="/images/user/owner.png" alt="User" />
        </span>

       <span class="block mr-1 font-medium text-theme-sm">{{ auth()->user()->first_name }}</span>

        <!-- Chevron Icon -->
        <svg
            class="w-5 h-5 transition-transform duration-200"
            :class="{ 'rotate-180': dropdownOpen }"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Start -->
    <div
        x-show="dropdownOpen"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
        class="absolute right-0 mt-[17px] flex w-[260px] flex-col rounded-2xl border border-gray-200 bg-white p-3 shadow-theme-lg dark:border-gray-800 dark:bg-gray-dark z-50"
        style="display: none;"
    >
        <!-- User Info -->
        <div>
            <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
            <span class="mt-0.5 block text-theme-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</span>
            <span class="mt-2 inline-block">
                <x-ui.badge color="primary" size="sm">{{ $roleLabels[$currentRole] ?? $currentRole }}</x-ui.badge>
            </span>
        </div>

        <!-- Menu Items -->
        <ul class="flex flex-col gap-1 pt-4 pb-3 border-b border-gray-200 dark:border-gray-800">
            <li>
                <button
                    type="button"
                    @click="closeDropdown(); $dispatch('open-edit-profile-modal')"
                    class="flex w-full items-center gap-3 px-3 py-2 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
                >
                    <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                fill-rule="evenodd"
                                clip-rule="evenodd"
                                d="M12 3.5C7.30558 3.5 3.5 7.30558 3.5 12C3.5 14.1526 4.3002 16.1184 5.61936 17.616C6.17279 15.3096 8.24852 13.5955 10.7246 13.5955H13.2746C15.7509 13.5955 17.8268 15.31 18.38 17.6167C19.6996 16.119 20.5 14.153 20.5 12C20.5 7.30558 16.6944 3.5 12 3.5ZM17.0246 18.8566V18.8455C17.0246 16.7744 15.3457 15.0955 13.2746 15.0955H10.7246C8.65354 15.0955 6.97461 16.7744 6.97461 18.8455V18.856C8.38223 19.8895 10.1198 20.5 12 20.5C13.8798 20.5 15.6171 19.8898 17.0246 18.8566ZM2 12C2 6.47715 6.47715 2 12 2C17.5228 2 22 6.47715 22 12C22 17.5228 17.5228 22 12 22C6.47715 22 2 17.5228 2 12ZM11.9991 7.25C10.8847 7.25 9.98126 8.15342 9.98126 9.26784C9.98126 10.3823 10.8847 11.2857 11.9991 11.2857C13.1135 11.2857 14.0169 10.3823 14.0169 9.26784C14.0169 8.15342 13.1135 7.25 11.9991 7.25ZM8.48126 9.26784C8.48126 7.32499 10.0563 5.75 11.9991 5.75C13.9419 5.75 15.5169 7.32499 15.5169 9.26784C15.5169 11.2107 13.9419 12.7857 11.9991 12.7857C10.0563 12.7857 8.48126 11.2107 8.48126 9.26784Z"
                                fill="currentColor"
                            />
                        </svg>
                    </span>
                    Modifier mon profil
                </button>
            </li>
        </ul>

        <!-- Sign Out -->
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button
                type="submit"
                class="flex items-center w-full gap-3 px-3 py-2 mt-3 font-medium text-gray-700 rounded-lg group text-theme-sm hover:bg-gray-100 hover:text-gray-700 dark:text-gray-400 dark:hover:bg-white/5 dark:hover:text-gray-300"
            >
                <span class="text-gray-500 group-hover:text-gray-700 dark:group-hover:text-gray-300">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                    </svg>
                </span>
                Déconnexion
            </button>
        </form>
    </div>
    <!-- Dropdown End -->
</div>

<!-- Modale Modifier mon profil -->
<x-ui.modal x-data="{ open: {{ old('_editing_profile') ? 'true' : 'false' }} }"
    @open-edit-profile-modal.window="open = true" :isOpen="false" class="max-w-[600px]">
    <div class="relative w-full max-w-[600px] overflow-y-auto rounded-3xl bg-white p-6 dark:bg-gray-900 lg:p-8">
        <h4 class="mb-6 text-xl font-semibold text-gray-800 dark:text-white/90">Modifier mon profil</h4>

        @if (session('success'))
            <div class="mb-5 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700 dark:border-green-800 dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('profile.update') }}">
            @csrf
            @method('PUT')
            <input type="hidden" name="_editing_profile" value="1">

            <div class="mb-5">
                <span class="{{ $labelClass }}">Rôle</span>
                <x-ui.badge color="primary">{{ $roleLabels[$currentRole] ?? $currentRole }}</x-ui.badge>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                <div>
                    <label class="{{ $labelClass }}">Prénom</label>
                    <input type="text" name="first_name" value="{{ old('_editing_profile') ? old('first_name') : auth()->user()->first_name }}" class="{{ $inputClass }}">
                    @error('first_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Nom</label>
                    <input type="text" name="last_name" value="{{ old('_editing_profile') ? old('last_name') : auth()->user()->last_name }}" class="{{ $inputClass }}">
                    @error('last_name') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Email</label>
                    <input type="email" name="email" value="{{ old('_editing_profile') ? old('email') : auth()->user()->email }}" class="{{ $inputClass }}">
                    @error('email') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}">Téléphone</label>
                    <input type="tel" name="phone_number" value="{{ old('_editing_profile') ? old('phone_number') : auth()->user()->phone_number }}" class="{{ $inputClass }}">
                    @error('phone_number') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="mt-6 border-t border-gray-200 pt-5 dark:border-gray-800">
                <p class="mb-4 text-sm font-medium text-gray-700 dark:text-gray-400">Changer de mot de passe (facultatif)</p>
                <div class="grid grid-cols-1 gap-5 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="{{ $labelClass }}">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="{{ $inputClass }}">
                        @error('current_password') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Nouveau mot de passe</label>
                        <input type="password" name="new_password" class="{{ $inputClass }}">
                        @error('new_password') <p class="mt-1.5 text-sm text-red-500">{{ $message }}</p> @enderror
                    </div>
                    <div>
                        <label class="{{ $labelClass }}">Confirmation</label>
                        <input type="password" name="new_password_confirmation" class="{{ $inputClass }}">
                    </div>
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <button @click="open = false" type="button"
                    class="rounded-lg border border-gray-300 px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 dark:border-gray-700 dark:text-gray-400 dark:hover:bg-white/[0.03]">Annuler</button>
                <button type="submit"
                    class="rounded-lg bg-brand-500 px-4 py-2.5 text-sm font-medium text-white hover:bg-brand-600">Enregistrer</button>
            </div>
        </form>
    </div>
</x-ui.modal>

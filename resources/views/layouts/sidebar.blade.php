<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="icon" href="{{ asset('admin-template/assets/images/favicon.svg') }}" type="image/x-icon">

    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])
</head>

<body class="bg-gray-50 dark:bg-gray-900">

<div id="app" x-data>
    
    {{-- Sidebar du nouveau gabarit --}}
    @include('layouts.sidebar')

    {{-- Contenu principal --}}
    <div 
        class="min-h-screen transition-all duration-300"
        :class="$store.sidebar.isExpanded ? 'xl:ml-[290px]' : 'xl:ml-[90px]'"
    >

        {{-- Barre de navigation --}}
        <header class="sticky top-0 z-40 bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-800">
            <div class="flex items-center justify-between px-4 py-4">

                {{-- Bouton menu mobile --}}
                <button 
                    type="button"
                    class="text-gray-600 dark:text-gray-300"
                    @click="$store.sidebar.toggleMobileOpen()"
                >
                    ☰
                </button>

                {{-- Nom application --}}
                <a href="{{ url('/') }}" class="font-semibold text-gray-800 dark:text-white">
                    {{ config('app.name', 'Laravel') }}
                </a>

                {{-- Authentification --}}
                <div>
                    @guest
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}" class="text-sm text-gray-700 dark:text-gray-300">
                                Login
                            </a>
                        @endif
                    @else
                        <div class="relative" x-data="{ open: false }">
                            <button 
                                type="button"
                                @click="open = !open"
                                class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300"
                            >
                                {{ Auth::user()->name ?? Auth::user()->first_name }}
                                <span>▼</span>
                            </button>

                            <div 
                                x-show="open"
                                @click.outside="open = false"
                                class="absolute right-0 mt-2 w-40 bg-white border rounded shadow-lg dark:bg-gray-800 dark:border-gray-700"
                            >
                                <a 
                                    href="{{ route('logout') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    Logout
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </div>
                        </div>
                    @endguest
                </div>

            </div>
        </header>

        {{-- Zone dynamique des pages --}}
        <main class="p-4">
            @yield('content')
        </main>

    </div>
</div>

</body>
</html>
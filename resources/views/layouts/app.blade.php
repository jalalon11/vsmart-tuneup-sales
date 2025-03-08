<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @stack('styles')
    </head>
    <body class="font-sans antialiased h-full bg-gray-100 dark:bg-gray-900">
        <div class="min-h-screen">
            <!-- Top Navigation Bar -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <!-- Left side -->
                        <div class="flex">
                            <!-- Logo -->
                            <div class="flex-shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-blue-600 dark:text-blue-400">
                                    VS
                                </a>
                            </div>

                            <!-- Main Navigation -->
                            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                                <a href="{{ route('dashboard') }}"
                                    class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('services.index') }}"
                                    class="{{ request()->routeIs('services.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Services
                                </a>

                                <a href="{{ route('customers.index') }}"
                                    class="{{ request()->routeIs('customers.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Customers
                                </a>

                                <a href="{{ route('repairs.index') }}"
                                    class="{{ request()->routeIs('repairs.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Repairs
                                </a>

                                <a href="{{ route('inventory.index') }}"
                                    class="{{ request()->routeIs('inventory.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-colors">
                                    Inventory
                                </a>
                            </div>
                        </div>

                        <!-- Right side -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                            <!-- Dark mode toggle -->
                            <button 
                                @click="darkMode = !darkMode"
                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg"
                            >
                                <!-- Sun icon -->
                                <svg x-show="!darkMode" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <!-- Moon icon -->
                                <svg x-show="darkMode" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                </svg>
                            </button>

                            <!-- Profile dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <div>
                                    <button @click="open = !open" class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                                        Test User
                                        <svg class="ml-1 h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>

        @stack('scripts')
    </body>
</html>

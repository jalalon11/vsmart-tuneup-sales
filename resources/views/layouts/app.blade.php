<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        pageLoading: false
    }" 
    x-init="
        $watch('darkMode', val => {
            localStorage.setItem('darkMode', val);
            document.documentElement.classList.toggle('dark-transition');
        });
        
        window.addEventListener('beforeunload', () => pageLoading = true);
        
        // Handle navigation without page refresh
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && link.href.startsWith(window.location.origin) && !link.hasAttribute('download')) {
                e.preventDefault();
                navigate(link.href);
            }
        });

        async function navigate(url) {
            pageLoading = true;
            try {
                const response = await fetch(url);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                document.querySelector('main').innerHTML = doc.querySelector('main').innerHTML;
                window.history.pushState({}, '', url);
                document.querySelectorAll('a').forEach(a => {
                    a.classList.remove('border-blue-500', 'text-gray-900', 'dark:text-white');
                    a.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    if (a.getAttribute('href') === url) {
                        a.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                        a.classList.add('border-blue-500', 'text-gray-900', 'dark:text-white');
                    }
                });
            } catch (error) {
                window.location.href = url;
            }
            pageLoading = false;
        }
    "
    :class="{ 'dark': darkMode }">
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
        
        <style>
            /* Dark mode transition */
            .dark-transition * {
                transition: background-color 0.5s ease, border-color 0.5s ease, color 0.5s ease;
            }

            /* Page transition */
            .page-transition-enter {
                opacity: 0;
                transform: translateY(10px);
            }
            .page-transition-enter-active {
                opacity: 1;
                transform: translateY(0);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }
            .page-transition-leave {
                opacity: 1;
                transform: translateY(0);
            }
            .page-transition-leave-active {
                opacity: 0;
                transform: translateY(-10px);
                transition: opacity 0.3s ease, transform 0.3s ease;
            }

            /* Loading indicator */
            .loading-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 3px;
                background: #3b82f6;
                transform-origin: 0;
                z-index: 50;
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased h-full bg-gray-100 dark:bg-gray-900">
        <!-- Loading bar -->
        <div x-show="pageLoading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="transform scale-x-0"
             x-transition:enter-end="transform scale-x-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="transform scale-x-100"
             x-transition:leave-end="transform scale-x-0"
             class="loading-bar"></div>

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
                                    class="{{ request()->routeIs('dashboard') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-all duration-200">
                                    Dashboard
                                </a>
                                
                                <a href="{{ route('services.index') }}"
                                    class="{{ request()->routeIs('services.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-all duration-200">
                                    Services
                                </a>

                                <a href="{{ route('customers.index') }}"
                                    class="{{ request()->routeIs('customers.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-all duration-200">
                                    Customers
                                </a>

                                <a href="{{ route('repairs.index') }}"
                                    class="{{ request()->routeIs('repairs.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-all duration-200">
                                    Repairs
                                </a>

                                <a href="{{ route('inventory.index') }}"
                                    class="{{ request()->routeIs('inventory.*') ? 'border-blue-500 text-gray-900 dark:text-white' : 'border-transparent text-gray-500 dark:text-gray-400' }} hover:border-gray-300 dark:hover:border-gray-600 hover:text-gray-700 dark:hover:text-gray-300 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium transition-all duration-200">
                                    Inventory
                                </a>
                            </div>
                        </div>

                        <!-- Right side -->
                        <div class="hidden sm:ml-6 sm:flex sm:items-center space-x-4">
                            <!-- Dark mode toggle -->
                            <button 
                                @click="darkMode = !darkMode"
                                class="p-2 text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-lg transition-transform duration-300 hover:scale-110"
                            >
                                <!-- Sun icon -->
                                <svg x-show="!darkMode" 
                                     x-transition:enter="transition-transform duration-300"
                                     x-transition:enter-start="rotate-180 scale-0"
                                     x-transition:enter-end="rotate-0 scale-100"
                                     x-transition:leave="transition-transform duration-300"
                                     x-transition:leave-start="rotate-0 scale-100"
                                     x-transition:leave-end="rotate-180 scale-0"
                                     class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                <!-- Moon icon -->
                                <svg x-show="darkMode"
                                     x-transition:enter="transition-transform duration-300"
                                     x-transition:enter-start="-rotate-180 scale-0"
                                     x-transition:enter-end="rotate-0 scale-100"
                                     x-transition:leave="transition-transform duration-300"
                                     x-transition:leave-start="rotate-0 scale-100"
                                     x-transition:leave-end="-rotate-180 scale-0"
                                     class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
            <main class="py-6"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 transform translate-y-4"
                  x-transition:enter-end="opacity-100 transform translate-y-0"
                  x-transition:leave="transition ease-in duration-300"
                  x-transition:leave-start="opacity-100 transform translate-y-0"
                  x-transition:leave-end="opacity-0 transform -translate-y-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>

        @stack('scripts')
    </body>
</html>

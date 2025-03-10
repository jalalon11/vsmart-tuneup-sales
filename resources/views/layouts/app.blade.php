<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full dark-transition" 
    x-data="{ 
        darkMode: localStorage.getItem('darkMode') === 'true',
        pageLoading: false,
        
        async navigate(url) {
            if (this.pageLoading) return;
            this.pageLoading = true;
            
            const content = document.querySelector('.page-content');
            
            try {
                const response = await fetch(url);
                const html = await response.text();
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                // Update content
                const newContent = doc.querySelector('.page-content');
                if (newContent) {
                    content.innerHTML = newContent.innerHTML;
                }

                // Update title
                const newTitle = doc.querySelector('title');
                if (newTitle) {
                    document.title = newTitle.textContent;
                }

                // Update URL
                window.history.pushState({}, '', url);

                // Update navigation active states
                this.updateNavigation(url);

                // First, execute any inline scripts that might define functions
                const inlineScripts = Array.from(doc.querySelectorAll('script:not([src])'));
                inlineScripts.forEach(script => {
                    try {
                        const newScript = document.createElement('script');
                        newScript.textContent = script.textContent;
                        document.body.appendChild(newScript);
                    } catch (err) {
                        console.error('Error executing inline script:', err);
                    }
                });

                // Then handle external scripts
                const externalScripts = Array.from(doc.querySelectorAll('script[src]'));
                await Promise.all(externalScripts.map(script => {
                    return new Promise((resolve, reject) => {
                        const newScript = document.createElement('script');
                        newScript.src = script.src;
                        newScript.onload = resolve;
                        newScript.onerror = reject;
                        document.body.appendChild(newScript);
                    });
                }));

                // Wait a brief moment for scripts to be ready
                await new Promise(resolve => setTimeout(resolve, 100));

                // Reinitialize Alpine.js components
                Alpine.initTree(content);

                // Reinitialize Select2
                if (window.jQuery && jQuery().select2) {
                    jQuery('.select2').select2({
                        width: '100%',
                        dropdownParent: document.body
                    });
                }

                // Handle modals initialization
                const modals = document.querySelectorAll('[x-data]');
                modals.forEach(modal => {
                    if (modal._x_dataStack) {
                        Alpine.initTree(modal);
                    }
                });

                // Reinitialize Chart.js
                const chartCanvas = document.getElementById('dailySalesChart');
                if (chartCanvas) {
                    // Destroy existing chart if it exists
                    const existingChart = Chart.getChart(chartCanvas);
                    if (existingChart) {
                        existingChart.destroy();
                    }
                    
                    // Wait a brief moment for Chart.js to be ready
                    setTimeout(() => {
                        // If the initialization function exists, call it
                        if (typeof window.initializeDailySalesChart === 'function') {
                            window.initializeDailySalesChart();
                        } else {
                            console.warn('Daily sales chart initialization function not found');
                        }
                    }, 100);
                }

                // Execute any stacked scripts
                const stackedScripts = doc.querySelector('#script-container');
                if (stackedScripts) {
                    const scriptContent = stackedScripts.textContent.trim();
                    if (scriptContent) {
                        const newScript = document.createElement('script');
                        newScript.textContent = scriptContent;
                        document.body.appendChild(newScript);
                    }
                }
                
                // Dispatch a custom event to let page scripts know navigation is complete
                document.dispatchEvent(new CustomEvent('page:loaded'));

            } catch (error) {
                console.error('Navigation error:', error);
                window.location.href = url;
            }

            this.pageLoading = false;
        },

        updateNavigation(url) {
            document.querySelectorAll('nav a').forEach(a => {
                if (a.getAttribute('href') === url) {
                    a.classList.remove('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                    a.classList.add('border-blue-500', 'text-gray-900', 'dark:text-white');
                } else {
                    a.classList.remove('border-blue-500', 'text-gray-900', 'dark:text-white');
                    a.classList.add('border-transparent', 'text-gray-500', 'dark:text-gray-400');
                }
            });
        }
    }" 
    x-init="
        $watch('darkMode', val => localStorage.setItem('darkMode', val));
        
        // Handle navigation without page refresh
        document.addEventListener('click', (e) => {
            const link = e.target.closest('a');
            if (link && link.href && link.href.startsWith(window.location.origin) && 
                !link.hasAttribute('download') && 
                !link.href.includes('/profile') && // Skip SPA navigation for profile pages
                !link.classList.contains('no-spa')) {
                e.preventDefault();
                navigate(link.href);
            }
        });

        // Handle browser back/forward
        window.addEventListener('popstate', () => {
            navigate(window.location.href);
        });

        // Handle form submissions
        document.addEventListener('submit', (e) => {
            const form = e.target;
            if (form.method.toLowerCase() === 'get') {
                e.preventDefault();
                const formData = new FormData(form);
                const queryString = new URLSearchParams(formData).toString();
                const url = form.action + (queryString ? '?' + queryString : '');
                navigate(url);
            }
        });
    "
    :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>VSMART SMS</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('img/LogoClear.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        
        <style>
            /* Dark mode transition */
            .dark-transition,
            .dark-transition * {
                transition: background-color 0.5s ease,
                            border-color 0.5s ease,
                            color 0.5s ease !important;
            }

            /* Preserve button colors during transition */
            .dark-transition button.bg-blue-600 {
                transition: transform 0.3s ease,
                            opacity 0.3s ease !important;
            }

            /* Loading indicator */
            .loading-bar {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 2px;
                background: linear-gradient(to right, #3b82f6, #60a5fa);
                transform-origin: 0;
                z-index: 50;
                opacity: 0.7;
            }

            /* Dark mode toggle animation */
            .theme-toggle-icon {
                transform-origin: center;
            }

            /* Page content */
            .page-content {
                position: relative;
                width: 100%;
            }

            /* Ensure content doesn't overflow */
            body {
                overflow-x: hidden;
            }
        </style>
        @stack('styles')
    </head>
    <body class="font-sans antialiased h-full bg-gray-100 dark:bg-gray-900">
        <!-- Loading bar -->
        <div x-show="pageLoading" 
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
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
                                <a href="{{ route('dashboard') }}" class="text-xl font-bold text-red-600 dark:text-red-400">
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
                            <!-- User dropdown -->
                            @include('layouts.navigation')
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main class="py-6">
                <div class="page-content max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    @yield('content')
                </div>
            </main>
        </div>

        @stack('scripts')
        <div id="script-container" style="display: none;">
            @stack('scripts')
        </div>
        
        <script>
            // Create Alpine.js store for dark mode
            document.addEventListener('alpine:init', () => {
                Alpine.store('darkMode', {
                    dark: localStorage.getItem('darkMode') === 'true',
                    
                    toggle() {
                        this.dark = !this.dark;
                        localStorage.setItem('darkMode', this.dark);
                        
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                            document.getElementById('darkModeText').textContent = 'Light Mode';
                            document.getElementById('darkModeTextMobile').textContent = 'Light Mode';
                            document.querySelector('.dark-mode-light')?.classList.add('hidden');
                            document.querySelector('.dark-mode-dark')?.classList.remove('hidden');
                        } else {
                            document.documentElement.classList.remove('dark');
                            document.getElementById('darkModeText').textContent = 'Dark Mode';
                            document.getElementById('darkModeTextMobile').textContent = 'Dark Mode';
                            document.querySelector('.dark-mode-light')?.classList.remove('hidden');
                            document.querySelector('.dark-mode-dark')?.classList.add('hidden');
                        }
                    },
                    
                    init() {
                        if (this.dark) {
                            document.documentElement.classList.add('dark');
                        } else {
                            document.documentElement.classList.remove('dark');
                        }
                    }
                });
            });

            // Ensure Alpine.js is properly initialized
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Alpine.js if needed
                if (typeof Alpine !== 'undefined' && !window.alpineInitialized) {
                    Alpine.start();
                    window.alpineInitialized = true;
                    
                    // Force Alpine.js to reinitialize all components
                    document.querySelectorAll('[x-data]').forEach(el => {
                        if (el._x_dataStack) {
                            el._x_dataStack.forEach(item => {
                                if (typeof item === 'object') {
                                    // Ensure all Alpine.js states are properly reset
                                    Object.keys(item).forEach(key => {
                                        if (key.startsWith('is') && typeof item[key] === 'boolean') {
                                            item[key] = false;
                                        }
                                    });
                                }
                            });
                        }
                    });
                }
            });

            // Listen for navigation events and reinitialize Alpine components
            document.addEventListener('turbo:load', function() {
                if (typeof Alpine !== 'undefined') {
                    Alpine.start();
                }
            });
        </script>
    </body>
</html>

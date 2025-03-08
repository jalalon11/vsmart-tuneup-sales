<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
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
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex">
            <!-- Left Panel -->
            <div class="hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 to-blue-800 p-12 items-center justify-center relative overflow-hidden">
                <div class="relative z-10 text-white text-center">
                    <h1 class="text-4xl font-bold mb-6">VSMART TUNE UP</h1>
                    <p class="text-xl text-blue-100 mb-8">Professional Mobile Device Services</p>
                    <div class="space-y-4 text-lg text-blue-100">
                        <p>✓ LCD Replacement</p>
                        <p>✓ Battery Service</p>
                        <p>✓ Device Repair</p>
                        <p>✓ Quality Parts</p>
                    </div>
                </div>
                <!-- Decorative circles -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-white opacity-10 rounded-full"></div>
                    <div class="absolute bottom-1/4 right-1/4 w-48 h-48 bg-blue-400 opacity-10 rounded-full"></div>
                </div>
            </div>

            <!-- Right Panel -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
                <div class="w-full max-w-md">
                    <div class="text-center mb-8">
                        <div class="inline-block p-2 bg-blue-50 rounded-xl mb-4">
                            <div class="w-16 h-16 bg-gradient-to-r from-blue-600 to-blue-700 rounded-lg shadow-lg flex items-center justify-center">
                                <span class="text-2xl font-bold text-white">VS</span>
                            </div>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Welcome Back</h2>
                        <p class="text-gray-600 mt-2">Access your service dashboard</p>
                    </div>

                    @yield('content')

                    <div class="mt-8 text-center text-sm text-gray-500">
                        &copy; {{ date('Y') }} VSMART TUNE UP. All rights reserved.
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>

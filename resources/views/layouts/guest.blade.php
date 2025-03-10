<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Favicon -->
        <link rel="icon" href="{{ asset('img/logo.png') }}" type="image/png">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen flex justify-center items-center">
            <!-- Main Content -->
            <div class="w-full max-w-lg p-8">
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
    </body>
</html>

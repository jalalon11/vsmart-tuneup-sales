@extends('layouts.app')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">
            <!-- Profile Header Banner -->
            <div class="bg-gradient-to-r from-blue-500 to-blue-700 h-48 relative">
                <!-- Profile Avatar -->
                <div class="absolute -bottom-16 left-8">
                    <div class="h-32 w-32 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-4xl font-bold text-blue-600 dark:text-blue-400 border-4 border-white dark:border-gray-800 shadow-md">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
                
                <!-- Edit Profile Button -->
                <div class="absolute bottom-4 right-4">
                    <a href="{{ route('profile.edit') }}" class="inline-flex items-center px-4 py-2 bg-white/10 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-white/20 focus:bg-white/20 active:bg-white/25 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Edit Profile
                    </a>
                </div>
            </div>

            <div class="px-8 pt-20 pb-8">
                <!-- User Info -->
                <div class="mb-8">
                    <h3 class="text-3xl font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">{{ auth()->user()->email }}</p>
                    <div class="mt-4 flex items-center">
                        <div class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-sm font-medium">
                            {{ auth()->user()->position }}
                        </div>
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Joined {{ auth()->user()->created_at->format('F Y') }}
                        </span>
                    </div>
                </div>

                <!-- Profile Sections -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Contact Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Contact Information</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Email</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->phone ?? 'Not provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->address ?? 'Not provided' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Additional Information -->
                    <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                        <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Additional Information</h4>
                        <div class="space-y-4">
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Position</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->position }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bio</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->bio ?? 'No bio provided' }}</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Account Created</p>
                                <p class="mt-1 text-gray-800 dark:text-gray-200">{{ auth()->user()->created_at->format('F j, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 
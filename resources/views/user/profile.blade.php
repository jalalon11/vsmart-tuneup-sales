@extends('layouts.app')

@section('content')
<div class="py-6">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 shadow-xl sm:rounded-lg overflow-hidden">
            <!-- Profile Header Banner -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 h-32 relative">
                <!-- Profile Avatar -->
                <div class="absolute -bottom-12 left-8">
                    <div class="h-24 w-24 rounded-full bg-white dark:bg-gray-700 flex items-center justify-center text-3xl font-bold text-indigo-600 dark:text-indigo-400 border-4 border-white dark:border-gray-800 shadow-md">
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    </div>
                </div>
            </div>

            <div class="px-8 pt-16 pb-8">
                <!-- User Info -->
                <div class="mb-6">
                    <h3 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</h3>
                    <p class="text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    <div class="mt-2 flex items-center">
                        @if(auth()->user()->is_admin)
                        <div class="px-3 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs font-medium">
                            Super Administrator
                        </div>
                        @else
                        <div class="px-3 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-700 dark:text-blue-300 text-xs font-medium">
                            Administrator
                        </div>
                        @endif
                        <span class="mx-2 text-gray-400">•</span>
                        <span class="text-sm text-gray-500 dark:text-gray-400">
                            Joined {{ auth()->user()->created_at->format('M Y') }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 dark:border-gray-700" x-data="{ activeTab: 'personal' }">
                <div class="px-8">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button @click="activeTab = 'personal'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'personal', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'personal' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Personal Information
                        </button>
                        <button @click="activeTab = 'security'" :class="{ 'border-indigo-500 text-indigo-600 dark:text-indigo-400': activeTab === 'security', 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300': activeTab !== 'security' }" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Security
                        </button>
                    </nav>
                </div>
            </div>

            <!-- Tab Contents -->
            <div class="px-8 py-6">
                <!-- Personal Information Tab -->
                <div x-show="activeTab === 'personal'">
                    <form method="post" action="{{ route('user.profile.update') }}" class="space-y-6">
                        @csrf
                        @method('patch')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Full Name</label>
                                <input type="text" id="name" name="name" value="{{ auth()->user()->name }}" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" />
                                @error('name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div class="md:col-span-2">
                                <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Email Address</label>
                                <input type="email" id="email" name="email" value="{{ auth()->user()->email }}"
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300" />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="flex items-center justify-end mt-6">
                            @if (session('status') === 'profile-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 dark:text-green-400 mr-3">
                                    Profile information saved successfully.
                                </p>
                            @endif
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Save Changes
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Security Tab -->
                <div x-show="activeTab === 'security'" style="display: none;">
                    <form method="post" action="{{ route('user.password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')

                        <div class="grid grid-cols-1 gap-6">
                            <div>
                                <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Current Password</label>
                                <div class="relative mt-1">
                                    <input type="password" id="current_password" name="current_password"
                                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 pr-10" />
                                    <button type="button" onclick="togglePasswordVisibility('current_password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400">
                                        <svg id="current_password-icon-show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="current_password-icon-hide" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('current_password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">New Password</label>
                                <div class="relative mt-1">
                                    <input type="password" id="password" name="password"
                                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 pr-10" />
                                    <button type="button" onclick="togglePasswordVisibility('password')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400">
                                        <svg id="password-icon-show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="password-icon-hide" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Password must be at least 8 characters</p>
                                @error('password')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                            
                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Confirm New Password</label>
                                <div class="relative mt-1">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-300 pr-10" />
                                    <button type="button" onclick="togglePasswordVisibility('password_confirmation')" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 dark:text-gray-400">
                                        <svg id="password_confirmation-icon-show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                        <svg id="password_confirmation-icon-hide" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                        </svg>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            @if (session('status') === 'password-updated')
                                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="text-sm text-green-600 dark:text-green-400 mr-3">
                                    Password updated successfully.
                                </p>
                            @endif
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function togglePasswordVisibility(fieldId) {
        const passwordInput = document.getElementById(fieldId);
        const showIcon = document.getElementById(fieldId + '-icon-show');
        const hideIcon = document.getElementById(fieldId + '-icon-hide');
        
        if (passwordInput.type === 'password') {
            passwordInput.type = 'text';
            showIcon.classList.add('hidden');
            hideIcon.classList.remove('hidden');
        } else {
            passwordInput.type = 'password';
            showIcon.classList.remove('hidden');
            hideIcon.classList.add('hidden');
        }
    }
</script>
@endsection

@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-8 text-gray-900 dark:text-gray-100">
            <div class="mb-8 border-b dark:border-gray-700 pb-4">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Add New Customer</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Fill in the customer details to create a new account.</p>
            </div>

            <form action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                @csrf

                <!-- Customer Information -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        Customer Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Name -->
                        <div class="space-y-2">
                            <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Phone -->
                        <div class="space-y-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                            <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Facebook URL -->
                        <div class="space-y-2">
                            <label for="facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook Profile</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </div>
                                <input type="url" name="facebook_url" id="facebook_url" value="{{ old('facebook_url') }}" 
                                    placeholder="https://facebook.com/profile"
                                    class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                            </div>
                            @error('facebook_url')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Address -->
                        <div class="md:col-span-2 space-y-2">
                            <label for="address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                            <textarea name="address" id="address" rows="3" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">{{ old('address') }}</textarea>
                            @error('address')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Device Information -->
                <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        Device Information
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <!-- Brand -->
                        <div class="space-y-2">
                            <label for="device_brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                            <input type="text" name="device_brand" id="device_brand" value="{{ old('device_brand') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                            @error('device_brand')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Model -->
                        <div class="space-y-2">
                            <label for="device_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                            <input type="text" name="device_model" id="device_model" value="{{ old('device_model') }}" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                            @error('device_model')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6">
                    <a href="{{ route('customers.index') }}" 
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Cancel
                    </a>
                    <button type="submit" 
                        class="inline-flex items-center px-6 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                        Create Customer with Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection 
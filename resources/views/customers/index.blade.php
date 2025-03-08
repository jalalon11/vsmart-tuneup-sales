@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white leading-tight">Customers</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage your customer database and their devices
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <!-- Search Form -->
                <form action="{{ route('customers.index') }}" method="GET" class="flex-1 md:flex-none">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search customers..." 
                               class="w-full md:w-64 pl-10 pr-10 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:placeholder-gray-400"
                               autofocus
                               autocomplete="off">
                        
                        @if(request('search'))
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <a href="{{ route('customers.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
                <button type="button" 
                    onclick="openModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:scale-105">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Customer
                </button>
            </div>
        </div>
    </div>

    <!-- Alerts -->
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif

    <!-- Customers Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Contact</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Devices</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($customers as $customer)
                        <tr class="customer-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                @if($customer->facebook_url)
                                    <a href="{{ $customer->facebook_url }}" target="_blank" 
                                        class="inline-flex items-center mt-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                        </svg>
                                        Profile
                                    </a>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $customer->phone }}</div>
                                @if($customer->email)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $customer->email }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        <span class="font-medium">{{ $customer->devices_count }}</span> devices
                                    </div>
                                    @if($customer->pending_repairs_count > 0)
                                        <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            {{ $customer->pending_repairs_count }} in repair
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <button type="button" 
                                    data-customer-id="{{ $customer->id }}" 
                                    onclick="openViewModal(this.dataset.customerId)"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </button>
                                <button type="button" 
                                    data-customer-id="{{ $customer->id }}" 
                                    onclick="openEditModal(this.dataset.customerId)"
                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline delete-customer-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-base">No customers found</p>
                                    <button type="button" 
                                        onclick="openModal()"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Your First Customer
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="bg-white dark:bg-gray-800 px-4 py-3 border-t border-gray-200 dark:border-gray-700 sm:px-6">
            {{ $customers->links() }}
        </div>
    </div>
</div>

<!-- Add Customer Modal -->
<div id="customerModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="customerModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="customerModalContent">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="mb-8 border-b dark:border-gray-700 pb-4">
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="modal-title">Add New Customer</h3>
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Fill in the customer details to create a new account.</p>
                        </div>

                        <form id="customerForm" action="{{ route('customers.store') }}" method="POST" class="space-y-8">
                            @csrf

                            <!-- Customer Information -->
                            <div class="bg-gray-50 dark:bg-gray-700/50 rounded-lg p-6">
                                <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    Customer Information
                                </h2>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div class="space-y-2">
                                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="space-y-2">
                                        <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                        <input type="tel" name="phone" id="phone" value="{{ old('phone') }}" required
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
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" form="customerForm"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Create Customer
                </button>
                <button type="button" onclick="closeModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Customer Modal -->
<div id="viewCustomerModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="viewCustomerModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="viewCustomerModalContent">
            <div class="bg-white dark:bg-gray-800">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="view-modal-title">Customer Profile</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">View and manage customer information and devices</p>
                        </div>
                        <button type="button" onclick="closeViewModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <!-- Customer Information -->
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg">
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Customer Information
                                    </h4>
                                </div>

                                <div class="space-y-4">
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-name"></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Phone</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-phone"></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Facebook Profile</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-facebook"></p>
                                    </div>
                                    <div>
                                        <label class="text-sm font-medium text-gray-500 dark:text-gray-400">Address</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Devices Section -->
                        <div class="lg:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-lg">
                                <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            Devices
                                        </h4>
                                        <button type="button" 
                                            onclick="showAddDeviceForm()"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Device
                                        </button>
                                    </div>
                                </div>

                                <!-- Add Device Form -->
                                <div id="add-device-form" class="hidden mb-6 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Add New Device</h3>
                                    <form id="deviceForm" class="space-y-4">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="new-device-brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                                                <input type="text" id="new-device-brand" name="brand" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label for="new-device-model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                                <input type="text" id="new-device-model" name="model" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="hideAddDeviceForm()" 
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                Add Device
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Devices List -->
                                <div id="view-customer-devices" class="space-y-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                                    <!-- Devices will be dynamically inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeViewModal()"
                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Customer Modal -->
<div id="editCustomerModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="editCustomerModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="editCustomerModalContent">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <div class="mb-8 border-b dark:border-gray-700 pb-4">
                            <div class="flex justify-between items-center">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="edit-modal-title">Edit Customer</h3>
                                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update customer information and preferences.</p>
                                </div>
                            </div>
                        </div>

                        <form id="editCustomerForm" method="POST" class="space-y-8">
                            @csrf
                            @method('PUT')

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
                                        <label for="edit-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Customer Name</label>
                                        <input type="text" name="name" id="edit-name" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                                        @error('name')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Phone -->
                                    <div class="space-y-2">
                                        <label for="edit-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone Number</label>
                                        <input type="tel" name="phone" id="edit-phone" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                                        @error('phone')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Facebook URL -->
                                    <div class="space-y-2">
                                        <label for="edit-facebook_url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook Profile URL</label>
                                        <div class="mt-1 relative rounded-md shadow-sm">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                                </svg>
                                            </div>
                                            <input type="url" name="facebook_url" id="edit-facebook_url"
                                                placeholder="https://facebook.com/profile"
                                                class="pl-10 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150">
                                        </div>
                                        @error('facebook_url')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Address -->
                                    <div class="md:col-span-2 space-y-2">
                                        <label for="edit-address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                        <textarea name="address" id="edit-address" rows="3" 
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 transition duration-150"></textarea>
                                        @error('address')
                                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" form="editCustomerForm"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Update Customer
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm transition duration-150">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Animation utility functions
function showModal(modalId, contentId, overlayId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    const overlay = document.getElementById(overlayId);
    
    // Show modal
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
    
    // Animate overlay
    overlay.classList.add('ease-out', 'duration-300');
    overlay.classList.remove('opacity-0');
    overlay.classList.add('opacity-100');
    
    // Animate content
    content.classList.add('ease-out', 'duration-300');
    setTimeout(() => {
        content.classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        content.classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
    }, 100);
}

function hideModal(modalId, contentId, overlayId) {
    const modal = document.getElementById(modalId);
    const content = document.getElementById(contentId);
    const overlay = document.getElementById(overlayId);
    
    // Animate overlay
    overlay.classList.add('ease-in', 'duration-200');
    overlay.classList.remove('opacity-100');
    overlay.classList.add('opacity-0');
    
    // Animate content
    content.classList.add('ease-in', 'duration-200');
    content.classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
    content.classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
    
    // Hide modal after animation
    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }, 200);
}

function openModal() {
    showModal('customerModal', 'customerModalContent', 'customerModalOverlay');
}

function closeModal() {
    hideModal('customerModal', 'customerModalContent', 'customerModalOverlay');
}

// View modal functions
function showAddDeviceForm() {
    document.getElementById('add-device-form').classList.remove('hidden');
}

function hideAddDeviceForm() {
    const form = document.getElementById('add-device-form');
    form.classList.add('hidden');
    
    // Reset form to its original state
    document.getElementById('new-device-brand').value = '';
    document.getElementById('new-device-model').value = '';
    document.querySelector('#add-device-form h3').textContent = 'Add New Device';
    document.querySelector('#add-device-form button[type="submit"]').textContent = 'Add Device';
    
    // Reset the form submission handler
    document.getElementById('deviceForm').onsubmit = null;
}

let currentCustomerId = null;

function openViewModal(customerId) {
    currentCustomerId = customerId; // Store the customer ID
    fetch(`/customers/${customerId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.customer) {
            const customer = data.customer;
            document.getElementById('view-customer-name').textContent = customer.name || 'Not provided';
            document.getElementById('view-customer-phone').textContent = customer.phone || 'Not provided';
            document.getElementById('view-customer-facebook').innerHTML = customer.facebook_url ? 
                `<a href="${customer.facebook_url}" target="_blank" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">${customer.facebook_url}</a>` : 
                'Not provided';
            document.getElementById('view-customer-address').textContent = customer.address || 'Not provided';

            // Handle devices
            const devicesContainer = document.getElementById('view-customer-devices');
            devicesContainer.innerHTML = '';
            
            if (customer.devices && customer.devices.length > 0) {
                const devicesList = document.createElement('div');
                devicesList.className = 'space-y-4';
                
                customer.devices.forEach(device => {
                    const deviceElement = document.createElement('div');
                    deviceElement.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors';
                    
                    // Get status class based on device status
                    let statusClass = 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300';
                    let statusText = device.status || 'Unknown';
                    
                    if (device.status) {
                        switch(device.status.toLowerCase()) {
                            case 'completed':
                                statusClass = 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300';
                                break;
                            case 'repairing':
                            case 'pending':
                                statusClass = 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300';
                                break;
                            case 'received':
                                statusClass = 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300';
                                break;
                        }
                        statusText = statusText.charAt(0).toUpperCase() + statusText.slice(1);
                    }
                    
                    deviceElement.innerHTML = `
                        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <div class="flex items-start justify-between">
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-col">
                                        <div class="flex items-center space-x-3">
                                            <h3 class="text-base font-medium text-gray-900 dark:text-white">${device.name || `${device.brand} ${device.model}`}</h3>
                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full ${statusClass}">
                                                ${statusText}
                                            </span>
                                        </div>
                                        <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                            <span>${device.brand}</span>
                                            <span>&bull;</span>
                                            <span>${device.model}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-2">
                                    <button type="button"
                                        onclick="editDevice(${device.id}, '${device.brand}', '${device.model}')"
                                        class="inline-flex items-center p-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                    <a href="/repairs/create?customer_id=${currentCustomerId}&device_id=${device.id}" 
                                        class="inline-flex items-center p-2 border border-blue-500 dark:border-blue-400 rounded-lg text-sm font-medium text-blue-600 dark:text-blue-400 bg-white dark:bg-gray-800 hover:bg-blue-50 dark:hover:bg-blue-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                                        </svg>
                                    </a>
                                    <button type="button"
                                        onclick="deleteDevice(event, ${device.id})"
                                        class="inline-flex items-center p-2 border border-red-500 dark:border-red-400 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                    devicesList.appendChild(deviceElement);
                });
                
                devicesContainer.appendChild(devicesList);
            } else {
                devicesContainer.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No devices found.</p>';
            }
            
            showModal('viewCustomerModal', 'viewCustomerModalContent', 'viewCustomerModalOverlay');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error loading customer details');
    });
}

function closeViewModal() {
    hideModal('viewCustomerModal', 'viewCustomerModalContent', 'viewCustomerModalOverlay');
}

// Edit modal functions
function openEditModal(customerId) {
    const form = document.getElementById('editCustomerForm');
    form.action = `/customers/${customerId}`;

    fetch(`/customers/${customerId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.customer) {
                const customer = data.customer;
                document.getElementById('edit-name').value = customer.name;
                document.getElementById('edit-phone').value = customer.phone || '';
                document.getElementById('edit-facebook_url').value = customer.facebook_url || '';
                document.getElementById('edit-address').value = customer.address || '';
                
                showModal('editCustomerModal', 'editCustomerModalContent', 'editCustomerModalOverlay');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error loading customer details for editing');
        });
}

function closeEditModal() {
    hideModal('editCustomerModal', 'editCustomerModalContent', 'editCustomerModalOverlay');
}

// Handle edit form submission
document.getElementById('editCustomerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeEditModal();
            // Reload the page to show updated data
            window.location.reload();
        } else {
            alert(data.message || 'Error updating customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error updating customer');
    });
});

// Close modals when clicking outside
document.getElementById('customerModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeModal();
    }
});

document.getElementById('viewCustomerModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeViewModal();
    }
});

document.getElementById('editCustomerModal').addEventListener('click', function(event) {
    if (event.target === this) {
        closeEditModal();
    }
});

// Close modals on escape key press
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        if (!document.getElementById('customerModal').classList.contains('hidden')) {
            closeModal();
        }
        if (!document.getElementById('viewCustomerModal').classList.contains('hidden')) {
            closeViewModal();
        }
        if (!document.getElementById('editCustomerModal').classList.contains('hidden')) {
            closeEditModal();
        }
    }
});

// Add this to your JavaScript after the openViewModal function
document.getElementById('deviceForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    if (!currentCustomerId) {
        alert('Error: Customer ID not found');
        return;
    }

    const formData = new FormData();
    formData.append('brand', document.getElementById('new-device-brand').value);
    formData.append('model', document.getElementById('new-device-model').value);
    formData.append('_token', csrfToken);

    fetch(`/customers/${currentCustomerId}/devices`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear form
            document.getElementById('new-device-brand').value = '';
            document.getElementById('new-device-model').value = '';
            hideAddDeviceForm();
            
            // Refresh the devices list
            openViewModal(currentCustomerId);
        } else {
            alert(data.message || 'Error adding device');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error adding device');
    });
});

// Update the customer form submission to use AJAX
document.getElementById('customerForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const form = this;
    const formData = new FormData(form);

    // Clear any existing error messages
    form.querySelectorAll('.text-red-600').forEach(el => el.remove());
    form.querySelectorAll('.border-red-500').forEach(el => el.classList.remove('border-red-500'));

    fetch(form.action, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Close the modal
            closeModal();
            
            // Create the new row with fade-in animation
            const newRow = document.createElement('tr');
            newRow.className = 'customer-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 fade-in';
            newRow.innerHTML = `
                <td class="px-6 py-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">${data.customer.name}</div>
                    ${data.customer.facebook_url ? `
                        <a href="${data.customer.facebook_url}" target="_blank" 
                            class="inline-flex items-center mt-1 text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                            </svg>
                            Profile
                        </a>
                    ` : ''}
                </td>
                <td class="px-6 py-4">
                    <div class="text-sm text-gray-900 dark:text-white">${data.customer.phone}</div>
                    ${data.customer.email ? `
                        <div class="text-sm text-gray-500 dark:text-gray-400">${data.customer.email}</div>
                    ` : ''}
                </td>
                <td class="px-6 py-4">
                    <div class="flex items-center">
                        <div class="text-sm text-gray-900 dark:text-white">
                            <span class="font-medium">${data.customer.devices_count || 0}</span> devices
                        </div>
                        ${data.customer.pending_repairs_count > 0 ? `
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                ${data.customer.pending_repairs_count} in repair
                            </span>
                        ` : ''}
                    </div>
                </td>
                <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                    <button type="button" 
                        data-customer-id="${data.customer.id}" 
                        onclick="openViewModal(this.dataset.customerId)"
                        class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        View
                    </button>
                    <button type="button" 
                        data-customer-id="${data.customer.id}" 
                        onclick="openEditModal(this.dataset.customerId)"
                        class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit
                    </button>
                    <form action="/customers/${data.customer.id}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                            class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Delete
                        </button>
                    </form>
                </td>
            `;
            
            // Insert the new row at the beginning of the table
            const customersTable = document.querySelector('table tbody');
            customersTable.insertBefore(newRow, customersTable.firstChild);

            // Reset the form
            form.reset();
        } else {
            // Handle validation errors
            const errors = data.errors || {};
            Object.keys(errors).forEach(field => {
                const input = form.querySelector(`[name="${field}"]`);
                if (input) {
                    input.classList.add('border-red-500');
                    const errorElement = document.createElement('p');
                    errorElement.className = 'mt-1 text-sm text-red-600 dark:text-red-400';
                    errorElement.textContent = errors[field][0];
                    input.parentNode.appendChild(errorElement);
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const errorAlert = document.createElement('div');
        errorAlert.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm';
        errorAlert.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">Error creating customer. Please try again.</p>
                </div>
            </div>`;
        form.insertBefore(errorAlert, form.firstChild);
        
        setTimeout(() => {
            errorAlert.remove();
        }, 3000);
    });
});

// Add these JavaScript functions after your existing scripts
function editDevice(deviceId, brand, model) {
    // Show the add device form and populate it with current values
    showAddDeviceForm();
    
    // Update form title and button text to reflect edit mode
    document.querySelector('#add-device-form h3').textContent = 'Edit Device';
    document.querySelector('#add-device-form button[type="submit"]').textContent = 'Update Device';
    
    // Populate form fields
    document.getElementById('new-device-brand').value = brand;
    document.getElementById('new-device-model').value = model;
    
    // Update the form submission handler for editing
    const deviceForm = document.getElementById('deviceForm');
    deviceForm.onsubmit = function(e) {
        e.preventDefault();
        
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('brand', document.getElementById('new-device-brand').value);
        formData.append('model', document.getElementById('new-device-model').value);
        formData.append('_token', csrfToken);

        fetch(`/devices/${deviceId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const successMessage = document.createElement('div');
                successMessage.className = 'mb-4 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg';
                successMessage.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span class="text-sm text-green-700 dark:text-green-300">${data.message || 'Device updated successfully'}</span>
                    </div>
                `;
                const deviceForm = document.getElementById('add-device-form');
                deviceForm.insertBefore(successMessage, deviceForm.firstChild);
                
                // Remove success message after 3 seconds
                setTimeout(() => successMessage.remove(), 3000);
                
                // Reset form
                document.getElementById('new-device-brand').value = '';
                document.getElementById('new-device-model').value = '';
                document.querySelector('#add-device-form h3').textContent = 'Add New Device';
                document.querySelector('#add-device-form button[type="submit"]').textContent = 'Add Device';
                hideAddDeviceForm();
                
                // Reset the form submission handler
                deviceForm.onsubmit = null;
                
                // Refresh the devices list
                openViewModal(currentCustomerId);
            } else {
                // Show error message
                const errorMessage = document.createElement('div');
                errorMessage.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg';
                errorMessage.innerHTML = `
                    <div class="flex items-center">
                        <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm text-red-700 dark:text-red-300">${data.message || 'Error updating device'}</span>
                    </div>
                `;
                const deviceForm = document.getElementById('add-device-form');
                deviceForm.insertBefore(errorMessage, deviceForm.firstChild);
                
                // Remove error message after 3 seconds
                setTimeout(() => errorMessage.remove(), 3000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error updating device');
        });
    };
}

function deleteDevice(event, deviceId) {
    event.preventDefault();
    
    if (!confirm('Are you sure you want to delete this device? This action cannot be undone.')) {
        return false;
    }

    // Show loading state
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;
    button.disabled = true;
    button.innerHTML = `
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;

    fetch(`/devices/${deviceId}`, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Show success message
            const devicesContainer = document.getElementById('view-customer-devices');
            const successMessage = document.createElement('div');
            successMessage.className = 'mb-4 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg';
            successMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    <span class="text-sm text-green-700 dark:text-green-300">${data.message || 'Device deleted successfully'}</span>
                </div>
            `;
            devicesContainer.insertBefore(successMessage, devicesContainer.firstChild);
            
            // Remove success message after 3 seconds
            setTimeout(() => successMessage.remove(), 3000);
            
            // Refresh the devices list
            openViewModal(currentCustomerId);
        } else {
            throw new Error(data.message || 'Error deleting device');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error message
        const devicesContainer = document.getElementById('view-customer-devices');
        const errorMessage = document.createElement('div');
        errorMessage.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg';
        errorMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-red-700 dark:text-red-300">${error.message}</span>
            </div>
        `;
        devicesContainer.insertBefore(errorMessage, devicesContainer.firstChild);
        
        // Remove error message after 3 seconds
        setTimeout(() => errorMessage.remove(), 3000);
        
        // Restore button state
        button.disabled = false;
        button.innerHTML = originalContent;
    });

    return false;
}

// Add these new JavaScript functions before the closing script tag
function handleCustomerDelete(event) {
    event.preventDefault();
    const form = event.target;
    const row = form.closest('.customer-row');
    const deleteButton = form.querySelector('button[type="submit"]');
    const originalButtonContent = deleteButton.innerHTML;
    
    if (!confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
        return false;
    }

    // Show loading state
    deleteButton.disabled = true;
    deleteButton.innerHTML = `
        <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    `;

    fetch(form.action, {
        method: 'DELETE',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Add fade out animation
            row.classList.add('fade-out');
            
            // Show success message
            const successAlert = document.createElement('div');
            successAlert.className = 'success-alert mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm fade-in';
            successAlert.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">${data.message}</p>
                    </div>
                </div>`;
            
            const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
            tableContainer.insertBefore(successAlert, tableContainer.firstChild);

            // Remove success message after 3 seconds
            setTimeout(() => {
                successAlert.style.opacity = '0';
                successAlert.style.transform = 'translateY(-10px)';
                successAlert.style.transition = 'all 0.3s ease-in-out';
                setTimeout(() => successAlert.remove(), 300);
            }, 3000);
            
            // Remove the row after animation completes
            setTimeout(() => {
                row.remove();
                
                // Check if table is empty and show the "No customers found" message
                const tbody = document.querySelector('table tbody');
                if (!tbody.querySelector('tr:not(.fade-out)')) {
                    const noCustomersRow = document.createElement('tr');
                    noCustomersRow.className = 'fade-in';
                    noCustomersRow.innerHTML = `
                        <td colspan="4" class="px-6 py-8 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <p class="mt-4 text-gray-500 dark:text-gray-400 text-base">No customers found</p>
                                <button type="button" 
                                    onclick="openModal()"
                                    class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Your First Customer
                                </button>
                            </div>
                        </td>
                    `;
                    tbody.appendChild(noCustomersRow);
                }
            }, 500);
        } else {
            // Show error message
            const errorAlert = document.createElement('div');
            errorAlert.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm fade-in';
            errorAlert.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800 dark:text-red-200">${data.message || 'Error deleting customer'}</p>
                    </div>
                </div>`;
            
            const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
            tableContainer.insertBefore(errorAlert, tableContainer.firstChild);

            // Remove error message after 3 seconds
            setTimeout(() => {
                errorAlert.style.opacity = '0';
                errorAlert.style.transform = 'translateY(-10px)';
                errorAlert.style.transition = 'all 0.3s ease-in-out';
                setTimeout(() => errorAlert.remove(), 300);
            }, 3000);

            // Reset button state
            deleteButton.disabled = false;
            deleteButton.innerHTML = originalButtonContent;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Reset button state
        deleteButton.disabled = false;
        deleteButton.innerHTML = originalButtonContent;
        
        // Show error message
        const errorAlert = document.createElement('div');
        errorAlert.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm fade-in';
        errorAlert.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">Error deleting customer. Please try again.</p>
                </div>
            </div>`;
        
        const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
        tableContainer.insertBefore(errorAlert, tableContainer.firstChild);

        // Remove error message after 3 seconds
        setTimeout(() => {
            errorAlert.style.opacity = '0';
            errorAlert.style.transform = 'translateY(-10px)';
            errorAlert.style.transition = 'all 0.3s ease-in-out';
            setTimeout(() => errorAlert.remove(), 300);
        }, 3000);
    });

    return false;
}

// Add this JavaScript code after your existing scripts
document.addEventListener('DOMContentLoaded', function() {
    // Handle all delete customer forms
    document.querySelectorAll('.delete-customer-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const row = this.closest('.customer-row');
            const deleteButton = this.querySelector('button[type="submit"]');
            const originalButtonContent = deleteButton.innerHTML;
            
            if (!confirm('Are you sure you want to delete this customer? This action cannot be undone.')) {
                return false;
            }

            // Show loading state
            deleteButton.disabled = true;
            deleteButton.innerHTML = `
                <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            `;

            fetch(this.action, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Add fade out animation
                    row.classList.add('fade-out');
                    
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'success-alert mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm fade-in';
                    successAlert.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800 dark:text-green-200">${data.message}</p>
                            </div>
                        </div>`;
                    
                    const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
                    tableContainer.insertBefore(successAlert, tableContainer.firstChild);

                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successAlert.style.opacity = '0';
                        successAlert.style.transform = 'translateY(-10px)';
                        successAlert.style.transition = 'all 0.3s ease-in-out';
                        setTimeout(() => successAlert.remove(), 300);
                    }, 3000);
                    
                    // Remove the row after animation completes
                    setTimeout(() => {
                        row.remove();
                        
                        // Check if table is empty and show the "No customers found" message
                        const tbody = document.querySelector('table tbody');
                        if (!tbody.querySelector('tr:not(.fade-out)')) {
                            const noCustomersRow = document.createElement('tr');
                            noCustomersRow.className = 'fade-in';
                            noCustomersRow.innerHTML = `
                                <td colspan="4" class="px-6 py-8 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <p class="mt-4 text-gray-500 dark:text-gray-400 text-base">No customers found</p>
                                        <button type="button" 
                                            onclick="openModal()"
                                            class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                            <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Your First Customer
                                        </button>
                                    </div>
                                </td>
                            `;
                            tbody.appendChild(noCustomersRow);
                        }
                    }, 500);
                } else {
                    // Show error message
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm fade-in';
                    errorAlert.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800 dark:text-red-200">${data.message || 'Error deleting customer'}</p>
                            </div>
                        </div>`;
                    
                    const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
                    tableContainer.insertBefore(errorAlert, tableContainer.firstChild);

                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorAlert.style.opacity = '0';
                        errorAlert.style.transform = 'translateY(-10px)';
                        errorAlert.style.transition = 'all 0.3s ease-in-out';
                        setTimeout(() => errorAlert.remove(), 300);
                    }, 3000);

                    // Reset button state
                    deleteButton.disabled = false;
                    deleteButton.innerHTML = originalButtonContent;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Reset button state
                deleteButton.disabled = false;
                deleteButton.innerHTML = originalButtonContent;
                
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'mb-6 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm fade-in';
                errorAlert.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800 dark:text-red-200">Error deleting customer. Please try again.</p>
                        </div>
                    </div>`;
                
                const tableContainer = document.querySelector('.bg-white.dark\\:bg-gray-800.rounded-lg.shadow-sm.overflow-hidden');
                tableContainer.insertBefore(errorAlert, tableContainer.firstChild);

                // Remove error message after 3 seconds
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transform = 'translateY(-10px)';
                    errorAlert.style.transition = 'all 0.3s ease-in-out';
                    setTimeout(() => errorAlert.remove(), 300);
                }, 3000);
            });
        });
    });
});
</script>
@endpush

@endsection 
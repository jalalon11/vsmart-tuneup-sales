@extends('layouts.app')

@section('content')
<style>
    /* Highlight animation for the targeted customer row */
    @keyframes highlightPulse {
        0% { background-color: rgba(59, 130, 246, 0.1); }
        50% { background-color: rgba(59, 130, 246, 0.2); }
        100% { background-color: rgba(59, 130, 246, 0.1); }
    }

    /* Dark mode animation */
    @media (prefers-color-scheme: dark) {
        @keyframes highlightPulseDark {
            0% { background-color: rgba(59, 130, 246, 0.2); }
            50% { background-color: rgba(59, 130, 246, 0.3); }
            100% { background-color: rgba(59, 130, 246, 0.2); }
        }
    }

    .customer-row-highlight {
        animation: highlightPulse 2s ease-in-out infinite;
        border-left: 4px solid #3b82f6;
    }

    .dark .customer-row-highlight {
        animation: highlightPulseDark 2s ease-in-out infinite;
        border-left: 4px solid #60a5fa;
    }

    /* Customer avatar styles */
    .customer-avatar {
        @apply relative overflow-hidden;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
        color: rgba(0, 0, 0, 0.7); /* Dark text for light mode */
    }

    .customer-avatar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .dark .customer-avatar {
        background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
        box-shadow: 0 2px 10px rgba(79, 70, 229, 0.3);
        color: rgba(255, 255, 255, 0.95); /* White text for dark mode */
    }

    .dark .customer-avatar:hover {
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
    }

    /* Enhanced avatar with colored variants based on first letter */
    .avatar-a, .avatar-j, .avatar-s { background: linear-gradient(135deg, #3b82f6, #2dd4bf); }
    .avatar-b, .avatar-k, .avatar-t { background: linear-gradient(135deg, #8b5cf6, #ec4899); }
    .avatar-c, .avatar-l, .avatar-u { background: linear-gradient(135deg, #f59e0b, #ef4444); }
    .avatar-d, .avatar-m, .avatar-v { background: linear-gradient(135deg, #10b981, #3b82f6); }
    .avatar-e, .avatar-n, .avatar-w { background: linear-gradient(135deg, #6366f1, #8b5cf6); }
    .avatar-f, .avatar-o, .avatar-x { background: linear-gradient(135deg, #f97316, #f59e0b); }
    .avatar-g, .avatar-p, .avatar-y { background: linear-gradient(135deg, #ec4899, #f97316); }
    .avatar-h, .avatar-q, .avatar-z { background: linear-gradient(135deg, #14b8a6, #6366f1); }
    .avatar-i, .avatar-r, .avatar-0 { background: linear-gradient(135deg, #ef4444, #f59e0b); }

    .dark .avatar-a, .dark .avatar-j, .dark .avatar-s { background: linear-gradient(135deg, #2563eb, #0d9488); }
    .dark .avatar-b, .dark .avatar-k, .dark .avatar-t { background: linear-gradient(135deg, #7c3aed, #db2777); }
    .dark .avatar-c, .dark .avatar-l, .dark .avatar-u { background: linear-gradient(135deg, #d97706, #dc2626); }
    .dark .avatar-d, .dark .avatar-m, .dark .avatar-v { background: linear-gradient(135deg, #059669, #2563eb); }
    .dark .avatar-e, .dark .avatar-n, .dark .avatar-w { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
    .dark .avatar-f, .dark .avatar-o, .dark .avatar-x { background: linear-gradient(135deg, #ea580c, #d97706); }
    .dark .avatar-g, .dark .avatar-p, .dark .avatar-y { background: linear-gradient(135deg, #db2777, #ea580c); }
    .dark .avatar-h, .dark .avatar-q, .dark .avatar-z { background: linear-gradient(135deg, #0d9488, #4f46e5); }
    .dark .avatar-i, .dark .avatar-r, .dark .avatar-0 { background: linear-gradient(135deg, #dc2626, #d97706); }

    /* Modal animations */
    .modal-overlay {
        opacity: 0;
        transition: opacity 0.3s ease-out;
    }
    .modal-overlay.opacity-100 {
        opacity: 1;
    }
    .modal-content {
        transition: all 0.3s ease-out;
    }
</style>

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
                        <tr class="customer-row hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150 {{ request('highlight') == $customer->id ? 'customer-row-highlight' : '' }}" 
                            id="customer-{{ $customer->id }}">
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
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm h-full border border-gray-200 dark:border-gray-700">
                                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Customer Information
                                    </h4>
                                </div>

                                <div class="p-4 space-y-4">
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-name"></p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-phone"></p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Facebook Profile</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white overflow-hidden text-ellipsis" id="view-customer-facebook"></p>
                                    </div>
                                    <div class="bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Address</label>
                                        <p class="mt-1 text-base font-medium text-gray-900 dark:text-white" id="view-customer-address"></p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Devices Section -->
                        <div class="lg:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm h-full border border-gray-200 dark:border-gray-700">
                                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                    <div class="flex items-center justify-between">
                                        <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                            Devices
                                        </h4>
                                        <button type="button" 
                                            onclick="showAddDeviceForm()"
                                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Add Device
                                        </button>
                                    </div>
                                </div>

                                <!-- Add Device Form -->
                                <div id="add-device-form" class="hidden mx-4 my-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4 flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Add New Device
                                    </h3>
                                    <form id="deviceForm" class="space-y-4" action="javascript:void(0);" onsubmit="return false;">
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                            <div>
                                                <label for="new-device-brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                                                <input type="text" id="new-device-brand" name="brand" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                            <div>
                                                <label for="new-device-model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                                <input type="text" id="new-device-model" name="model" required
                                                    class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm">
                                            </div>
                                        </div>
                                        <div class="flex justify-end space-x-3">
                                            <button type="button" onclick="hideAddDeviceForm()" 
                                                class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                Cancel
                                            </button>
                                            <button type="submit" 
                                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 shadow-sm">
                                                Add Device
                                            </button>
                                        </div>
                                    </form>
                                </div>

                                <!-- Devices List -->
                                <div id="view-customer-devices" class="m-4 space-y-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4 min-h-[200px] border border-gray-200 dark:border-gray-600">
                                    <!-- Devices will be dynamically inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="bg-gray-50 dark:bg-gray-700/50 px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex justify-end">
                        <button type="button" onclick="closeViewModal()"
                            class="inline-flex justify-center items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
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
    try {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(contentId);
        const overlay = document.getElementById(overlayId);
        
        if (!modal) {
            console.error(`Modal element with ID '${modalId}' not found`);
            return;
        }
        
        if (!content) {
            console.error(`Content element with ID '${contentId}' not found`);
            return;
        }
        
        if (!overlay) {
            console.error(`Overlay element with ID '${overlayId}' not found`);
            return;
        }
        
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
    } catch (error) {
        console.error('Error showing modal:', error);
    }
}

function hideModal(modalId, contentId, overlayId) {
    try {
        const modal = document.getElementById(modalId);
        const content = document.getElementById(contentId);
        const overlay = document.getElementById(overlayId);
        
        if (!modal) {
            console.error(`Modal element with ID '${modalId}' not found`);
            return;
        }
        
        if (!content) {
            console.error(`Content element with ID '${contentId}' not found`);
            return;
        }
        
        if (!overlay) {
            console.error(`Overlay element with ID '${overlayId}' not found`);
            return;
        }
        
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
    } catch (error) {
        console.error('Error hiding modal:', error);
        // Still try to hide the modal even if animation fails
        try {
            document.getElementById(modalId).classList.add('hidden');
            document.body.style.overflow = 'auto';
        } catch (e) {}
    }
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
}

// Keep device form open after submission (don't hide it)
function resetDeviceForm() {
    // Reset form fields
    document.getElementById('new-device-brand').value = '';
    document.getElementById('new-device-model').value = '';
    document.querySelector('#add-device-form h3').textContent = 'Add New Device';
    document.querySelector('#add-device-form button[type="submit"]').textContent = 'Add Device';
    
    // Make sure the form is still visible
    document.getElementById('add-device-form').classList.remove('hidden');
}

let currentCustomerId = null;

// Function to open the customer view modal
function openViewModal(customerId) {
    currentCustomerId = customerId; // Store the customer ID
    
    // Show the modal with animation
    showModal('viewCustomerModal', 'viewCustomerModalContent', 'viewCustomerModalOverlay');
    
    // Show loading state in the devices container
    const devicesContainer = document.getElementById('view-customer-devices');
    if (devicesContainer) {
        devicesContainer.innerHTML = `
            <div class="flex justify-center items-center py-8">
                <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
    }
    
    fetch(`/customers/${customerId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Cache-Control': 'no-cache, no-store'
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
            
            // Handle devices - ensure we completely clear and rebuild the devices list
            const devicesContainer = document.getElementById('view-customer-devices');
            devicesContainer.innerHTML = '';
            
            if (customer.devices && customer.devices.length > 0) {
                const devicesList = document.createElement('div');
                devicesList.className = 'space-y-4';
                
                customer.devices.forEach(device => {
                    const deviceElement = document.createElement('div');
                    deviceElement.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200';
                    deviceElement.setAttribute('data-device-id', device.id);
                    
                    // Get status class based on device status
                    let statusClass = 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300';
                    let statusText = device.status || 'Unknown';
                    
                    if (device.status) {
                        const status = device.status.toLowerCase();
                        switch(status) {
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
                    
                    // Add the device content
                    deviceElement.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full customer-avatar avatar-${(device.brand?.charAt(0) || 'a').toLowerCase()} flex items-center justify-center text-sm font-bold">
                                    ${(device.brand?.charAt(0) || 'A').toUpperCase()}
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">${device.brand ? `${device.brand} ${device.model}` : 'Unknown Device'}</h3>
                                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">${device.brand || ''}</span>
                                        ${device.brand && device.model ? '<span>&bull;</span>' : ''}
                                        <span>${device.model || ''}</span>
                                        <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full ${statusClass}">
                                            ${statusText}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                    onclick="editDevice(${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                                    class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    onclick="createRepairForDevice(currentCustomerId, ${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                                    class="inline-flex items-center justify-center p-2 border border-indigo-500 dark:border-indigo-400 rounded-lg text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    onclick="deleteDevice(event, ${device.id})"
                                    class="inline-flex items-center justify-center p-2 border border-red-500 dark:border-red-400 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    devicesList.appendChild(deviceElement);
                });
                
                devicesContainer.appendChild(devicesList);
            } else {
                devicesContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-[180px]">
                        <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-center">No devices found for this customer.</p>
                        <button type="button" 
                            onclick="showAddDeviceForm()" 
                            class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add First Device
                        </button>
                    </div>
                `;
            }
        } else {
            console.error('Customer data not found');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const devicesContainer = document.getElementById('view-customer-devices');
        if (devicesContainer) {
            devicesContainer.innerHTML = `
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                Error loading customer data. Please try again.
                            </p>
                        </div>
                    </div>
                </div>
            `;
        }
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
    // Prevent the default form submission (though we already have onsubmit="return false" as a backup)
    e.preventDefault();
    e.stopPropagation();
    
    if (!currentCustomerId) {
        alert('Error: Customer ID not found');
        return false;
    }

    const formData = new FormData();
    formData.append('brand', document.getElementById('new-device-brand').value);
    formData.append('model', document.getElementById('new-device-model').value);
    formData.append('_token', csrfToken);

    // Show loading state on button
    const submitButton = document.querySelector('#add-device-form button[type="submit"]');
    const originalText = submitButton.textContent;
    submitButton.disabled = true;
    submitButton.innerHTML = `
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Adding...
    `;

    fetch(`/customers/${currentCustomerId}/devices`, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Cache-Control': 'no-cache, no-store'
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
                    <span class="text-sm text-green-700 dark:text-green-300">${data.message || 'Device added successfully'}</span>
                </div>
            `;
            const deviceForm = document.getElementById('add-device-form');
            deviceForm.insertBefore(successMessage, deviceForm.firstChild);
            
            // Remove success message after 3 seconds
            setTimeout(() => successMessage.remove(), 3000);
            
            // Reset form but keep it visible
            resetDeviceForm();
            
            // Restore submit button
            submitButton.disabled = false;
            submitButton.textContent = originalText;
            
            // Refresh the devices list without reopening the modal
            refreshDeviceList(currentCustomerId);
        } else {
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg';
            errorMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-red-700 dark:text-red-300">${data.message || 'Error adding device'}</span>
                </div>
            `;
            const deviceForm = document.getElementById('add-device-form');
            deviceForm.insertBefore(errorMessage, deviceForm.firstChild);
            
            // Remove error message after 3 seconds
            setTimeout(() => errorMessage.remove(), 3000);
            
            // Restore button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        
        // Show error message
        const errorMessage = document.createElement('div');
        errorMessage.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg';
        errorMessage.innerHTML = `
            <div class="flex items-center">
                <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span class="text-sm text-red-700 dark:text-red-300">Error adding device</span>
            </div>
        `;
        const deviceForm = document.getElementById('add-device-form');
        deviceForm.insertBefore(errorMessage, deviceForm.firstChild);
        
        // Remove error message after 3 seconds
        setTimeout(() => errorMessage.remove(), 3000);
        
        // Restore button state
        submitButton.disabled = false;
        submitButton.textContent = originalText;
    });
    
    // Return false to make absolutely sure the form doesn't submit
    return false;
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
    // Store the device ID being edited
    window.editingDeviceId = deviceId;
    
    // Show the add device form and populate it with current values
    showAddDeviceForm();
    
    // Update form title and button text to reflect edit mode
    document.querySelector('#add-device-form h3').textContent = 'Edit Device';
    document.querySelector('#add-device-form button[type="submit"]').textContent = 'Update Device';
    
    // Populate form fields with safe values
    document.getElementById('new-device-brand').value = brand || '';
    document.getElementById('new-device-model').value = model || '';
    
    // Update the form submission handler for editing
    const deviceForm = document.getElementById('deviceForm');
    deviceForm.onsubmit = function(e) {
        // Ensure the form doesn't trigger a page reload
        e.preventDefault();
        e.stopPropagation();
        
        const formData = new FormData();
        formData.append('_method', 'PUT');
        formData.append('brand', document.getElementById('new-device-brand').value);
        formData.append('model', document.getElementById('new-device-model').value);
        formData.append('_token', csrfToken);

        // Show loading state on button
        const submitButton = document.querySelector('#add-device-form button[type="submit"]');
        const originalText = submitButton.textContent;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Updating...
        `;

        fetch(`/devices/${deviceId}`, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken,
                'Cache-Control': 'no-cache'
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
                
                // Reset the form but keep it visible for further edits
                resetDeviceForm();
                
                // Reset the editing device ID
                window.editingDeviceId = null;
                
                // Reset the form submission handler
                deviceForm.onsubmit = null;
                
                // Restore button state
                submitButton.disabled = false;
                submitButton.textContent = 'Add Device';
                
                // Update the specific device in the UI without refreshing everything
                if (data.device) {
                    // Try to find and update the existing device element
                    updateDeviceInUI(data.device);
                } else {
                    // If the device data is not returned, refresh the entire list, but don't show the modal again since it's already shown
                    refreshDeviceList(currentCustomerId);
                }
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
                
                // Restore button state
                submitButton.disabled = false;
                submitButton.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Show error message
            const errorMessage = document.createElement('div');
            errorMessage.className = 'mb-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg';
            errorMessage.innerHTML = `
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-red-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-sm text-red-700 dark:text-red-300">Error updating device</span>
                </div>
            `;
            const deviceForm = document.getElementById('add-device-form');
            deviceForm.insertBefore(errorMessage, deviceForm.firstChild);
            
            // Remove error message after 3 seconds
            setTimeout(() => errorMessage.remove(), 3000);
            
            // Restore button state
            submitButton.disabled = false;
            submitButton.textContent = originalText;
        });
        
        // Return false to make absolutely sure no page reload occurs
        return false;
    };
}

// Function to update a specific device in the UI without refreshing everything
function updateDeviceInUI(device) {
    if (!device || !device.id) return;
    
    // Find the existing device element
    const deviceElement = document.querySelector(`[data-device-id="${device.id}"]`);
    if (!deviceElement) {
        // If the element doesn't exist, refresh the entire list
        openViewModal(currentCustomerId);
        return;
    }
    
    // Get status class based on device status
    let statusClass = 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300';
    let statusText = device.status || 'Unknown';
    
    if (device.status) {
        const status = device.status.toLowerCase();
        switch(status) {
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
    
    // Update the device element's content
    deviceElement.innerHTML = `
        <div class="flex items-center justify-between">
            <div class="flex items-start space-x-3">
                <div class="h-10 w-10 flex-shrink-0 rounded-full customer-avatar avatar-${(device.brand?.charAt(0) || 'a').toLowerCase()} flex items-center justify-center text-sm font-bold">
                    ${(device.brand?.charAt(0) || 'A').toUpperCase()}
                </div>
                <div>
                    <h3 class="text-base font-medium text-gray-900 dark:text-white">${device.brand ? `${device.brand} ${device.model}` : 'Unknown Device'}</h3>
                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">${device.brand || ''}</span>
                        ${device.brand && device.model ? '<span>&bull;</span>' : ''}
                        <span>${device.model || ''}</span>
                        <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full ${statusClass}">
                            ${statusText}
                        </span>
                    </div>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button type="button"
                    onclick="editDevice(${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                    class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                    <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </button>
                <button type="button"
                    onclick="createRepairForDevice(currentCustomerId, ${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                    class="inline-flex items-center justify-center p-2 border border-indigo-500 dark:border-indigo-400 rounded-lg text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                </button>
                <button type="button"
                    onclick="deleteDevice(event, ${device.id})"
                    class="inline-flex items-center justify-center p-2 border border-red-500 dark:border-red-400 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
        </div>
    `;
}

function deleteDevice(event, deviceId) {
    // Prevent any default action or bubbling
    event.preventDefault();
    event.stopPropagation();
    
    if (!confirm('Are you sure you want to delete this device? This action cannot be undone.')) {
        return false;
    }
    
    // Remember if the add device form was visible
    const addDeviceFormVisible = !document.getElementById('add-device-form').classList.contains('hidden');

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
            'Accept': 'application/json',
            'Cache-Control': 'no-cache, no-store'
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
            
            // Refresh the devices list without reopening the modal
            refreshDeviceList(currentCustomerId);
            
            // Restore the add device form state if it was visible
            if (addDeviceFormVisible) {
                document.getElementById('add-device-form').classList.remove('hidden');
            }
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
        
        // Restore the add device form state if it was visible
        if (addDeviceFormVisible) {
            document.getElementById('add-device-form').classList.remove('hidden');
        }
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

    // Initialize delete confirmation
    initDeleteConfirmation();

    // Handle customer highlighting
    const highlightedCustomerId = new URLSearchParams(window.location.search).get('highlight');
    if (highlightedCustomerId) {
        const customerRow = document.getElementById('customer-' + highlightedCustomerId);
        if (customerRow) {
            // Scroll to the customer row
            setTimeout(() => {
                customerRow.scrollIntoView({ behavior: 'smooth', block: 'center' });
                
                // Add a focus outline for accessibility
                customerRow.setAttribute('tabindex', '-1');
                customerRow.focus({ preventScroll: true });
                
                // Remove highlight after 5 seconds
                setTimeout(() => {
                    customerRow.classList.remove('customer-row-highlight');
                }, 5000);
            }, 500);
        }
    }

    function initDeleteConfirmation() {
        // ... existing code ...
    }
});

// Function to refresh only the device list without reopening the modal
function refreshDeviceList(customerId) {
    // Remember if the add device form was visible
    const addDeviceFormVisible = !document.getElementById('add-device-form').classList.contains('hidden');
    
    // Show loading state in the devices container
    const devicesContainer = document.getElementById('view-customer-devices');
    if (devicesContainer) {
        devicesContainer.innerHTML = `
            <div class="flex justify-center items-center py-8">
                <svg class="animate-spin h-8 w-8 text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
    }
    
    // Use a timestamp to prevent caching
    const timestamp = new Date().getTime();
    
    // Fetch the customer data with its devices
    fetch(`/customers/${customerId}?_=${timestamp}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json',
            'Cache-Control': 'no-cache, no-store, must-revalidate',
            'Pragma': 'no-cache',
            'Expires': '0'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.customer) {
            const customer = data.customer;
            
            // Only update the devices section
            const devicesContainer = document.getElementById('view-customer-devices');
            if (!devicesContainer) return;
            
            devicesContainer.innerHTML = '';
            
            if (customer.devices && customer.devices.length > 0) {
                const devicesList = document.createElement('div');
                devicesList.className = 'space-y-4';
                
                customer.devices.forEach(device => {
                    const deviceElement = document.createElement('div');
                    deviceElement.className = 'bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 border border-gray-200 dark:border-gray-700 hover:shadow-md transition-all duration-200';
                    deviceElement.setAttribute('data-device-id', device.id);
                    
                    // Get status class based on device status
                    let statusClass = 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300';
                    let statusText = device.status || 'Unknown';
                    
                    if (device.status) {
                        const status = device.status.toLowerCase();
                        switch(status) {
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
                    
                    // Add the device content
                    deviceElement.innerHTML = `
                        <div class="flex items-center justify-between">
                            <div class="flex items-start space-x-3">
                                <div class="h-10 w-10 flex-shrink-0 rounded-full customer-avatar avatar-${(device.brand?.charAt(0) || 'a').toLowerCase()} flex items-center justify-center text-sm font-bold">
                                    ${(device.brand?.charAt(0) || 'A').toUpperCase()}
                                </div>
                                <div>
                                    <h3 class="text-base font-medium text-gray-900 dark:text-white">${device.brand ? `${device.brand} ${device.model}` : 'Unknown Device'}</h3>
                                    <div class="mt-1 flex items-center space-x-2 text-sm text-gray-500 dark:text-gray-400">
                                        <span class="font-medium">${device.brand || ''}</span>
                                        ${device.brand && device.model ? '<span>&bull;</span>' : ''}
                                        <span>${device.model || ''}</span>
                                        <span class="ml-2 px-2.5 py-0.5 text-xs font-medium rounded-full ${statusClass}">
                                            ${statusText}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-center space-x-2">
                                <button type="button"
                                    onclick="editDevice(${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                                    class="inline-flex items-center justify-center p-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    onclick="createRepairForDevice(currentCustomerId, ${device.id}, '${device.brand?.replace(/'/g, "\\'")}', '${device.model?.replace(/'/g, "\\'")}')"
                                    class="inline-flex items-center justify-center p-2 border border-indigo-500 dark:border-indigo-400 rounded-lg text-sm font-medium text-indigo-600 dark:text-indigo-400 bg-white dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                </button>
                                <button type="button"
                                    onclick="deleteDevice(event, ${device.id})"
                                    class="inline-flex items-center justify-center p-2 border border-red-500 dark:border-red-400 rounded-lg text-sm font-medium text-red-600 dark:text-red-400 bg-white dark:bg-gray-800 hover:bg-red-50 dark:hover:bg-red-900/20 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    `;
                    devicesList.appendChild(deviceElement);
                });
                
                devicesContainer.appendChild(devicesList);
            } else {
                devicesContainer.innerHTML = `
                    <div class="flex flex-col items-center justify-center h-[180px]">
                        <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400 text-center">No devices found for this customer.</p>
                        <button type="button" 
                            onclick="showAddDeviceForm()" 
                            class="mt-3 inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                            </svg>
                            Add First Device
                        </button>
                    </div>
                `;
            }
            
            // Restore the add device form state if it was visible
            if (addDeviceFormVisible) {
                document.getElementById('add-device-form').classList.remove('hidden');
            }
        } else {
            console.error('Customer data not found');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        const devicesContainer = document.getElementById('view-customer-devices');
        if (devicesContainer) {
            devicesContainer.innerHTML = `
                <div class="p-4 bg-red-50 dark:bg-red-900/20 border-l-4 border-red-500 rounded-md">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-red-700 dark:text-red-200">
                                Error loading device data. Please try again.
                            </p>
                        </div>
                    </div>
                </div>
            `;
            
            // Restore the add device form state if it was visible
            if (addDeviceFormVisible) {
                document.getElementById('add-device-form').classList.remove('hidden');
            }
        }
    });
}

// Function to open repair modal with pre-selected customer and device
function createRepairForDevice(customerId, deviceId, brand, model) {
    // Close the customer view modal first
    closeViewModal();
    
    // First, set the customer ID in the hidden input
    document.getElementById('repair_customer_id').value = customerId;
    
    // Load devices for this customer
    loadCustomerDevices(customerId, deviceId);
    
    // Open the repair modal
    openRepairModal();
}

// Load devices for a specific customer and optionally select a device
function loadCustomerDevices(customerId, selectedDeviceId = null) {
    if (!customerId) return;
    
    fetch(`/api/customers/${customerId}/devices`)
        .then(response => response.json())
        .then(data => {
            if (data && data.length) {
                // Update all device selects
                const deviceSelects = document.querySelectorAll('.device-select');
                deviceSelects.forEach(select => {
                    // Clear existing options except the first one
                    while (select.options.length > 1) {
                        select.remove(1);
                    }
                    
                    // Add device options
                    data.forEach(device => {
                        const option = document.createElement('option');
                        option.value = device.id;
                        option.textContent = `${device.brand} ${device.model}`;
                        select.appendChild(option);
                    });
                    
                    // If selectedDeviceId is provided, select that device
                    if (selectedDeviceId && select.id === 'initial_device_id') {
                        select.value = selectedDeviceId;
                    }
                    
                    // Enable the select
                    select.disabled = false;
                });
            }
        })
        .catch(error => console.error('Error loading devices:', error));
}

// Function to handle service selection and update cost
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('service-select')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        if (selectedOption && selectedOption.dataset.price) {
            const costInput = e.target.closest('.repair-item').querySelector('.cost-input');
            if (costInput) {
                costInput.value = selectedOption.dataset.price;
            }
        }
    }
});

// Repair modal functions
let repairItemIndex = 1;

function openRepairModal() {
    const modal = document.getElementById('repairModal');
    const content = document.getElementById('modalContent');
    
    if (!modal || !content) {
        console.error('Repair modal elements not found');
        return;
    }
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Add a small delay to ensure the transition works
    setTimeout(() => {
        // Make overlay visible
        const overlay = modal.querySelector('.modal-overlay');
        if (overlay) overlay.classList.add('opacity-100');
        
        // Animate content from below
        content.classList.remove('opacity-0', 'translate-y-4');
        content.classList.add('opacity-100', 'translate-y-0');
    }, 10);
}

function closeRepairModal() {
    const modal = document.getElementById('repairModal');
    const content = document.getElementById('modalContent');
    
    if (!modal || !content) return;
    
    // Animate content disappearing
    content.classList.remove('opacity-100', 'translate-y-0');
    content.classList.add('opacity-0', 'translate-y-4');
    
    // Fade out overlay
    const overlay = modal.querySelector('.modal-overlay');
    if (overlay) overlay.classList.remove('opacity-100');
    
    // Hide modal after animation completes
    setTimeout(() => {
        modal.classList.add('hidden');
    }, 300);
}

function addRepairItem() {
    const template = document.getElementById('repair-item-template');
    if (!template) {
        console.error('Repair item template not found');
        return;
    }
    
    const clone = template.content.cloneNode(true);
    
    // Update indices
    clone.querySelectorAll('[name*="[INDEX]"]').forEach(element => {
        element.name = element.name.replace('INDEX', repairItemIndex);
    });
    
    // Get container and append new item
    const repairItemsContainer = document.getElementById('repair-items');
    if (repairItemsContainer) {
        repairItemsContainer.appendChild(clone);
        
        // Load devices for the new device select
        const customerId = document.getElementById('repair_customer_id').value;
        if (customerId) {
            const newDeviceSelect = document.querySelectorAll('.device-select')[repairItemIndex];
            if (newDeviceSelect) {
                loadCustomerDevices(customerId);
            }
        }
        
        repairItemIndex++;
    }
}

function removeRepairItem(button) {
    if (button) {
        const repairItem = button.closest('.repair-item');
        if (repairItem) {
            repairItem.remove();
        }
    }
}

// Setup event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Close repair modal button
    const closeBtn = document.getElementById('closeRepairModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', closeRepairModal);
    }
    
    // Close modal when clicking outside
    const repairModal = document.getElementById('repairModal');
    if (repairModal) {
        repairModal.addEventListener('click', function(e) {
            if (e.target === repairModal) {
                closeRepairModal();
            }
        });
    }
    
    // Close modal on Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && repairModal && !repairModal.classList.contains('hidden')) {
            closeRepairModal();
        }
    });
});

// Setup event handlers
document.addEventListener('DOMContentLoaded', function() {
    // Submit repair form with AJAX
    const repairForm = document.getElementById('repairForm');
    if (repairForm) {
        repairForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Show loading state on submit button
            const submitButton = repairForm.querySelector('button[type="submit"]');
            const originalButtonText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Creating...
            `;
            
            // Submit form via AJAX
            fetch(repairForm.action, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: new FormData(repairForm)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    const successAlert = document.createElement('div');
                    successAlert.className = 'fixed top-4 right-4 z-50 p-4 bg-green-100 border-l-4 border-green-500 rounded-md shadow-md fade-in';
                    successAlert.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-green-800">${data.message || 'Repair created successfully!'}</p>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(successAlert);
                    
                    // Update repairs page table if it exists
                    updateRepairsTable(data.repair);
                    
                    // Close modal
                    closeRepairModal();
                    
                    // Remove success message after 3 seconds
                    setTimeout(() => {
                        successAlert.style.opacity = '0';
                        successAlert.style.transform = 'translateY(-10px)';
                        successAlert.style.transition = 'all 0.3s ease-in-out';
                        setTimeout(() => successAlert.remove(), 300);
                    }, 3000);
                    
                    // Reset form
                    repairForm.reset();
                    
                    // Reset repair items (remove all except the first one)
                    const repairItems = document.querySelectorAll('.repair-item');
                    for (let i = 1; i < repairItems.length; i++) {
                        repairItems[i].remove();
                    }
                    repairItemIndex = 1;
                } else {
                    // Show error message
                    const errorAlert = document.createElement('div');
                    errorAlert.className = 'fixed top-4 right-4 z-50 p-4 bg-red-100 border-l-4 border-red-500 rounded-md shadow-md fade-in';
                    errorAlert.innerHTML = `
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium text-red-800">${data.message || 'Error creating repair'}</p>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(errorAlert);
                    
                    // Remove error message after 3 seconds
                    setTimeout(() => {
                        errorAlert.style.opacity = '0';
                        errorAlert.style.transform = 'translateY(-10px)';
                        errorAlert.style.transition = 'all 0.3s ease-in-out';
                        setTimeout(() => errorAlert.remove(), 300);
                    }, 3000);
                }
                
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            })
            .catch(error => {
                console.error('Error:', error);
                
                // Show error message
                const errorAlert = document.createElement('div');
                errorAlert.className = 'fixed top-4 right-4 z-50 p-4 bg-red-100 border-l-4 border-red-500 rounded-md shadow-md fade-in';
                errorAlert.innerHTML = `
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-800">An error occurred. Please try again.</p>
                        </div>
                    </div>
                `;
                document.body.appendChild(errorAlert);
                
                // Remove error message after 3 seconds
                setTimeout(() => {
                    errorAlert.style.opacity = '0';
                    errorAlert.style.transform = 'translateY(-10px)';
                    errorAlert.style.transition = 'all 0.3s ease-in-out';
                    setTimeout(() => errorAlert.remove(), 300);
                }, 3000);
                
                // Restore button state
                submitButton.disabled = false;
                submitButton.innerHTML = originalButtonText;
            });
        });
    }

    // Close repair modal button
    const closeBtn = document.getElementById('closeRepairModal');
    if (closeBtn) {
        closeBtn.addEventListener('click', function() {
            closeRepairModal();
            // Reset form
            const form = document.getElementById('repairForm');
            if (form) {
                form.reset();
            }
        });
    }
    
    // ... existing code ...
});

// Function to update the repairs table on the repairs page if it exists
function updateRepairsTable(repair) {
    // Check if we're on the repairs page or have the repairs table in view
    const repairsTableBody = document.querySelector('.repairs-page table tbody');
    if (!repairsTableBody) return; // Not on repairs page or table not found
    
    // Create a new row for the repair
    const newRow = document.createElement('tr');
    newRow.className = 'table-row-hover fade-in';
    
    // Format the date
    const createdDate = new Date(repair.created_at);
    const formattedDate = createdDate.toLocaleDateString('en-US', {
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
    
    // Get the first repair item
    const firstItem = repair.items[0];
    const customer = firstItem.device.customer;
    const device = firstItem.device;
    const service = firstItem.service;
    const status = firstItem.status;
    
    // Get status class based on repair status
    let statusClass = 'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300';
    if (status) {
        switch(status.toLowerCase()) {
            case 'completed':
                statusClass = 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300';
                break;
            case 'in_progress':
                statusClass = 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300';
                break;
            case 'pending':
                statusClass = 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300';
                break;
            case 'cancelled':
                statusClass = 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300';
                break;
        }
    }
    
    // Generate avatar color class based on customer name
    const avatarClass = `avatar-${customer.name.charAt(0).toLowerCase()}`;
    
    // Create the row HTML
    newRow.innerHTML = `
        <td class="px-6 py-4 whitespace-nowrap">
            <div class="flex items-center">
                <div class="flex-shrink-0 h-10 w-10 rounded-full customer-avatar ${avatarClass} flex items-center justify-center font-bold shadow-md">
                    ${customer.name.charAt(0).toUpperCase()}
                </div>
                <div class="ml-4">
                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                        <a href="/customers?highlight=${customer.id}" 
                           class="customer-profile-link text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline"
                           onclick="event.stopPropagation();">
                            ${customer.name}
                        </a>
                    </div>
                    <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                        <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        ${customer.phone}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 dark:text-white">
                <div class="mb-1 flex items-center">
                    <div class="flex-shrink-0 mr-2">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">
                            ${device.brand} ${device.model}
                        </span>
                        ${device.serial_number ? 
                            `<div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                S/N: ${device.serial_number}
                            </div>` : ''}
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4">
            <div class="text-sm text-gray-900 dark:text-white">
                <div class="mb-1 flex items-center">
                    <div class="flex-shrink-0 mr-2">
                        <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <span class="font-medium">${service.name}</span>
                        <div class="text-xs text-gray-500 dark:text-gray-400">
                            ₱${service.price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}
                        </div>
                    </div>
                </div>
            </div>
        </td>
        <td class="px-6 py-4 whitespace-nowrap">
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClass}">
                ${status.charAt(0).toUpperCase() + status.slice(1).replace('_', ' ')}
            </span>
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
            <div class="text-sm text-gray-900 dark:text-white">
                ${formattedDate}
            </div>

            ${status === 'pending' ? 
                `<div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 font-medium">
                    <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    Started on: ${formattedDate}
                </div>` 
            : status === 'completed' ?
                `<div class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">
                    <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Completed on: ${formattedDate}
                </div>`
            : status === 'cancelled' ?
                `<div class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">
                    <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Cancelled on: ${formattedDate}
                </div>`
            : ''
            }
        </td>
        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
            <div class="flex items-center justify-end space-x-2">
                <a href="/repairs/${repair.id}/edit" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300" title="Edit Repair">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </a>
                <form action="/repairs/${repair.id}" method="POST" class="inline-block">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" title="Delete Repair" onclick="return confirm('Are you sure you want to delete this repair?');">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </form>
            </div>
        </td>
    `;
    
    // Add the new row to the beginning of the table
    if (repairsTableBody.firstChild) {
        repairsTableBody.insertBefore(newRow, repairsTableBody.firstChild);
    } else {
        repairsTableBody.appendChild(newRow);
    }
    
    // Highlight the new row
    setTimeout(() => {
        newRow.classList.add('bg-green-50', 'dark:bg-green-900/20');
        setTimeout(() => {
            newRow.classList.remove('bg-green-50', 'dark:bg-green-900/20');
            newRow.classList.add('transition-colors', 'duration-1000');
        }, 2000);
    }, 300);
    
    // Update repair stats if they exist
    updateRepairStats();
}

// Function to update repair stats on the repairs page
function updateRepairStats() {
    const pendingCount = document.querySelector('.repair-stat-pending');
    const inProgressCount = document.querySelector('.repair-stat-in-progress');
    const completedCount = document.querySelector('.repair-stat-completed');
    const totalCount = document.querySelector('.repair-stat-total');
    
    if (pendingCount) {
        const currentCount = parseInt(pendingCount.textContent);
        pendingCount.textContent = (currentCount + 1).toString();
    }
    
    if (totalCount) {
        const currentCount = parseInt(totalCount.textContent);
        totalCount.textContent = (currentCount + 1).toString();
    }
}

// ... existing code ...

// Initialize event handlers
function initializeEventHandlers() {
    // Close repair modal button
    const closeBtn = document.getElementById('closeRepairModal');
    const repairModal = document.getElementById('repairModal');
    const modalContent = document.getElementById('modalContent');

    if (closeBtn && repairModal && modalContent) {
        // Create new button to avoid duplicate listeners
        const newCloseBtn = closeBtn.cloneNode(true);
        closeBtn.parentNode.replaceChild(newCloseBtn, closeBtn);

        // Function to close modal
        function closeRepairModal() {
            // Animate content disappearing
            modalContent.classList.remove('opacity-100', 'translate-y-0');
            modalContent.classList.add('opacity-0', 'translate-y-4');
            
            // Fade out overlay
            const overlay = repairModal.querySelector('.modal-overlay');
            if (overlay) overlay.classList.remove('opacity-100');
            
            // Hide modal after animation completes
            setTimeout(() => {
                repairModal.classList.add('hidden');
                // Reset form
                const form = document.getElementById('repairForm');
                if (form) {
                    form.reset();
                }
            }, 300);
        }

        // Add click event to new button
        newCloseBtn.addEventListener('click', closeRepairModal);
        
        // Close modal when clicking outside
        repairModal.addEventListener('click', function(e) {
            if (e.target === repairModal) {
                closeRepairModal();
            }
        });
        
        // Close modal on Escape key
        const escapeHandler = function(e) {
            if (e.key === 'Escape' && !repairModal.classList.contains('hidden')) {
                closeRepairModal();
            }
        };
        
        // Remove existing keydown listener and add new one
        document.removeEventListener('keydown', escapeHandler);
        document.addEventListener('keydown', escapeHandler);
    }
}

// Initialize when the document is ready
document.addEventListener('DOMContentLoaded', initializeEventHandlers);

// Also initialize when the page content changes (for navigation)
document.addEventListener('turbo:render', initializeEventHandlers);
document.addEventListener('turbolinks:load', initializeEventHandlers);
</script>
@endpush

<!-- Repair Modal -->
<div id="repairModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity modal-overlay">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-800 opacity-75"></div>
        </div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        
        <div id="modalContent" class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 modal-content">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 modal-header">
                <div class="sm:flex sm:items-start">
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">New Repair</h3>
                        
                        <!-- Form will be here -->
                        <form id="repairForm" action="{{ route('repairs.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <!-- Customer Selection (Hidden as it will be pre-selected) -->
                            <input type="hidden" name="customer_id" id="repair_customer_id" value="">

                            <div id="repair-items">
                                <!-- Template for repair item -->
                                <template id="repair-item-template">
                                    <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Device & Service</h3>
                                            <button type="button" onclick="removeRepairItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Device Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                                                <select name="items[INDEX][device_id]" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select">
                                                    <option value="">Select a device</option>
                                                </select>
                                            </div>

                                            <!-- Service Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                                                <select name="items[INDEX][service_id]" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select">
                                                    <option value="">Select a service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                            {{ $service->name }} - ₱{{ number_format($service->price, 2) }} - {{ $service->category->name ?? 'Uncategorized' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Cost -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                                    </div>
                                                    <input type="number" step="0.01" min="0" name="items[INDEX][cost]" required
                                                        class="pl-8 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm cost-input">
                                                </div>
                                            </div>

                                            <!-- Status -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                                <select name="items[INDEX][status]" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                    <option value="pending">Pending</option>
                                                    <option value="completed">Completed</option>
                                                    <option value="cancelled">Cancelled</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Initial repair item -->
                                <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Device & Service</h3>
                                        <button type="button" onclick="removeRepairItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hidden">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Device Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                                            <select name="items[0][device_id]" id="initial_device_id" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select">
                                                <option value="">Select a device</option>
                                            </select>
                                        </div>

                                        <!-- Service Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                                            <select name="items[0][service_id]" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select">
                                                <option value="">Select a service</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                        {{ $service->name }} - ₱{{ number_format($service->price, 2) }} - {{ $service->category->name ?? 'Uncategorized' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Cost -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                                </div>
                                                <input type="number" step="0.01" min="0" name="items[0][cost]" required
                                                    class="pl-8 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm cost-input">
                                            </div>
                                        </div>

                                        <!-- Status -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                            <select name="items[0][status]" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                                <option value="pending">Pending</option>
                                                <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Add Item Button -->
                            <div class="flex justify-center">
                                <button type="button" id="add-item-btn" onclick="addRepairItem()"
                                    class="inline-flex items-center px-4 py-2 border border-indigo-300 dark:border-indigo-700 text-sm font-medium rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Add Another Item
                                </button>
                            </div>

                            <!-- Payment Information -->
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 mt-4">
                                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Payment Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Hidden date fields -->
                                    <input type="hidden" name="started_at" value="">
                                    <input type="hidden" name="completed_at" value="">
                                    
                                    <!-- Payment Method -->
                                    <div>
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                                        <select name="payment_method" id="payment_method" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="cash">Cash</option>
                                            <option value="gcash">GCash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Notes -->
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 mt-4">
                                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Additional Notes</h2>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                                        placeholder="Enter any additional notes about this repair..."></textarea>
                                </div>
                            </div>

                            <div class="flex justify-end space-x-3 mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                <button type="button" id="closeRepairModal" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Cancel
                                </button>
                                <button type="submit" class="px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Create Repair
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection 
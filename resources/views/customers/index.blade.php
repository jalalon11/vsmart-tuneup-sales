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

    /* Status animations */
    @keyframes flashPending {
        0% { background-color: rgba(251, 191, 36, 0.1); }
        50% { background-color: rgba(251, 191, 36, 0.3); }
        100% { background-color: rgba(251, 191, 36, 0.1); }
    }

    @keyframes flashInProgress {
        0% { background-color: rgba(59, 130, 246, 0.1); }
        50% { background-color: rgba(59, 130, 246, 0.3); }
        100% { background-color: rgba(59, 130, 246, 0.1); }
    }

    @keyframes flashCompleted {
        0% { background-color: rgba(16, 185, 129, 0.1); }
        50% { background-color: rgba(16, 185, 129, 0.3); }
        100% { background-color: rgba(16, 185, 129, 0.1); }
    }

    @keyframes flashCancelled {
        0% { background-color: rgba(239, 68, 68, 0.1); }
        50% { background-color: rgba(239, 68, 68, 0.3); }
        100% { background-color: rgba(239, 68, 68, 0.1); }
    }

    /* Dark mode animations */
    @media (prefers-color-scheme: dark) {
        @keyframes flashPendingDark {
            0% { background-color: rgba(251, 191, 36, 0.2); }
            50% { background-color: rgba(251, 191, 36, 0.4); }
            100% { background-color: rgba(251, 191, 36, 0.2); }
        }

        @keyframes flashInProgressDark {
            0% { background-color: rgba(59, 130, 246, 0.2); }
            50% { background-color: rgba(59, 130, 246, 0.4); }
            100% { background-color: rgba(59, 130, 246, 0.2); }
        }

        @keyframes flashCompletedDark {
            0% { background-color: rgba(16, 185, 129, 0.2); }
            50% { background-color: rgba(16, 185, 129, 0.4); }
            100% { background-color: rgba(16, 185, 129, 0.2); }
        }

        @keyframes flashCancelledDark {
            0% { background-color: rgba(239, 68, 68, 0.2); }
            50% { background-color: rgba(239, 68, 68, 0.4); }
            100% { background-color: rgba(239, 68, 68, 0.2); }
        }
    }

    .status-pending {
        animation: flashPending 2s ease-in-out infinite;
        border-left: 4px solid #F59E0B;
    }

    .status-in-progress {
        animation: flashInProgress 2s ease-in-out infinite;
        border-left: 4px solid #3B82F6;
    }

    .status-completed {
        animation: flashCompleted 2s ease-in-out infinite;
        border-left: 4px solid #10B981;
    }

    .status-cancelled {
        animation: flashCancelled 2s ease-in-out infinite;
        border-left: 4px solid #EF4444;
    }

    .dark .status-pending {
        animation: flashPendingDark 2s ease-in-out infinite;
    }

    .dark .status-in-progress {
        animation: flashInProgressDark 2s ease-in-out infinite;
    }

    .dark .status-completed {
        animation: flashCompletedDark 2s ease-in-out infinite;
    }

    .dark .status-cancelled {
        animation: flashCancelledDark 2s ease-in-out infinite;
    }
</style>

<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <div class="flex items-center mb-2">
                    <img class="h-8 w-8 text-blue-500" src="./img/customer.png" alt="Mobile Icon">
                    <h1 class="text-4xl font-bold text-gray-900 dark:text-white leading-tight ml-3">Manage Customers</h1>
                </div>
                <p class="text-sm text-gray-600 dark:text-gray-400">
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

    <!-- Alerts Container -->
    <div id="alerts-container" class="mb-6"></div>

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
                                    onclick="openDeviceModal(this.dataset.customerId)"
                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    Devices
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
<div id="customerModal" class="fixed inset-0 z-40 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
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
<div id="viewCustomerModal" class="fixed inset-0 z-40 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="viewCustomerModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="viewCustomerModalContent">
            <div class="bg-white dark:bg-gray-800">
                <!-- Header with Avatar -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-start justify-between">
                        <div class="flex items-center space-x-4">
                            <div class="h-12 w-12 rounded-full customer-avatar flex items-center justify-center text-xl font-bold" id="view-customer-avatar">
                                <!-- First letter will be inserted here -->
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="view-customer-name">Loading...</h3>
                                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Customer Profile</p>
                            </div>
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
                        <!-- Customer Information Card -->
                        <div class="lg:col-span-1">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Contact Details
                                    </h4>
                                </div>

                                <div class="p-4 space-y-4">
                                    <div class="space-y-1">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Phone</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <span id="view-customer-phone">Loading...</span>
                                        </p>
                                    </div>
                                    
                                    <div class="space-y-1">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Facebook Profile</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-gray-400" fill="currentColor" viewBox="0 0 24 24">
                                                <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                            </svg>
                                            <span id="view-customer-facebook" class="truncate">Loading...</span>
                                        </p>
                                    </div>

                                    <div class="space-y-1">
                                        <label class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Address</label>
                                        <p class="text-sm font-medium text-gray-900 dark:text-white flex items-start">
                                            <svg class="w-4 h-4 mr-2 mt-0.5 text-gray-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span id="view-customer-address" class="whitespace-pre-wrap">Loading...</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Devices List Card -->
                        <div class="lg:col-span-2">
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700">
                                <div class="border-b border-gray-200 dark:border-gray-700 p-4">
                                    <h4 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                                        <svg class="w-5 h-5 mr-2 text-blue-500 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                        Registered Devices
                                    </h4>
                                </div>

                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700/50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Device</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Repair</th>
                                            </tr>
                                        </thead>
                                        <tbody id="view-customer-devices" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            <tr>
                                                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                                    Loading devices...
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
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
<div id="editCustomerModal" class="fixed inset-0 z-40 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="editCustomerModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="editCustomerModalContent">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="modal-title">Edit Customer</h3>
                        
                        <!-- Edit Customer Form -->
                        <form id="editCustomerForm" class="space-y-4">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label for="edit-name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Name</label>
                                    <input type="text" name="name" id="edit-name" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="edit-phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                                    <input type="text" name="phone" id="edit-phone"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="edit-facebook" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Facebook URL</label>
                                    <input type="url" name="facebook_url" id="edit-facebook"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="edit-address" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Address</label>
                                    <textarea name="address" id="edit-address" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="submit" form="editCustomerForm"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Update Customer
                </button>
                <button type="button" onclick="closeEditModal()"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Global state
let selectedCustomerId = null;

// Get CSRF token from meta tag
const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// Utility functions for showing messages
function showSuccessMessage(message) {
    const alertsContainer = document.getElementById('alerts-container');
    alertsContainer.innerHTML = `
        <div class="p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800 dark:text-green-200">${message}</p>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        alertsContainer.innerHTML = '';
    }, 5000);
}

function showErrorMessage(message) {
    const alertsContainer = document.getElementById('alerts-container');
    alertsContainer.innerHTML = `
        <div class="p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-500 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-red-800 dark:text-red-200">${message}</p>
                </div>
            </div>
        </div>
    `;
    
    setTimeout(() => {
        alertsContainer.innerHTML = '';
    }, 5000);
}

// Function to show modals
function showModal(modalId, contentId, overlayId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);
    const content = document.getElementById(contentId);
    
    if (modal && overlay && content) {
        modal.classList.remove('hidden');
        
        // Add entry animation
        overlay.classList.add('opacity-0');
        content.classList.add('opacity-0', 'translate-y-4');
        
        // Force a reflow to enable the transition
        void overlay.offsetWidth;
        
        // Add transition classes
        overlay.classList.add('transition-opacity', 'duration-300');
        content.classList.add('transition-all', 'duration-300');
        
        // Show overlay and content
        overlay.classList.remove('opacity-0');
        content.classList.remove('opacity-0', 'translate-y-4');
    }
}

// Function to hide modals
function hideModal(modalId, contentId, overlayId) {
    const modal = document.getElementById(modalId);
    const overlay = document.getElementById(overlayId);
    const content = document.getElementById(contentId);
    
    if (modal && overlay && content) {
        // Add exit animation
        overlay.classList.add('transition-opacity', 'duration-200');
        content.classList.add('transition-all', 'duration-200');
        
        overlay.classList.add('opacity-0');
        content.classList.add('opacity-0', 'translate-y-4');
        
        // Hide modal after animation
        setTimeout(() => {
            modal.classList.add('hidden');
            overlay.classList.remove('transition-opacity', 'duration-200', 'opacity-0');
            content.classList.remove('transition-all', 'duration-200', 'opacity-0', 'translate-y-4');
        }, 200);
    }
}

// Add Customer modal functions
function openModal() {
    showModal('customerModal', 'customerModalContent', 'customerModalOverlay');
    
    // Reset form when modal is opened
    document.getElementById('customerForm').reset();
}

function closeModal() {
    hideModal('customerModal', 'customerModalContent', 'customerModalOverlay');
}

// Make functions globally available for SPA navigation
window.showModal = showModal;
window.hideModal = hideModal;
window.openModal = openModal;
window.closeModal = closeModal;
window.openViewModal = openViewModal;
window.closeViewModal = closeViewModal;
window.openDeviceModal = openDeviceModal;
window.closeDeviceModal = closeDeviceModal;
window.openAddDeviceModal = openAddDeviceModal;
window.closeAddDeviceModal = closeAddDeviceModal;
window.openEditModal = openEditModal;
window.closeEditModal = closeEditModal;

// All of these functions will be defined later in the script
// We'll assign them after they are defined
document.addEventListener('DOMContentLoaded', function() {
    // Wait until all functions are defined
    setTimeout(() => {
        if (typeof openViewModal === 'function') window.openViewModal = openViewModal;
        if (typeof closeViewModal === 'function') window.closeViewModal = closeViewModal;
        if (typeof openDeviceModal === 'function') window.openDeviceModal = openDeviceModal;
        if (typeof closeDeviceModal === 'function') window.closeDeviceModal = closeDeviceModal;
        if (typeof openAddDeviceModal === 'function') window.openAddDeviceModal = openAddDeviceModal;
        if (typeof closeAddDeviceModal === 'function') window.closeAddDeviceModal = closeAddDeviceModal;
        if (typeof openEditModal === 'function') window.openEditModal = openEditModal;
        if (typeof closeEditModal === 'function') window.closeEditModal = closeEditModal;
    }, 100);
});

// Device modal functions
function openDeviceModal(customerId) {
    selectedCustomerId = customerId;
    showModal('deviceModal', 'deviceModalContent', 'deviceModalOverlay');
    loadCustomerDevices(customerId, 'devices-list');
}

function closeDeviceModal() {
    hideModal('deviceModal', 'deviceModalContent', 'deviceModalOverlay');
}

function openAddDeviceForm() {
    showModal('addDeviceModal', 'addDeviceModalContent', 'addDeviceModalOverlay');
}

function closeAddDeviceModal() {
    hideModal('addDeviceModal', 'addDeviceModalContent', 'addDeviceModalOverlay');
    document.getElementById('addDeviceForm').reset();
}

// Device management functions
function loadCustomerDevices(customerId, container = 'devices-list') {
    const devicesList = document.getElementById(container);
    if (!devicesList) {
        console.error(`Devices list container '${container}' not found`);
        return;
    }

    // Show loading state
    devicesList.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;

    console.log('Fetching devices for customer:', customerId);

    fetch(`/api/customers/${customerId}/devices`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        console.log('Received data:', data);
        devicesList.innerHTML = '';

        if (!data || data.length === 0) {
            devicesList.innerHTML = `
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No devices found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding a new device.</p>
                    <button type="button" onclick="openAddDeviceForm()" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Device
                    </button>
                </div>
            `;
            return;
        }

        data.forEach(device => {
            const statusClass = {
                'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                'in_progress': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'completed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'no_repairs': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
            }[device.status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';

            const deviceElement = document.createElement('div');
            deviceElement.className = 'bg-white dark:bg-gray-700 shadow rounded-lg p-4 mb-4';
            deviceElement.innerHTML = `
                <div class="flex flex-col space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">${device.brand} ${device.model}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                    ${device.status.replace('_', ' ').charAt(0).toUpperCase() + device.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editDevice(${device.id})" 
                                class="inline-flex items-center px-3 py-1.5 border border-indigo-300 dark:border-indigo-700 text-sm font-medium rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Edit
                            </button>
                            <button onclick="deleteDevice(${device.id})" 
                                class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-700 text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/50 hover:bg-red-100 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete
                            </button>
                        </div>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <div class="grid grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Pending:</span>
                                <span class="ml-2 font-medium text-yellow-600 dark:text-yellow-400" id="pending-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">In Progress:</span>
                                <span class="ml-2 font-medium text-blue-600 dark:text-blue-400" id="in-progress-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Completed:</span>
                                <span class="ml-2 font-medium text-green-600 dark:text-green-400" id="completed-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Cancelled:</span>
                                <span class="ml-2 font-medium text-red-600 dark:text-red-400" id="cancelled-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            devicesList.appendChild(deviceElement);

            // Update the counts after a short delay to simulate loading
            setTimeout(() => {
                // Helper function to create check icon
                const createCheckIcon = () => `
                    <svg class="inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                `;

                // Helper function to create loading spinner
                const createLoadingSpinner = () => `
                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                `;

                // Update pending count
                const pendingCount = document.getElementById(`pending-count-${device.id}`);
                pendingCount.innerHTML = device.pending_repairs_count > 0 ? createCheckIcon() : createLoadingSpinner();
                pendingCount.className = `ml-2 font-medium ${device.pending_repairs_count > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-400 dark:text-gray-500'}`;

                // Update in progress count
                const inProgressCount = document.getElementById(`in-progress-count-${device.id}`);
                inProgressCount.innerHTML = device.in_progress_repairs_count > 0 ? createCheckIcon() : createLoadingSpinner();
                inProgressCount.className = `ml-2 font-medium ${device.in_progress_repairs_count > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500'}`;

                // Update completed count
                const completedCount = document.getElementById(`completed-count-${device.id}`);
                completedCount.innerHTML = device.completed_repairs_count > 0 ? createCheckIcon() : createLoadingSpinner();
                completedCount.className = `ml-2 font-medium ${device.completed_repairs_count > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-400 dark:text-gray-500'}`;

                // Update cancelled count
                const cancelledCount = document.getElementById(`cancelled-count-${device.id}`);
                cancelledCount.innerHTML = device.cancelled_repairs_count > 0 ? createCheckIcon() : createLoadingSpinner();
                cancelledCount.className = `ml-2 font-medium ${device.cancelled_repairs_count > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-400 dark:text-gray-500'}`;
            }, 1000);
        });
    })
    .catch(error => {
        console.error('Error loading devices:', error);
        devicesList.innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-500 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-red-800 dark:text-red-200">Error loading devices</h3>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">${error.message || 'An unexpected error occurred'}</p>
                <button type="button" onclick="loadCustomerDevices(${customerId}, '${container}')" 
                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
                </button>
            </div>
        `;
    });
}

function handleAddDevice(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch(`/customers/${selectedCustomerId}/devices`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
        },
        body: JSON.stringify({
            brand: formData.get('brand'),
            model: formData.get('model')
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeAddDeviceModal();
            // Reload devices in both containers
            loadCustomerDevices(selectedCustomerId, 'devices-list');
            loadCustomerDevices(selectedCustomerId, 'view-customer-devices');
            showSuccessMessage(data.message);
            // Return to Customer Profile Modal
            openViewModal(selectedCustomerId);
        } else {
            showErrorMessage(data.message || 'Error adding device');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error adding device');
    });
}

function deleteDevice(deviceId) {
    if (!confirm('Are you sure you want to delete this device?')) {
        return;
    }

    fetch(`/devices/${deviceId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Reload devices in both containers
            loadCustomerDevices(selectedCustomerId, 'devices-list');
            loadCustomerDevices(selectedCustomerId, 'view-customer-devices');
            showSuccessMessage('Device deleted successfully');
        } else {
            showErrorMessage(data.message || 'Error deleting device');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error deleting device');
    });
}

function editDevice(deviceId) {
    fetch(`/devices/${deviceId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const device = data.device;
        document.getElementById('edit-device-brand').value = device.brand;
        document.getElementById('edit-device-model').value = device.model;
        document.getElementById('editDeviceForm').action = `/devices/${deviceId}`;
        // Add method override for PUT request
        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'PUT';
        document.getElementById('editDeviceForm').appendChild(methodInput);
        showModal('editDeviceModal', 'editDeviceModalContent', 'editDeviceModalOverlay');
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error loading device details');
    });
}

// Form handling functions
function handleCustomerFormSubmit(event) {
    event.preventDefault();
    const form = event.target;
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': csrfToken,
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            closeModal();
            showSuccessMessage(data.message);
            setTimeout(() => window.location.reload(), 1000);
        } else {
            showErrorMessage(data.message || 'Error creating customer');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error creating customer');
    });
}

// Initialize page
document.addEventListener('DOMContentLoaded', function() {
    // Handle Laravel session flash messages
    const successMessage = '{{ session('success') }}';
    const errorMessage = '{{ session('error') }}';
    
    if (successMessage) {
        showSuccessMessage(successMessage);
    }
    
    if (errorMessage) {
        showErrorMessage(errorMessage);
    }

    // Initialize form event listeners
    const customerForm = document.getElementById('customerForm');
    if (customerForm) {
        customerForm.addEventListener('submit', handleCustomerFormSubmit);
    }

    // Initialize edit device form
    const editDeviceForm = document.getElementById('editDeviceForm');
    if (editDeviceForm) {
        editDeviceForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            formData.append('_method', 'PUT'); // Add method override for PUT request

            fetch(this.action, {
                method: 'POST', // We use POST but override with PUT
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    hideModal('editDeviceModal', 'editDeviceModalContent', 'editDeviceModalOverlay');
                    // Reload devices in both containers
                    loadCustomerDevices(selectedCustomerId, 'devices-list');
                    loadCustomerDevices(selectedCustomerId, 'view-customer-devices');
                    showSuccessMessage('Device updated successfully');
                    // Don't automatically open any other modal after update
                } else {
                    showErrorMessage(data.message || 'Error updating device');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error updating device');
            });
        });
    }

    // Initialize delete buttons
    document.querySelectorAll('.delete-customer-form').forEach(form => {
        form.addEventListener('submit', function(event) {
            event.preventDefault();
            if (!confirm('Are you sure you want to delete this customer?')) {
                return;
            }

            const formData = new FormData(this);
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.reload();
                } else {
                    showErrorMessage(data.message || 'Error deleting customer');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error deleting customer');
            });
        });
    });

    // Initialize modal close events
    const modalIds = ['customerModal', 'deviceModal', 'addDeviceModal'];
    modalIds.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (modal) {
            modal.addEventListener('click', function(event) {
                if (event.target === this) {
                    switch(modalId) {
                        case 'customerModal':
                            closeModal();
                            break;
                        case 'deviceModal':
                            closeDeviceModal();
                            break;
                        case 'addDeviceModal':
                            closeAddDeviceModal();
                            break;
                    }
                }
            });
        }
    });

    // Close modals with escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeDeviceModal();
            closeAddDeviceModal();
        }
    });
});

// View modal functions
function openViewModal(customerId) {
    selectedCustomerId = customerId;
    showModal('viewCustomerModal', 'viewCustomerModalContent', 'viewCustomerModalOverlay');
    
    // Show loading state
    document.getElementById('view-customer-name').textContent = 'Loading...';
    document.getElementById('view-customer-phone').textContent = 'Loading...';
    document.getElementById('view-customer-facebook').textContent = 'Loading...';
    document.getElementById('view-customer-address').textContent = 'Loading...';
    
    // Fetch customer details
    fetch(`/customers/${customerId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        const customer = data.customer;
        
        // Update customer information
        document.getElementById('view-customer-name').textContent = customer.name;
        document.getElementById('view-customer-phone').textContent = customer.phone || 'Not provided';
        document.getElementById('view-customer-facebook').textContent = customer.facebook_url || 'Not provided';
        document.getElementById('view-customer-address').textContent = customer.address || 'Not provided';
        
        // Update avatar with first letter and appropriate class
        const avatarElement = document.getElementById('view-customer-avatar');
        const firstLetter = customer.name.charAt(0).toLowerCase();
        avatarElement.textContent = customer.name.charAt(0).toUpperCase();
        
        // Remove any existing avatar classes
        avatarElement.className = avatarElement.className.replace(/avatar-[a-z0-9]/g, '');
        // Add new avatar class
        avatarElement.classList.add(`avatar-${firstLetter}`);
        
        // Load customer devices in the profile view
        loadCustomerProfileDevices(customerId);
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error loading customer details');
    });
}

function closeViewModal() {
    hideModal('viewCustomerModal', 'viewCustomerModalContent', 'viewCustomerModalOverlay');
}

// Function to open edit modal
function openEditModal(customerId) {
    selectedCustomerId = customerId;
    
    // Show loading state
    const modalContent = document.getElementById('editCustomerModalContent');
    const form = document.getElementById('editCustomerForm');
    
    // Show the modal first
    showModal('editCustomerModal', 'editCustomerModalContent', 'editCustomerModalOverlay');
    
    // Fetch customer data
    fetch(`/customers/${customerId}/edit`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Update form action
        form.action = `/customers/${customerId}`;
        
        // Fill form fields
        document.getElementById('edit-name').value = data.customer.name;
        document.getElementById('edit-phone').value = data.customer.phone || '';
        document.getElementById('edit-facebook').value = data.customer.facebook_url || '';
        document.getElementById('edit-address').value = data.customer.address || '';
    })
    .catch(error => {
        console.error('Error:', error);
        showErrorMessage('Error loading customer data');
        closeEditModal();
    });
}

// Function to close edit modal
function closeEditModal() {
    hideModal('editCustomerModal', 'editCustomerModalContent', 'editCustomerModalOverlay');
    selectedCustomerId = null;
    const form = document.getElementById('editCustomerForm');
    if (form) form.reset();
}

// Initialize edit form submission
document.addEventListener('DOMContentLoaded', function() {
    const editForm = document.getElementById('editCustomerForm');
    if (editForm) {
        editForm.addEventListener('submit', function(event) {
            event.preventDefault();
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeEditModal();
                    showSuccessMessage(data.message);
                    setTimeout(() => window.location.reload(), 1000);
                } else {
                    showErrorMessage(data.message || 'Error updating customer');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error updating customer');
            });
        });
    }
});

// Add function to load devices in customer profile
function loadCustomerProfileDevices(customerId) {
    fetch(`/api/customers/${customerId}/devices`, {
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        const deviceTableBody = document.getElementById('view-customer-devices');
        
        if (data && data.length > 0) {
            deviceTableBody.innerHTML = data.map(device => `
                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 h-8 w-8 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300">
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    ${device.brand} ${device.model}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    Added ${new Date(device.created_at).toLocaleDateString()}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            ${device.status === 'received' ? 'bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300' :
                            device.status === 'in_repair' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300' :
                            device.status === 'completed' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' :
                            'bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300'}">
                            ${device.status ? device.status.replace('_', ' ').charAt(0).toUpperCase() + device.status.slice(1) : 'N/A'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        ${device.latest_repair ? `
                            <div class="text-sm text-gray-900 dark:text-white">
                                ${new Date(device.latest_repair.created_at).toLocaleDateString()}
                            </div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                ${device.latest_repair.status.charAt(0).toUpperCase() + device.latest_repair.status.slice(1)}
                            </div>
                        ` : `
                            <div class="text-sm text-gray-500 dark:text-gray-400">No repairs</div>
                        `}
                    </td>
                </tr>
            `).join('');
        } else {
            deviceTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                        No devices found
                    </td>
                </tr>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        document.getElementById('view-customer-devices').innerHTML = `
            <tr>
                <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                    Error loading devices. Please try again.
                </td>
            </tr>
        `;
    });
}

// Device Management Modal functions
function openDeviceModal(customerId) {
    selectedCustomerId = customerId;
    showModal('deviceModal', 'deviceModalContent', 'deviceModalOverlay');
    loadCustomerDevices(customerId, 'devices-list');
}

function closeDeviceManagementModal() {
    hideModal('deviceManagementModal', 'deviceManagementModalContent', 'deviceManagementModalOverlay');
    selectedCustomerId = null;
}

// Device management functions
function loadCustomerDevices(customerId, container = 'device-management-list') {
    const devicesList = document.getElementById(container);
    if (!devicesList) {
        console.error(`Devices list container '${container}' not found`);
        return;
    }

    // Show loading state
    devicesList.innerHTML = `
        <div class="flex justify-center items-center py-8">
            <svg class="animate-spin h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    `;

    fetch(`/api/customers/${customerId}/devices`, {
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        credentials: 'same-origin'
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => Promise.reject(err));
        }
        return response.json();
    })
    .then(data => {
        devicesList.innerHTML = '';

        if (!data || data.length === 0) {
            devicesList.innerHTML = `
                <div class="text-center py-8">
                    <div class="mb-4">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No devices found</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by adding a new device.</p>
                    <button type="button" onclick="openAddDeviceForm()" 
                        class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Add New Device
                    </button>
                </div>
            `;
            return;
        }

        data.forEach(device => {
            const statusClass = {
                'pending': 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
                'in_progress': 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                'completed': 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                'cancelled': 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                'no_repairs': 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
            }[device.status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200';

            const deviceElement = document.createElement('div');
            deviceElement.className = 'bg-white dark:bg-gray-700 shadow rounded-lg p-4 mb-4';
            deviceElement.innerHTML = `
                <div class="flex flex-col space-y-4">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center space-x-3">
                            <div class="flex-shrink-0">
                                <div class="h-10 w-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center">
                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">${device.brand} ${device.model}</h4>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${statusClass}">
                                    ${device.status.replace('_', ' ').charAt(0).toUpperCase() + device.status.slice(1).replace('_', ' ')}
                                </span>
                            </div>
                        </div>
                        <div class="flex space-x-2">
                            <button onclick="editDevice(${device.id})" 
                                class="inline-flex items-center px-3 py-1.5 border border-indigo-300 dark:border-indigo-700 text-sm font-medium rounded-md text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-indigo-900/50 hover:bg-indigo-100 dark:hover:bg-indigo-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Edit
                            </button>
                            <button onclick="deleteDevice(${device.id})" 
                                class="inline-flex items-center px-3 py-1.5 border border-red-300 dark:border-red-700 text-sm font-medium rounded-md text-red-700 dark:text-red-300 bg-red-50 dark:bg-red-900/50 hover:bg-red-100 dark:hover:bg-red-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                                Delete
                            </button>
                        </div>
                    </div>
                   <!-- <div class="border-t border-gray-200 dark:border-gray-600 pt-4">
                        <div class="grid grid-cols-4 gap-4 text-sm">
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Pending:</span>
                                <span class="ml-2 font-medium text-yellow-600 dark:text-yellow-400" id="pending-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">In Progress:</span>
                                <span class="ml-2 font-medium text-blue-600 dark:text-blue-400" id="in-progress-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Completed:</span>
                                <span class="ml-2 font-medium text-green-600 dark:text-green-400" id="completed-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-500 dark:text-gray-400">Cancelled:</span>
                                <span class="ml-2 font-medium text-red-600 dark:text-red-400" id="cancelled-count-${device.id}">
                                    <svg class="inline-block h-4 w-4 animate-spin" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div> -->
                </div>
            `;
            devicesList.appendChild(deviceElement);

            // Update the counts after a short delay to simulate loading
            setTimeout(() => {
                // Helper function to create check icon
                const createCheckIcon = (color) => `
                    <svg class="inline-block h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                `;

                // Update pending count
                const pendingCount = document.getElementById(`pending-count-${device.id}`);
                pendingCount.innerHTML = device.pending_repairs_count > 0 ? createCheckIcon() : '0';
                pendingCount.className = `ml-2 font-medium ${device.pending_repairs_count > 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-gray-500 dark:text-gray-400'}`;

                // Update in progress count
                const inProgressCount = document.getElementById(`in-progress-count-${device.id}`);
                inProgressCount.innerHTML = device.in_progress_repairs_count > 0 ? createCheckIcon() : '0';
                inProgressCount.className = `ml-2 font-medium ${device.in_progress_repairs_count > 0 ? 'text-blue-600 dark:text-blue-400' : 'text-gray-500 dark:text-gray-400'}`;

                // Update completed count
                const completedCount = document.getElementById(`completed-count-${device.id}`);
                completedCount.innerHTML = device.completed_repairs_count > 0 ? createCheckIcon() : '0';
                completedCount.className = `ml-2 font-medium ${device.completed_repairs_count > 0 ? 'text-green-600 dark:text-green-400' : 'text-gray-500 dark:text-gray-400'}`;

                // Update cancelled count
                const cancelledCount = document.getElementById(`cancelled-count-${device.id}`);
                cancelledCount.innerHTML = device.cancelled_repairs_count > 0 ? createCheckIcon() : '0';
                cancelledCount.className = `ml-2 font-medium ${device.cancelled_repairs_count > 0 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400'}`;
            }, 1000);
        });
    })
    .catch(error => {
        console.error('Error loading devices:', error);
        devicesList.innerHTML = `
            <div class="text-center py-8">
                <div class="text-red-500 mb-4">
                    <svg class="mx-auto h-12 w-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="mt-2 text-sm font-medium text-red-800 dark:text-red-200">Error loading devices</h3>
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">${error.message || 'An unexpected error occurred'}</p>
                <button type="button" onclick="loadCustomerDevices(${customerId}, '${container}')" 
                    class="mt-4 inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Try Again
                </button>
            </div>
        `;
    });
}
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
                                                    <!-- <option value="completed">Completed</option>
                                                    <option value="cancelled">Cancelled</option> -->
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
                                                <!-- <option value="completed">Completed</option>
                                                <option value="cancelled">Cancelled</option> -->
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

<!-- Device Modal -->
<div id="deviceModal" class="fixed inset-0 z-40 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="deviceModalOverlay"></div>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="deviceModalContent">
            <div class="bg-white dark:bg-gray-800">
                <!-- Header -->
                <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-2xl font-bold text-gray-900 dark:text-white" id="device-modal-title">Customer Devices</h3>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage customer devices</p>
                        </div>
                        <div class="flex items-center space-x-4">
                            <button type="button" onclick="openAddDeviceForm()" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                                Add New Device
                            </button>
                            <button type="button" onclick="closeDeviceModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Content -->
                <div class="px-6 py-4">
                    <!-- Devices List -->
                    <div id="devices-list" class="space-y-4">
                        <!-- Devices will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add Device Form Modal -->
<div id="addDeviceModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="addDeviceModalOverlay"></div>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="addDeviceModalContent">
            <form id="addDeviceForm" onsubmit="handleAddDevice(event)">
                <div class="bg-white dark:bg-gray-800 px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Add New Device</h3>
                        <button type="button" onclick="closeAddDeviceModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                            <input type="text" name="brand" id="brand" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                            <input type="text" name="model" id="model" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" onclick="closeAddDeviceModal()"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Add Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Device Modal -->
<div id="editDeviceModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"
            id="editDeviceModalOverlay"></div>

        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            id="editDeviceModalContent">
            <form id="editDeviceForm" method="POST">
                @csrf
                <div class="bg-white dark:bg-gray-800 px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Device</h3>
                        <button type="button" onclick="hideModal('editDeviceModal', 'editDeviceModalContent', 'editDeviceModalOverlay')" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <label for="edit-device-brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                            <input type="text" name="brand" id="edit-device-brand" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        <div>
                            <label for="edit-device-model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                            <input type="text" name="model" id="edit-device-model" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" onclick="hideModal('editDeviceModal', 'editDeviceModalContent', 'editDeviceModalOverlay')"
                        class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Cancel
                    </button>
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Update Device
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection 
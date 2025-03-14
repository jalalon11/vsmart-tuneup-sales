@extends('layouts.app')

@section('content')
<style>
    /* Enhanced avatar with colored variants based on first letter */
    .customer-avatar {
        @apply relative overflow-hidden;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
        color: rgba(0, 0, 0, 0.7); /* Dark text for light mode */
    }

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
    
    .dark .customer-avatar {
        color: rgba(255, 255, 255, 0.95); /* White text for dark mode */
    }
</style>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-8 text-gray-900 dark:text-gray-100">
            <!-- Header with customer info and actions -->
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-4 mb-8 pb-4 border-b dark:border-gray-700">
                <div class="flex items-center">
                    <div class="h-16 w-16 rounded-full customer-avatar avatar-{{ strtolower(substr($customer->name, 0, 1)) }} flex items-center justify-center text-2xl font-bold shadow-lg">
                        {{ strtoupper(substr($customer->name, 0, 1)) }}
                    </div>
                    <div class="ml-4">
                        <h1 class="text-3xl font-bold text-gray-900 dark:text-white">{{ $customer->name }}</h1>
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-2">
                            @if($customer->phone)
                                <div class="flex items-center text-gray-600 dark:text-gray-400">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span>{{ $customer->phone }}</span>
                                </div>
                            @endif
                            @if($customer->facebook_url)
                                <a href="{{ $customer->facebook_url }}" target="_blank" class="flex items-center text-blue-600 dark:text-blue-400 hover:underline">
                                    <svg class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                    <span>Facebook Profile</span>
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="flex items-center space-x-4">
                    <button type="button" id="addDeviceBtn" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 focus:bg-green-700 active:bg-green-900 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition ease-in-out duration-150">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        Add Device
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg flex items-center">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($customer->address)
                <div class="mb-8 bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        Address
                    </h2>
                    <p class="text-gray-600 dark:text-gray-300">{{ $customer->address }}</p>
                </div>
            @endif

            <!-- Devices Section -->
            <div class="mb-6">
                <h2 class="text-2xl font-semibold text-gray-900 dark:text-white mb-6 flex items-center">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                    </svg>
                    Customer Devices
                </h2>

                @if($devices->count() > 0)
                    <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg overflow-hidden">
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Brand & Model</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Serial Number</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Last Repair</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="deviceTableBody" class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                </tbody>
                            </table>
                        </div>
                        <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                            <div id="devicePagination"></div>
                        </div>
                    </div>
                @else
                    <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-8 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-white">No devices</h3>
                        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">This customer doesn't have any devices yet.</p>
                        <div class="mt-6">
                            <button type="button" id="emptyAddDeviceBtn" 
                                class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add a device
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Add Device Modal -->
<div id="addDeviceModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-800 opacity-75"></div>
        </div>
        
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4" id="deviceModalTitle">Add a New Device</h3>
                        
                        <!-- Add Device Form -->
                        <form id="addDeviceForm" class="device-form space-y-4">
                            @csrf
                            <input type="hidden" name="customer_id" value="{{ $customer->id }}">
                            <div class="space-y-4">
                                <div>
                                    <label for="add_brand" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Brand</label>
                                    <input type="text" name="brand" id="add_brand" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="add_model" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                    <input type="text" name="model" id="add_model" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="add_serial_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Serial Number</label>
                                    <input type="text" name="serial_number" id="add_serial_number"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="submitAddDeviceBtn"
                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Add Device
                </button>
                <button type="button" id="closeModal"
                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-800 text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Message handling functions
        function showErrorMessage(message) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'mb-6 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded-lg flex items-center animate-fade-in';
            errorDiv.innerHTML = `
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>${message}</span>
            `;
            
            // Insert the new message at the top of the content area
            const contentArea = document.querySelector('.p-8.text-gray-900');
            contentArea.insertBefore(errorDiv, contentArea.firstChild.nextSibling);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                errorDiv.remove();
            }, 5000);
        }

        function showSuccessMessage(message) {
            const successDiv = document.createElement('div');
            successDiv.className = 'mb-6 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded-lg flex items-center animate-fade-in';
            successDiv.innerHTML = `
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span>${message}</span>
            `;
            
            // Insert the new message at the top of the content area
            const contentArea = document.querySelector('.p-8.text-gray-900');
            contentArea.insertBefore(successDiv, contentArea.firstChild.nextSibling);
            
            // Auto-remove after 5 seconds
            setTimeout(() => {
                successDiv.remove();
            }, 5000);
        }

        // Modals
        const addDeviceModal = document.getElementById('addDeviceModal');
        const addDeviceBtn = document.getElementById('addDeviceBtn');
        const emptyAddDeviceBtn = document.getElementById('emptyAddDeviceBtn');
        const closeModalBtn = document.getElementById('closeModal');
        const addDeviceForm = document.getElementById('addDeviceForm');
        const deviceModalTitle = document.getElementById('deviceModalTitle');
        const submitAddDeviceBtn = document.getElementById('submitAddDeviceBtn');
        const deviceTableBody = document.getElementById('deviceTableBody');
        const devicePagination = document.getElementById('devicePagination');
        
        let isSubmitting = false;
        
        // Function to load devices from the server
        function loadDevices(page = 1) {
            fetch(`/customers/{{ $customer->id }}/devices?page=${page}`, {
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
                deviceTableBody.innerHTML = '';
                
                if (data && data.length > 0) {
                    data.forEach(device => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-150';
                        
                        row.innerHTML = `
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center text-blue-600 dark:text-blue-300">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${device.brand} ${device.model}
                                        </div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">
                                            Added ${new Date(device.created_at).toLocaleDateString()}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    ${device.serial_number || 'Not specified'}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    ${device.status === 'received' ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' :
                                    device.status === 'in_repair' ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300' :
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
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <div class="flex justify-end space-x-2">
                                    <button data-device-id="${device.id}" class="edit-device-btn text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        `;
                        
                        deviceTableBody.appendChild(row);
                    });
                    
                    // Add event listeners to edit buttons
                    const editButtons = document.querySelectorAll('.edit-device-btn');
                    editButtons.forEach(button => {
                        button.addEventListener('click', function() {
                            const deviceId = this.getAttribute('data-device-id');
                            openEditModal(deviceId);
                        });
                    });
                } else {
                    deviceTableBody.innerHTML = `
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No devices found
                            </td>
                        </tr>
                    `;
                }
                
                updatePagination(data);
            })
            .catch(error => {
                console.error('Error:', error);
                deviceTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                            Error loading devices. Please try again.
                        </td>
                    </tr>
                `;
            });
        }
        
        function updatePagination(data) {
            if (!data.meta) return;
            
            devicePagination.innerHTML = data.meta.total > data.meta.per_page ? `
                <nav class="flex items-center justify-between">
                    <div class="flex-1 flex justify-between">
                        <button ${data.meta.current_page === 1 ? 'disabled' : ''} 
                            onclick="loadDevices(${data.meta.current_page - 1})"
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 ${data.meta.current_page === 1 ? 'opacity-50 cursor-not-allowed' : ''}">
                            Previous
                        </button>
                        <button ${data.meta.current_page === data.meta.last_page ? 'disabled' : ''}
                            onclick="loadDevices(${data.meta.current_page + 1})"
                            class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 ${data.meta.current_page === data.meta.last_page ? 'opacity-50 cursor-not-allowed' : ''}">
                            Next
                        </button>
                    </div>
                </nav>
            ` : '';
        }
        
        // Open edit modal with device data
        function openEditModal(deviceId) {
            fetch(`/devices/${deviceId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to load device data');
                }
                return response.json();
            })
            .then(data => {
                const device = data.device;
                
                // Fill form fields
                document.getElementById('edit-device-brand').value = device.brand || '';
                document.getElementById('edit-device-model').value = device.model || '';
                document.getElementById('edit-device-serial').value = device.serial_number || '';
                
                // Set form action and method
                document.getElementById('editDeviceForm').action = `/devices/${deviceId}`;
                
                // Add method override for PUT request
                let methodField = document.querySelector('#editDeviceForm input[name="_method"]');
                if (!methodField) {
                    methodField = document.createElement('input');
                    methodField.type = 'hidden';
                    methodField.name = '_method';
                    methodField.value = 'PUT';
                    document.getElementById('editDeviceForm').appendChild(methodField);
                }
                
                // Show the modal
                document.getElementById('editDeviceModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error loading device details');
            });
        }
        
        // Close edit modal
        function closeEditModal() {
            document.getElementById('editDeviceModal').classList.add('hidden');
        }
        
        // Add Device Modal functions
        function openAddModal() {
            addDeviceForm.reset();
            deviceModalTitle.textContent = 'Add a New Device';
            addDeviceModal.classList.remove('hidden');
        }
        
        function closeModal() {
            addDeviceModal.classList.add('hidden');
            addDeviceForm.reset();
            isSubmitting = false;
        }
        
        // Event handlers for add device modal
        submitAddDeviceBtn.addEventListener('click', async function(e) {
            e.preventDefault();
            
            if (isSubmitting) return;
            
            const form = document.getElementById('addDeviceForm');
            const formData = new FormData(form);
            
            // Validate required fields
            const brand = formData.get('brand');
            const model = formData.get('model');
            
            if (!brand || !model) {
                showErrorMessage('Brand and model are required');
                return;
            }
            
            // Show loading state
            isSubmitting = true;
            this.disabled = true;
            const originalButtonText = this.innerHTML;
            this.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Adding...';

            try {
                const token = document.querySelector('meta[name="csrf-token"]').content;
                const response = await fetch(`/customers/{{ $customer->id }}/devices`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': token,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    },
                    credentials: 'same-origin'
                });

                const data = await response.json();
                
                if (!response.ok) {
                    if (response.status === 422) {
                        const errors = Object.values(data.errors || {}).flat();
                        throw new Error(errors.join('\n'));
                    }
                    throw new Error(data.message || 'Failed to add device');
                }

                // Clear form fields but keep modal open
                form.reset();
                
                // Show success message and refresh devices list
                showSuccessMessage(data.message || 'Device added successfully');
                await loadDevices();
                
                // Focus on the brand input for the next device
                document.getElementById('add_brand').focus();
                
            } catch (error) {
                console.error('Error:', error);
                showErrorMessage(error.message || 'An error occurred while adding the device');
            } finally {
                // Reset button state
                isSubmitting = false;
                this.disabled = false;
                this.innerHTML = originalButtonText;
            }
        });
        
        // Form submission handlers
        addDeviceForm.addEventListener('submit', function(e) {
            e.preventDefault(); // Prevent default form submission
        });
        
        // Edit form submission handler
        document.getElementById('editDeviceForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch(this.action, {
                method: 'POST', // Will be overridden by _method field
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Failed to update device');
                }
                return response.json();
            })
            .then(data => {
                closeEditModal();
                showSuccessMessage(data.message || 'Device updated successfully');
                loadDevices(); // Refresh device list
            })
            .catch(error => {
                console.error('Error:', error);
                showErrorMessage('Error updating device');
            });
        });
        
        // Button click handlers for edit modal
        if (closeEditModalBtn) {
            closeEditModalBtn.addEventListener('click', closeEditModal);
        }
        
        if (cancelEditBtn) {
            cancelEditBtn.addEventListener('click', closeEditModal);
        }
        
        // Button click handlers for add modal
        if (addDeviceBtn) {
            addDeviceBtn.addEventListener('click', openAddModal);
        }
        
        if (emptyAddDeviceBtn) {
            emptyAddDeviceBtn.addEventListener('click', openAddModal);
        }
        
        if (closeModalBtn) {
            closeModalBtn.addEventListener('click', closeModal);
        }
        
        // Close modals when clicking outside
        addDeviceModal.addEventListener('click', function(e) {
            if (e.target === addDeviceModal) {
                closeModal();
            }
        });
        
        editDeviceModal.addEventListener('click', function(e) {
            if (e.target === editDeviceModal) {
                closeEditModal();
            }
        });
        
        // Make pagination function available globally
        window.loadDevices = loadDevices;
        
        // Initial load of devices
        loadDevices();
    });
</script>
@endpush

<!-- Edit Device Modal -->
<div id="editDeviceModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 dark:bg-gray-900 bg-opacity-75 dark:bg-opacity-75 transition-opacity" 
            aria-hidden="true"></div>

        <!-- Modal container -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full"
            id="editDeviceModalContent">
            <form id="editDeviceForm" method="POST">
                @csrf
                <!-- Hidden method field will be added dynamically -->
                <div class="bg-white dark:bg-gray-800 px-6 py-4">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Edit Device</h3>
                        <button type="button" id="closeEditModalBtn" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
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
                        <div>
                            <label for="edit-device-serial" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Serial Number</label>
                            <input type="text" name="serial_number" id="edit-device-serial" 
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 dark:bg-gray-700 px-6 py-3 flex justify-end space-x-3">
                    <button type="button" id="cancelEditBtn"
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
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
        const deviceTableBody = document.getElementById('deviceTableBody');
        const devicePagination = document.getElementById('devicePagination');
        
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
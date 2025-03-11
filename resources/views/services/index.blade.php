@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Header Section -->
        <div class="mb-8">
            <div class="flex justify-between items-center">
                <div>
                <h1 class="text-2xl font-bold animated-title flex items-center">
                    <img class="h-8 w-8 text-blue-500 mr-3" src="./img/optimizing.png" alt="Mobile Icon">
                        Manage Services
                    </h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your service offerings and pricing</p>
                </div>
                <button type="button" 
                    onclick="openModal()"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:scale-105 shadow-sm">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add New Service
                </button>
            </div>

            @if (session('success'))
                <div class="mt-4 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm">
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
                <div class="mt-4 p-4 bg-red-50 dark:bg-red-900/50 border-l-4 border-red-500 rounded-lg shadow-sm">
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
        </div>

        <!-- Search Section -->
        <div class="mb-6 bg-gray-50 dark:bg-gray-700/50 p-4 rounded-lg">
            <form action="{{ route('services.index') }}" method="GET">
                <div class="flex gap-4">
                    <div class="flex-1">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Services</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                            </div>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-md leading-5 bg-white dark:bg-gray-700 placeholder-gray-500 dark:placeholder-gray-400 text-gray-900 dark:text-white focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                placeholder="Search by name, description, or category...">
                        </div>
                    </div>
                    <div class="flex items-end space-x-2">
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                            <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                            Search
                        </button>
                        @if(request()->has('search'))
                            <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                                Clear
                            </a>
                        @endif
                    </div>
                </div>
            </form>
        </div>

        <!-- Table Section -->
        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden border dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Base Price</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($services as $service)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $service->name }}
                                </div>
                                @if($service->description)
                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">
                                        {{ $service->description }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                    {{ $service->category?->name ?? 'No Category' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">₱{{ number_format($service->price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $service->is_active 
                                        ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' 
                                        : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex justify-end items-center space-x-3">
                                    <a href="{{ route('services.show', $service) }}" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        View
                                    </a>
                                    <a href="{{ route('services.edit', $service) }}" 
                                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center">
                                        <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        Edit
                                    </a>
                                    <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline-flex" onsubmit="return confirmDelete(event)">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                <div class="flex flex-col items-center">
                                    <svg class="h-12 w-12 text-gray-400 dark:text-gray-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                    </svg>
                                    <p class="text-sm">No services found.</p>
                                    <button type="button" onclick="openModal()" class="mt-2 text-sm text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300">
                                        Add your first service
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $services->links() }}
        </div>
    </div>
</div>

@include('services.modals.create')
@include('services.modals.category')

@push('scripts')
<script>
    let isSubmitting = false;

    function confirmDelete(event) {
        event.preventDefault();
        
        if (confirm('Are you sure you want to delete this service? This action cannot be undone.')) {
            const form = event.target;
            form.submit();
        }
    }
    
    function showAlert(type, message) {
        const alertClass = type === 'success' 
            ? 'bg-green-50 dark:bg-green-900/50 border-green-500 text-green-800 dark:text-green-200' 
            : 'bg-red-50 dark:bg-red-900/50 border-red-500 text-red-800 dark:text-red-200';
        
        const iconPath = type === 'success'
            ? 'M5 13l4 4L19 7'
            : 'M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
        
        const iconColor = type === 'success' ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400';
        
        const alert = document.createElement('div');
        alert.className = `mb-6 p-4 ${alertClass} border-l-4 rounded-lg shadow-sm`;
        alert.innerHTML = `
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 ${iconColor}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="${iconPath}"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium">${message}</p>
                </div>
            </div>
        `;
        
        // Insert alert after the header
        const header = document.querySelector('.mb-8');
        if (header) {
            header.insertAdjacentElement('afterend', alert);
            
            // Remove the alert after 5 seconds with fade out
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transition = 'opacity 0.5s ease-in-out';
                setTimeout(() => alert.remove(), 500);
            }, 5000);
        }
    }

    function submitForm() {
        if (isSubmitting) return;
        
        const form = document.getElementById('createForm');
        const formData = new FormData(form);
        
        // Reset validation errors
        const errorElements = form.querySelectorAll('.error-message');
        errorElements.forEach(element => element.textContent = '');
        
        isSubmitting = true;
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                form.reset();
                
                // Show success message
                showAlert('success', data.message);
                
                // Add the new service to the table
                const tbody = document.querySelector('tbody');
                const noServicesRow = tbody.querySelector('tr td[colspan="5"]');
                if (noServicesRow) {
                    tbody.innerHTML = '';
                }

                const newRow = document.createElement('tr');
                newRow.className = 'hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-200';
                newRow.style.opacity = '0';
                newRow.style.transition = 'opacity 0.5s';
                newRow.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                            ${data.service.name}
                        </div>
                        ${data.service.description ? `
                            <div class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">
                                ${data.service.description}
                            </div>
                        ` : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                            ${data.service.category ? data.service.category.name : 'No Category'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900 dark:text-white font-medium">₱${parseFloat(data.service.price).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full ${data.service.is_active ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300'}">
                            ${data.service.is_active ? 'Active' : 'Inactive'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end items-center space-x-3">
                            <a href="/services/${data.service.id}" 
                                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                View
                            </a>
                            <a href="/services/${data.service.id}/edit" 
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center">
                                <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                                Edit
                            </a>
                            <form action="/services/${data.service.id}" method="POST" class="inline-flex" onsubmit="return confirmDelete(event)">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Delete
                                </button>
                            </form>
                        </div>
                    </td>
                `;
                
                // Insert the new row at the top of the table
                tbody.insertBefore(newRow, tbody.firstChild);
                
                // Fade in the new row
                setTimeout(() => {
                    newRow.style.opacity = '1';
                }, 50);
            } else {
                // Handle validation errors
                if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        const errorElement = document.getElementById(`${field}-error`);
                        if (errorElement) {
                            errorElement.textContent = data.errors[field][0];
                        }
                    });
                }
                showAlert('error', data.message || 'Failed to create service. Please check the form and try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Failed to create service. Please try again.');
        })
        .finally(() => {
            isSubmitting = false;
        });
    }

    function showModal(modalId, contentId, overlayId) {
        try {
            const modal = document.getElementById(modalId);
            const content = document.getElementById(contentId);
            const overlay = document.getElementById(overlayId);
            
            if (!modal || !content || !overlay) {
                console.error(`Modal elements not found for ${modalId}`);
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
            
            if (!modal || !content || !overlay) {
                console.error(`Modal elements not found for ${modalId}`);
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
        showModal('createModal', 'createModalContent', 'createModalOverlay');
    }

    function closeModal() {
        hideModal('createModal', 'createModalContent', 'createModalOverlay');
    }

    function openCategoryModal() {
        showModal('categoryModal', 'categoryModalContent', 'categoryModalOverlay');
    }

    function closeCategoryModal() {
        hideModal('categoryModal', 'categoryModalContent', 'categoryModalOverlay');
    }

    function submitCategory() {
        const form = document.getElementById('categoryForm');
        const formData = new FormData(form);

        // Reset error messages
        document.getElementById('category-name-error').classList.add('hidden');
        document.getElementById('category-description-error').classList.add('hidden');

        fetch('{{ route('categories.store') }}', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            // Success case - close modal first to improve user experience
            closeCategoryModal();
            form.reset();
            
            // Then handle the response data if it exists
            if (data && data.category) {
                // Add the new category to the select dropdown
                const select = document.getElementById('category_id');
                const option = new Option(data.category.name, data.category.id);
                select.add(option);
                select.value = data.category.id;
            }

            // Show success message
            const successMessage = data && data.message ? data.message : 'Category added successfully!';
            const successAlert = document.createElement('div');
            successAlert.className = 'mb-6 p-4 bg-green-50 dark:bg-green-900/50 border-l-4 border-green-500 rounded-lg shadow-sm';
            successAlert.innerHTML = `
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-500 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">${successMessage}</p>
                    </div>
                </div>
            `;
            
            // Insert the alert after the header
            const header = document.querySelector('.mb-8');
            if (header) {
                header.insertAdjacentElement('afterend', successAlert);
                
                // Remove the alert after 5 seconds
                setTimeout(() => {
                    successAlert.remove();
                }, 5000);
            }
        })
        .catch(error => {
            console.error('Error:', error);
            
            // Try to handle validation errors if they exist
            if (error.response && error.response.status === 422 && error.response.data && error.response.data.errors) {
                const errors = error.response.data.errors;
                if (errors.name) {
                    const errorElement = document.getElementById('category-name-error');
                    errorElement.textContent = errors.name[0];
                    errorElement.classList.remove('hidden');
                }
                if (errors.description) {
                    const errorElement = document.getElementById('category-description-error');
                    errorElement.textContent = errors.description[0];
                    errorElement.classList.remove('hidden');
                }
            } else {
                // Show a generic error message in the form
                const errorElement = document.getElementById('category-name-error');
                errorElement.textContent = 'An error occurred. Please try again.';
                errorElement.classList.remove('hidden');
            }
        });
    }

    // Initialize modal functions
    function initializeModalFunctions() {
        // Make functions available globally for inline button onclick handlers
        window.openModal = openModal;
        window.closeModal = closeModal;
        window.openCategoryModal = openCategoryModal;
        window.closeCategoryModal = closeCategoryModal;
        window.submitForm = submitForm;
        window.submitCategory = submitCategory;
        window.confirmDelete = confirmDelete;
        
        // Close modals when clicking outside
        const modals = {
            'createModal': closeModal,
            'categoryModal': closeCategoryModal
        };

        Object.entries(modals).forEach(([modalId, closeFunction]) => {
            const modal = document.getElementById(modalId);
            if (modal) {
                modal.addEventListener('click', function(event) {
                    if (event.target === this || event.target.id.includes('Overlay')) {
                        closeFunction();
                    }
                });
            }
        });

        // Close modals with escape key
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                Object.values(modals).forEach(closeFunction => closeFunction());
            }
        });
        
        console.log('Service page modal functions initialized');
    }

    // Initialize on page load and after Turbo navigation
    document.addEventListener('DOMContentLoaded', initializeModalFunctions);
    document.addEventListener('turbo:load', initializeModalFunctions);
    document.addEventListener('turbo:render', initializeModalFunctions);

    // Cleanup before navigation
    document.addEventListener('turbo:before-cache', function() {
        // Reset modal functions
        const functionsToCleanup = [
            'openModal',
            'closeModal',
            'openCategoryModal',
            'closeCategoryModal',
            'submitForm',
            'submitCategory',
            'confirmDelete'
        ];
        
        functionsToCleanup.forEach(function(func) {
            if (window[func]) {
                window[func] = null;
            }
        });
        
        // Remove event listeners
        const keydownListener = function(event) {
            if (event.key === 'Escape') {
                closeModal();
                closeCategoryModal();
            }
        };
        document.removeEventListener('keydown', keydownListener);
    });
</script>
@endpush

@endsection 
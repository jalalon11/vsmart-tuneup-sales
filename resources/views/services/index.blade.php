@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Services</h1>
            <button type="button" 
                onclick="openModal()"
                class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 ease-in-out transform hover:scale-105">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Add New Service
            </button>
        </div>

        @if (session('success'))
            <div class="bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 px-4 py-3 rounded relative mb-4" role="alert">
                <span class="block sm:inline">{{ session('error') }}</span>
            </div>
        @endif

        <!-- Search Form -->
        <form action="{{ route('services.index') }}" method="GET" class="mb-4">
            <div class="flex gap-4">
                <div class="flex-1">
                    <label for="search" class="sr-only">Search services</label>
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
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Search
                </button>
                @if(request()->has('search'))
                    <a href="{{ route('services.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Clear
                    </a>
                @endif
            </div>
        </form>

        <div class="overflow-x-auto">
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
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $service->name }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                    {{ $service->category?->name ?? 'No Category' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                ₱{{ number_format($service->price, 2) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    {{ $service->is_active 
                                        ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' 
                                        : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                                    {{ $service->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('services.show', $service) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 mr-3">View</a>
                                <a href="{{ route('services.edit', $service) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 mr-3">Edit</a>
                                <form action="{{ route('services.destroy', $service) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Are you sure you want to delete this service?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 whitespace-nowrap text-center text-gray-500 dark:text-gray-400">
                                No services found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $services->links() }}
        </div>
    </div>
</div>

@include('services.modals.create')
@include('services.modals.category')

@push('scripts')
<script>
    function openModal() {
        document.getElementById('createModal').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('createModalOverlay').classList.remove('opacity-0');
            document.getElementById('createModalContent').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            document.getElementById('createModalContent').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 50);
    }

    function closeModal() {
        document.getElementById('createModalOverlay').classList.add('opacity-0');
        document.getElementById('createModalContent').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        document.getElementById('createModalContent').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        setTimeout(() => {
            document.getElementById('createModal').classList.add('hidden');
        }, 300);
    }

    // Category modal functions
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
        setTimeout(() => {
            document.getElementById('categoryModalOverlay').classList.remove('opacity-0');
            document.getElementById('categoryModalContent').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
            document.getElementById('categoryModalContent').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
        }, 50);
    }

    function closeCategoryModal() {
        document.getElementById('categoryModalOverlay').classList.add('opacity-0');
        document.getElementById('categoryModalContent').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        document.getElementById('categoryModalContent').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        setTimeout(() => {
            document.getElementById('categoryModal').classList.add('hidden');
        }, 300);
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
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Add the new category to the select dropdown
                const select = document.getElementById('category_id');
                const option = new Option(data.category.name, data.category.id);
                select.add(option);
                select.value = data.category.id;

                // Show success message
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
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">${data.message}</p>
                        </div>
                    </div>
                `;
                
                // Insert the alert after the header
                const header = document.querySelector('.flex.justify-between.items-center.mb-6');
                header.parentNode.insertBefore(successAlert, header.nextSibling);
                
                // Remove the alert after 5 seconds
                setTimeout(() => {
                    successAlert.remove();
                }, 5000);

                // Close the category modal and reset form
                closeCategoryModal();
                form.reset();
            }
        })
        .catch(error => {
            if (error.response && error.response.status === 422) {
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
            }
            console.error('Error:', error);
        });
    }

    // Handle form submission
    document.getElementById('createForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch(this.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeModal();
                // Show success message
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
                            <p class="text-sm font-medium text-green-800 dark:text-green-200">${data.message}</p>
                        </div>
                    </div>
                `;
                
                // Insert the alert after the header
                const header = document.querySelector('.flex.justify-between.items-center.mb-6');
                header.parentNode.insertBefore(successAlert, header.nextSibling);
                
                // Remove the alert after 5 seconds
                setTimeout(() => {
                    successAlert.remove();
                }, 5000);
                
                // Reset form
                this.reset();
                
                // Reload the page to show updated data
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.id === 'createModalOverlay') {
            closeModal();
        } else if (event.target.id === 'categoryModalOverlay') {
            closeCategoryModal();
        }
    });

    // Close modals with escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeCategoryModal();
        }
    });
</script>
@endpush

@endsection 
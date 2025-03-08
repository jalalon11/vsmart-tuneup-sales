@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8 sm:px-6 lg:px-8">
    <!-- Page Header -->
    <div class="mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex-1 min-w-0">
                <h1 class="text-4xl font-bold text-gray-900 dark:text-white leading-tight">Inventory</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    Manage your inventory items and stock levels
                </p>
            </div>
            <div class="mt-4 md:mt-0 flex items-center space-x-4">
                <!-- Search Form -->
                <form action="{{ route('inventory.index') }}" method="GET" class="flex-1 md:flex-none">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search inventory..." 
                               class="w-full md:w-64 pl-10 pr-10 py-2 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white dark:border-gray-600 dark:placeholder-gray-400"
                               autofocus
                               autocomplete="off">
                        
                        @if(request('search'))
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <a href="{{ route('inventory.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
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
                    Add New Item
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

    <!-- Inventory Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Total Items</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $totalItems ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Low Stock Items</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $lowStockItems ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm rounded-lg">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                        <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/>
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400 truncate">Out of Stock Items</dt>
                            <dd class="flex items-baseline">
                                <div class="text-2xl font-semibold text-gray-900 dark:text-white">{{ $outOfStockItems ?? 0 }}</div>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Brand/Model</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Stock</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Price</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse ($items as $item)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors duration-150">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $item->name }}</div>
                                @if($item->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($item->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">{{ $item->brand }}</div>
                                @if($item->model)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ $item->model }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    @if($item->quantity <= 0)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Out of Stock
                                        </span>
                                    @elseif($item->quantity <= $item->reorder_point)
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                            Low Stock ({{ $item->quantity }})
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            In Stock ({{ $item->quantity }})
                                        </span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">₱{{ number_format($item->selling_price, 2) }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">Cost: ₱{{ number_format($item->unit_price, 2) }}</div>
                            </td>
                            <td class="px-6 py-4 text-right text-sm font-medium space-x-3">
                                <button type="button" 
                                    data-item-id="{{ $item->id }}" 
                                    onclick="openViewModal(this.dataset.itemId)"
                                    class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    View
                                </button>
                                <button type="button" 
                                    data-item-id="{{ $item->id }}" 
                                    onclick="openEditModal(this.dataset.itemId)"
                                    class="inline-flex items-center text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                <form action="{{ route('inventory.destroy', $item) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        class="inline-flex items-center text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                        onclick="return confirm('Are you sure you want to delete this item? This action cannot be undone.')">
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
                            <td colspan="5" class="px-6 py-8 text-center">
                                <div class="flex flex-col items-center">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                    <p class="mt-4 text-gray-500 dark:text-gray-400 text-base">No inventory items found</p>
                                    <button type="button" 
                                        onclick="openModal()"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Add Your First Item
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
            {{ $items->links() }}
        </div>
    </div>
</div>

@include('inventory.modals.create')
@include('inventory.modals.edit')
@include('inventory.modals.view')

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

    function openViewModal(itemId) {
        fetch(`/inventory/${itemId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Populate the view modal with item data
            document.getElementById('view-item-name').textContent = data.item.name;
            document.getElementById('view-item-brand').textContent = data.item.brand || 'N/A';
            document.getElementById('view-item-model').textContent = data.item.model || 'N/A';
            document.getElementById('view-item-serial').textContent = data.item.serial_number || 'N/A';
            document.getElementById('view-item-quantity').textContent = data.item.quantity;
            document.getElementById('view-item-unit-price').textContent = `₱${parseFloat(data.item.unit_price).toFixed(2)}`;
            document.getElementById('view-item-selling-price').textContent = `₱${parseFloat(data.item.selling_price).toFixed(2)}`;
            document.getElementById('view-item-description').textContent = data.item.description || 'No description available';
            
            // Show the modal
            document.getElementById('viewModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('viewModalOverlay').classList.remove('opacity-0');
                document.getElementById('viewModalContent').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                document.getElementById('viewModalContent').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 50);
        });
    }

    function closeViewModal() {
        document.getElementById('viewModalOverlay').classList.add('opacity-0');
        document.getElementById('viewModalContent').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        document.getElementById('viewModalContent').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        setTimeout(() => {
            document.getElementById('viewModal').classList.add('hidden');
        }, 300);
    }

    function openEditModal(itemId) {
        fetch(`/inventory/${itemId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            // Populate the edit form with item data
            document.getElementById('edit-form').action = `/inventory/${itemId}`;
            document.getElementById('edit-name').value = data.item.name;
            document.getElementById('edit-brand').value = data.item.brand || '';
            document.getElementById('edit-model').value = data.item.model || '';
            document.getElementById('edit-serial_number').value = data.item.serial_number || '';
            document.getElementById('edit-quantity').value = data.item.quantity;
            document.getElementById('edit-unit_price').value = data.item.unit_price;
            document.getElementById('edit-selling_price').value = data.item.selling_price;
            document.getElementById('edit-description').value = data.item.description || '';
            document.getElementById('edit-reorder_point').value = data.item.reorder_point;
            
            // Show the modal
            document.getElementById('editModal').classList.remove('hidden');
            setTimeout(() => {
                document.getElementById('editModalOverlay').classList.remove('opacity-0');
                document.getElementById('editModalContent').classList.remove('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
                document.getElementById('editModalContent').classList.add('opacity-100', 'translate-y-0', 'sm:scale-100');
            }, 50);
        });
    }

    function closeEditModal() {
        document.getElementById('editModalOverlay').classList.add('opacity-0');
        document.getElementById('editModalContent').classList.add('opacity-0', 'translate-y-4', 'sm:translate-y-0', 'sm:scale-95');
        document.getElementById('editModalContent').classList.remove('opacity-100', 'translate-y-0', 'sm:scale-100');
        setTimeout(() => {
            document.getElementById('editModal').classList.add('hidden');
        }, 300);
    }

    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target.id === 'createModalOverlay') {
            closeModal();
        } else if (event.target.id === 'viewModalOverlay') {
            closeViewModal();
        } else if (event.target.id === 'editModalOverlay') {
            closeEditModal();
        }
    });

    // Close modals with escape key
    window.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            closeModal();
            closeViewModal();
            closeEditModal();
        }
    });
</script>
@endpush

@endsection 
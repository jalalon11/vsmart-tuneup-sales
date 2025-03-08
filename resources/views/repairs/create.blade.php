@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">New Repair</h1>
        </div>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('repairs.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Customer Selection -->
            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Customer Information</h2>
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Customer</label>
                    <select name="customer_id" id="customer_id" required
                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a customer</option>
                        @foreach($customers as $customer)
                            <option value="{{ $customer->id }}">
                                {{ $customer->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

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
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select" disabled>
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
                                            {{ $service->name }} - ₱{{ number_format($service->price, 2) }}
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
                                    <input type="number" name="items[INDEX][cost]" step="0.01" min="0" required
                                        class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 cost-input">
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="items[INDEX][notes]" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
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
                            <select name="items[0][device_id]" required
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select" disabled>
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
                                        {{ $service->name }} - ₱{{ number_format($service->price, 2) }}
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
                                <input type="number" name="items[0][cost]" step="0.01" min="0" required
                                    class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 cost-input">
                            </div>
                        </div>

                        <!-- Notes -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                            <textarea name="items[0][notes]" rows="2"
                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center">
                <button type="button" onclick="addRepairItem()" id="add-item-btn" disabled
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50 disabled:cursor-not-allowed">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Another Device
                </button>
            </div>

            <!-- Status and Dates -->
            <div class="border-t dark:border-gray-700 pt-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending">Pending</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input type="date" name="started_at" id="started_at"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Completion Date -->
                    <div>
                        <label for="completed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Completion Date</label>
                        <input type="date" name="completed_at" id="completed_at"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Overall Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Overall Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('repairs.index') }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Create Repair
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = 1;

// Function to load customer's devices
function loadCustomerDevices(customerId, targetSelect) {
    if (!customerId) {
        targetSelect.innerHTML = '<option value="">Select a device</option>';
        targetSelect.disabled = true;
        return;
    }

    // Show loading state
    targetSelect.disabled = true;
    targetSelect.innerHTML = '<option value="">Loading devices...</option>';

    // Get CSRF token
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/api/customers/${customerId}/devices`, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': token,
            'X-Requested-With': 'XMLHttpRequest'
        },
        credentials: 'same-origin'
    })
    .then(response => response.json())
    .then(data => {
        targetSelect.innerHTML = '<option value="">Select a device</option>';
        data.forEach(device => {
            const option = document.createElement('option');
            option.value = device.id;
            option.textContent = `${device.brand} ${device.model}`;
            targetSelect.appendChild(option);
        });
        targetSelect.disabled = false;
    })
    .catch(error => {
        console.error('Error:', error);
        targetSelect.innerHTML = '<option value="">Error loading devices</option>';
    });
}

// Function to add new repair item
function addRepairItem() {
    const template = document.getElementById('repair-item-template');
    const clone = template.content.cloneNode(true);
    
    // Update the indices
    clone.querySelectorAll('[name*="[INDEX]"]').forEach(element => {
        element.name = element.name.replace('INDEX', itemIndex);
    });
    
    document.getElementById('repair-items').appendChild(clone);
    itemIndex++;
}

// Function to remove repair item
function removeRepairItem(button) {
    button.closest('.repair-item').remove();
}

// Event listener for customer selection
document.getElementById('customer_id').addEventListener('change', function() {
    const customerId = this.value;
    document.querySelectorAll('.device-select').forEach(select => {
        loadCustomerDevices(customerId, select);
    });
    document.getElementById('add-item-btn').disabled = !customerId;
});

// Event listener for service selection
document.addEventListener('change', function(e) {
    if (e.target.classList.contains('service-select')) {
        const selectedOption = e.target.options[e.target.selectedIndex];
        const price = selectedOption.dataset.price;
        const costInput = e.target.closest('.repair-item').querySelector('.cost-input');
        if (price && costInput) {
            costInput.value = price;
        }
    }
});
</script>
@endpush
@endsection 
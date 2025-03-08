@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Edit Repair</h1>
        </div>

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('repairs.update', $repair) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <div id="repair-items">
                <!-- Template for repair item -->
                <template id="repair-item-template">
                    <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Repair Item</h3>
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
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a device</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}">
                                            {{ $device->customer->name }} - {{ $device->brand }} {{ $device->model }}
                                        </option>
                                    @endforeach
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

                <!-- Existing repair items will be loaded here -->
                @foreach($repair->items as $index => $item)
                    <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Repair Item</h3>
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
                                <select name="items[{{ $index }}][device_id]" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Select a device</option>
                                    @foreach($devices as $device)
                                        <option value="{{ $device->id }}" {{ $item->device_id == $device->id ? 'selected' : '' }}>
                                            {{ $device->customer->name }} - {{ $device->brand }} {{ $device->model }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Service Selection -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                                <select name="items[{{ $index }}][service_id]" required
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select">
                                    <option value="">Select a service</option>
                                    @foreach($services as $service)
                                        <option value="{{ $service->id }}" 
                                            data-price="{{ $service->price }}"
                                            {{ $item->service_id == $service->id ? 'selected' : '' }}>
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
                                    <input type="number" name="items[{{ $index }}][cost]" step="0.01" min="0" required
                                        value="{{ $item->cost }}"
                                        class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 cost-input">
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                <textarea name="items[{{ $index }}][notes]" rows="2"
                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $item->notes }}</textarea>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="flex justify-center">
                <button type="button" onclick="addRepairItem()"
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 shadow-sm text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Add Another Item
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
                            <option value="pending" {{ $repair->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="completed" {{ $repair->status == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $repair->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <input type="date" name="started_at" id="started_at"
                            value="{{ $repair->started_at ? $repair->started_at->format('Y-m-d') : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <!-- Completion Date -->
                    <div>
                        <label for="completed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Completion Date</label>
                        <input type="date" name="completed_at" id="completed_at"
                            value="{{ $repair->completed_at ? $repair->completed_at->format('Y-m-d') : '' }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <!-- Overall Notes -->
            <div>
                <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Overall Notes</label>
                <textarea name="notes" id="notes" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ $repair->notes }}</textarea>
            </div>

            <div class="flex justify-end space-x-4">
                <a href="{{ route('repairs.show', $repair) }}" 
                    class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-md text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Update Repair
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
let itemIndex = {{ count($repair->items) }};

function addRepairItem() {
    const template = document.getElementById('repair-item-template');
    const container = document.getElementById('repair-items');
    const clone = template.content.cloneNode(true);
    
    // Replace INDEX placeholder with actual index
    clone.querySelectorAll('[name*="INDEX"]').forEach(element => {
        element.name = element.name.replace('INDEX', itemIndex);
    });

    // Add event listeners
    const serviceSelect = clone.querySelector('.service-select');
    const costInput = clone.querySelector('.cost-input');
    
    serviceSelect.addEventListener('change', function() {
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            costInput.value = selectedOption.dataset.price;
        } else {
            costInput.value = '';
        }
    });

    container.appendChild(clone);
    itemIndex++;
}

function removeRepairItem(button) {
    const item = button.closest('.repair-item');
    if (document.querySelectorAll('.repair-item').length > 1) {
        item.remove();
    } else {
        alert('At least one repair item is required.');
    }
}

// Add event listeners to existing service selects
document.querySelectorAll('.service-select').forEach(select => {
    select.addEventListener('change', function() {
        const costInput = this.closest('.repair-item').querySelector('.cost-input');
        if (this.value) {
            const selectedOption = this.options[this.selectedIndex];
            costInput.value = selectedOption.dataset.price;
        } else {
            costInput.value = '';
        }
    });
});
</script>
@endpush
@endsection 
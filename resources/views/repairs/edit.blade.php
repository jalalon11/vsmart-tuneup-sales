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
                    <!-- Date Created -->
                    <div>
                        <label for="created_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date Created</label>
                        <input type="datetime-local" id="created_at" 
                               value="{{ $repair->created_at ? $repair->created_at->timezone('Asia/Manila')->format('Y-m-d\TH:i') : '' }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-100"
                               readonly>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Initial creation date of the repair</p>
                    </div>

                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                        <select name="status" id="status" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="pending" {{ $repair->status === 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="in_progress" {{ $repair->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="completed" {{ $repair->status === 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ $repair->status === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>

                    <!-- Payment Method -->
                    <div>
                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                        <select name="payment_method" id="payment_method" required
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                            <option value="cash" {{ $repair->payment_method === 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="gcash" {{ $repair->payment_method === 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="bank_transfer" {{ $repair->payment_method === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                            <option value="credit_card" {{ $repair->payment_method === 'credit_card' ? 'selected' : '' }}>Credit Card</option>
                        </select>
                    </div>

                    <!-- Start Date -->
                    <div>
                        <label for="started_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Start Date</label>
                        <!-- Store exact PHP datetime string to preserve format -->
                        <input type="hidden" name="started_at" id="started_at_hidden"
                               value="{{ ($repair->status === 'in_progress' && $repair->started_at) ? $repair->started_at->format('Y-m-d H:i:s') : '' }}">
                        
                        <!-- Visible readonly field to show the correct formatted date -->
                        <input type="datetime-local" id="started_at" 
                               value="{{ ($repair->status === 'in_progress' && $repair->started_at) ? $repair->started_at->timezone('Asia/Manila')->format('Y-m-d\TH:i') : '' }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               {{ $repair->started_at ? 'readonly' : '' }}>
                    </div>

                    <!-- Duration -->
                    <div>
                        <label for="duration" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Duration</label>
                        <div class="mt-1 flex">
                            <input type="text" id="duration" readonly
                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 bg-gray-100 dark:bg-gray-800">
                            <input type="hidden" name="duration_seconds" id="duration_seconds" value="0">
                        </div>
                        <!-- Live timer indicator -->
                        <div id="timer-indicator" class="hidden mt-1 text-sm text-green-500 flex items-center">
                            <span class="mr-1">●</span> <!-- Pulsing dot -->
                            <span>Timer active</span>
                        </div>
                    </div>

                    <!-- Completion Date -->
                    <div>
                        <label for="completed_at" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Completion Date</label>
                        <!-- Store exact PHP datetime string to preserve format -->
                        <input type="hidden" name="completed_at" id="completed_at_hidden" 
                               value="{{ $repair->completed_at ? $repair->completed_at->format('Y-m-d H:i:s') : '' }}">
                        
                        <!-- Visible readonly field to show the correct formatted date -->
                        <input type="datetime-local" id="completed_at"
                               value="{{ $repair->completed_at ? $repair->completed_at->timezone('Asia/Manila')->format('Y-m-d\TH:i') : '' }}"
                               class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"
                               {{ $repair->completed_at ? 'readonly' : '' }}>
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

// Timer functionality with continuous duration counter
(function() {
    // Get DOM elements
    const durationField = document.getElementById('duration');
    const durationSecondsField = document.getElementById('duration_seconds');
    const statusSelect = document.getElementById('status');
    const startDateField = document.getElementById('started_at');
    const startDateHidden = document.getElementById('started_at_hidden');
    const completedDateField = document.getElementById('completed_at');
    const completedDateHidden = document.getElementById('completed_at_hidden');
    const timerIndicator = document.getElementById('timer-indicator');
    const createdAtField = document.getElementById('created_at');
    
    // Store PHP server timestamps in milliseconds
    let startTime = {{ ($repair->status === 'in_progress' && $repair->started_at) ? 
        $repair->started_at->timestamp * 1000 : 
        "null" }};
    
    let completedTime = {{ $repair->completed_at ? 
        $repair->completed_at->timestamp * 1000 : 
        "null" }};
    
    // Flag to track if timer is running
    let isTimerRunning = {{ $repair->status === 'in_progress' ? 'true' : 'false' }};
    let timerInterval = null;
    
    console.log("TIMER INIT - Status:", "{{ $repair->status }}", "StartTime:", startTime);
    
    // Add animation for the timer indicator
    function pulseAnimation() {
        const dot = timerIndicator.querySelector('span');
        let opacity = 1;
        let increasing = false;
        
        setInterval(() => {
            if (opacity <= 0.3) increasing = true;
            if (opacity >= 1) increasing = false;
            
            opacity = increasing ? opacity + 0.1 : opacity - 0.1;
            dot.style.opacity = opacity;
        }, 200);
    }
    
    // Format milliseconds as human-readable time
    function formatDuration(milliseconds) {
        const totalSeconds = Math.floor(milliseconds / 1000);
        const seconds = totalSeconds % 60;
        const minutes = Math.floor(totalSeconds / 60) % 60;
        const hours = Math.floor(totalSeconds / 3600) % 24;
        const days = Math.floor(totalSeconds / 86400);
        
        let result = '';
        if (days > 0) result += days + (days === 1 ? ' day ' : ' days ');
        if (hours > 0) result += hours + (hours === 1 ? ' hour ' : ' hours ');
        if (minutes > 0) result += minutes + (minutes === 1 ? ' minute ' : ' minutes ');
        result += seconds + (seconds === 1 ? ' second' : ' seconds');
        
        return result;
    }
    
    // Update duration display with real-time counting
    function updateDuration() {
        if (!startTime || statusSelect.value === 'pending') {
            durationField.value = 'Not started';
            durationSecondsField.value = '0';
            return;
        }
        
        let now = new Date().getTime();
        let endTime = isTimerRunning ? now : (completedTime || now);
        let elapsed = endTime - startTime;
        
        // Ensure we don't show negative time
        if (elapsed < 0) elapsed = 0;
        
        // Update the duration field with the formatted time
        durationField.value = formatDuration(elapsed);
        durationSecondsField.value = Math.floor(elapsed / 1000);
        
        // Add visual cue by briefly changing the border color
        if (isTimerRunning) {
            durationField.style.borderColor = "#10B981"; // Green
            setTimeout(() => {
                durationField.style.borderColor = ""; // Reset to default
            }, 500);
        }
    }
    
    // Start the continuously updating timer
    function startTimer() {
        console.log("START TIMER called");
        // Set the flag to indicate timer is running
        isTimerRunning = true;
        
        // Show the timer indicator
        timerIndicator.classList.remove('hidden');
        
        // Only set new start time if there isn't one already (changing from pending to in_progress)
        if (!startTime) {
            const now = new Date();
            startTime = now.getTime();
            
            // Format for datetime-local input
            const formattedDate = formatDateForInput(now);
            startDateField.value = formattedDate;
            
            // Store the timestamp in the hidden field for PHP
            startDateHidden.value = now.toISOString().slice(0, 19).replace('T', ' ');
        }
        
        // Create interval for continuous updates (every second)
        clearInterval(timerInterval);
        timerInterval = setInterval(updateDuration, 1000);
        updateDuration(); // Initial update
        
        // Start the pulse animation for the indicator
        pulseAnimation();
    }
    
    // Stop the timer
    function stopTimer() {
        console.log("STOP TIMER called");
        // Set the flag to indicate timer is stopped
        isTimerRunning = false;
        
        // Hide the timer indicator
        timerIndicator.classList.add('hidden');
        
        // If status is completed, record completion time
        if (statusSelect.value === 'completed' && !completedTime) {
            // Get current time
            const now = new Date();
            completedTime = now.getTime();
            
            // Format for datetime-local input
            const formattedDate = formatDateForInput(now);
            completedDateField.value = formattedDate;
            
            // Store the ISO string in the hidden field for PHP
            completedDateHidden.value = now.toISOString().slice(0, 19).replace('T', ' ');
        }
        
        // Continue showing the final duration
        clearInterval(timerInterval);
        updateDuration();
        
        // Reset border color
        durationField.style.borderColor = "";
    }
    
    // Reset the timer
    function resetTimer() {
        console.log("RESET TIMER called");
        // Reset all timer-related variables
        startTime = null;
        completedTime = null;
        isTimerRunning = false;
        
        // Clear the fields
        startDateField.value = '';
        startDateHidden.value = '';
        completedDateField.value = '';
        completedDateHidden.value = '';
        durationField.value = 'Not started';
        durationSecondsField.value = '0';
        
        // Stop any running timer
        clearInterval(timerInterval);
        
        // Hide the timer indicator
        timerIndicator.classList.add('hidden');
        
        // Reset border color
        durationField.style.borderColor = "";
    }
    
    // Format a date for datetime-local input (YYYY-MM-DDTHH:MM)
    function formatDateForInput(date) {
        const year = date.getFullYear();
        const month = (date.getMonth() + 1).toString().padStart(2, '0');
        const day = date.getDate().toString().padStart(2, '0');
        const hours = date.getHours().toString().padStart(2, '0');
        const minutes = date.getMinutes().toString().padStart(2, '0');
        
        return `${year}-${month}-${day}T${hours}:${minutes}`;
    }
    
    // Handle status changes
    if (statusSelect) {
        statusSelect.addEventListener('change', function() {
            const currentStatus = this.value;
            
            if (currentStatus === 'in_progress') {
                // When changed to in progress, start the timer
                startTimer();
                
                // Make completion date field readonly
                if (completedDateField) {
                    completedDateField.value = '';
                    completedDateField.readOnly = true;
                    if (completedDateHidden) {
                        completedDateHidden.value = '';
                    }
                }
            } else if (currentStatus === 'completed') {
                // When status is changed to completed, stop the timer
                stopTimer();
                
                // If no completion date set, set it to now
                if (completedDateField && !completedDateField.value) {
                    const now = new Date();
                    const formattedDate = formatDateForInput(now);
                    completedDateField.value = formattedDate;
                    completedDateField.readOnly = true;
                    
                    if (completedDateHidden) {
                        completedDateHidden.value = now.toISOString().slice(0, 19).replace('T', ' ');
                    }
                }
            } else if (currentStatus === 'pending') {
                // For pending status, reset everything
                resetTimer();
                
                // Make completion date field editable
                if (completedDateField) {
                    completedDateField.readOnly = false;
                }
            } else {
                // For other statuses (cancelled), just stop the timer
                stopTimer();
            }
        });
        
        // Initialize based on current status and start time
        const currentStatus = statusSelect.value;
        let initialStartTime = {{ ($repair->status === 'in_progress' && $repair->started_at) ? 
            $repair->started_at->timestamp * 1000 : 
            "null" }};

        if (currentStatus === 'in_progress') {
            // If repair is already in progress, use the existing start time
            startTime = initialStartTime;
            startTimer();
        } else {
            // For all other statuses, reset the timer
            resetTimer();
        }
    }
    
    // Force an initial update based on current status
    if (statusSelect.value === 'pending') {
        resetTimer();
    } else if (statusSelect.value === 'in_progress') {
        // Don't call startTimer() again, as it was already called above if needed
        updateDuration();
    } else {
        updateDuration();
    }
    
    console.log("Timer initialization complete. Status:", statusSelect.value, "Running:", isTimerRunning);
})();

// Other existing functions
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
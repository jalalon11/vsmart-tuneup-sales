@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Repair Details</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('repairs.edit', $repair) }}" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Edit Repair
                </a>
                @if($repair->status === 'completed')
                    <a href="{{ route('repairs.receipt', $repair) }}" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        View Receipt
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Status Information -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Status Information</h2>
                <dl class="space-y-2">
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                        <dd class="mt-1">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                @if($repair->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                @elseif($repair->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                @elseif($repair->status === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300
                                @endif">
                                {{ ucfirst($repair->status) }}
                            </span>
                        </dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</dt>
                        <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $repair->created_at->format('F j, Y g:i A') }}</dd>
                    </div>
                    @if($repair->started_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Started</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $repair->started_at->format('F j, Y') }}</dd>
                        </div>
                    @endif
                    @if($repair->completed_at)
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Completed</dt>
                            <dd class="mt-1 text-gray-900 dark:text-gray-300">{{ $repair->completed_at->format('F j, Y') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>

            <!-- Overall Notes -->
            <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Overall Notes</h2>
                <p class="text-sm text-gray-700 dark:text-gray-300">{{ $repair->notes ?? 'No notes provided.' }}</p>
            </div>
        </div>

        <!-- Repair Items -->
        <div class="mt-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-900 dark:text-white">Repair Items</h2>
            <div class="bg-gray-50 dark:bg-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-600">
                @foreach($repair->items as $item)
                    <div class="p-4">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <!-- Device Information -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Device</h3>
                                <div class="mt-1">
                                    <div class="text-sm text-gray-900 dark:text-white">
                                        @if($item->device->deviceModel)
                                            {{ $item->device->deviceModel->full_name }}
                                        @else
                                            {{ $item->device->brand }} {{ $item->device->model }}
                                        @endif
                                    </div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">
                                        Owner: 
                                        <a href="{{ route('customers.show', $item->device->customer) }}" 
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $item->device->customer->name }}
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Service Information -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Service</h3>
                                <div class="mt-1">
                                    <div class="text-sm text-gray-900 dark:text-white">{{ $item->service->name }}</div>
                                    <div class="text-sm text-gray-600 dark:text-gray-400">Cost: ₱{{ number_format($item->cost, 2) }}</div>
                                </div>
                            </div>

                            <!-- Item Notes -->
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Notes</h3>
                                <div class="mt-1 text-sm text-gray-700 dark:text-gray-300">
                                    {{ $item->notes ?? 'No notes for this item.' }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Total Cost -->
            <div class="mt-4 text-right">
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Cost:</span>
                <span class="ml-2 text-lg font-semibold text-gray-900 dark:text-white">₱{{ number_format($repair->total_cost, 2) }}</span>
            </div>
        </div>

        <div class="mt-6">
            <a href="{{ route('repairs.index') }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                &larr; Back to Repairs
            </a>
        </div>
    </div>
</div>
@endsection 
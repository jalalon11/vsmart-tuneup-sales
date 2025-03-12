@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Header Section with Actions -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center space-x-3">
                <div class="bg-blue-100 dark:bg-blue-900 p-2 rounded-lg">
                    <svg class="h-8 w-8 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Repair #{{ $repair->id }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Created {{ $repair->created_at->setTimezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if($repair->status !== 'completed')
                    <a href="{{ route('repairs.edit', $repair) }}" 
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 btn-hover-effect">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Repair
                    </a>
                @endif
                @if($repair->status === 'completed')
                    <a href="{{ route('repairs.receipt', $repair) }}" 
                        class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 btn-hover-effect"
                        target="_blank"
                        data-turbo="false"
                        data-turbolinks="false">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        View Receipt
                    </a>
                @endif
            </div>
        </div>

        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 dark:bg-green-900 border-l-4 border-green-500 rounded-md flex items-center justify-between animate__animated animate__fadeIn">
                <div class="flex items-center">
                    <svg class="h-5 w-5 text-green-500 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="text-green-700 dark:text-green-300">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Left Column: Status and Customer Info -->
            <div class="lg:col-span-1 space-y-6">
                <!-- Status Card -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-600">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Status Information</h3>
                        <div class="space-y-4">
                            <div>
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($repair->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($repair->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($repair->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($repair->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @endif">
                                    @if($repair->status === 'completed')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        Completed
                                    @elseif($repair->status === 'pending')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        Pending
                                    @elseif($repair->status === 'in_progress')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        In Progress
                                    @elseif($repair->status === 'cancelled')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                        Cancelled
                                    @endif
                                </span>
                            </div>
                            
                            <div class="border-t dark:border-gray-600 pt-4 space-y-3">
                                <!-- Date Received -->
                                <div class="flex items-center text-sm">
                                    <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span class="text-gray-600 dark:text-gray-300">
                                        Received: {{ $repair->created_at->timezone('Asia/Manila')->format('F j, Y g:i A') }} PHT
                                    </span>
                                </div>
                                
                                @if($repair->started_at && $repair->status !== 'pending')
                                    <div class="flex items-center text-sm">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">Started: {{ $repair->started_at->timezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</span>
                                    </div>
                                @endif
                                
                                @if($repair->completed_at)
                                    <div class="flex items-center text-sm">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">Completed: {{ $repair->completed_at->timezone('Asia/Manila')->format('F j, Y g:i A') }} PHT</span>
                                    </div>
                                @endif
                                
                                @if($repair->started_at && $repair->completed_at && $repair->status !== 'pending')
                                    <div class="flex items-center text-sm">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Duration: 
                                            @php
                                                $duration = $repair->started_at->diff($repair->completed_at);
                                                $parts = [];
                                                
                                                if ($duration->d > 0) {
                                                    $parts[] = $duration->d . ' ' . Str::plural('day', $duration->d);
                                                }
                                                if ($duration->h > 0) {
                                                    $parts[] = $duration->h . ' ' . Str::plural('hour', $duration->h);
                                                }
                                                if ($duration->i > 0) {
                                                    $parts[] = $duration->i . ' ' . Str::plural('minute', $duration->i);
                                                }
                                                
                                                echo empty($parts) ? 'Less than a minute' : implode(', ', $parts);
                                            @endphp
                                        </span>
                                    </div>
                                @endif

                                @if($repair->payment_method)
                                    <div class="flex items-center text-sm">
                                        <svg class="h-5 w-5 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                        </svg>
                                        <span class="text-gray-600 dark:text-gray-300">
                                            Payment Method: {{ ucfirst($repair->payment_method) }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Customer Info Card -->
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-600">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Information</h3>
                        <div class="space-y-4">
                            @if($repair->items->isNotEmpty())
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-12 w-12 rounded-full customer-avatar avatar-{{ strtolower(substr($repair->items->first()->device->customer->name, 0, 1)) }} flex items-center justify-center text-xl font-bold">
                                        {{ strtoupper(substr($repair->items->first()->device->customer->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <a href="{{ route('customers.show', $repair->items->first()->device->customer) }}" 
                                           class="text-lg font-medium text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $repair->items->first()->device->customer->name }}
                                        </a>
                                        <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mt-1">
                                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            {{ $repair->items->first()->device->customer->phone }}
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Notes Card -->
                @if($repair->notes)
                    <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-600">
                        <div class="px-4 py-5 sm:p-6">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Additional Notes</h3>
                            <p class="text-gray-600 dark:text-gray-300 text-sm whitespace-pre-wrap">{{ $repair->notes }}</p>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Right Column: Repair Items -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden border border-gray-200 dark:border-gray-600">
                    <div class="px-4 py-5 sm:p-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-6">Repair Items</h3>
                        <div class="space-y-6">
                            @foreach($repair->items as $index => $item)
                                <div class="@if(!$loop->last) border-b border-gray-200 dark:border-gray-600 pb-6 @endif">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Device Details -->
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg">
                                                    <svg class="h-6 w-6 text-blue-600 dark:text-blue-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">
                                                    @if($item->device->deviceModel)
                                                        {{ $item->device->deviceModel->full_name }}
                                                    @else
                                                        {{ $item->device->brand }} {{ $item->device->model }}
                                                    @endif
                                                </h4>
                                                @if($item->device->serial_number)
                                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                                                        Serial Number: {{ $item->device->serial_number }}
                                                    </p>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- Service Details -->
                                        <div class="flex items-start space-x-4">
                                            <div class="flex-shrink-0">
                                                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg">
                                                    <svg class="h-6 w-6 text-purple-600 dark:text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <h4 class="text-lg font-medium text-gray-900 dark:text-white">{{ $item->service->name }}</h4>
                                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Cost: ₱{{ number_format($item->cost, 2) }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    @if($item->notes)
                                        <div class="mt-4 bg-gray-50 dark:bg-gray-600 rounded-md p-4">
                                            <p class="text-sm text-gray-600 dark:text-gray-300">{{ $item->notes }}</p>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>

                        <!-- Total Cost -->
                        <div class="mt-6 pt-6 border-t border-gray-200 dark:border-gray-600">
                            <div class="flex justify-between items-center">
                                <span class="text-lg font-medium text-gray-900 dark:text-white">Total Cost</span>
                                <span class="text-2xl font-bold text-gray-900 dark:text-white">₱{{ number_format($repair->total_cost, 2) }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-6">
            <a href="{{ route('repairs.index') }}" 
                class="inline-flex items-center text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Back to Repairs
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .btn-hover-effect {
        transition: all 0.2s ease;
    }
    
    .btn-hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .customer-avatar {
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
    }

    .dark .customer-avatar {
        background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
        box-shadow: 0 2px 10px rgba(79, 70, 229, 0.3);
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
</style>
@endpush

@endsection 
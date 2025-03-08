@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Repairs</h1>
            <div class="flex items-center space-x-4">
                <!-- Search Form -->
                <form action="{{ route('repairs.index') }}" method="GET" class="flex items-center">
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" 
                               name="search" 
                               value="{{ request('search') }}" 
                               placeholder="Search repairs..." 
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:text-white">
                        @if(request('search'))
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                <a href="{{ route('repairs.index') }}" class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                </form>
                <a href="{{ route('repairs.create') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    New Repair
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                {{ session('error') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        @php
                            $headers = [
                                'customer' => 'Customer',
                                'device' => 'Device',
                                'service' => 'Service',
                                'status' => 'Status',
                                'created_at' => 'Date'
                            ];
                        @endphp

                        @foreach($headers as $key => $label)
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                <a href="{{ route('repairs.index', [
                                    'sort' => $key,
                                    'direction' => ($sortField === $key && $sortDirection === 'asc') ? 'desc' : 'asc',
                                    'search' => request('search')
                                ]) }}" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300">
                                    <span>{{ $label }}</span>
                                    @if($sortField === $key)
                                        <span>
                                            @if($sortDirection === 'asc')
                                                ↑
                                            @else
                                                ↓
                                            @endif
                                        </span>
                                    @endif
                                </a>
                            </th>
                        @endforeach
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($repairs as $repair)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($repair->items->isNotEmpty())
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">
                                        <a href="{{ route('customers.show', $repair->items->first()->device->customer) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                            {{ $repair->items->first()->device->customer->name }}
                                        </a>
                                    </div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400">
                                        {{ $repair->items->first()->device->customer->phone }}
                                    </div>
                                @else
                                    <div class="text-sm text-gray-500 dark:text-gray-400">No items</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @foreach($repair->items as $item)
                                        <div class="mb-1">
                                            @if($item->device->deviceModel)
                                                {{ $item->device->deviceModel->full_name }}
                                            @else
                                                {{ $item->device->brand }} {{ $item->device->model }}
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    @foreach($repair->items as $item)
                                        <div class="mb-1">
                                            {{ $item->service->name }}
                                            <span class="text-gray-500 dark:text-gray-400">₱{{ number_format($item->cost, 2) }}</span>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                    Total: ₱{{ number_format($repair->total_cost, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($repair->status === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                    @elseif($repair->status === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                    @elseif($repair->status === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                    @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst($repair->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $repair->created_at->format('M d, Y') }}
                                @if($repair->started_at)
                                    <div class="text-xs">Started: {{ $repair->started_at->format('M d, Y') }}</div>
                                @endif
                                @if($repair->completed_at)
                                    <div class="text-xs">Completed: {{ $repair->completed_at->format('M d, Y') }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium space-x-2">
                                <a href="{{ route('repairs.show', $repair) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                                <a href="{{ route('repairs.edit', $repair) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">Edit</a>
                                <form action="{{ route('repairs.destroy', $repair) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" 
                                        onclick="return confirm('Are you sure you want to delete this repair?')">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400 text-center">
                                No repairs found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $repairs->links() }}
        </div>
    </div>
</div>
@endsection 
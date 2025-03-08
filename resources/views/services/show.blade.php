@extends('layouts.app')

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold">Service Details</h1>
            <div class="flex items-center space-x-4">
                <a href="{{ route('services.edit', $service) }}" 
                    class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Edit Service
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded">
                {{ session('error') }}
            </div>
        @endif

        <!-- Service Information -->
        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-6 mb-6">
            <dl class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $service->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">{{ $service->category->name }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Price</dt>
                    <dd class="mt-1 text-lg text-gray-900 dark:text-white">₱{{ number_format($service->price, 2) }}</dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</dt>
                    <dd class="mt-1">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                            {{ $service->is_active 
                                ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300' 
                                : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300' }}">
                            {{ $service->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </dd>
                </div>
                @if($service->description)
                <div class="md:col-span-2">
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Description</dt>
                    <dd class="mt-1 text-gray-900 dark:text-white">{{ $service->description }}</dd>
                </div>
                @endif
            </dl>
        </div>

        <!-- Repairs List -->
        <div class="mt-6">
            <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Recent Repairs</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Device
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Customer
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cost
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="relative px-6 py-3">
                                <span class="sr-only">Actions</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($repairs as $repair)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    @if($repair['device']->deviceModel)
                                        {{ $repair['device']->deviceModel->full_name }}
                                    @else
                                        {{ $repair['device']->brand }} {{ $repair['device']->model }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    <a href="{{ route('customers.show', $repair['customer']) }}" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                                        {{ $repair['customer']->name }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        @if($repair['status'] === 'completed') bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300
                                        @elseif($repair['status'] === 'pending') bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300
                                        @elseif($repair['status'] === 'cancelled') bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-300
                                        @else bg-gray-100 dark:bg-gray-600 text-gray-800 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst($repair['status']) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                                    ₱{{ number_format($repair['cost'], 2) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                    {{ $repair['created_at']->format('M d, Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('repairs.show', $repair['id']) }}" 
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
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
        </div>

        <div class="mt-6">
            <a href="{{ route('services.index') }}" 
                class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                &larr; Back to Services
            </a>
        </div>
    </div>
</div>
@endsection 
@extends('layouts.app')

@section('content')
<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900">
        <div class="mb-6">
            <h1 class="text-2xl font-semibold">Edit Device</h1>
        </div>

        <form action="{{ route('devices.update', $device) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Device Name</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $device->name) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Model -->
                <div>
                    <label for="model" class="block text-sm font-medium text-gray-700">Model</label>
                    <input type="text" name="model" id="model" value="{{ old('model', $device->model) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('model')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Serial Number -->
                <div>
                    <label for="serial_number" class="block text-sm font-medium text-gray-700">Serial Number</label>
                    <input type="text" name="serial_number" id="serial_number" value="{{ old('serial_number', $device->serial_number) }}" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('serial_number')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Customer -->
                <div>
                    <label for="customer_id" class="block text-sm font-medium text-gray-700">Customer</label>
                    <select name="customer_id" id="customer_id" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Select a customer</option>
                        @foreach($customers as $customerOption)
                            <option value="{{ $customerOption->id }}" 
                                {{ (old('customer_id', $device->customer_id) == $customerOption->id) ? 'selected' : '' }}>
                                {{ $customerOption->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('customer_id')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Notes -->
                <div class="md:col-span-2">
                    <label for="notes" class="block text-sm font-medium text-gray-700">Notes</label>
                    <textarea name="notes" id="notes" rows="3" 
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('notes', $device->notes) }}</textarea>
                    @error('notes')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="mt-6 flex items-center justify-end">
                <a href="{{ route('devices.show', $device) }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancel</a>
                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    Update Device
                </button>
            </div>
        </form>
    </div>
</div>
@endsection 
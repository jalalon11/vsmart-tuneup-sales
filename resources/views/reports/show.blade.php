@extends('layouts.app')

@section('content')
<div class="space-y-6">
    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Navigation Bar -->
            <nav class="flex items-center justify-between mb-6 border-b pb-4">
                <div class="flex items-center space-x-2">
                    <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-lg transition duration-150 ease-in-out">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                        </svg>
                        Back
                    </a>
                    <h1 class="text-2xl font-semibold">Sales Report - {{ $periodLabel }}</h1>
                </div>
                <div class="flex items-center space-x-4">
                    <form action="{{ route('reports.generate') }}" method="GET" class="flex items-center space-x-4">
                        <div class="flex items-center space-x-2">
                            <label for="period" class="text-sm font-medium text-gray-700">Period:</label>
                            <select name="period" id="period" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                                <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                                <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                            </select>
                        </div>

                        @if($period === 'monthly')
                            <input type="month" name="date" value="{{ request('date', now()->format('Y-m')) }}"
                                class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                        @elseif($period === 'quarterly')
                            <select name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @for($i = 0; $i < 4; $i++)
                                    @php
                                        $quarterMonth = ($i * 3) + 1;
                                        $date = now()->startOfYear()->addMonths($quarterMonth);
                                        $value = $date->format('Y-n');
                                    @endphp
                                    <option value="{{ $value }}" {{ request('date') == $value ? 'selected' : '' }}>
                                        Q{{ $i + 1 }} {{ $date->format('Y') }}
                                    </option>
                                @endfor
                            </select>
                        @else
                            <select name="date" class="rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                                @for($i = 0; $i < 5; $i++)
                                    @php
                                        $year = now()->subYears($i)->year;
                                    @endphp
                                    <option value="{{ $year }}" {{ request('date', now()->year) == $year ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        @endif

                        <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out text-sm">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10H9m3 0h3m-3 4h3m-3-4h-.01M9 17h.01M9 17h-.01"/>
                            </svg>
                            Generate Report
                        </button>
                    </form>
                    <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition duration-150 ease-in-out text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Print Report
                    </button>
                </div>
            </nav>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <div class="bg-blue-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-blue-900 mb-2">Total Sales</h3>
                    <p class="text-3xl font-bold text-blue-600">₱{{ number_format($totalSales, 2) }}</p>
                </div>
                <div class="bg-green-50 rounded-lg p-6">
                    <h3 class="text-lg font-medium text-green-900 mb-2">Total Repairs</h3>
                    <p class="text-3xl font-bold text-green-600">{{ $totalRepairs }}</p>
                </div>
            </div>

            <!-- Service Breakdown -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Service Breakdown</h3>
                <div class="bg-white shadow overflow-hidden rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Service</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Count</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Sales</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($serviceBreakdown as $service => $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $service }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($data['total'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Customer Breakdown -->
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Customer Breakdown</h3>
                <div class="bg-white shadow overflow-hidden rounded-lg">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Repairs</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Spent</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($customerBreakdown as $customer => $data)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $customer }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $data['count'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₱{{ number_format($data['total'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @media print {
        .no-print {
            display: none;
        }
        body {
            padding: 0;
            margin: 0;
        }
        .shadow-sm {
            box-shadow: none !important;
        }
    }
</style>
@endpush
@endsection 
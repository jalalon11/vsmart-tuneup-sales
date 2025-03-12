@extends('layouts.app')

@section('content')
<div class="space-y-6 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition duration-150 ease-in-out">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                            </svg>
                            Back
                        </a>
                        <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Sales Report</h1>
                            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">{{ $periodLabel }}</p>
                        </div>
                    </div>
                    <div class="flex flex-col space-y-4 md:flex-row md:items-center md:space-x-4 md:space-y-0">
                        <div class="flex space-x-2">
                            <button onclick="window.print()" class="inline-flex items-center px-4 py-2 bg-gray-600 dark:bg-gray-700 text-white rounded-lg hover:bg-gray-700 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print
                            </button>
                            <button onclick="exportToExcel()" class="inline-flex items-center px-4 py-2 bg-green-600 dark:bg-green-700 text-white rounded-lg hover:bg-green-700 dark:hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out text-sm">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Export Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="mb-8 bg-gray-50 dark:bg-gray-700/50 rounded-lg p-4">
                <form action="{{ route('reports.generate') }}" method="GET" class="flex flex-col space-y-4 md:flex-row md:items-center md:space-x-4 md:space-y-0">
                    <div class="flex items-center space-x-2">
                        <label for="period" class="text-sm font-medium text-gray-700 dark:text-gray-300">Period:</label>
                        <select name="period" id="period" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                            <option value="monthly" {{ $period === 'monthly' ? 'selected' : '' }}>Monthly</option>
                            <option value="quarterly" {{ $period === 'quarterly' ? 'selected' : '' }}>Quarterly</option>
                            <option value="yearly" {{ $period === 'yearly' ? 'selected' : '' }}>Yearly</option>
                        </select>
                    </div>

                    @if($period === 'monthly')
                        <input type="month" name="date" value="{{ request('date', now()->format('Y-m')) }}"
                            class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
                    @elseif($period === 'quarterly')
                        <select name="date" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
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
                        <select name="date" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">
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

                    <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 dark:bg-blue-700 text-white rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out text-sm">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10H9m3 0h3m-3 4h3m-3-4h-.01M9 17h.01M9 17h-.01"/>
                        </svg>
                        Generate Report
                    </button>
                </form>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-6 border border-blue-100 dark:border-blue-800">
                    <h3 class="text-lg font-medium text-blue-900 dark:text-blue-100 mb-2">Total Sales</h3>
                    <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($totalSales, 2) }}</p>
                    <p class="text-sm text-blue-600 dark:text-blue-300 mt-2">{{ $totalRepairs }} repairs completed</p>
                </div>
                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-6 border border-green-100 dark:border-green-800">
                    <h3 class="text-lg font-medium text-green-900 dark:text-green-100 mb-2">Average Sale</h3>
                    <p class="text-3xl font-bold text-green-600 dark:text-green-400">₱{{ number_format($totalRepairs > 0 ? $totalSales / $totalRepairs : 0, 2) }}</p>
                    <p class="text-sm text-green-600 dark:text-green-300 mt-2">Per repair</p>
                </div>
                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-6 border border-purple-100 dark:border-purple-800">
                    <h3 class="text-lg font-medium text-purple-900 dark:text-purple-100 mb-2">Top Service</h3>
                    <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ array_key_first($serviceBreakdown) ?? 'N/A' }}</p>
                    <p class="text-sm text-purple-600 dark:text-purple-300 mt-2">{{ isset($serviceBreakdown[array_key_first($serviceBreakdown)]) ? $serviceBreakdown[array_key_first($serviceBreakdown)]['count'] . ' repairs' : '' }}</p>
                </div>
                <div class="bg-amber-50 dark:bg-amber-900/20 rounded-lg p-6 border border-amber-100 dark:border-amber-800">
                    <h3 class="text-lg font-medium text-amber-900 dark:text-amber-100 mb-2">Top Customer</h3>
                    <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ array_key_first($customerBreakdown) ?? 'N/A' }}</p>
                    <p class="text-sm text-amber-600 dark:text-amber-300 mt-2">₱{{ isset($customerBreakdown[array_key_first($customerBreakdown)]) ? number_format($customerBreakdown[array_key_first($customerBreakdown)]['total'], 2) : '0.00' }}</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Service Distribution Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Service Distribution</h3>
                    <canvas id="serviceChart" height="300"></canvas>
                </div>
                <!-- Customer Revenue Chart -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Top Customer Revenue</h3>
                    <canvas id="customerChart" height="300"></canvas>
                </div>
            </div>

            <!-- Detailed Tables Section -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Service Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Service Breakdown</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Service</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Count</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Sales</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avg. Sale</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($serviceBreakdown as $service => $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $service }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $data['count'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">₱{{ number_format($data['total'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Customer Breakdown -->
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Customer Breakdown</h3>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Repairs</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Total Spent</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Avg. Repair</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($customerBreakdown as $customer => $data)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">{{ $customer }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $data['count'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">₱{{ number_format($data['total'], 2) }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<!-- Chart.js library with explicit loading check -->
<script>
// Check if Chart.js is already loaded to avoid conflicts
if (typeof Chart === 'undefined') {
    // Create a script element to load Chart.js
    var chartScript = document.createElement('script');
    chartScript.src = 'https://cdn.jsdelivr.net/npm/chart.js';
    chartScript.onload = function() {
        console.log('Chart.js loaded successfully');
        // Initialize charts once loaded
        if (typeof initializeCharts === 'function') {
            initializeCharts();
        }
    };
    document.head.appendChild(chartScript);
} else {
    console.log('Chart.js already loaded');
}
</script>

<!-- XLSX library -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<!-- Main report functionality -->
<script>
// Remove the custom print override and simplify the print function
function handlePrint() {
    window.print();
}

// Update onclick handler in the print button
document.addEventListener('DOMContentLoaded', function() {
    const printButton = document.querySelector('button[onclick="window.print()"]');
    if (printButton) {
        printButton.setAttribute('onclick', 'handlePrint()');
    }
});

// Excel export function
function exportToExcel() {
    var workbook = XLSX.utils.book_new();
    var data = [];
    
    // Header
    data.push(['VSMART TUNE UP - SALES REPORT']);
    data.push(['{{ $periodLabel }}']);
    data.push(['Generated: ' + new Date().toLocaleString() + ' PHT']);
    data.push([]);
    
    // Summary
    data.push(['SUMMARY']);
    data.push(['Total Sales:', '₱{{ number_format($totalSales, 2) }}']);
    data.push(['Total Repairs:', '{{ $totalRepairs }}']);
    data.push(['Average per Repair:', '₱{{ number_format($totalRepairs > 0 ? $totalSales / $totalRepairs : 0, 2) }}']);
    data.push([]);
    
    // Services breakdown
    data.push(['SERVICES BREAKDOWN']);
    data.push(['Service', 'Count', 'Total Sales', 'Average Sale', '% of Total']);
    
    @foreach($serviceBreakdown as $service => $data)
    data.push([
        '{{ $service }}',
        {{ $data['count'] }},
        '₱{{ number_format($data['total'], 2) }}',
        '₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}',
        '{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%'
    ]);
    @endforeach
    
    // Create and save the workbook
    var ws = XLSX.utils.aoa_to_sheet(data);
    XLSX.utils.book_append_sheet(workbook, ws, 'Sales Report');
    
    var fileName = 'VSmart_Sales_Report_{{ $periodLabel }}_' + 
        new Date().toISOString().replace(/[:.]/g, '-') + '.xlsx';
    XLSX.writeFile(workbook, fileName);
}
</script>

<!-- Chart initialization script -->
<script>
// Initialize charts function (called after Chart.js is loaded)
function initializeCharts() {
    console.log('Initializing charts...');
    
    // Find the chart canvases
    var serviceChartCanvas = document.getElementById('serviceChart');
    var customerChartCanvas = document.getElementById('customerChart');
    
    // Check if canvases exist before proceeding
    if (!serviceChartCanvas || !customerChartCanvas) {
        console.warn('Chart canvases not found in DOM');
        return;
    }
    
    try {
        // Service chart data
        var serviceLabels = [];
        var serviceValues = [];
        
        @foreach($serviceBreakdown as $service => $data)
        serviceLabels.push('{{ $service }}');
        serviceValues.push({{ $data['total'] }});
        @endforeach
        
        // Service Distribution Chart
        new Chart(serviceChartCanvas, {
            type: 'doughnut',
            data: {
                labels: serviceLabels,
                datasets: [{
                    data: serviceValues,
                    backgroundColor: [
                        '#3B82F6', '#10B981', '#8B5CF6', '#F59E0B',
                        '#EF4444', '#6366F1', '#EC4899', '#14B8A6'
                    ]
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                var value = context.raw;
                                var total = context.dataset.data.reduce(function(a, b) { 
                                    return a + b; 
                                }, 0);
                                var percentage = ((value / total) * 100).toFixed(1);
                                return '₱' + value.toLocaleString() + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            }
        });
        
        // Customer chart data - limit to top 5 customers
        var customerLabels = [];
        var customerValues = [];
        var customerData = {};
        
        @foreach($customerBreakdown as $customer => $data)
        customerData['{{ $customer }}'] = {{ $data['total'] }};
        @endforeach
        
        // Sort customers by total spent and take top 5
        var sortedCustomers = Object.keys(customerData).sort(function(a, b) {
            return customerData[b] - customerData[a];
        }).slice(0, 5);
        
        sortedCustomers.forEach(function(customer) {
            customerLabels.push(customer);
            customerValues.push(customerData[customer]);
        });
        
        // Customer Revenue Chart
        new Chart(customerChartCanvas, {
            type: 'bar',
            data: {
                labels: customerLabels,
                datasets: [{
                    label: 'Total Spent (₱)',
                    data: customerValues,
                    backgroundColor: '#3B82F6'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
        
        console.log('Charts initialized successfully');
    } catch (err) {
        console.error('Error initializing charts:', err);
    }
}

// Try to initialize charts immediately if Chart.js is already loaded
if (typeof Chart !== 'undefined') {
    // Using timeout to ensure DOM is ready
    setTimeout(initializeCharts, 100);
}
</script>
@endpush

@push('styles')
<style>
    /* Hide print-only content when not printing */
    .print-only {
        display: none;
    }

    @media print {
        /* Hide screen-only elements */
        .no-print {
            display: none !important;
        }

        /* Show print-only elements */
        .print-only {
            display: block !important;
        }

        /* Reset body styles for printing */
        body {
            padding: 0 !important;
            margin: 0 !important;
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
            font-size: 10pt;
            line-height: 1.3;
        }

        /* Hide navigation and other UI elements */
        nav, header, footer, .space-y-6:not(.print-only) {
            display: none !important;
        }

        /* Print layout styling */
        .print-layout {
            display: block !important;
            width: 100%;
            padding: 15px;
            background: white;
            margin: 0 auto;
        }

        /* Page settings */
        @page {
            size: landscape;
            margin: 1cm;
        }

        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 10px;
            border-bottom: 1px solid #000;
        }

        .print-header h1 {
            font-size: 18pt;
            font-weight: bold;
            margin: 0 0 5px 0;
            letter-spacing: 0.5px;
        }

        .print-header p {
            margin: 2px 0;
            font-size: 10pt;
        }

        .print-section {
            margin-bottom: 15px;
        }

        .print-section h2 {
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .print-table th {
            border-bottom: 1px solid #000;
            padding: 6px 8px;
            text-align: left;
            font-size: 9pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        .print-table td {
            padding: 4px 8px;
            border-bottom: 1px solid #ddd;
            font-size: 9pt;
            line-height: 1.2;
        }

        .print-table td:not(:first-child) {
            text-align: right;
        }

        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            margin-bottom: 15px;
        }

        .print-footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #000;
        }

        .print-footer p {
            margin: 2px 0;
            font-size: 8pt;
            color: #666;
        }

        /* Improve table data alignment and spacing */
        .print-table td[data-label="Total Sales"],
        .print-table td[data-label="Average Sale"],
        .print-table td[data-label="% of Total"] {
            font-variant-numeric: tabular-nums;
            font-feature-settings: "tnum";
            padding-right: 8px;
            white-space: nowrap;
        }

        /* Add subtle alternating row colors */
        .print-table tbody tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        /* Make summary tables more compact */
        .print-section .print-table tr td {
            padding: 3px 8px;
        }

        /* Ensure all numerical values are properly aligned */
        .print-table td:nth-child(2),
        .print-table td:nth-child(3),
        .print-table td:nth-child(4),
        .print-table td:nth-child(5) {
            text-align: right;
            white-space: nowrap;
        }

        /* Prevent text wrapping in critical columns */
        .print-table th,
        .print-table td:first-child {
            white-space: nowrap;
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    }
</style>
@endpush

<!-- Print Layout -->
<div class="print-only">
    <div class="print-layout">
        <!-- Header -->
        <div class="print-header">
            <h1>VSMART TUNE UP</h1>
            <p>Sales Report - {{ $periodLabel }}</p>
            <p>Generated: {{ now()->timezone('Asia/Manila')->format('F d, Y h:i A') }} PHT</p>
        </div>

        <!-- Summary Section -->
        <div class="print-section">
            <div class="print-grid">
                <div>
                    <h2>Summary</h2>
                    <table class="print-table">
                        <tr>
                            <td>Total Sales:</td>
                            <td><strong>₱{{ number_format($totalSales, 2) }}</strong></td>
                        </tr>
                        <tr>
                            <td>Total Repairs:</td>
                            <td><strong>{{ $totalRepairs }}</strong></td>
                        </tr>
                        <tr>
                            <td>Average per Repair:</td>
                            <td><strong>₱{{ number_format($totalRepairs > 0 ? $totalSales / $totalRepairs : 0, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
                <div>
                    <h2>Top Performers</h2>
                    <table class="print-table">
                        <tr>
                            <td>Top Service:</td>
                            <td><strong>{{ array_key_first($serviceBreakdown) ?? 'N/A' }}</strong></td>
                        </tr>
                        <tr>
                            <td>Top Customer:</td>
                            <td><strong>{{ array_key_first($customerBreakdown) ?? 'N/A' }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Services Breakdown -->
        <div class="print-section">
            <h2>Services Breakdown</h2>
            <table class="print-table">
                <thead>
                    <tr>
                        <th class="text-left">Service</th>
                        <th class="text-right">Count</th>
                        <th class="text-right">Total Sales</th>
                        <th class="text-right">Average Sale</th>
                        <th class="text-right">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceBreakdown as $service => $data)
                    <tr>
                        <td>{{ $service }}</td>
                        <td>{{ number_format($data['count']) }}</td>
                        <td>₱{{ number_format($data['total'], 2) }}</td>
                        <td>₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                        <td>{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Customers Breakdown -->
        <div class="print-section">
            <h2>Customers Breakdown</h2>
            <table class="print-table">
                <thead>
                    <tr>
                        <th class="text-left">Customer</th>
                        <th class="text-right">Repairs</th>
                        <th class="text-right">Total Spent</th>
                        <th class="text-right">Average Repair</th>
                        <th class="text-right">% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerBreakdown as $customer => $data)
                    <tr>
                        <td>{{ $customer }}</td>
                        <td>{{ number_format($data['count']) }}</td>
                        <td>₱{{ number_format($data['total'], 2) }}</td>
                        <td>₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                        <td>{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Footer -->
        <div class="print-footer">
            <p>Report generated by VSmart SMS</p>
            <p>{{ now()->timezone('Asia/Manila')->format('Y-m-d H:i:s') }} PHT</p>
        </div>
    </div>
</div>
@endsection 
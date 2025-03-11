@extends('layouts.app')

@section('content')
<div class="space-y-6 dark:bg-gray-900">
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6">
            <!-- Header Section -->
            <div class="mb-6">
                <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                    <div class="flex items-center space-x-4">
                        <a href="{{ url()->previous() }}" class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 dark:hover:bg-gray-600 text-gray-800 dark:text-gray-200 rounded-lg transition duration-150 ease-in-out">
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
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
    // Function to initialize or reinitialize charts
    function initializeCharts() {
        const isDark = document.documentElement.classList.contains('dark');
        const textColor = isDark ? '#9ca3af' : '#4b5563';
        const gridColor = isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.1)';

        // Destroy existing charts if they exist
        const existingServiceChart = Chart.getChart('serviceChart');
        if (existingServiceChart) {
            existingServiceChart.destroy();
        }
        const existingCustomerChart = Chart.getChart('customerChart');
        if (existingCustomerChart) {
            existingCustomerChart.destroy();
        }

        // Service Distribution Chart
        const serviceCtx = document.getElementById('serviceChart')?.getContext('2d');
        if (serviceCtx) {
            const serviceData = @json($serviceBreakdown);
            new Chart(serviceCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(serviceData),
                    datasets: [{
                        data: Object.values(serviceData).map(item => item.total),
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
                            position: 'right',
                            labels: {
                                color: textColor
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `₱${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Customer Revenue Chart
        const customerCtx = document.getElementById('customerChart')?.getContext('2d');
        if (customerCtx) {
            const customerData = @json($customerBreakdown);
            new Chart(customerCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(customerData),
                    datasets: [{
                        label: 'Revenue',
                        data: Object.values(customerData).map(item => item.total),
                        backgroundColor: isDark ? '#60A5FA' : '#3B82F6'
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor,
                                callback: value => '₱' + value.toLocaleString()
                            }
                        },
                        x: {
                            grid: {
                                color: gridColor
                            },
                            ticks: {
                                color: textColor
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return '₱' + context.raw.toLocaleString();
                                }
                            }
                        }
                    }
                }
            });
        }
    }

    // Initialize charts on page load
    document.addEventListener('DOMContentLoaded', initializeCharts);

    // Initialize charts when navigating using Laravel's navigation
    document.addEventListener('turbo:load', initializeCharts);
    document.addEventListener('turbolinks:load', initializeCharts);
    
    // Handle dark mode changes
    if (window.Alpine) {
        Alpine.effect(() => {
            const isDark = document.documentElement.classList.contains('dark');
            initializeCharts();
        });
    }

    // Export to Excel function
    function exportToExcel() {
        // Get the data from PHP variables
        const serviceData = @json($serviceBreakdown);
        const customerData = @json($customerBreakdown);
        const totalSales = @json($totalSales);
        const totalRepairs = @json($totalRepairs);
        const periodLabel = @json($periodLabel);
        const currentTime = @json(now()->timezone('Asia/Manila')->format('F d, Y h:i A'));
        
        // Create a new workbook
        const workbook = XLSX.utils.book_new();
        
        // Prepare the data array
        let data = [];
        
        // Add header section
        data.push(['VSMART TUNE UP']);
        data.push(['Sales Report - ' + periodLabel]);
        data.push(['Generated: ' + currentTime + ' PHT']);
        data.push([]);  // Empty row
        
        // Add summary section
        data.push(['SUMMARY']);
        data.push(['Total Sales:', '₱' + totalSales.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2})]);
        data.push(['Total Repairs:', totalRepairs]);
        data.push(['Average per Repair:', '₱' + (totalRepairs > 0 ? (totalSales / totalRepairs).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00')]);
        data.push([]);  // Empty row
        
        // Add top performers section
        data.push(['TOP PERFORMERS']);
        data.push(['Top Service:', Object.keys(serviceData)[0] || 'N/A']);
        data.push(['Top Customer:', Object.keys(customerData)[0] || 'N/A']);
        data.push([]);  // Empty row
        
        // Add services breakdown section
        data.push(['SERVICES BREAKDOWN']);
        data.push(['Service', 'Count', 'Total Sales', 'Average Sale', '% of Total']);
        
        // Add service rows
        Object.entries(serviceData).forEach(([service, details]) => {
            data.push([
                service,
                details.count,
                '₱' + details.total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                '₱' + (details.count > 0 ? (details.total / details.count).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'),
                ((details.total / totalSales) * 100).toFixed(1) + '%'
            ]);
        });
        
        data.push([]);  // Empty row
        
        // Add customers breakdown section
        data.push(['CUSTOMERS BREAKDOWN']);
        data.push(['Customer', 'Repairs', 'Total Spent', 'Average Repair', '% of Total']);
        
        // Add customer rows
        Object.entries(customerData).forEach(([customer, details]) => {
            data.push([
                customer,
                details.count,
                '₱' + details.total.toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}),
                '₱' + (details.count > 0 ? (details.total / details.count).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) : '0.00'),
                ((details.total / totalSales) * 100).toFixed(1) + '%'
            ]);
        });
        
        data.push([]);  // Empty row
        
        // Add footer
        data.push(['Report generated by VSmart SMS']);
        data.push([@json(now()->timezone('Asia/Manila')->format('Y-m-d H:i:s')) + ' PHT']);
        
        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(data);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 35 }, // A
            { wch: 15 }, // B
            { wch: 15 }, // C
            { wch: 15 }, // D
            { wch: 12 }  // E
        ];
        
        // Merge cells for header
        ws['!merges'] = [
            { s: { r: 0, c: 0 }, e: { r: 0, c: 4 } }, // A1:E1
            { s: { r: 1, c: 0 }, e: { r: 1, c: 4 } }, // A2:E2
            { s: { r: 2, c: 0 }, e: { r: 2, c: 4 } }, // A3:E3
        ];
        
        // Add the worksheet to workbook
        XLSX.utils.book_append_sheet(workbook, ws, 'Sales Report');
        
        // Generate filename
        const date = new Date();
        const dateStr = date.toLocaleDateString('en-PH').replace(/\//g, '-');
        const timeStr = date.toLocaleTimeString('en-PH').replace(/:/g, '-').split(' ')[0];
        const fileName = `VSmart_Sales_Report_${periodLabel}_${dateStr}_${timeStr}_PHT.xlsx`;
        
        // Save the file
        XLSX.writeFile(workbook, fileName);
    }
</script>
@endpush

@push('styles')
<style>
    @media print {
        /* Hide everything except print layout */
        .no-print, .space-y-6, nav, header, footer {
            display: none !important;
        }
        .print-only {
            display: block !important;
        }
        
        /* Print page setup */
        @page {
            size: landscape;
            margin: 0.5cm;
        }
        
        body {
            padding: 0 !important;
            margin: 0 !important;
            font-family: 'Courier New', monospace;
            background: white !important;
            -webkit-print-color-adjust: exact !important;
            print-color-adjust: exact !important;
        }

        /* Print layout styling */
        .print-layout {
            width: 100%;
            padding: 20px;
            background: white;
        }

        .print-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 10px;
            border-bottom: 2px solid #000;
        }

        .print-header h1 {
            font-size: 24px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .print-header p {
            margin: 2px 0;
            font-size: 14px;
        }

        .print-section {
            margin-bottom: 20px;
        }

        .print-section h2 {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        .print-table th {
            border-bottom: 1px solid #000;
            padding: 8px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
        }

        .print-table td {
            padding: 6px 8px;
            border-bottom: 1px dotted #ccc;
            font-size: 12px;
        }

        .print-table td:not(:first-child) {
            text-align: right;
        }

        .print-footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 10px;
            border-top: 2px solid #000;
        }

        .print-footer p {
            margin: 5px 0;
            font-size: 12px;
        }

        .print-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 40px;
        }
    }

    .print-only {
        display: none;
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
                        <th>Service</th>
                        <th>Count</th>
                        <th>Total Sales</th>
                        <th>Average Sale</th>
                        <th>% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($serviceBreakdown as $service => $data)
                    <tr>
                        <td>{{ $service }}</td>
                        <td>{{ $data['count'] }}</td>
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
                        <th>Customer</th>
                        <th>Repairs</th>
                        <th>Total Spent</th>
                        <th>Average Repair</th>
                        <th>% of Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($customerBreakdown as $customer => $data)
                    <tr>
                        <td>{{ $customer }}</td>
                        <td>{{ $data['count'] }}</td>
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
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
                            <!-- New Print Button -->
                            <button id="printButton" class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-lg hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 transition duration-150 ease-in-out text-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                                </svg>
                                Print Report
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

                    <div class="date-input-container">
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
                    </div>

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
<!-- XLSX library -->
<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Wrap all JavaScript in an IIFE to avoid global scope pollution
(function() {
    // Flag variables for preventing multiple executions
    let isPrintingInProgress = false;
    let excelExportInProgress = false;
    let reportPageInitialized = false;

    // Print function
    function handlePrint() {
        // Check if a print operation is already in progress
        if (isPrintingInProgress) {
            console.log('Print operation already in progress, ignoring additional clicks');
            return;
        }
        
        // Set flag to prevent multiple print operations
        isPrintingInProgress = true;
        
        try {
            const printWindow = window.open('', '_blank');
            if (!printWindow) {
                alert('Please allow pop-ups to print the report');
                isPrintingInProgress = false;
                return;
            }
            
            // Get the print-only element
            let printContent = document.querySelector('.print-only');
            if (!printContent) {
                console.error('Print content not found, generating printable content dynamically');
                // Create a dynamic print version if the static one doesn't exist
                const mainContent = document.querySelector('.space-y-6');
                if (!mainContent) {
                    alert('Unable to generate printable content');
                    isPrintingInProgress = false;
                    return;
                }
                
                // Create print container
                printContent = document.createElement('div');
                printContent.className = 'print-only';
                printContent.innerHTML = `
                    <div class="print-layout">
                        <div class="print-header">
                            <h1>VSMART TUNE UP</h1>
                            <p>Sales Report - ${document.querySelector('p.mt-1.text-sm').textContent || 'Sales Report'}</p>
                            <p>Generated: ${new Date().toLocaleString('en-US', { timeZone: 'Asia/Manila' })} PHT</p>
                        </div>
                        <div class="print-section">
                            <!-- Dynamic content will be generated here -->
                        </div>
                    </div>
                `;
                
                // This is a fallback and may not be perfect - recommend user to check the print preview
                document.body.appendChild(printContent);
            }

            // Get all style tags
            const styles = Array.from(document.getElementsByTagName('style'))
                .map(style => style.outerHTML)
                .join('\n');

            // Add any additional styles needed for printing
            const printStyles = `
                <style>
                    @page {
                        size: landscape;
                        margin: 0.5cm;
                    }
                    body {
                        padding: 0 !important;
                        margin: 0 !important;
                        background: white !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                        font-family: Arial, sans-serif;
                        font-size: 8pt !important;
                        line-height: 1.2;
                    }
                    .print-only { display: block !important; }
                    @media print {
                        body { print-color-adjust: exact !important; }
                    }
                    .print-layout {
                        padding: 8px;
                        max-width: 100%;
                        margin: 0 auto;
                    }
                    .print-header {
                        text-align: center;
                        margin-bottom: 8px;
                        padding-bottom: 4px;
                        border-bottom: 1px solid #000;
                    }
                    .print-header h1 {
                        font-size: 16px;
                        font-weight: bold;
                        margin: 0 0 2px 0;
                        color: #000;
                    }
                    .print-header p {
                        font-size: 8pt;
                        margin: 1px 0;
                    }
                    h2 {
                        font-size: 10px;
                        font-weight: bold;
                        margin: 4px 0;
                        text-transform: uppercase;
                    }
                    .print-table {
                        width: 100%;
                        border-collapse: collapse;
                        margin-bottom: 6px;
                        font-size: 7.5pt;
                    }
                    .print-table th {
                        background-color: #f3f4f6 !important;
                        color: #000 !important;
                        font-weight: bold;
                        text-align: left;
                        padding: 3px 4px;
                        border: 1px solid #e5e7eb;
                        font-size: 7.5pt;
                        white-space: nowrap;
                    }
                    .print-table td {
                        padding: 2px 4px;
                        border: 1px solid #e5e7eb;
                        font-size: 7.5pt;
                    }
                    .print-table tr:nth-child(even) {
                        background-color: #f9fafb;
                    }
                    /* Compact layout for better data fitting */
                    .print-grid {
                        display: grid;
                        grid-template-columns: 1fr 1fr;
                        gap: 8px;
                        margin-bottom: 6px;
                    }
                    .print-table td:not(:first-child) {
                        text-align: right;
                    }
                    /* Set fixed height for main content to prevent page breaks in the middle */
                    .print-section {
                        page-break-inside: avoid;
                    }
                    /* Ensure numerical values align properly */
                    .print-table td:nth-child(2),
                    .print-table td:nth-child(3),
                    .print-table td:nth-child(4),
                    .print-table td:nth-child(5) {
                        text-align: right;
                        white-space: nowrap;
                    }
                    .print-footer {
                        font-size: 7pt;
                        text-align: center;
                        margin-top: 4px;
                        padding-top: 3px;
                        border-top: 1px solid #ddd;
                    }
                    .print-footer p {
                        margin: 1px 0;
                        line-height: 1.2;
                    }
                    /* Optimize column widths for services/customers tables */
                    .print-table th:first-child,
                    .print-table td:first-child {
                        max-width: 150px;
                        overflow: hidden;
                        text-overflow: ellipsis;
                    }
                    .print-table th:not(:first-child),
                    .print-table td:not(:first-child) {
                        width: 1%;
                        white-space: nowrap;
                    }
                    /* Add proper spacing for better readability */
                    .print-table td strong {
                        font-weight: bold;
                    }
                </style>
            `;
            
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>VSmart Sales Report - ${new Date().toLocaleDateString()}</title>
                    ${styles}
                    ${printStyles}
                </head>
                <body>
                    ${printContent.innerHTML}
                </body>
                </html>
            `);
            
            printWindow.document.close();
            
            // Wait for images and styles to load
            setTimeout(() => {
                printWindow.print();
                // Reset the printing flag after a delay to ensure the print dialog has time to appear
                setTimeout(() => {
                    isPrintingInProgress = false;
                }, 1000);
            }, 500);
        } catch (error) {
            console.error('Error during print operation:', error);
            isPrintingInProgress = false;
        }
    }

    // Excel export function
    function exportToExcel() {
        // Check if export is already in progress
        if (excelExportInProgress) {
            console.log('Excel export already in progress, ignoring additional clicks');
            return;
        }
        
        // Set flag to prevent multiple exports
        excelExportInProgress = true;
        
        // Check if XLSX is loaded
        if (typeof XLSX === 'undefined') {
            alert('Excel export library is not loaded. Please try again or check your internet connection.');
            excelExportInProgress = false;
            return;
        }

        try {
            // Show loading indicator
            const exportBtn = document.querySelector('button[onclick="exportToExcel()"]');
            if (exportBtn) {
                exportBtn.disabled = true;
                exportBtn.innerHTML = '<svg class="animate-spin w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Exporting...';
            }

            var workbook = XLSX.utils.book_new();
            
            // Create a spreadsheet that matches the print layout
            // Header section
            var headerData = [
                ['VSMART TUNE UP'],
                ['Sales Report - {{ $periodLabel }}'],
                ['Generated: ' + new Date().toLocaleString() + ' PHT'],
                []
            ];
            
            // Create main worksheet
            var ws = XLSX.utils.aoa_to_sheet(headerData);
            
            // Add merged cell for title (A1:H1)
            if(!ws['!merges']) ws['!merges'] = [];
            ws['!merges'].push({s:{r:0,c:0}, e:{r:0,c:7}});
            ws['!merges'].push({s:{r:1,c:0}, e:{r:1,c:7}});
            ws['!merges'].push({s:{r:2,c:0}, e:{r:2,c:7}});
            
            // Apply styles to header
            if(!ws['!cols']) ws['!cols'] = [];
            // Set column widths
            for(let i = 0; i < 8; i++) {
                ws['!cols'].push({ wch: i === 0 ? 25 : 15 });
            }
            
            // Create Summary & Top Performers section - side by side
            var summaryStartRow = 5;
            XLSX.utils.sheet_add_aoa(ws, [
                ['Summary', '', 'Top Performers', ''],
                ['Total Sales:', '₱{{ number_format($totalSales, 2) }}', 'Top Service:', '{{ array_key_first($serviceBreakdown) ?? "N/A" }}'],
                ['Total Repairs:', '{{ $totalRepairs }}', 'Top Customer:', '{{ array_key_first($customerBreakdown) ?? "N/A" }}'],
                ['Average per Repair:', '₱{{ number_format($totalRepairs > 0 ? $totalSales / $totalRepairs : 0, 2) }}', '', ''],
                []
            ], {origin: {r: summaryStartRow, c: 0}});
            
            // Style headers for summary sections
            ws['!merges'].push({s:{r:summaryStartRow,c:0}, e:{r:summaryStartRow,c:1}});
            ws['!merges'].push({s:{r:summaryStartRow,c:2}, e:{r:summaryStartRow,c:3}});
            
            // Add Services Breakdown and Customer Breakdown side by side
            var dataStartRow = summaryStartRow + 6;
            
            // Service breakdown header
            XLSX.utils.sheet_add_aoa(ws, [
                ['Services Breakdown', '', '', ''],
                ['Service', 'Count', 'Total', '%'],
            ], {origin: {r: dataStartRow, c: 0}});
            
            // Customer breakdown header (to the right)
            XLSX.utils.sheet_add_aoa(ws, [
                ['Customers Breakdown', '', '', ''],
                ['Customer', 'Repairs', 'Total', '%'],
            ], {origin: {r: dataStartRow, c: 4}});
            
            // Merge header cells
            ws['!merges'].push({s:{r:dataStartRow,c:0}, e:{r:dataStartRow,c:3}});
            ws['!merges'].push({s:{r:dataStartRow,c:4}, e:{r:dataStartRow,c:7}});
            
            // Add service data rows
            var serviceDataRow = dataStartRow + 2;
            @foreach($serviceBreakdown as $service => $data)
            XLSX.utils.sheet_add_aoa(ws, [
                [
                    '{{ Str::limit($service, 20) }}', 
                    {{ $data['count'] }}, 
                    '₱{{ number_format($data['total'], 2) }}', 
                    '{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%'
                ]
            ], {origin: {r: serviceDataRow, c: 0}});
            serviceDataRow++;
            @endforeach
            
            // Add customer data rows
            var customerDataRow = dataStartRow + 2;
            @foreach($customerBreakdown as $customer => $data)
            XLSX.utils.sheet_add_aoa(ws, [
                [
                    '{{ Str::limit($customer, 20) }}', 
                    {{ $data['count'] }}, 
                    '₱{{ number_format($data['total'], 2) }}', 
                    '{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%'
                ]
            ], {origin: {r: customerDataRow, c: 4}});
            customerDataRow++;
            @endforeach
            
            // Add footer
            var footerRow = Math.max(serviceDataRow, customerDataRow) + 2;
            XLSX.utils.sheet_add_aoa(ws, [
                ['Report generated by VSmart SMS'],
                ['{{ now()->timezone("Asia/Manila")->format("Y-m-d H:i:s") }} PHT']
            ], {origin: {r: footerRow, c: 0}});
            
            // Merge footer cells
            ws['!merges'].push({s:{r:footerRow,c:0}, e:{r:footerRow,c:7}});
            ws['!merges'].push({s:{r:footerRow+1,c:0}, e:{r:footerRow+1,c:7}});
            
            // Add worksheet to workbook
            XLSX.utils.book_append_sheet(workbook, ws, 'Sales Report');
            
            // Create filename
            var fileName = 'VSmart_Sales_Report_{{ $periodLabel }}_' + 
                new Date().toISOString().replace(/[:.]/g, '-') + '.xlsx';
            
            // Write and download file
            XLSX.writeFile(workbook, fileName);
            
            console.log('Excel export completed successfully');
        } catch (error) {
            console.error('Error exporting to Excel:', error);
            alert('There was an error creating the Excel file. Please try again.');
        } finally {
            // Reset export button and flag after a delay
            setTimeout(() => {
                excelExportInProgress = false;
                const exportBtn = document.querySelector('button[onclick="exportToExcel()"]');
                if (exportBtn) {
                    exportBtn.disabled = false;
                    exportBtn.innerHTML = '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Export Excel';
                }
            }, 1000);
        }
    }

    // Function to handle period selection change
    function handlePeriodChange() {
        const periodSelect = document.getElementById('period');
        const dateContainer = document.querySelector('.date-input-container');
        
        if (periodSelect && dateContainer) {
            periodSelect.addEventListener('change', function() {
                const selectedPeriod = this.value;
                let dateInput = '';
                
                if (selectedPeriod === 'monthly') {
                    const currentDate = new Date();
                    const year = currentDate.getFullYear();
                    const month = (currentDate.getMonth() + 1).toString().padStart(2, '0');
                    
                    dateInput = `<input type="month" name="date" value="${year}-${month}" 
                        class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">`;
                } else if (selectedPeriod === 'quarterly') {
                    const currentDate = new Date();
                    const year = currentDate.getFullYear();
                    
                    dateInput = `<select name="date" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">`;
                    
                    for(let i = 0; i < 4; i++) {
                        const quarter = i + 1;
                        dateInput += `<option value="${year}-${(i * 3) + 1}">Q${quarter} ${year}</option>`;
                    }
                    
                    dateInput += `</select>`;
                } else {
                    // Yearly
                    const currentDate = new Date();
                    const currentYear = currentDate.getFullYear();
                    
                    dateInput = `<select name="date" class="rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-sm">`;
                    
                    for(let i = 0; i < 5; i++) {
                        const year = currentYear - i;
                        dateInput += `<option value="${year}">${year}</option>`;
                    }
                    
                    dateInput += `</select>`;
                }
                
                dateContainer.innerHTML = dateInput;
            });
        }
    }

    // Update initializeReportPage to include the period change handler
    window.initializeReportPage = function() {
        // Exit early if already initialized in this session
        if (reportPageInitialized) {
            console.log('Report page already initialized in this session, skipping');
            return;
        }
        
        console.log('Initializing report page components...');
        reportPageInitialized = true;
        
        try {
            // Ensure print-only element is hidden
            const printOnlyElement = document.querySelector('.print-only');
            if (printOnlyElement) {
                printOnlyElement.style.display = 'none';
            }

            // Setup print button - support both old and new button IDs/selectors
            const printButton = document.getElementById('printButton');
            
            // Clear any existing event listeners using a more reliable approach
            if (printButton) {
                // Clone the button to remove all event listeners
                const newPrintButton = printButton.cloneNode(true);
                printButton.parentNode.replaceChild(newPrintButton, printButton);
                
                // Add new click listener
                newPrintButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    handlePrint();
                });
                console.log('Print button initialized with new event listener');
            } else {
                console.warn('Print button not found. Will use event delegation as fallback.');
            }

            // Initialize charts if Chart.js is loaded
            if (typeof Chart !== 'undefined') {
                initializeCharts();
            } else {
                console.warn('Chart.js not loaded yet - will initialize when available');
            }
            
            // Add period change handler
            handlePeriodChange();
            
            console.log('Report page initialization complete');
        } catch (err) {
            console.error('Error initializing report page:', err);
        }
    };

    // Initialize on DOM ready - but only once
    document.addEventListener('DOMContentLoaded', function() {
        window.initializeReportPage();
        
        // Fix Excel button event handling when page loads
        const exportButton = document.querySelector('button[onclick="exportToExcel()"]');
        if (exportButton) {
            // Alternative approach: Remove the onclick attribute and add a proper event listener
            const newExportBtn = exportButton.cloneNode(true);
            newExportBtn.removeAttribute('onclick');
            newExportBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                exportToExcel();
            });
            exportButton.parentNode.replaceChild(newExportBtn, exportButton);
            console.log('Export button reinitialized with JavaScript event listener');
        }
    });

    // Initialize when page is loaded via SPA navigation
    document.addEventListener('page:loaded', window.initializeReportPage);

    // Use a more targeted approach for delegation
    document.body.addEventListener('click', function(e) {
        const button = e.target.closest('button[id="printButton"]');
        if (button) {
            e.preventDefault();
            e.stopPropagation();
            handlePrint();
        }
    });

    // Make exportToExcel globally accessible for the inline onclick attribute
    window.exportToExcel = exportToExcel;

})(); // End of IIFE
</script>

<!-- Chart initialization script -->
<script>
// Wrap chart initialization code in IIFE
(function() {
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

    // Make initializeCharts globally accessible for the page initialization function
    window.initializeCharts = initializeCharts;

    // Try to initialize charts immediately if Chart.js is already loaded
    if (typeof Chart !== 'undefined') {
        // Using timeout to ensure DOM is ready
        setTimeout(window.initializeCharts, 100);
    } else {
        // If Chart.js isn't loaded yet, try again when it loads
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof Chart !== 'undefined') {
                window.initializeCharts();
            } else {
                // Set a watcher to check if Chart.js loads later
                let chartJsCheckInterval = setInterval(function() {
                    if (typeof Chart !== 'undefined') {
                        window.initializeCharts();
                        clearInterval(chartJsCheckInterval);
                    }
                }, 200);
                // Clear interval after 5 seconds if Chart.js never loads
                setTimeout(function() { clearInterval(chartJsCheckInterval); }, 5000);
            }
        });
    }
})();
</script>
@endpush

@push('styles')
<style>
    /* Hide print-only content when not printing */
    .print-only {
        display: none !important;
    }

    body .print-only, 
    html .print-only {
        display: none !important;
    }

    @media print {
        /* Base print reset */
        *, *::before, *::after {
            box-sizing: border-box;
        }
        
        /* Hide screen-only elements */
        nav, header, footer, .space-y-6:not(.print-only) {
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
            font-family: 'Arial', sans-serif;
            font-size: 9pt;
            line-height: 1.4;
            color: #1e293b;
        }

        /* Page settings */
        @page {
            size: landscape;
            margin: 1cm;
        }

        /* Print layout styling */
        .print-layout {
            max-width: 100%;
            margin: 0 auto;
            background: white;
        }

        /* Header */
        .print-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #2563eb;
        }

        .print-header h1 {
            font-size: 18pt;
            font-weight: 700;
            margin: 0 0 4px 0;
            color: #1e3a8a;
        }

        .print-header p {
            margin: 2px 0;
            font-size: 9pt;
            color: #475569;
        }

        /* Report info bar */
        .print-metadata {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 8pt;
        }

        /* KPI Cards */
        .print-kpi-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 20px;
            margin-bottom: 25px;
        }

        .print-kpi-card {
            padding: 12px 15px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
        }

        .print-kpi-card h3 {
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 0 0 8px 0;
            color: #64748b;
        }

        .print-kpi-card .value {
            font-size: 14pt;
            font-weight: 700;
            margin: 0;
            color: #1e293b;
        }

        .print-kpi-card .caption {
            font-size: 8pt;
            color: #64748b;
            margin: 4px 0 0 0;
        }

        /* Section styling */
        .print-section {
            margin-bottom: 25px;
        }

        .print-section-header {
            margin-bottom: 12px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .print-section-header h2 {
            font-size: 11pt;
            font-weight: 600;
            margin: 0;
            color: #1e293b;
        }

        /* Tables */
        .print-table-wrapper {
            margin-bottom: 20px;
            border: 1px solid #e2e8f0;
            border-radius: 4px;
            overflow: hidden;
        }

        .print-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8.5pt;
        }

        .print-table thead {
            background-color: #f1f5f9;
        }

        .print-table th {
            padding: 8px 12px;
            text-align: left;
            font-weight: 600;
            color: #475569;
            border-bottom: 1px solid #e2e8f0;
            font-size: 8pt;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .print-table td {
            padding: 6px 12px;
            border-bottom: 1px solid #f1f5f9;
        }

        .print-table tr:last-child td {
            border-bottom: none;
        }

        .print-table tbody tr:nth-child(even) {
            background-color: #f8fafc;
        }

        /* Data styling */
        .print-table th:not(:first-child),
        .print-table td:not(:first-child) {
            text-align: right;
        }

        .print-table td:first-child {
            font-weight: 500;
            color: #334155;
        }

        .print-table .number {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
            white-space: nowrap;
        }

        .print-table .currency {
            color: #1e293b;
            font-weight: 600;
        }

        .print-table .percentage {
            color:rgb(235, 37, 37);
            font-weight: 500;
        }

        /* Footer */
        .print-footer {
            margin-top: 30px;
            padding-top: 10px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            font-size: 7.5pt;
            color: #64748b;
        }

        .print-footer p {
            margin: 0;
        }

        .print-footer .confidential {
            font-weight: 600;
            letter-spacing: 0.5px;
        }

        /* Summary Table Specific Styles */
        .summary-table {
            margin-bottom: 30px;
        }

        .summary-table th {
            background-color: #f8fafc !important;
            color: #1e293b !important;
            font-size: 9pt;
            padding: 10px 15px;
            border-bottom: 2px solid #e2e8f0;
        }

        .summary-table td {
            padding: 8px 15px;
            vertical-align: middle;
        }

        .summary-table .metric-name {
            font-weight: 600;
            color: #1e293b;
            width: 25%;
        }

        .summary-table .metric-value {
            font-weight: 700;
            color:rgb(235, 37, 37);
            width: 25%;
            text-align: right;
        }

        .summary-table .metric-detail {
            color: #64748b;
            font-size: 8.5pt;
            padding-left: 20px;
        }

        .summary-table tr:hover {
            background-color: #f8fafc;
        }

        .summary-table .currency {
            font-feature-settings: "tnum";
            font-variant-numeric: tabular-nums;
        }

        .smart-text {
            font-weight: 700;
            color: #e10000;
        }
    }
</style>
@endpush

<!-- Print Layout -->
<div class="print-only">
    <div class="print-layout">
        <!-- Header -->
        <div class="print-header">
            <h1>V<span class="smart-text">SMART</span> TUNE UP</h1>
            <p>Sales Performance Report - {{ $periodLabel }}</p>
        </div>

        <!-- Report Info -->
        <div class="print-metadata">
            <div>Report ID: SR-{{ now()->format('Ymd') }}-{{ rand(1000, 9999) }}</div>
            <div>Generated: {{ now()->timezone('Asia/Manila')->format('F d, Y h:i A') }} PHT</div>
        </div>

        <!-- KPI Summary -->
        <div class="print-section">
            <div class="print-section-header">
                <h2>Performance Summary</h2>
            </div>
            <div class="print-table-wrapper">
                <table class="print-table summary-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;">Metric</th>
                            <th style="width: 30%;">Value</th>
                            <th style="width: 40%;">Details</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="metric-name">Total Sales Revenue</td>
                            <td class="metric-value currency">₱{{ number_format($totalSales, 2) }}</td>
                            <td class="metric-detail">Total revenue for the period</td>
                        </tr>
                        <tr>
                            <td class="metric-name">Completed Repairs</td>
                            <td class="metric-value">{{ number_format($totalRepairs) }}</td>
                            <td class="metric-detail">Total number of repair jobs</td>
                        </tr>
                        <tr>
                            <td class="metric-name">Average Revenue per Repair</td>
                            <td class="metric-value currency">₱{{ number_format($totalRepairs > 0 ? $totalSales / $totalRepairs : 0, 2) }}</td>
                            <td class="metric-detail">Average revenue generated per repair job</td>
                        </tr>
                        <tr>
                            <td class="metric-name">Most Requested Service</td>
                            <td class="metric-value">{{ Str::limit(array_key_first($serviceBreakdown) ?? 'N/A', 35) }}</td>
                            <td class="metric-detail">{{ isset($serviceBreakdown[array_key_first($serviceBreakdown)]) ? $serviceBreakdown[array_key_first($serviceBreakdown)]['count'] . ' repairs completed' : 'No data available' }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Services Breakdown -->
        <div class="print-section">
            <div class="print-section-header">
                <h2>Services Performance</h2>
            </div>
            <div class="print-table-wrapper">
                <table class="print-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;" >Service Type</th>
                            <th style="width: 20%;">Jobs</th>
                            <th style="width: 20%;">Revenue</th>
                            <th style="width: 20%;">Average</th>
                            <th style="width: 30%;">Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($serviceBreakdown as $service => $data)
                        <tr>
                            <td>{{ Str::limit($service, 35) }}</td>
                            <td class="number">{{ number_format($data['count']) }}</td>
                            <td class="number currency">₱{{ number_format($data['total'], 2) }}</td>
                            <td class="number currency">₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                            <td class="number percentage">{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Customer Analysis -->
        <div class="print-section">
            <div class="print-section-header">
                <h2>Customer Analysis</h2>
            </div>
            <div class="print-table-wrapper">
                <table class="print-table">
                    <thead>
                        <tr>
                            <th style="width: 30%;" >Customer Name</th>
                            <th style="width: 20%;">Total Service</th>
                            <th style="width: 20%;">Total Revenue</th>
                            <th style="width: 20%;">Average Spend</th>
                            <th style="width: 30%;">Revenue Share</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customerBreakdown as $customer => $data)
                        <tr>
                            <td>{{ Str::limit($customer, 35) }}</td>
                            <td class="number">{{ number_format($data['count']) }}</td>
                            <td class="number currency">₱{{ number_format($data['total'], 2) }}</td>
                            <td class="number currency">₱{{ number_format($data['count'] > 0 ? $data['total'] / $data['count'] : 0, 2) }}</td>
                            <td class="number percentage">{{ number_format(($data['total'] / $totalSales) * 100, 1) }}%</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Footer -->
        <div class="print-footer">
            <div>
                <p>VSMART SMS © {{ now()->format('Y') }} | Financial Report</p>
            </div>
            <div class="confidential">
                <p>CONFIDENTIAL AND PROPRIETARY</p>
            </div>
        </div>
    </div>
</div>
@endsection 

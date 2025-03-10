@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome back, {{ Auth::user()->name }}!</h2>
                    <p class="mt-1 text-gray-600 dark:text-gray-400">Here's an overview of your business today.</p>
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ now()->format('l, F j, Y') }}
                </div>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Total Pending Repairs -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-amber-200 dark:border-amber-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">Total Pending Repair</p>
                            <p class="text-3xl font-extrabold text-amber-700 dark:text-amber-300 mt-2">{{ $pendingRepairs ?? 0 }}</p>
                            <div class="flex items-center mt-1 text-xs text-amber-600 dark:text-amber-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Awaiting completion
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-amber-400 to-amber-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Total Customers -->
            <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-green-200 dark:border-green-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-green-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Total Customers</p>
                            <p class="text-3xl font-extrabold text-green-700 dark:text-green-300 mt-2">{{ $totalCustomers ?? 0 }}</p>
                            <div class="flex items-center mt-1 text-xs text-green-600 dark:text-green-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Registered clients
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-green-400 to-green-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Active Services -->
            <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-purple-200 dark:border-purple-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-purple-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Active Services</p>
                            <p class="text-3xl font-extrabold text-purple-700 dark:text-purple-300 mt-2">{{ $activeServices ?? 0 }}</p>
                            <div class="flex items-center mt-1 text-xs text-purple-600 dark:text-purple-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                    </svg>
                                    Available offerings
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-purple-400 to-purple-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Low Stock Items -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-red-200 dark:border-red-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">Low Stock Items</p>
                            <p class="text-3xl font-extrabold text-red-700 dark:text-red-300 mt-2">{{ $lowStockItems ?? 0 }}</p>
                            <div class="flex items-center mt-1 text-xs text-red-600 dark:text-red-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                    </svg>
                                    Needs reordering
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Quick Actions</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-4">
                <a href="{{ route('repairs.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors group">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                    </svg>
                    <span class="font-medium">New Repair</span>
                </a>
                
                <a href="{{ route('customers.create') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors group">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                    </svg>
                    <span class="font-medium">Add Customer</span>
                </a>

                <a href="{{ route('customers.index') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-yellow-600 text-white rounded-lg hover:bg-yellow-700 transition-colors group">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                    </svg>
                    <span class="font-medium">Manage Devices</span>
                </a>

                <a href="{{ route('reports.generate') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors group">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span class="font-medium">Generate Report</span>
                </a>
                
                <a href="{{ route('user.profile') }}" 
                   class="inline-flex items-center justify-center px-4 py-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors group">
                    <svg class="h-6 w-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    <span class="font-medium">My Profile</span>
                </a>
            </div>
        </div>

        <!-- Sales Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Weekly Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Weekly Sales</h2>
                    <select id="weekSelect" class="text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @for ($i = 0; $i < 4; $i++)
                            <option value="{{ $i }}">
                                {{ $i == 0 ? 'This Week' : ($i == 1 ? 'Last Week' : $i . ' Weeks Ago') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($weeklySales ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1" id="weekRange"></p>
            </div>

            <!-- Monthly Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Monthly Sales</h2>
                    <select id="monthSelect" class="text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @for ($i = 0; $i < 12; $i++)
                            @php
                                $date = now()->subMonths($i);
                            @endphp
                            <option value="{{ $date->format('Y-m') }}">
                                {{ $date->format('F Y') }}
                            </option>
                        @endfor
                    </select>
                </div>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($monthlySales ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $selectedMonth ?? now()->format('F Y') }}</p>
            </div>

            <!-- Yearly Sales -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Yearly Sales</h2>
                    <select id="yearSelect" class="text-sm border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @for ($i = 0; $i < 5; $i++)
                            @php
                                $year = now()->subYears($i)->year;
                            @endphp
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">₱{{ number_format($yearlySales ?? 0, 2) }}</p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $selectedYear ?? now()->year }}</p>
            </div>
        </div>

        <!-- Sales Chart -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Daily Sales - Last 7 Days</h2>
            <div class="h-80">
                <canvas id="dailySalesChart" data-sales='{{ json_encode($salesData ?? []) }}'></canvas>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Initialize chart on page load
    document.addEventListener('DOMContentLoaded', initializeChart);
    
    // Also initialize when client-side navigation occurs
    document.addEventListener('page:loaded', initializeChart);
    
    // Make chart initialization function available globally for the client-side navigation system
    window.initializeDailySalesChart = initializeChart;
    
    function initializeChart() {
        console.log('Initializing daily sales chart');
        
        // Get chart element
        var canvas = document.getElementById('dailySalesChart');
        if (!canvas) {
            console.warn('Sales chart canvas not found');
            return;
        }
        
        // Clear existing chart if it exists
        var existingChart = Chart.getChart(canvas);
        if (existingChart) {
            existingChart.destroy();
        }
        
        var ctx = canvas.getContext('2d');
        var chartData = JSON.parse(canvas.dataset.sales);
        
        // Chart configuration
        const isDark = document.documentElement.classList.contains('dark');
        
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(chartData).map(date => {
                    return new Date(date).toLocaleDateString('en-US', {
                        month: 'short',
                        day: 'numeric'
                    });
                }),
                datasets: [{
                    label: 'Daily Sales',
                    data: Object.values(chartData),
                    borderColor: isDark ? 'rgb(96, 165, 250)' : 'rgb(59, 130, 246)',
                    backgroundColor: isDark ? 'rgba(96, 165, 250, 0.1)' : 'rgba(59, 130, 246, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: isDark ? 'rgb(96, 165, 250)' : 'rgb(59, 130, 246)',
                    pointBorderColor: isDark ? '#1f2937' : '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: isDark ? 'rgba(0, 0, 0, 0.9)' : 'rgba(0, 0, 0, 0.8)',
                        titleColor: isDark ? '#fff' : '#fff',
                        bodyColor: isDark ? '#fff' : '#fff',
                        padding: 12,
                        titleFont: {
                            size: 14,
                            weight: 'bold'
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                return '₱' + context.parsed.y.toLocaleString();
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: isDark ? 'rgba(255, 255, 255, 0.1)' : 'rgba(0, 0, 0, 0.05)'
                        },
                        ticks: {
                            callback: value => '₱' + value.toLocaleString(),
                            color: isDark ? '#9ca3af' : '#4b5563',
                            font: {
                                size: 12
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: isDark ? '#9ca3af' : '#4b5563',
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    }

    // Watch for dark mode changes
    if (window.Alpine) {
        Alpine.effect(() => {
            const isDark = document.documentElement.classList.contains('dark');
            initializeChart();
        });
    }

    // Handle dropdown changes
    ['week', 'month', 'year'].forEach(period => {
        const select = document.getElementById(period + 'Select');
        if (select) {
            select.addEventListener('change', function() {
                const params = new URLSearchParams(window.location.search);
                params.set(period, this.value);
                window.location.href = `${window.location.pathname}?${params.toString()}`;
            });
        }
    });

    // Update week range text
    const weekSelect = document.getElementById('weekSelect');
    const weekRange = document.getElementById('weekRange');
    if (weekSelect && weekRange) {
        const selectedWeek = parseInt(weekSelect.value);
        const endDate = new Date();
        endDate.setDate(endDate.getDate() - (selectedWeek * 7));
        const startDate = new Date(endDate);
        startDate.setDate(startDate.getDate() - 6);
        weekRange.textContent = `${startDate.toLocaleDateString()} - ${endDate.toLocaleDateString()}`;
    }
    </script>
    @endpush
@endsection

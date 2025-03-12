@extends('layouts.app')

@section('head')
<style>
    /* Modal animation styles */
    #modalContent {
        transition: opacity 0.3s ease, transform 0.3s ease;
    }
    
    .modal-overlay {
        transition: opacity 0.3s ease;
    }
    
    /* Form animations */
    .repair-item {
        animation: fadeIn 0.3s ease-in-out;
    }
    
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    /* Button hover effects */
    .btn-hover-effect {
        transition: all 0.2s ease;
    }
    
    .btn-hover-effect:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    /* Dark mode enhancements */
    .dark .modal-content {
        background-color: #1f2937;
        color: #f3f4f6;
    }
    
    .dark .modal-header {
        border-bottom-color: #374151;
    }
    
    .dark .modal-footer {
        border-top-color: #374151;
    }
    
    .dark input, 
    .dark select, 
    .dark textarea {
        background-color: #374151 !important;
        color: #f3f4f6 !important;
        border-color: #4b5563 !important;
    }
    
    .dark input::placeholder, 
    .dark textarea::placeholder {
        color: #9ca3af !important;
    }
    
    .dark label {
        color: #e5e7eb !important;
    }
    
    /* Enhanced UI styles */
    .status-badge {
        @apply px-3 py-1 text-xs font-medium rounded-full;
        transition: all 0.2s ease;
    }
    
    .status-badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    
    .table-row-hover {
        @apply transition-all duration-200;
    }
    
    .table-row-hover:hover {
        @apply bg-gray-50 dark:bg-gray-700 transform scale-[1.01] shadow-sm;
    }
    
    .table-row-hover td {
        @apply transition-colors duration-200;
    }
    
    .table-row-hover:hover td {
        @apply text-gray-900 dark:text-white;
    }
    
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-lg shadow p-4 transition-all duration-300;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Animated hamburger icon */
    .filter-icon {
        @apply h-5 w-5 text-gray-500 dark:text-gray-400;
        transition: transform 0.3s ease;
    }
    
    .filter-icon.active {
        transform: rotate(90deg);
    }
    
    /* Table enhancements */
    .enhanced-table {
        @apply min-w-full divide-y divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden;
        border-collapse: separate;
        border-spacing: 0;
    }
    
    .enhanced-table thead {
        @apply bg-gray-100 dark:bg-gray-600;
    }
    
    .enhanced-table th {
        @apply sticky top-0 px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-200 uppercase tracking-wider bg-gray-100 dark:bg-gray-600;
        z-index: 10;
    }
    
    /* Tooltip styles */
    .tooltip {
        @apply relative inline-block;
    }
    
    .tooltip .tooltip-text {
        @apply absolute hidden z-10 p-2 text-xs text-white bg-gray-900 dark:bg-gray-700 rounded whitespace-nowrap;
        bottom: 125%;
        left: 50%;
        transform: translateX(-50%);
    }
    
    .tooltip:hover .tooltip-text {
        @apply block;
    }
    
    .tooltip .tooltip-text::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #111827 transparent transparent transparent;
    }
    
    .dark .tooltip .tooltip-text::after {
        border-color: #374151 transparent transparent transparent;
    }
    
    /* Page title animation */
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateX(-10px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    .animated-title {
        animation: slideIn 0.5s ease-out;
    }
    
    /* Action buttons hover effects */
    .action-button {
        @apply transition-all duration-200 transform;
    }
    
    .action-button:hover {
        @apply scale-110;
    }

    /* Customer avatar styles */
    .customer-avatar {
        @apply relative overflow-hidden;
        background: linear-gradient(135deg, #6366f1 0%, #a855f7 100%);
        box-shadow: 0 2px 10px rgba(99, 102, 241, 0.2);
        transition: all 0.3s ease;
        color: rgba(0, 0, 0, 0.7); /* Dark text for light mode */
    }

    .customer-avatar:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
    }

    .dark .customer-avatar {
        background: linear-gradient(135deg, #4f46e5 0%, #9333ea 100%);
        box-shadow: 0 2px 10px rgba(79, 70, 229, 0.3);
        color: rgba(255, 255, 255, 0.95); /* White text for dark mode */
    }

    .dark .customer-avatar:hover {
        box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);
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
    .dark .avatar-c, .avatar-l, avatar-u { background: linear-gradient(135deg, #d97706, #dc2626); }
    .dark .avatar-d, .avatar-m, avatar-v { background: linear-gradient(135deg, #059669, #2563eb); }
    .dark .avatar-e, .avatar-n, .avatar-w { background: linear-gradient(135deg, #4f46e5, #7c3aed); }
    .dark .avatar-f, .avatar-o, .avatar-x { background: linear-gradient(135deg, #ea580c, #d97706); }
    .dark .avatar-g, .avatar-p, .avatar-y { background: linear-gradient(135deg, #db2777, #ea580c); }
    .dark .avatar-h, .avatar-q, .avatar-z { background: linear-gradient(135deg, #0d9488, #4f46e5); }
    .dark .avatar-i, .avatar-r, .avatar-0 { background: linear-gradient(135deg, #dc2626, #d97706); }
    
    /* Alpine.js cloak directive - critical for modal functionality */
    [x-cloak] { display: none !important; }
</style>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
@endsection

@section('content')
<div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">
        <!-- Header Section -->
        <div class="mb-6">
            <div class="flex flex-col space-y-4 md:flex-row md:items-center md:justify-between md:space-y-0">
                <div class="flex items-center space-x-4">
                    <h1 class="text-2xl font-bold animated-title flex items-center">
                        <img class="h-8 w-8 text-blue-500 mr-3" src="{{ asset('img/mobile.png') }}" alt="Mobile Icon">
                        Manage Repairs
                    </h1>
                </div>
                
                <div class="flex flex-col md:flex-row items-center gap-2 md:gap-4 w-full md:w-auto">
                    <!-- Enhanced Search Form -->
                    <form id="searchForm" action="{{ route('repairs.index') }}" method="GET" class="flex items-center w-full md:w-auto relative">
                        <div class="relative flex-1 md:w-96">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <input type="text" 
                                   id="searchInput"
                                   name="search" 
                                   value="{{ request('search') }}" 
                                   placeholder="Search by customer name, phone, device..." 
                                   class="block w-full pl-10 pr-12 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-blue-500 sm:text-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white transition-all duration-200">
                            @if(request('search'))
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" 
                                            onclick="clearSearch()" 
                                            class="text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                    
                    <!-- New Repair Button -->
                    <button type="button" 
                        @click="$dispatch('open-repair-modal')"
                        class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 btn-hover-effect whitespace-nowrap">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        New Repair
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- Pending Repairs -->
            <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-amber-200 dark:border-amber-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-amber-600 dark:text-amber-400">Pending Repairs</p>
                            <p class="text-3xl font-extrabold text-amber-700 dark:text-amber-300 mt-2 repair-stat-pending">{{ $repairs->where('status', 'pending')->count() }}</p>
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
                    <div class="mt-4 border-t border-amber-200 dark:border-amber-900 pt-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-amber-700 dark:text-amber-300">Oldest</span>
                                <span class="text-lg font-bold text-amber-800 dark:text-amber-200">
                                    @php
                                        $oldestPending = $repairs->where('status', 'pending')->sortBy('created_at')->first();
                                        echo $oldestPending ? $oldestPending->created_at->format('M j') : 'None';
                                    @endphp
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-amber-700 dark:text-amber-300">Avg Time</span>
                                <span class="text-lg font-bold text-amber-800 dark:text-amber-200">
                                    @php
                                        $pendingRepairs = $repairs->where('status', 'pending');
                                        $totalSeconds = 0;
                                        $count = 0;
                                        
                                        foreach($pendingRepairs as $repair) {
                                            if($repair->started_at) {
                                                $totalSeconds += now()->diffInSeconds($repair->started_at);
                                                $count++;
                                            }
                                        }
                                        
                                        if($count > 0) {
                                            $avgSeconds = $totalSeconds / $count;
                                            $avgHours = floor($avgSeconds / 3600);
                                            echo $avgHours > 24 ? floor($avgHours / 24) . 'd' : $avgHours . 'h';
                                        } else {
                                            echo '0h';
                                        }
                                    @endphp
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Completed Repairs -->
            <div class="bg-gradient-to-br from-emerald-50 to-emerald-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-emerald-200 dark:border-emerald-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-emerald-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-emerald-600 dark:text-emerald-400">Completed Repairs</p>
                            <p class="text-3xl font-extrabold text-emerald-700 dark:text-emerald-300 mt-2">{{ $repairs->where('status', 'completed')->count() }}</p>
                            <div class="flex items-center mt-1 text-xs text-emerald-600 dark:text-emerald-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Successfully serviced
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-emerald-400 to-emerald-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-emerald-200 dark:border-emerald-900 pt-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-300">This Month</span>
                                <span class="text-lg font-bold text-emerald-800 dark:text-emerald-200">
                                    {{ $repairs->where('status', 'completed')->filter(function($repair) {
                                        return $repair->completed_at && $repair->completed_at->format('Y-m') === now()->format('Y-m');
                                    })->count() }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-emerald-700 dark:text-emerald-300">Avg Time</span>
                                <span class="text-lg font-bold text-emerald-800 dark:text-emerald-200">
                                    @php
                                        $completedRepairs = $repairs->where('status', 'completed');
                                        $totalDuration = 0;
                                        $count = 0;
                                        
                                        foreach($completedRepairs as $repair) {
                                            if($repair->started_at && $repair->completed_at) {
                                                $totalDuration += $repair->completed_at->diffInSeconds($repair->started_at);
                                                $count++;
                                            }
                                        }
                                        
                                        if($count > 0) {
                                            $avgSeconds = $totalDuration / $count;
                                            $avgHours = floor($avgSeconds / 3600);
                                            echo $avgHours > 24 ? floor($avgHours / 24) . 'd' : $avgHours . 'h';
                                        } else {
                                            echo '0h';
                                        }
                                    @endphp
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- In Progress Repairs -->
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-blue-200 dark:border-blue-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">In Progress Repairs</p>
                            <p class="text-3xl font-extrabold text-blue-700 dark:text-blue-300 mt-2">{{ $repairs->where('status', 'in_progress')->count() }}</p>
                            <div class="flex items-center mt-1 text-xs text-blue-600 dark:text-blue-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                                    </svg>
                                    Currently working
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-blue-400 to-blue-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-blue-200 dark:border-blue-900 pt-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-300">Active</span>
                                <span class="text-lg font-bold text-blue-800 dark:text-blue-200">
                                    @php
                                        $inProgressCount = $repairs->where('status', 'in_progress')->count();
                                        echo $inProgressCount ?: 'None';
                                    @endphp
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-blue-700 dark:text-blue-300">Avg Time</span>
                                <span class="text-lg font-bold text-blue-800 dark:text-blue-200">
                                    @php
                                        $inProgressRepairs = $repairs->where('status', 'in_progress');
                                        $totalSeconds = 0;
                                        $count = 0;
                                        
                                        foreach($inProgressRepairs as $repair) {
                                            if($repair->started_at) {
                                                $totalSeconds += now()->diffInSeconds($repair->started_at);
                                                $count++;
                                            }
                                        }
                                        
                                        if($count > 0) {
                                            $avgSeconds = $totalSeconds / $count;
                                            $avgHours = floor($avgSeconds / 3600);
                                            echo $avgHours > 24 ? floor($avgHours / 24) . 'd' : $avgHours . 'h';
                                        } else {
                                            echo '0h';
                                        }
                                    @endphp
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Canceled Repairs -->
            <div class="bg-gradient-to-br from-red-50 to-red-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow-lg overflow-hidden transform transition-all duration-300 hover:scale-105 border border-red-200 dark:border-red-900">
                <div class="relative p-6">
                    <div class="absolute top-0 right-0 mt-4 mr-4 opacity-10">
                        <svg class="h-24 w-24 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"></path>
                        </svg>
                    </div>
                    <div class="flex items-center justify-between relative">
                        <div>
                            <p class="text-sm font-medium text-red-600 dark:text-red-400">Canceled Repairs</p>
                            <p class="text-3xl font-extrabold text-red-700 dark:text-red-300 mt-2 repair-stat-cancelled">{{ $repairs->where('status', 'cancelled')->count() }}</p>
                            <div class="flex items-center mt-1 text-xs text-red-600 dark:text-red-400">
                                <span class="inline-flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Service discontinued
                                </span>
                            </div>
                        </div>
                        <div class="p-3 bg-gradient-to-br from-red-400 to-red-500 rounded-xl shadow-lg">
                            <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-4 border-t border-red-200 dark:border-red-900 pt-3">
                        <div class="grid grid-cols-2 gap-2">
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-red-700 dark:text-red-300">This Month</span>
                                <span class="text-lg font-bold text-red-800 dark:text-red-200">
                                    {{ $repairs->where('status', 'cancelled')->filter(function($repair) {
                                        return $repair->updated_at && $repair->updated_at->format('Y-m') === now()->format('Y-m');
                                    })->count() }}
                                </span>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs font-medium text-red-700 dark:text-red-300">Lost Revenue</span>
                                <span class="text-lg font-bold text-red-800 dark:text-red-200">
                                    ₱{{ number_format($repairs->where('status', 'cancelled')->sum('total_cost'), 0) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg flex items-center justify-between animate__animated animate__fadeIn">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button type="button" class="text-green-700 hover:text-green-900 close-alert">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg flex items-center justify-between animate__animated animate__fadeIn">
                <div class="flex items-center">
                    <svg class="h-5 w-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                <button type="button" class="text-red-700 hover:text-red-900 close-alert">
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 shadow-sm rounded-lg overflow-hidden repairs-page">
        <div class="overflow-x-auto">
                <table class="enhanced-table">
                    <thead>
                    <tr>
                    @php
                        $headers = [
                            'customer' => 'Customer',
                            'device' => 'Device',
                            'service' => 'Service',
                            'status' => 'Status',
                            'created_at' => 'Date',
                            'actions' => 'Actions'
                        ];
                    @endphp

                    @foreach($headers as $key => $label)
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            <a href="{{ route('repairs.index', [
                                'sort' => $key,
                                'direction' => ($sortField === $key && $sortDirection === 'asc') ? 'desc' : 'asc',
                                'search' => request('search')
                                ]) }}" class="flex items-center space-x-1 hover:text-gray-700 dark:hover:text-gray-300 group">
                                <span>{{ $label }}</span>
                                    <span class="opacity-0 group-hover:opacity-100 transition-opacity duration-200 {{ $sortField === $key ? 'opacity-100' : '' }}">
                                @if($sortField === $key)
                                        @if($sortDirection === 'asc')
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                </svg>
                                            @else
                                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            @endif
                                        @else
                                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v-4m0 4L21 8m-4 4l-4-4"></path>
                                            </svg>
                                        @endif
                                    </span>
                            </a>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($repairs as $repair)
                        <tr class="table-row-hover">
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($repair->items->isNotEmpty())
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full customer-avatar avatar-{{ strtolower(substr($repair->items->first()->device->customer->name, 0, 1)) }} flex items-center justify-center font-bold shadow-md">
                                            {{ strtoupper(substr($repair->items->first()->device->customer->name, 0, 1)) }}
                                        </div>
                                        <div class="ml-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                <a href="{{ route('customers.index', ['highlight' => $repair->items->first()->device->customer->id]) }}" 
                                                   class="customer-profile-link text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:underline"
                                                   onclick="event.stopPropagation();">
                                        {{ $repair->items->first()->device->customer->name }}
                                    </a>
                                </div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400 flex items-center">
                                                <svg class="h-4 w-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                            {{ $repair->items->first()->device->customer->phone }}
                                        </div>
                                    </div>
                            @else
                                <div class="text-sm text-gray-500 dark:text-gray-400">No items</div>
                            @endif
                        </td>
                            <td class="px-6 py-4">
                            <div class="text-sm text-gray-900 dark:text-white">
                                    @foreach($repair->items as $index => $item)
                                        <div class="mb-1 flex items-center {{ $index > 0 ? 'mt-2 pt-2 border-t border-gray-100 dark:border-gray-700' : '' }}">
                                            <div class="flex-shrink-0 mr-2">
                                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                                </svg>
                                            </div>
                                            <div>
                                                <span class="font-medium">
                                        @if($item->device->deviceModel)
                                                {{ $item->device->deviceModel->full_name }}
                                            @else
                                                {{ $item->device->brand }} {{ $item->device->model }}
                                            @endif
                                                </span>
                                                @if($item->device->serial_number)
                                                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                        S/N: {{ $item->device->serial_number }}
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                                <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white">
                                        @foreach($repair->items as $index => $item)
                                            <div class="mb-1 flex items-center {{ $index > 0 ? 'mt-2 pt-2 border-t border-gray-100 dark:border-gray-700' : '' }}">
                                                <div class="flex-shrink-0 mr-2">
                                                    <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                                    </svg>
                                                </div>
                                                <div>
                                                    <span class="font-medium">{{ $item->service->name }}</span>
                                                    <div class="text-sm text-gray-500 dark:text-gray-400">₱{{ number_format($item->cost, 2) }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="text-sm font-bold text-gray-900 dark:text-white mt-2 pt-2 border-t border-gray-200 dark:border-gray-700">
                                    Total: ₱{{ number_format($repair->total_cost, 2) }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                    @if($repair->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                    @elseif($repair->status === 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                    @elseif($repair->status === 'in_progress') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                    @elseif($repair->status === 'cancelled') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300
                                    @endif"
                                    data-status="{{ $repair->status }}">
                                    @if($repair->status === 'completed')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    @elseif($repair->status === 'pending')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($repair->status === 'in_progress')
                                        <svg class="w-4 h-4 mr-1.5 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                                        </svg>
                                    @elseif($repair->status === 'cancelled')
                                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    @endif
                                    {{ ucfirst(str_replace('_', ' ', $repair->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-white">
                                    {{ $repair->created_at->format('M d, Y') }}
                                </div>
                                
                                @if($repair->status === 'pending')
                                    <div class="text-xs text-yellow-600 dark:text-yellow-400 mt-1 font-medium">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                        Pending since: {{ $repair->created_at->format('M d, Y') }}
                                    </div>
                                @elseif($repair->status === 'completed')
                                    <div class="text-xs text-green-600 dark:text-green-400 mt-1 font-medium">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Completed on: {{ $repair->completed_at ? Carbon\Carbon::parse($repair->completed_at)->format('M d, Y') : $repair->updated_at->format('M d, Y') }}
                                    </div>
                                    @if($repair->started_at && $repair->completed_at)
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                            Duration: 
                                            @php
                                                $duration = Carbon\Carbon::parse($repair->started_at)->diff(Carbon\Carbon::parse($repair->completed_at));
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
                                        </div>
                                    @endif
                                @elseif($repair->status === 'in_progress')
                                    <div class="text-xs text-blue-600 dark:text-blue-400 mt-1 font-medium">
                                        <svg class="w-3 h-3 inline-block mr-1 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                                        </svg>
                                        @if($repair->started_at)
                                            <span class="repair-elapsed-time" data-started="{{ Carbon\Carbon::parse($repair->started_at)->timestamp }}">
                                                In progress: {{ Carbon\Carbon::parse($repair->started_at)->diffForHumans(null, true) }}
                                            </span>
                                        @else
                                            Started: {{ $repair->updated_at->format('M d, Y') }}
                                        @endif
                                    </div>
                                @elseif($repair->status === 'cancelled')
                                    <div class="text-xs text-red-600 dark:text-red-400 mt-1 font-medium">
                                        <svg class="w-3 h-3 inline-block mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancelled on: {{ $repair->updated_at->format('M d, Y') }}
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('repairs.show', $repair) }}" class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 tooltip action-button">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="tooltip-text">View</span>
                                    </a>
                                    
                                    <a href="{{ route('repairs.edit', $repair) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 tooltip action-button">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="tooltip-text">Edit</span>
                                    </a>
                                    
                                    <form action="{{ route('repairs.destroy', $repair) }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this repair?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 tooltip action-button">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="tooltip-text">Delete</span>
                                        </button>
                                    </form>
                                </div>
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

        <div class="mt-6 mb-10">
            <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">Showing {{ $repairs->firstItem() ?? 0 }} to {{ $repairs->lastItem() ?? 0 }} of {{ $repairs->total() }} repairs</span>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <label for="per-page" class="text-sm font-medium text-gray-700 dark:text-gray-300">Per page:</label>
                        <select id="per-page" class="block w-full mt-1 rounded-md border-gray-300 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-700 dark:border-gray-600 dark:text-white text-sm py-1">
                            <option value="10" {{ request('perPage') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('perPage') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('perPage') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('perPage') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <a href="{{ request()->url() }}?{{ http_build_query(array_merge(request()->except(['page', 'perPage']), ['perPage' => (request('perPage', 10) == 10 ? 25 : (request('perPage', 10) == 25 ? 50 : (request('perPage', 10) == 50 ? 100 : 10)))])) }}" 
                       class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md shadow-sm text-sm font-medium transition-colors duration-200 transform hover:scale-105">
                        Show {{ request('perPage', 10) == 10 ? 'More' : (request('perPage', 10) == 100 ? 'Less' : 'More') }}
                    </a>
                </div>
            </div>
            <div class="mt-4 pagination-container">
                {{ $repairs->appends(request()->except('page'))->links('pagination.custom') }}
            </div>
        </div>
    </div>
</div>
</div>

<!-- Repair Modal with Alpine.js -->
<div id="repairModal" 
    x-data="{ open: false }" 
    @open-repair-modal.window="open = true; $nextTick(() => { document.getElementById('customer_id').focus(); })"
    @keydown.escape.window="open = false"
    x-show="open"
    x-cloak
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="fixed inset-0 z-50 overflow-y-auto">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity modal-overlay" @click="open = false">
            <div class="absolute inset-0 bg-gray-500 dark:bg-gray-800 opacity-75"></div>
        </div>
        
        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen"></span>
        
        <div id="modalContent" 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            @click.away="open = false"
            class="inline-block align-bottom bg-white dark:bg-gray-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white dark:bg-gray-800 px-4 pt-5 pb-4 sm:p-6 sm:pb-4 modal-header">
                <div class="sm:flex sm:items-start">
                    <div class="absolute top-0 right-0 pt-5 pr-5">
                        <button type="button" @click="open = false" class="bg-white dark:bg-transparent rounded-md text-gray-400 dark:text-gray-300 hover:text-gray-500 dark:hover:text-gray-200 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white mb-4">New Repair</h3>
                        
                        <!-- Form will be here -->
                        <form id="repairForm" action="{{ route('repairs.store') }}" method="POST" class="space-y-6">
                            @csrf
                            <!-- Customer Selection -->
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Customer Information</h2>
                                <div>
                                    <label for="customer_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Select Customer</label>
                                    <select name="customer_id" id="customer_id" required
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="">Select a customer</option>
                                        @foreach($customers as $customer)
                                            <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            
                            <div id="repair-items">
                                <!-- Template for repair item -->
                                <template id="repair-item-template">
                                    <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                                        <div class="flex justify-between items-center mb-4">
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white">Device & Service</h3>
                                            <button type="button" onclick="removeRepairItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                                </svg>
                                            </button>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <!-- Device Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                                                <select name="items[INDEX][device_id]" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select">
                                                    <option value="">Select a device</option>
                                                </select>
                                            </div>

                                            <!-- Service Selection -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                                                <select name="items[INDEX][service_id]" required
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select">
                                                    <option value="">Select a service</option>
                                                    @foreach($services as $service)
                                                        <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                            {{ $service->name }} - ₱{{ number_format($service->price, 2) }} - {{ $service->category->name ?? 'Uncategorized' }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <!-- Cost -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                                                <div class="mt-1 relative rounded-md shadow-sm">
                                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                        <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                                    </div>
                                                    <input type="number" name="items[INDEX][cost]" step="0.01" min="0" required
                                                        class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 cost-input">
                                                </div>
                                            </div>

                                            <!-- Status (Hidden) -->
                                            <input type="hidden" name="items[INDEX][status]" value="pending">

                                            <!-- Notes -->
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                                <textarea name="items[INDEX][notes]" rows="2"
                                                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </template>

                                <!-- Initial repair item -->
                                <div class="repair-item border dark:border-gray-700 rounded-lg p-4 mb-4 bg-gray-50 dark:bg-gray-700">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Device & Service</h3>
                                        <button type="button" onclick="removeRepairItem(this)" class="text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300 hidden">
                                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Device Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Device</label>
                                            <select name="items[0][device_id]" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 device-select">
                                                <option value="">Select a device</option>
                                            </select>
                                        </div>

                                        <!-- Service Selection -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Service</label>
                                            <select name="items[0][service_id]" required
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 service-select">
                                                <option value="">Select a service</option>
                                                @foreach($services as $service)
                                                    <option value="{{ $service->id }}" data-price="{{ $service->price }}">
                                                        {{ $service->name }} - ₱{{ number_format($service->price, 2) }} - {{ $service->category->name ?? 'Uncategorized' }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Cost -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Cost</label>
                                            <div class="mt-1 relative rounded-md shadow-sm">
                                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                    <span class="text-gray-500 dark:text-gray-400 sm:text-sm">₱</span>
                                                </div>
                                                <input type="number" name="items[0][cost]" step="0.01" min="0" required
                                                    class="pl-7 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500 cost-input">
                                            </div>
                                        </div>

                                        <!-- Status (Hidden) -->
                                        <input type="hidden" name="items[0][status]" value="pending">

                                        <!-- Notes -->
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                            <textarea name="items[0][notes]" rows="2"
                                                class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Add More Items Button -->
                            <div class="mt-4 flex justify-center">
                                <button type="button" id="add-item-btn" onclick="addRepairItem()" 
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-700 text-white rounded-md hover:bg-indigo-700 dark:hover:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                                    <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Add Another Item
                                </button>
                            </div>
                            
                            <!-- Payment Information -->
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700">
                                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Payment Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Status -->
                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Status</label>
                                        <select name="status" id="status" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="pending">Pending</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                            <option value="cancelled">Cancelled</option>
                                        </select>

                                        <!-- Timer display for in-progress repairs -->
                                        <div id="repair-timer-container" class="mt-2 hidden">
                                            <div class="flex items-center space-x-2">
                                                <svg class="h-5 w-5 text-blue-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.7 6.3a1 1 0 000 1.4l1.6 1.6a1 1 0 001.4 0l3.77-3.77a6 6 0 01-7.94 7.94l-6.91 6.91a2.12 2.12 0 01-3-3l6.91-6.91a6 6 0 017.94-7.94l-3.76 3.76z"></path>
                                                </svg>
                                                <span id="repair-timer" class="text-sm font-medium text-blue-600 dark:text-blue-400">00:00:00</span>
                                            </div>
                                        </div>
                                        
                                        <!-- Hidden date fields -->
                                        <input type="hidden" name="started_at" id="started_at" value="">
                                        <input type="hidden" name="completed_at" id="completed_at" value="">
                                    </div>
                                    
                                    <!-- Payment Method -->
                                    <div>
                                        <label for="payment_method" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Payment Method</label>
                                        <select name="payment_method" id="payment_method" required
                                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                            <option value="cash">Cash</option>
                                            <option value="gcash">GCash</option>
                                            <option value="bank_transfer">Bank Transfer</option>
                                            <option value="credit_card">Credit Card</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Additional Notes -->
                            <div class="border dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700 mt-4">
                                <h2 class="text-lg font-medium mb-4 text-gray-900 dark:text-white">Additional Notes</h2>
                                <div>
                                    <label for="notes" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Notes</label>
                                    <textarea name="notes" id="notes" rows="3"
                                        class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse modal-footer">
                <button type="submit" id="submitRepairForm" form="repairForm" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save Repair
                </button>
                <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 dark:border-gray-600 shadow-sm px-4 py-2 bg-white dark:bg-gray-700 text-base font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Function to fetch devices for a customer
    async function fetchDevicesForCustomer(customerId) {
        if (!customerId) return;
        
        console.log('Fetching devices for customer ID:', customerId);
        
        // Show loading indicator in all device selects
        const deviceSelects = document.querySelectorAll('.device-select');
        deviceSelects.forEach(select => {
            select.innerHTML = '<option value="">Loading devices...</option>';
            select.disabled = true;
        });
        
        try {
            const response = await fetch(`/api/customers/${customerId}/devices`);
            if (!response.ok) throw new Error('Failed to fetch devices');
            
            const devices = await response.json();
            console.log('Devices fetched:', devices);
            
            // Create options HTML
            let optionsHtml = '<option value="">Select a device</option>';
            devices.forEach(device => {
                const deviceName = `${device.brand} ${device.model}${device.serial_number ? ` (${device.serial_number})` : ''}`;
                optionsHtml += `<option value="${device.id}">${deviceName}</option>`;
            });
            
            // Update all device select elements
            deviceSelects.forEach(select => {
                select.innerHTML = optionsHtml;
                select.disabled = false;
            });
            
            // Enable add item button
            const addItemBtn = document.getElementById('add-item-btn');
            if (addItemBtn) addItemBtn.disabled = false;
            
        } catch (error) {
            console.error('Error fetching devices:', error);
            deviceSelects.forEach(select => {
                select.innerHTML = '<option value="">Error loading devices</option>';
                select.disabled = false;
            });
        }
    }

    // Function to initialize repair form
    function initializeRepairForm() {
        console.log('Initializing repair form');
        
        // Set up customer select event listener
        const customerSelect = document.getElementById('customer_id');
        if (customerSelect) {
            // Remove existing event listeners
            customerSelect.removeEventListener('change', handleCustomerChange);
            // Add new event listener
            customerSelect.addEventListener('change', handleCustomerChange);

            // If customer is already selected, fetch devices
            if (customerSelect.value) {
                fetchDevicesForCustomer(customerSelect.value);
            }
        }

        // Set up service select events
        setupServiceSelectEvents();
    }

    // Handler for customer select changes
    function handleCustomerChange(event) {
        fetchDevicesForCustomer(event.target.value);
    }

    // Initialize when DOM content is loaded
    document.addEventListener('DOMContentLoaded', initializeRepairForm);

    // Initialize when Turbo loads a new page
    document.addEventListener('turbo:load', initializeRepairForm);

    // Initialize when the repair modal is opened
    document.addEventListener('open-repair-modal', function() {
        console.log('Repair modal opened');
        setTimeout(initializeRepairForm, 100); // Small delay to ensure modal content is loaded
    });

    // Function to add a new repair item
    function addRepairItem() {
        const repairItems = document.getElementById('repair-items');
        const template = document.getElementById('repair-item-template');
        const itemCount = repairItems.querySelectorAll('.repair-item').length;
        
        // Clone the template
        const templateContent = template.content.cloneNode(true);
        
        // Update the index in the names
        const inputs = templateContent.querySelectorAll('select, input, textarea');
        inputs.forEach(input => {
            if (input.name) {
                input.name = input.name.replace('INDEX', itemCount);
            }
        });
        
        // Get the current customer ID and fetch devices if needed
        const customerSelect = document.getElementById('customer_id');
        if (customerSelect && customerSelect.value) {
            const deviceSelect = templateContent.querySelector('.device-select');
            if (deviceSelect) {
                // Copy options from the first device select
                const firstDeviceSelect = document.querySelector('.device-select');
                if (firstDeviceSelect && firstDeviceSelect.options.length > 0) {
                    Array.from(firstDeviceSelect.options).forEach(option => {
                        deviceSelect.add(option.cloneNode(true));
                    });
                }
            }
        }
        
        // Add animation class
        const repairItem = templateContent.querySelector('.repair-item');
        if (repairItem) {
            repairItem.classList.add('animate__animated', 'animate__fadeIn');
        }
        
        // Append the new item
        repairItems.appendChild(templateContent);
        
        // Set up service select change event to update cost
        setupServiceSelectEvents();
    }

    // Function to remove a repair item
    function removeRepairItem(button) {
        const repairItem = button.closest('.repair-item');
        repairItem.classList.add('animate__animated', 'animate__fadeOut');
        setTimeout(() => repairItem.remove(), 300);
    }

    // Function to set up service select change events
    function setupServiceSelectEvents() {
        const serviceSelects = document.querySelectorAll('.service-select');
        serviceSelects.forEach(select => {
            select.removeEventListener('change', handleServiceChange);
            select.addEventListener('change', handleServiceChange);
        });
    }

    // Handler for service select changes
    function handleServiceChange(event) {
        const option = event.target.options[event.target.selectedIndex];
        if (option && option.dataset.price) {
            const repairItem = event.target.closest('.repair-item');
            const costInput = repairItem.querySelector('.cost-input');
            if (costInput) {
                costInput.value = parseFloat(option.dataset.price).toFixed(2);
            }
        }
    }

    // Clean up function to remove event listeners
    function cleanupRepairForm() {
        const customerSelect = document.getElementById('customer_id');
        if (customerSelect) {
            customerSelect.removeEventListener('change', handleCustomerChange);
        }

        const serviceSelects = document.querySelectorAll('.service-select');
        serviceSelects.forEach(select => {
            select.removeEventListener('change', handleServiceChange);
        });
    }

    // Clean up when navigating away
    document.addEventListener('turbo:before-cache', cleanupRepairForm);

    // Add this to your existing JavaScript
    function clearSearch() {
        document.getElementById('searchInput').value = '';
        document.getElementById('searchForm').submit();
    }

    // Initialize search functionality
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const searchForm = document.getElementById('searchForm');

        // Submit form on Enter key
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                searchForm.submit();
            }
        });
    });
</script>
@endpush
@endsection
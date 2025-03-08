<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Repair;
use App\Models\Customer;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\View\View;
use Illuminate\Support\Facades\DB;
use Exception;

class DashboardController extends Controller
{
    public function index(): View
    {
        try {
            // Get the current date
            $now = Carbon::now();

            // Get selected periods from request
            $selectedWeek = (int) request('week', 0);
            $selectedMonth = request('month', $now->format('Y-m'));
            $selectedYear = (int) request('year', $now->year);

            // Parse selected month
            $selectedMonthDate = Carbon::createFromFormat('Y-m', $selectedMonth);

            // Get today's repairs with error handling
            try {
                $todayRepairs = Repair::whereDate('created_at', today())->count();
            } catch (Exception $e) {
                $todayRepairs = 0;
            }

            // Get total customers with error handling
            try {
                $totalCustomers = Customer::count();
            } catch (Exception $e) {
                $totalCustomers = 0;
            }

            // Get active services with error handling
            try {
                $activeServices = Service::where('is_active', true)->count();
            } catch (Exception $e) {
                $activeServices = 0;
            }

            // Get low stock items with error handling
            try {
                $lowStockItems = DB::table('inventory')
                    ->where('quantity', '<', DB::raw('reorder_level'))
                    ->count();
            } catch (Exception $e) {
                $lowStockItems = 0;
            }

            // Weekly sales (selected week)
            try {
                $weekStart = $now->copy()->subWeeks($selectedWeek)->startOfWeek();
                $weekEnd = $weekStart->copy()->endOfWeek();
                $weeklySales = Sale::whereBetween('sale_date', [
                    $weekStart,
                    $weekEnd
                ])->sum('amount');
            } catch (Exception $e) {
                $weeklySales = 0;
            }

            // Monthly sales (selected month)
            try {
                $monthlySales = Sale::whereYear('sale_date', $selectedMonthDate->year)
                    ->whereMonth('sale_date', $selectedMonthDate->month)
                    ->sum('amount');
            } catch (Exception $e) {
                $monthlySales = 0;
            }

            // Yearly sales (selected year)
            try {
                $yearlySales = Sale::whereYear('sale_date', $selectedYear)
                    ->sum('amount');
            } catch (Exception $e) {
                $yearlySales = 0;
            }

            // Daily sales for the chart (last 7 days)
            try {
                $dailySales = Sale::whereBetween('sale_date', [
                    $now->copy()->subDays(6)->startOfDay(),
                    $now->endOfDay()
                ])
                    ->selectRaw('DATE(sale_date) as date, SUM(amount) as total')
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get()
                    ->pluck('total', 'date')
                    ->toArray();
            } catch (Exception $e) {
                $dailySales = [];
            }

            // Fill in missing days with zero
            $salesData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = $now->copy()->subDays($i)->format('Y-m-d');
                $salesData[$date] = $dailySales[$date] ?? 0;
            }

            // Return view with all required data
            return view('dashboard', compact(
                'todayRepairs',
                'totalCustomers',
                'activeServices',
                'lowStockItems',
                'weeklySales',
                'monthlySales',
                'yearlySales',
                'salesData',
                'selectedMonth',
                'selectedYear'
            ));

        } catch (Exception $e) {
            // If anything fails, return view with default values
            return view('dashboard', [
                'todayRepairs' => 0,
                'totalCustomers' => 0,
                'activeServices' => 0,
                'lowStockItems' => 0,
                'weeklySales' => 0,
                'monthlySales' => 0,
                'yearlySales' => 0,
                'salesData' => array_fill_keys(
                    array_map(
                        fn($i) => Carbon::now()->subDays($i)->format('Y-m-d'),
                        range(6, 0)
                    ),
                    0
                ),
                'selectedMonth' => now()->format('F Y'),
                'selectedYear' => now()->year
            ]);
        }
    }
} 
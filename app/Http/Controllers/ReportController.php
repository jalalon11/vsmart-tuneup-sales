<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Repair;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

class ReportController extends Controller
{
    public function generate(Request $request)
    {
        $period = $request->input('period', 'monthly');
        $date = $request->input('date');
        
        $query = Sale::query()
            ->select('sales.*')
            ->join('repairs', 'repairs.id', '=', 'sales.repair_id')
            ->with(['repair.items.device.customer', 'repair.items.service']);

        switch ($period) {
            case 'monthly':
                $date = $date ? Carbon::createFromFormat('Y-m', $date) : now();
                $query->whereYear('sale_date', $date->year)
                      ->whereMonth('sale_date', $date->month);
                $periodLabel = $date->format('F Y');
                break;

            case 'quarterly':
                $date = $date ? Carbon::createFromFormat('Y-n', $date) : now();
                $quarter = ceil($date->month / 3);
                $startMonth = ($quarter - 1) * 3 + 1;
                $endMonth = $quarter * 3;
                $query->whereYear('sale_date', $date->year)
                      ->whereMonth('sale_date', '>=', $startMonth)
                      ->whereMonth('sale_date', '<=', $endMonth);
                $periodLabel = "Q{$quarter} {$date->year}";
                break;

            case 'yearly':
                $year = $date ?: now()->year;
                $query->whereYear('sale_date', $year);
                $periodLabel = "Year {$year}";
                break;
        }

        $sales = $query->get();

        $totalSales = $sales->sum('amount');
        $totalRepairs = $sales->count();
        $serviceBreakdown = [];
        $customerBreakdown = [];

        foreach ($sales as $sale) {
            $processedServices = []; // Track services already counted for this repair
            $processedCustomers = []; // Track customers already counted for this repair
            
            foreach ($sale->repair->items as $item) {
                $serviceName = $item->service->name;
                $customerName = $item->device->customer->name;
                
                // Service breakdown
                if (!isset($serviceBreakdown[$serviceName])) {
                    $serviceBreakdown[$serviceName] = [
                        'count' => 0,
                        'total' => 0
                    ];
                }
                
                // Only increment service count if we haven't counted this service for this repair
                if (!in_array($serviceName, $processedServices)) {
                    $serviceBreakdown[$serviceName]['count']++;
                    $processedServices[] = $serviceName;
                }
                
                // Customer breakdown
                if (!isset($customerBreakdown[$customerName])) {
                    $customerBreakdown[$customerName] = [
                        'count' => 0,
                        'total' => 0
                    ];
                }
                
                // Only increment customer count if we haven't counted this customer for this repair
                if (!in_array($customerName, $processedCustomers)) {
                    $customerBreakdown[$customerName]['count']++;
                    $processedCustomers[] = $customerName;
                }
            }
            
            // Add the sale amount to the customer's total
            $customerName = $sale->repair->items->first()->device->customer->name;
            $customerBreakdown[$customerName]['total'] += $sale->amount;
            
            // Add the sale amount to each service's total based on their proportion of the repair
            $totalItemCost = $sale->repair->items->sum('cost');
            foreach ($sale->repair->items as $item) {
                $serviceName = $item->service->name;
                $proportion = $totalItemCost > 0 ? $item->cost / $totalItemCost : 0;
                $serviceBreakdown[$serviceName]['total'] += $sale->amount * $proportion;
            }
        }

        arsort($serviceBreakdown);
        arsort($customerBreakdown);

        return View::make('reports.show', compact(
            'sales',
            'totalSales',
            'totalRepairs',
            'serviceBreakdown',
            'customerBreakdown',
            'period',
            'periodLabel'
        ));
    }
} 
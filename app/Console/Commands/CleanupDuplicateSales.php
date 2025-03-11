<?php

namespace App\Console\Commands;

use App\Models\Repair;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanupDuplicateSales extends Command
{
    protected $signature = 'sales:cleanup-duplicates';
    protected $description = 'Clean up duplicate sales records and ensure one sale per repair';

    public function handle()
    {
        $this->info('Starting sales cleanup...');

        // Get all repairs with multiple sales
        $repairsWithMultipleSales = DB::table('sales')
            ->select('repair_id', DB::raw('COUNT(*) as sale_count'))
            ->groupBy('repair_id')
            ->having('sale_count', '>', 1)
            ->get();

        if ($repairsWithMultipleSales->isEmpty()) {
            $this->info('No duplicate sales found.');
            return;
        }

        $this->info("Found {$repairsWithMultipleSales->count()} repairs with duplicate sales.");
        
        foreach ($repairsWithMultipleSales as $repairData) {
            $repair = Repair::find($repairData->repair_id);
            if (!$repair) continue;

            // Delete all existing sales for this repair
            $repair->sales()->delete();

            // Create a single sale record with the total cost
            $repair->sales()->create([
                'amount' => $repair->total_cost,
                'sale_date' => $repair->completed_at ?? $repair->updated_at ?? now(),
            ]);

            $this->info("Cleaned up sales for repair #{$repair->id}");
        }

        $this->info('Sales cleanup completed successfully.');
    }
} 
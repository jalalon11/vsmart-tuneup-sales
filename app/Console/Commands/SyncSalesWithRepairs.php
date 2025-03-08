<?php

namespace App\Console\Commands;

use App\Models\Repair;
use App\Models\Sale;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncSalesWithRepairs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repairs:sync-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronize sales records with completed repairs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting sales synchronization...');

        try {
            // 1. Check for duplicate sales
            $this->info('Checking for duplicate sales records...');
            $duplicates = DB::table('sales')
                ->select('repair_id')
                ->groupBy('repair_id')
                ->havingRaw('COUNT(*) > 1')
                ->get();

            if ($duplicates->isNotEmpty()) {
                $this->warn('Found duplicate sales records. Cleaning up...');
                foreach ($duplicates as $duplicate) {
                    // Keep the latest sale record and delete others
                    $sales = Sale::where('repair_id', $duplicate->repair_id)
                        ->orderBy('created_at', 'desc')
                        ->get();
                    
                    // Skip the first (latest) record and delete the rest
                    foreach ($sales->skip(1) as $sale) {
                        $sale->delete();
                    }
                }
                $this->info('Duplicate sales records cleaned up.');
            }

            // 2. Get all completed repairs
            $repairs = Repair::where('status', 'completed')->get();
            $this->info("Found {$repairs->count()} completed repairs.");

            // 3. Create or update sales records
            $created = 0;
            $updated = 0;
            foreach ($repairs as $repair) {
                $sale = Sale::firstOrCreate(
                    ['repair_id' => $repair->id],
                    [
                        'amount' => $repair->cost,
                        'sale_date' => $repair->completed_at ?? $repair->updated_at ?? now(),
                    ]
                );

                // Update amount if it doesn't match the repair cost
                if ($sale->amount != $repair->cost) {
                    $sale->update(['amount' => $repair->cost]);
                    $updated++;
                } elseif ($sale->wasRecentlyCreated) {
                    $created++;
                }
            }

            // 4. Remove orphaned sales (sales without completed repairs)
            $orphanedCount = Sale::whereNotIn('repair_id', $repairs->pluck('id'))->delete();

            $this->info("Sales synchronization completed:");
            $this->info("- Created {$created} new sales records");
            $this->info("- Updated {$updated} existing sales records");
            if ($orphanedCount > 0) {
                $this->info("- Removed {$orphanedCount} orphaned sales records");
            }

            // 5. Show total sales amount
            $totalSales = Sale::sum('amount');
            $this->info("\nTotal sales amount: ₱" . number_format($totalSales, 2));

            // 6. Show individual repairs and their costs
            $this->info("\nDetailed repair costs:");
            foreach ($repairs as $repair) {
                $this->line("Repair #{$repair->id}: ₱" . number_format($repair->cost, 2) . 
                    " (Sale date: " . ($repair->completed_at ?? $repair->updated_at)?->format('Y-m-d') . ")");
            }

        } catch (\Exception $e) {
            $this->error('An error occurred: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
        }
    }
}

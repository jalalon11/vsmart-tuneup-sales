<?php

namespace App\Console\Commands;

use App\Models\Repair;
use App\Models\Sale;
use Illuminate\Console\Command;

class CreateMissingSales extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'repairs:create-missing-sales';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create missing sales records for completed repairs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Checking for completed repairs without sales records...');

        // Get all completed repairs that don't have associated sales
        $repairs = Repair::where('status', 'completed')
            ->whereDoesntHave('sale')
            ->get();

        if ($repairs->isEmpty()) {
            $this->info('No missing sales records found.');
            return;
        }

        $count = 0;
        foreach ($repairs as $repair) {
            // Create sale record
            $repair->sale()->create([
                'amount' => $repair->cost,
                'sale_date' => $repair->completed_at ?? $repair->updated_at ?? now(),
            ]);
            $count++;
        }

        $this->info("Created {$count} missing sales records.");
    }
}

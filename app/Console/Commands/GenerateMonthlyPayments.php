<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\DivorceCase;
use App\Models\Payment;
use Carbon\Carbon;

class GenerateMonthlyPayments extends Command
{
    protected $signature = 'app:generate-monthly-payments';
    protected $description = 'Generate monthly payments for divorce case obligations';

    public function handle()
    {
        $today = Carbon::today();
        $month = $today->month;
        $year = $today->year;

        // Get all active divorce cases with obligations
        $divorceCases = DivorceCase::whereHas('obligation')->get();

        $createdCount = 0;

        foreach ($divorceCases as $case) {
            $obligation = $case->obligation;

            // Optional: skip if payment already exists for this month
            $existingPayment = Payment::where('divorce_case_id', $case->id)
                                      ->whereMonth('due_date', $month)
                                      ->whereYear('due_date', $year)
                                      ->first();
            if ($existingPayment) {
                continue; // skip already created payment
            }

            // Create the new payment
            Payment::create([
                'divorce_case_id' => $case->id,
                'amount' => $obligation->amount,
                'due_date' => $today->copy()->endOfMonth(), // payment due at the end of the month
                'status' => 'entry', // default status
            ]);

            $createdCount++;
        }

        $this->info("Monthly payments generated: {$createdCount}");
    }
}

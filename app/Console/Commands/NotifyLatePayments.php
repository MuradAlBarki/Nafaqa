<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Payment;
use App\Notifications\UserAlertNotification;
use Carbon\Carbon;

class NotifyLatePayments extends Command
{
    protected $signature = 'app:notify-late-payments';
    protected $description = 'Notify fathers about payments with status "entry" from last month';

    public function handle()
    {
        $today = Carbon::today();
        $lastMonth = $today->copy()->subMonth();

        // Get payments with 'entry' status from last month
        $latePayments = Payment::where('status', 'entry')
                               ->whereBetween('due_date', [$lastMonth->startOfMonth(), $lastMonth->endOfMonth()])
                               ->get();

        if ($latePayments->isEmpty()) {
            $this->info('No late payments found.');
            return;
        }

        foreach ($latePayments as $payment) {
            // Access the father via the related divorce case
            $father = $payment->divorceCase?->father;

            if ($father) {
                $father->notify(new UserAlertNotification(
                    'Late Payment',
                    "Payment #{$payment->id} for last month is overdue. Please take action.",
                    'payment',
                    route('payments.show', $payment)
                ));

                $this->info("Notified father: {$father->name} for payment #{$payment->id}");
            }
        }

        $this->info('Late payment notifications sent successfully.');
    }
}

<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LatePaymentsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        // Get late payments (status 'entry' and due last month)
        $latePayments = Payment::where('status', 'entry')
            ->whereMonth('due_date', now()->subMonth()->month)
            ->with('divorceCase.father.profileRole') // eager load profileRole
            ->get();

        // Map data for Excel
        return $latePayments->map(function($payment) {
            $profile = $payment->divorceCase->father?->profileRole;
            return [
                'Payment ID' => $payment->id,
                'Amount' => $payment->amount,
                'Due Date' => $payment->due_date->format('Y-m-d'),
                'Father Name' => $profile?->first_name . ' ' . $profile?->mid_name . ' ' . $profile?->last_name,
                'Father National No' => $profile?->national_no,
                'Father Email' => $payment->divorceCase->father?->email,
                'Divorce Case ID' => $payment->divorceCase->id,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Payment ID',
            'Amount',
            'Due Date',
            'Father Name',
            'Father National No',
            'Father Email',
            'Divorce Case ID',
        ];
    }
}

<?php

namespace App\Exports;

use App\Models\ProfileRole;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class ProfileRolesExport implements FromCollection, WithHeadings
{
    /**
     * Return a collection of all profile roles with user info
     *
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return ProfileRole::with('user')->get()->map(function ($profileRole) {
            return [
                'ID'           => $profileRole->id,
                'User Name'    => $profileRole->user?->name,
                'User Phone'   => $profileRole->user?->phone,
                'First Name'   => $profileRole->first_name,
                'Middle Name'  => $profileRole->mid_name,
                'Last Name'    => $profileRole->last_name,
                'Date of Birth'=> $profileRole->date_of_birth,
                'Nationality'  => $profileRole->nationality->name,
                'National No'  => $profileRole->national_no,
                'IBAN'         => $profileRole->IBAN,
                'Document Type'=> $profileRole->document_type?->label(),
                'Document No'  => $profileRole->document_no,
                'Document File'=> $profileRole->document_file_url,
                'Status'       => $profileRole->status?->label(), 
                'Gender'       => $profileRole->gender?->label(),
            ];
        });
    }

    /**
     * Column headings for Excel
     */
    public function headings(): array
    {
        return [
            'ID',
            'User Name',
            'User Phone',
            'First Name',
            'Middle Name',
            'Last Name',
            'Date of Birth',
            'Nationality',
            'National No',
            'IBAN',
            'Document Type',
            'Document No',
            'Document File',
            'Status',
            'Gender',
            'Created At',
            'Updated At',
        ];
    }
}

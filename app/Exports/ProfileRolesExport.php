<?php

namespace App\Exports;

use App\Models\ProfileRole;
use Maatwebsite\Excel\Concerns\FromCollection;

class ProfileRolesExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return ProfileRole::all();
    }
}

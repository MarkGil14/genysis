<?php

namespace App\Exports;

use App\SiforderreturnHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifreturnHeaderExport implements FromCollection
{
    public function collection()
    {
        return SiforderreturnHeader::all();
    }

}
<?php

namespace App\Exports;

use App\SifcustomerHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifcustomerHeaderExport implements FromCollection
{
    public function collection()
    {
        return SifcustomerHeader::all();
    }

}
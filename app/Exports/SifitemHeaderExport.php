<?php

namespace App\Exports;

use App\SifitemHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifitemHeaderExport implements FromCollection
{
    public function collection()
    {
        return SifitemHeader::all();
    }

}
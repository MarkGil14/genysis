<?php

namespace App\Exports;

use App\SiforderHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SiforderHeaderExport implements FromCollection
{
    public function collection()
    {
        return SiforderHeader::all();
    }

}
<?php

namespace App\Exports;

use App\SifdspHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifdspHeaderExport implements FromCollection
{
    public function collection()
    {
        return SifdspHeader::all();
    }

}
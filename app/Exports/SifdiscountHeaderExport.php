<?php

namespace App\Exports;

use App\SifdiscountHeader;
use Maatwebsite\Excel\Concerns\FromCollection;

class SifdiscountHeaderExport implements FromCollection
{
    public function collection()
    {
        return SifdiscountHeader::all();
    }

}
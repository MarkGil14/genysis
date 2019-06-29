<?php

namespace App\Imports;
use App\Siforder;
use App\SiforderValidation;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithValidation;

class SifdiscountValidateImport implements  ToModel, WithHeadingRow, WithValidation  
{
    use Importable;

    public function model(array $row)
    {
        return new Siforder([
            'InvoiceNumber' => $row['invoice_number'],
        ]);        
    }

    public function rules(): array
    {
        return [
            'InvoiceNumber' => 'required',
            '*.InvoiceNumber' => 'required',
        ];
    }

   
}

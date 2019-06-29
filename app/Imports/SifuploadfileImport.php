<?php

namespace App\Imports;

use App\Sifuploadfile;
use App\Siforder;
use App\Siforderitem;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Contracts\Queue\ShouldQueue;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\ToCollection;

class SifuploadfileImport implements ToModel,   WithHeadingRow, WithValidation 
{
    use Importable;    
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Sifuploadfile([
            'user_id'  => $row['invoice_number'],            
            'file_name' => $row['filename'],                        
        ]);        
    }



    
    public function rules(): array
    {
        return [
            'InvoiceNumber' => 'required',
            '*.InvoiceNumber' => 'required',
            'SalesType' => 'required',
            '*.SalesType' => 'required',
            'MaterialCode' => 'required',
            '*.MaterialCode' => 'required',
        ];
    }


    // public function collection(Collection $rows)
    // {
    //     $arrFile = [];
    //     foreach($rows as $row)
    //     {
    //         $arrFile = [
    //             ''
    //         ];
    //     }

    // }


}

<?php

namespace App\Imports;
use App\Siforder;
use App\SiforderValidation;
use App\Siforderitem;
use App\Sifitem;
use App\SifFunction;


use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\Importable;
use PhpOffice\PhpSpreadsheet\Spreadsheet\Date;
use Illuminate\Support\Carbon;

class SiforderValidateImport implements   WithHeadingRow , ToCollection
{
    use Importable;


    public function collection(Collection $rows)
    {
            $rowCount = 2; //start of the rows in the CSV/EXCEL
            $arrError = []; //this will be the storage of all error msges
            $validateOrder = new SiforderValidation();
    
            foreach($rows as $row)
            {
                $fieldErrors = false;
    
                //validate invoice number
                $fieldErrors = $validateOrder->validate($row['invoice_number'], 'InvoiceNumber');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'InvoiceNumber',
                        'errors' => $fieldErrors
                    ];
                }
    
    
                //validate sales type
                $fieldErrors = $validateOrder->validate($row['sales_type'],  'SalesType');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'SalesType',
                        'errors' => $fieldErrors
                    ];
                }
    
    
    
                //validate account id
                $fieldErrors = $validateOrder->validate($row['account_id'],  'AccountId');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'AccountId',
                        'errors' => $fieldErrors
                    ];
                }
     
    
                //validate SASID
                $fieldErrors = $validateOrder->validate($row['sas_id'],  'SASId');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'SASId',
                        'errors' => $fieldErrors
                    ];
                }
    
                //validate DSPID
                $fieldErrors = $validateOrder->validate($row['dsp_id'],  'DSPId');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'DSPId',
                        'errors' => $fieldErrors
                    ];
                }
    
    
                //validate Transaction Id
                $fieldErrors = $validateOrder->validate($row['transaction_id'],  'TransactionId');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'TransactionId',
                        'errors' => $fieldErrors
                    ];
                }
    
    
    
                //validate Payment Term
                $fieldErrors = $validateOrder->validate($row['transaction_id'],  'PaymentTerm');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'PaymentTerm',
                        'errors' => $fieldErrors
                    ];
                }            
    
    
                //validate material code
                if(!$this->getMaterialConversion($row['material_code'])){
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'MaterialCode',
                        'errors' => 'This "'.$row['material_code'].'" Material Code does not exists'
                    ];
                }
    
                $fieldErrors = $validateOrder->validate($row['material_code'],  'MaterialCode');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'MaterialCode',
                        'errors' => $fieldErrors
                    ];
                }
     
    
                //validate price
                $fieldErrors = $validateOrder->validate(abs($row['price']),  'Price');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'Price',
                        'errors' => $fieldErrors
                    ];
                }
    
                //validate quantity
                $fieldErrors = $validateOrder->validate(abs($row['quantity']),  'Quantity');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'Quantity',
                        'errors' => $fieldErrors
                    ];
                }
    
    
                //validate date
                $fieldErrors = $validateOrder->validate(SifFunction::formatDate($this->transformDate($row['date'])),'OrderDate');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'OrderDate',
                        'errors' => $fieldErrors
                    ];
                }
    
    
                //validate requested date
                $fieldErrors = $validateOrder->validate(SifFunction::formatDate($this->transformDate($row['delivery_date'])),'RequestedDeliveryDate');
                if($fieldErrors != false)
                {
                    $arrError[] = [
                        'row' => $rowCount,
                        'field' => 'RequestedDeliveryDate',
                        'errors' => $fieldErrors
                    ];
                }
    
                $rowCount++; //increment the row
            }
    
            if(count($arrError) > 0){
                $errorResponse = [
                    'entity' => 'Order',
                    'errors' => json_encode($arrError)
                ];
                exit(json_encode($errorResponse));
            } 
             
    }


    
    
    /**
     * @param matcode //material code of an item
     * this method use to get the conversion id of the material
     * @return conversion_id : false
     */
    public function getMaterialConversion($matcode)
    {
        $item = Sifitem::find($matcode);
        return $item ? $item->ConversionId : false;
    }


    
    /**
     * @param value , $format = 'Y-m-d'
     * use to transform the date in the CSV/EXCEL FILE
     * @return result
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try{
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));            
        }catch(\ErrorException $e)
        {
            //validate if the value is date
            //if not , the date will return to the default 1970-01-01             
            $date = SifFunction::isDate($value); 
            return \Carbon\Carbon::createFromFormat($format, $date);       
        }
    }




}

<?php

namespace App\Imports;

use App\Sifcustomer;
use App\SifcustomerHeader;
use App\SifcustomerValidation;
use App\FormatName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;


class SifcustomerImport implements  ToCollection, WithHeadingRow 
{
     
    public function collection(Collection $rows) 
    {
        $arrFile = [];


        //validate and get all the error of the imported CSV;/EXCEL
        $errors = $this->validateRows($rows);
        //if has an error found
        //then exit and return all the error messages
        if(count($errors) > 0){
            $errorResponse = [
                'entity' => FormatName::first()->sifcustomer.'.xlsx',
                'errors' => json_encode($errors)
            ];
            exit(json_encode($errorResponse));
        }         


        foreach($rows as $row)
        {
            $key = $this->isExists($row['account_id'], $arrFile);      
            $has = Sifcustomer::where('AccountId', $row['account_id'])->exists();
            if(!$has){
        
                if($key !== false)
                {
                    $arrFile[$key] = [
                        'AccountId' => $row['account_id'],
                        'AccountName' => $row['account_name'],
                        'Channel' => $row['channel'],
                        'City' => $row['city'],
                        'Street' => $row['street']
                    ];                            
                }else{
                    $arrFile[] = [
                        'AccountId' => $row['account_id'],
                        'AccountName' => $row['account_name'],
                        'Channel' => $row['channel'],
                        'City' => $row['city'],
                        'Street' => $row['street']
                    ];            
                }
            }else{

                Sifcustomer::where('AccountId', $row['account_id'])
                ->update([
                    'AccountId' => $row['account_id'],
                    'AccountName' => $row['account_name'],
                    'Channel' => $row['channel'],
                    'City' => $row['city'],
                    'Street' => $row['street']
                ]);               

            }

        }

        Sifcustomer::insert($arrFile);

    }

    public function isExists($keyword, $arrFile)
    {            
        $output = false;
        foreach ($arrFile as $key => $value) {
                if($keyword === $value['AccountId'])
                    $output = $key;
        }                                  
        return $output;                      
    }




       /**
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {
         $headerArr = [];       
         $headers = SifcustomerHeader::first();
         return $headerArr = json_decode($headers);
    }


    /**
     * @param rows 
     * this method use to validate all the rows of the import EXCEL/CSV
     * @return ArrError array()
     */
    public function validateRows($rows)
    {
        $rowCount = 2; //start of the rows in the CSV/EXCEL
        $arrError = []; //this will be the storage of all error msges
        $validateItem = new SifcustomerValidation();
        $headerArr = self::getHeaderRow(); //get the header row of an every data

        foreach($rows as $row)
        {
            $fieldErrors = false;

            /**
             * $key = header of a table
             * $value = the value of an header
             */
            foreach($headerArr as $key => $value): 

                // loop one by one
                // and validate 
                $fieldErrors = $validateItem->validate($row[SifFunction::makeSlug($value)], $key);
                    if($fieldErrors != false)
                    {
                        $arrError[] = [
                            'row' => $rowCount,
                            'field' => $value,
                            'errors' => $fieldErrors
                        ];
                    }                   

            endforeach;

            $rowCount++; //increment the row

             

        }

        return $arrError;
    }

}

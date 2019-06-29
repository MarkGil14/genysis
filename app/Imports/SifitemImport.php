<?php

namespace App\Imports;

use App\Sifitem;
use App\SifitemValidation;
use App\SifitemHeader;
use App\SifFunction;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\FormatName;


class SifitemImport implements ToCollection, WithHeadingRow
{

    public function collection(Collection $rows)
    {
        $arrFile = [];

        //get the header row of an item
        $headerRow = SifitemHeader::first();

        //validate and get all the error of the imported CSV;/EXCEL
        $errors = $this->validateRows($rows);
        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {
            $errorResponse = [
                'entity' => FormatName::first()->sifitem . '.xlsx',
                'errors' => json_encode($errors)
            ];
            exit(json_encode($errorResponse));
        }

        foreach ($rows as $row) {
            $key = $this->isExists($row[SifFunction::makeSlug($headerRow->material_code)], $arrFile);
            $has = Sifitem::where('MaterialCode', $row[SifFunction::makeSlug($headerRow->material_code)])->exists();
            if (!$has) {
                if ($key !== false) {
                    $arrFile[$key] = [
                        'MaterialCode' => $row[SifFunction::makeSlug($headerRow->material_code)],
                        'Description' => $row[SifFunction::makeSlug($headerRow->description)],
                        'MaterialGroup' => $row[SifFunction::makeSlug($headerRow->material_group)],
                        'ConversionId' => $row[SifFunction::makeSlug($headerRow->conversion_id)]
                    ];
                } else {
                    $arrFile[] = [
                        'MaterialCode' => $row[SifFunction::makeSlug($headerRow->material_code)],
                        'Description' => $row[SifFunction::makeSlug($headerRow->description)],
                        'MaterialGroup' => $row[SifFunction::makeSlug($headerRow->material_group)],
                        'ConversionId' => $row[SifFunction::makeSlug($headerRow->conversion_id)]
                    ];
                }
            } else {

                $item = Sifitem::where('MaterialCode', $row[SifFunction::makeSlug($headerRow->material_code)])->first();
                $item->MaterialCode = $row[SifFunction::makeSlug($headerRow->material_code)];
                $item->Description = $row[SifFunction::makeSlug($headerRow->description)];
                $item->MaterialGroup = $row[SifFunction::makeSlug($headerRow->material_group)];
                $item->ConversionId = $row[SifFunction::makeSlug($headerRow->conversion_id)];
                $item->save();
            }
        }

        Sifitem::insert($arrFile);
    }

    public function isExists($keyword, $arrFile)
    {
        $output = false;
        foreach ($arrFile as $key => $value) {
            if ($keyword === $value['MaterialCode'])
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
        $headers = SifitemHeader::first();
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
        $validateItem = new SifitemValidation();
        $headerArr = self::getHeaderRow(); //get the header row of an every data

        foreach ($rows as $row) {
            $fieldErrors = false;

            /**
             * $key = header of an table
             * $value = the value of an header
             */
            foreach ($headerArr as $key => $value) :

                // loop one by one
                // and validate 
                $fieldErrors = $validateItem->validate($row[SifFunction::makeSlug($value)], $key);
                if ($fieldErrors != false) {
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

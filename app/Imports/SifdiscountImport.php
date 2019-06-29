<?php

namespace App\Imports;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Sifdsp;
use App\Siforder;
use App\Siforderitem;
use App\Sifitem;
use App\SifFunction;
use App\SifdiscountHeader;
use App\RuleValidation;
use App\FormatName;

// use Illuminate\Support\Facades\Validator;
// use Maatwebsite\Excel\Concerns\ToCollection;
// use Illuminate\Support\Collection;
// use Maatwebsite\Excel\Concerns\Importable;
// use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\SifdiscountValidation;

// class SifdiscountImport implements ToCollection, WithHeadingRow
class SifdiscountImport
{
    // use Importable;

    private $discountDescription;
    private $invoiceNumber;
    private $discountAmount;

    // public function collection(Collection $rows)
    public function processDiscount($rows, $orderArr, $itemArr)
    {
        $arrFile = [];

        (float)$totalDiscount = 0;
        //validate and get all the error of the imported CSV;/EXCEL

        $rowHeader = SifdiscountHeader::first();

        foreach ($rows as $row) {

            //determine if the invoice discountn is multiplel or not
            if (SifFunction::hasMultipleInvoice($row['invoice_number'])) {

                //if has multiple invoice
                //seperate all multiple invoice , convert it into array
                $invoiceArr = array();
                $invoiceArr = SifFunction::seperateMultipleInvoice($row['invoice_number']);
                //todo
                // 'material_code'                
                if (is_null($row['material_code']) || strlen($row['material_code']) < 13) {

                    /**
                     * determine if the invoice number is exists
                     * by looping in invoiceArr
                     */
                    $key = false;
                    for ($i = 0; $i < count($invoiceArr); $i++) {
                        $key = SifFunction::isExists($invoiceArr[$i], $orderArr);
                        if ($key != false)
                            break;
                    }

                    // $sifOrder = Siforder::where('InvoiceNumber', $invoiceNumber)->first(); //get the siforder from the database by invoiceNumber
                    if ($key != false) {
                        $this->discountAmount = abs($row['total_amount']);    //the  discount amount of order 
                        $this->discountDescription = $row['material_description'];
                        //save the discount in header
                        $this->addDiscountHeader($orderArr[$key]);

                        //add in totalDiscount the discount added in the order header
                        // $totalDiscount = $totalDiscount + abs($row['total_amount']);
                    }
                } else {
                    /**
                     * determine if the invoice number is exists
                     * by looping in invoiceArr
                     */
                    $key = false;
                    for ($i = 0; $i < count($invoiceArr); $i++) {
                        $key = SifFunction::isExists($invoiceArr[$i], $orderArr);
                        if ($key != false)
                            break;
                    }

                    // $result = $this->getOneInvoiceWithMatcode($invoiceArr, $row['material_code']);
                    if ($key != false) //check if the result has a value 
                    {
                        $this->discountAmount = abs($row['total_amount']);    //the  discount amount of order 
                        $this->discountDescription = $row['material_description'];
                        $this->addDiscountItem($orderArr[$key], $itemArr);
                        // $this->addDiscountInItem($result);
                        //add in totalDiscount the discount added in item
                        // $totalDiscount = $totalDiscount + abs($row['total_amount']);
                    }
                }
            } else {
                //if the invoice is not a multiple .

                //determine if the discount is PROMOTIONAL or Item discount
                //by checking if the materialcode column has a value or not
                if (is_null($row['material_code']) || strlen($row['material_code']) < 13) {
                    //if null, the discount will go in the header . (Discount Header)                    
                    //get the siforder from the database by invoiceNumber
                    // $sifOrder = Siforder::where('InvoiceNumber', $row['invoice_number'])->first();
                    $key = false;
                    $key = SifFunction::isExists($row['invoice_number'], $orderArr);
                    if ($key !== false) {
                        $this->discountAmount = abs($row['total_amount']);    //the  discount amount of order 
                        $this->discountDescription = $row['material_description'];
                        $this->addDiscountHeader($orderArr[$key]);
                        //add in totalDiscount the discount added in the order header
                        // $totalDiscount = $totalDiscount + abs($row['total_amount']);

                        // $customer->updateTotalAmount($CId, abs($discountAmount));
                    }
                } else {

                    //if has a value, the discount is store in material code
                    //find the invoice with matcode in the database
                    // $result = Siforder::findInvoiceWithMatcode($row['invoice_number'], $row['material_code']);
                    $key = false;
                    $key = SifFunction::isExists($row['invoice_number'], $orderArr);

                    if ($key !== false) //check if the result has a value 
                    {
                        $this->discountAmount = abs($row['total_amount']);    //the  discount amount of order 
                        $this->discountDescription = $row['material_description'];
                        // $this->addDiscountInItem($result->first());
                        $this->addDiscountItem($orderArr[$key], $itemArr);
                        //add in totalDiscount the discount added in item
                        // $totalDiscount = $totalDiscount + abs($row['total_amount']);
                    }
                }
            }
        }

        return $totalDiscount;
    }


    public function addDiscountHeader($orderArr)
    {
        if ($orderArr['InvoiceDiscountFromTotal1'] == 0) {
            //for invoice            
            $orderArr['InvoiceDiscountFromTotal1'] = $this->discountAmount;
            $orderArr['InvoiceDiscountFromTotal1Type'] = $this->discountDescription;
            $orderArr['InvoiceDiscountFromTotal1Amount'] = $this->discountAmount;
            $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal1'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal1Type'] = $this->discountDescription;
            $orderArr['OrderDiscountFromTotal1Amount'] = $this->discountAmount;
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal2'] == 0) {
            //for invoice            
            $orderArr['InvoiceDiscountFromTotal2'] = $this->discountAmount;
            $orderArr['InvoiceDiscountFromTotal2Type'] = $this->discountDescription;
            $orderArr['InvoiceDiscountFromTotal2Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal2Scheme'] = 'Gross';
            $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal2'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal2Type'] = $this->discountDescription;
            $orderArr['OrderDiscountFromTotal2Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal2Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal3'] == 0) {

            //for invoice            
            $orderArr['InvoiceDiscountFromTotal3'] = $this->discountAmount;
            $orderArr['InvoiceDiscountFromTotal3Type'] = $this->discountDescription;
            $orderArr['InvoiceDiscountFromTotal3Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal3Scheme'] = 'Gross';
            $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal3'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal3Type'] = $this->discountDescription;
            $orderArr['OrderDiscountFromTotal3Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal3Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal4'] == 0) {


            //for invoice            
            $orderArr['InvoiceDiscountFromTotal4'] = $this->discountAmount;
            $orderArr['InvoiceDiscountFromTotal4Type'] = $this->discountDescription;
            $orderArr['InvoiceDiscountFromTotal4Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal4Scheme'] = 'Gross';
            $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal4'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal4Type'] = $this->discountDescription;
            $orderArr['OrderDiscountFromTotal4Amount'] = $this->discountAmount;
            $orderArr['OrderDiscountFromTotal4Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;
        } else {
            $orderArr['InvoiceDiscountFromTotal4'] += $this->discountAmount;
            $orderArr['InvoiceDiscountFromTotal4Type'] = $orderArr['InvoiceDiscountFromTotal4Type'] . ', ' . $this->discountDescription;
            $orderArr['InvoiceDiscountFromTotal4Amount'] += $this->discountAmount;
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal4'] += $this->discountAmount;
            $orderArr['OrderDiscountFromTotal4Type'] = $orderArr['InvoiceDiscountFromTotal4Type'] . ', ' . $this->discountDescription;
            $orderArr['OrderDiscountFromTotal4Amount'] += $this->discountAmount;
            $orderArr['OrderDiscountFromTotal4Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $this->discountAmount;
        }
    }


    public function updateDiscountTotalOfOrder()
    {
        (float)$currentTotalDiscount = 0;
        (float)$headerDiscount = 0;
        (float)$totalHeaderDiscount = 0;
        (float)$itemDiscount = 0;
        $fieldScheme1 = null;
        $fieldScheme2 = null;
        $fieldScheme1Value = null;
        $fieldScheme2Value = null;
        $oldDiscountType = null;

        $fieldDiscount1 = null;
        $fieldDiscount2  = null;
        $fieldIsPercent1 = 0;
        $fieldIsPercent2 = 0;
        $fieldType1 = null;
        $fieldType2 = null;
        $fieldAmount1  = 0;
        $fieldAmount2 = 0;

        (float)$oldTotalAmount = 0;
        (float)$oldDiscountAmountInHeader = 0;

        //loop all the item of the order 
        foreach (Siforder::orderItems($this->invoiceNumber)->get() as $orderInfo) {
            //determine the null discount of order
            if ($orderInfo->InvoiceDiscountFromTotal1 == 0) {
                //if the InvoiceDiscountFromTotal1 is 0
                //get the field included of the 1st invoice
                $fieldDiscount1 = 'OrderDiscountFromTotal1';
                $fieldDiscount2 = 'InvoiceDiscountFromTotal1';
                $fieldIsPercent1 = 'IsOrderDiscountFromTotal1Percent';
                $fieldIsPercent2 = 'IsInvoiceDiscountFromTotal1Percent';
                $fieldType1 = 'OrderDiscountFromTotal1Type';
                $fieldType2 = 'InvoiceDiscountFromTotal1Type';
                $fieldAmount1 = 'OrderDiscountFromTotal1Amount';
                $fieldAmount2 = 'InvoiceDiscountFromTotal1Amount';
                $fieldScheme1 = 'OrderDiscountFromTotal2Scheme';
                $fieldScheme2 = 'InvoiceDiscountFromTotal2Scheme';
                $fieldScheme1Value = null;
                $fieldScheme2Value = null;
            } else if ($orderInfo->InvoiceDiscountFromTotal2 == 0) {
                //if the InvoiceDiscountFromTotal2 is 0
                //get the field included of the 2nd invoice
                $fieldDiscount1 = 'OrderDiscountFromTotal2';
                $fieldDiscount2 = 'InvoiceDiscountFromTotal2';
                $fieldIsPercent1 = 'IsOrderDiscountFromTotal2Percent';
                $fieldIsPercent2 = 'IsInvoiceDiscountFromTotal2Percent';
                $fieldType1 = 'OrderDiscountFromTotal2Type';
                $fieldType2 = 'InvoiceDiscountFromTotal2Type';
                $fieldAmount1 = 'OrderDiscountFromTotal2Amount';
                $fieldAmount2 = 'InvoiceDiscountFromTotal2Amount';
                $fieldScheme1 = 'OrderDiscountFromTotal2Scheme';
                $fieldScheme2 = 'InvoiceDiscountFromTotal2Scheme';
                $fieldScheme1Value = 'Gross';
                $fieldScheme2Value = 'Gross';
            } else if ($orderInfo->InvoiceDiscountFromTotal3 == 0) {
                //if the InvoiceDiscountFromTotal3 is 0
                //get the field included of the 3rd invoice
                $fieldDiscount1 = 'OrderDiscountFromTotal3';
                $fieldDiscount2 = 'InvoiceDiscountFromTotal3';
                $fieldIsPercent1 = 'IsOrderDiscountFromTotal3Percent';
                $fieldIsPercent2 = 'IsInvoiceDiscountFromTotal3Percent';
                $fieldType1 = 'OrderDiscountFromTotal3Type';
                $fieldType2 = 'InvoiceDiscountFromTotal3Type';
                $fieldAmount1 = 'OrderDiscountFromTotal3Amount';
                $fieldAmount2 = 'InvoiceDiscountFromTotal3Amount';
                $fieldScheme1 = 'OrderDiscountFromTotal3Scheme';
                $fieldScheme2 = 'InvoiceDiscountFromTotal3Scheme';
                $fieldScheme1Value = 'Gross';
                $fieldScheme2Value = 'Gross';
            } else if ($orderInfo->InvoiceDiscountFromTotal4 == 0) {

                //if the InvoiceDiscountFromTotal4 is 0
                //get the field included of the 4th invoice
                $fieldDiscount1 = 'OrderDiscountFromTotal4';
                $fieldDiscount2 = 'InvoiceDiscountFromTotal4';
                $fieldIsPercent1 = 'IsOrderDiscountFromTotal4Percent';
                $fieldIsPercent2 = 'IsInvoiceDiscountFromTotal4Percent';
                $fieldType1 = 'OrderDiscountFromTotal4Type';
                $fieldType2 = 'InvoiceDiscountFromTotal4Type';
                $fieldAmount1 = 'OrderDiscountFromTotal4Amount';
                $fieldAmount2 = 'InvoiceDiscountFromTotal4Amount';
                $fieldScheme1 = 'OrderDiscountFromTotal4Scheme';
                $fieldScheme2 = 'InvoiceDiscountFromTotal4Scheme';
                $fieldScheme1Value = 'Gross';
                $fieldScheme2Value = 'Gross';
            } else {
                //if all invoicediscountFromTottal has a value
                //get the field included of the 4th invoice

                $fieldDiscount1 = 'OrderDiscountFromTotal4';
                $fieldDiscount2 = 'InvoiceDiscountFromTotal4';
                $fieldIsPercent1 = 'IsOrderDiscountFromTotal4Percent';
                $fieldIsPercent2 = 'IsInvoiceDiscountFromTotal4Percent';
                $fieldType1 = 'OrderDiscountFromTotal4Type';
                $fieldType2 = 'InvoiceDiscountFromTotal4Type';
                $fieldAmount1 = 'OrderDiscountFromTotal4Amount';
                $fieldAmount2 = 'InvoiceDiscountFromTotal4Amount';
                $fieldScheme1 = 'OrderDiscountFromTotal4Scheme';
                $fieldScheme2 = 'InvoiceDiscountFromTotal4Scheme';
                $fieldScheme1Value = 'Gross';
                $fieldScheme2Value = 'Gross';

                $oldDiscountAmountInHeader = $orderInfo->InvoiceDiscountFromTotal4;

                if (is_null($orderInfo->InvoiceDiscountFromTotal4Type))
                    $oldDiscountType = $orderInfo->InvoiceDiscountFromTotal4Type;
                else
                    $oldDiscountType = $orderInfo->InvoiceDiscountFromTotal4Type . ',';
            }

            $totalHeaderDiscount = $orderInfo->InvoiceDiscountFromTotal1 + $orderInfo->InvoiceDiscountFromTotal2 + $orderInfo->InvoiceDiscountFromTotal3 + $orderInfo->InvoiceDiscountFromTotal4;
            $itemDiscount = $itemDiscount + ($orderInfo->InvoiceDiscount1 + $orderInfo->InvoiceDiscount2 + $orderInfo->InvoiceDiscount3 + $orderInfo->InvoiceDiscount4);
            $oldTotalAmount = SifFunction::amountNoCommaFormat($orderInfo->InvoiceTotal);
        }


        $newTotalAmount = $oldTotalAmount - $this->discountAmount;
        $currentTotalDiscount = SifFunction::amountNoCommaFormat($totalHeaderDiscount) + SifFunction::amountNoCommaFormat($itemDiscount);
        $newDiscount = $this->discountAmount + SifFunction::amountNoCommaFormat($currentTotalDiscount);
        $discount = SifFunction::amountNoCommaFormat($this->discountAmount) + SifFunction::amountNoCommaFormat($oldDiscountAmountInHeader);
        $newDiscountFormat = SifFunction::amountNoCommaFormat($newDiscount);

        //update the discount of order  by new discount

        Siforder::where('InvoiceNumber', $this->invoiceNumber)
            ->update([
                'InvoiceTotalDiscount' => $newDiscountFormat,
                'OrderTotalDiscount' => $newDiscountFormat,
                'InvoiceTotal' => $newTotalAmount,
                'OrderTotal' => $newTotalAmount,
                $fieldDiscount1 => $discount,
                $fieldDiscount2 => $discount,
                $fieldIsPercent1 => 0,
                $fieldIsPercent2 => 0,
                $fieldType1 => $oldDiscountType . $this->discountDescription,
                $fieldType2 => $oldDiscountType . $this->discountDescription,
                $fieldAmount1 => $discount,
                $fieldAmount2 => $discount,
                $fieldScheme1 => $fieldScheme1Value,
                $fieldScheme2 => $fieldScheme2Value
            ]);
    }


    /**
     * this method use to get the invoice number randomly
     */
    public function getOneInvoiceInMultiple($invoiceArr)
    {
        $start = $invoiceArr[0][strlen($invoiceArr[0]) - 2] . $invoiceArr[0][strlen($invoiceArr[0]) - 1]; //the ten'th value of the invoice number

        /**
         * seperate the letter and number
         */
        $value = SifFunction::seperateStringAndNumber($invoiceArr[0]);
        $prefix = $value[0]['letter']; //this will be the prefix of the invoice number
        $invoiceNumber = $value[0]['number']; //the number of invoice number        
        $result = null;
        $arrInvoice = array();

        while ($start <= $invoiceArr[1]) {
            if (SifFunction::isNumber($invoiceArr[0])) {
                array_push($arrInvoice, $invoiceArr[0]);
                $invoiceArr[0] = $invoiceArr[0] + 1; //increment the invoice number
                $start++;
            } else {
                array_push($arrInvoice, $prefix . $invoiceNumber);
                $invoiceNumber = $invoiceNumber + 1; //increment the invoice number without prefix
                $start++;
            }
        }

        $randomIndex = mt_rand(0, count($arrInvoice) - 1); // get element in the random index in the array
        $selectedInvoice = $arrInvoice[$randomIndex];
        return $selectedInvoice;
    }

    public function getOneInvoice($invoiceArr)
    {
        $arrInvoice = array();

        //store first invoice        
        array_push($arrInvoice, $invoiceArr[0]);

        //store second invoice
        array_push($arrInvoice, $invoiceArr[0]);

        $suffixLength = strlen($invoiceArr[1]);

        for ($i = 0; $i < $suffixLength; $i++) {
            $invoiceArr[0][strlen($invoiceArr[0]) - ($suffixLength - $i)] = $invoiceArr[1][$i];
            // $invoiceArr[0][strlen($invoiceArr[0]) -3] = $invoiceArr[1][0];
            // $invoiceArr[0][strlen($invoiceArr[0]) -2] = $invoiceArr[1][1];
            // $invoiceArr[0][strlen($invoiceArr[0]) - 1] = $invoiceArr[1][2];
        }

        array_push($arrInvoice, $invoiceArr[0]);

        $randomIndex = mt_rand(0, count($arrInvoice) - 1); // get element in the random index in the array
        $selectedInvoice = $arrInvoice[$randomIndex];

        return $selectedInvoice;
    }



    public function getOneInvoiceWithMatcode($invoiceArr, $matCode)
    {
        $arrInvoice = array();

        //store first invoice        
        array_push($arrInvoice, $invoiceArr[0]);
        $result = Siforder::findInvoiceWithMatcode($invoiceArr[0], $matCode);
        if ($result->exists()) //if exits 
            return $result->first();

        $suffixLength = strlen($invoiceArr[1]);

        for ($i = 0; $i < $suffixLength; $i++) {
            $invoiceArr[0][strlen($invoiceArr[0]) - ($suffixLength - $i)] = $invoiceArr[1][$i];
            // $invoiceArr[0][strlen($invoiceArr[0]) -3] = $invoiceArr[1][0];
            // $invoiceArr[0][strlen($invoiceArr[0]) -2] = $invoiceArr[1][1];
            // $invoiceArr[0][strlen($invoiceArr[0]) - 1] = $invoiceArr[1][2];
        }

        $result = Siforder::findInvoiceWithMatcode($invoiceArr[0], $matCode);
        if ($result->exists()) //if exits 
            return $result->first();


        return false;
    }





    /**
     * this method use to get the invoice number with matcode
     */
    public function getOneInvoiceInMultipleWithMatcode($invoiceArr, $matCode)
    {
        $start = $invoiceArr[0][strlen($invoiceArr[0]) - 2] . $invoiceArr[0][strlen($invoiceArr[0]) - 1]; //the ten'th value of the invoice number

        /**
         * seperate the letter and number
         */
        $value = SifFunction::seperateStringAndNumber($invoiceArr[0]);
        $prefix = $value[0]['letter']; //this will be the prefix of the invoice number
        $invoiceNumber = $value[0]['number']; //the number of invoice number        

        $result = null;
        $arrInvoice = array();

        while ($start <= $invoiceArr[1]) {

            if (SifFunction::isNumber($invoiceArr[0])) {

                //find the invoice that has a material code 
                $result = Siforder::findInvoiceWithMatcode($invoiceArr[0], $matCode);

                if ($result->exists()) //if exits 
                    return $result->first();

                $invoiceArr[0] = $invoiceArr[0] + 1; //increment the invoice number
                $start++;
            } else {

                //find the invoice that has a material code 
                $result = Siforder::findInvoiceWithMatcode($invoiceArr[0], $matCode);

                if ($result->exists()) //if exits 
                    return $result->first();

                $invoiceNumber = $invoiceNumber + 1; //increment the invoice number without prefix
                $start++;
            }
        }

        return false; //if no invoice with mat code found

    }

    public function addDiscountItem($orderArr, $itemArr)
    {
        $itemKey = false;
        foreach ($itemArr as $key => $item) {
            if ($item['ReferenceKeyId'] === $orderArr['KeyId'] && $item['ProductId'] === $this->material_code)
                $itemKey = $key;
        }

        if ($itemKey !== false) {

            if ($itemArr[$itemKey]['InvoiceDiscount1Amount'] == 0) {
                //for order
                $itemArr[$itemKey]['OrderDiscount1'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount1Type'] = $this->discountDescription;
                $itemArr[$itemKey]['OrderDiscount1Amount'] = $this->discountAmount;
                $orderArr['OrderTotalDiscount'] += $this->discountAmount;

                //for invoice
                $itemArr[$itemKey]['InvoiceDiscount1'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount1Type'] = $this->discountDescription;
                $itemArr[$itemKey]['InvoiceDiscount1Amount'] = $this->discountAmount;
                $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;
            } else if ($itemArr[$itemKey]['InvoiceDiscount2Amount'] == 0) {
                //for order
                $itemArr[$itemKey]['OrderDiscount2'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount2Type'] = $this->discountDescription;
                $itemArr[$itemKey]['OrderDiscount2Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount2Scheme'] = 'Gross';
                $orderArr['OrderTotalDiscount'] += $this->discountAmount;

                //for invoice
                $itemArr[$itemKey]['InvoiceDiscount2'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount2Type'] = $this->discountDescription;
                $itemArr[$itemKey]['InvoiceDiscount2Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount2Scheme'] = 'Gross';
                $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;
            } else if ($itemArr[$itemKey]['InvoiceDiscount3Amount'] == 0) {
                //for order
                $itemArr[$itemKey]['OrderDiscount3'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount3Type'] = $this->discountDescription;
                $itemArr[$itemKey]['OrderDiscount3Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount3Scheme'] = 'Gross';
                $orderArr['OrderTotalDiscount'] += $this->discountAmount;

                //for invoice
                $itemArr[$itemKey]['InvoiceDiscount3'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount3Type'] = $this->discountDescription;
                $itemArr[$itemKey]['InvoiceDiscount3Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount3Scheme'] = 'Gross';
                $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;
            } else if ($itemArr[$itemKey]['InvoiceDiscount4Amount'] == 0) {
                //for order
                $itemArr[$itemKey]['OrderDiscount4'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount4Type'] = $this->discountDescription;
                $itemArr[$itemKey]['OrderDiscount4Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount4Scheme'] = 'Gross';
                $orderArr['OrderTotalDiscount'] += $this->discountAmount;

                //for invoice
                $itemArr[$itemKey]['InvoiceDiscount4'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount4Type'] = $this->discountDescription;
                $itemArr[$itemKey]['InvoiceDiscount4Amount'] = $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount4Scheme'] = 'Gross';
                $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;
            } else {

                //for order
                $itemArr[$itemKey]['OrderDiscount4'] += $this->discountAmount;
                $itemArr[$itemKey]['OrderDiscount4Type'] = $itemArr[$itemKey]['OrderDiscount4Type'] . ', ' . $this->discountDescription;
                $itemArr[$itemKey]['OrderDiscount4Amount'] += $this->discountAmount;
                $orderArr['OrderTotalDiscount'] += $this->discountAmount;

                //for invoice
                $itemArr[$itemKey]['InvoiceDiscount4'] += $this->discountAmount;
                $itemArr[$itemKey]['InvoiceDiscount4Type'] = $itemArr[$itemKey]['InvoiceDiscount4Type'] . ', ' . $this->discountDescription;
                $itemArr[$itemKey]['InvoiceDiscount4Amount'] += $this->discountAmount;
                $orderArr['InvoiceTotalDiscount'] += $this->discountAmount;
            }
        }
    }


    public function addDiscountInItem($orderInfo)
    {
        (float)$currentTotalDiscount = 0;
        (float)$headerDiscount = 0;
        (float)$totalHeaderDiscount = 0;
        (float)$itemDiscount = 0;
        $fieldScheme1 = null;
        $fieldScheme2 = null;
        $fieldScheme1Value = null;
        $fieldScheme2Value = null;

        $oldDiscountType = null;
        (float)$oldDiscountAmountInHeader = 0;
        $refKeyId = null;
        (float)$lastSubtractedDiscount = 0;


        if ($orderInfo->InvoiceDiscount1 < 1) {

            $fieldDiscount1 = 'OrderDiscount1';
            $fieldDiscount2 = 'InvoiceDiscount1';
            $fieldIsPercent1 = 'IsOrderDiscount1Percent';
            $fieldIsPercent2 = 'IsInvoiceDiscount1Percent';
            $fieldType1 = 'OrderDiscount1Type';
            $fieldType2 = 'InvoiceDiscount1Type';
            $fieldAmount1 = 'OrderDiscount1Amount';
            $fieldAmount2 = 'InvoiceDiscount1Amount';
            $fieldScheme1 = 'OrderDiscount2Scheme';
            $fieldScheme2 = 'InvoiceDiscount2Scheme';
            $fieldScheme1Value = null;
            $fieldScheme2Value = null;

            $itemDiscount = $itemDiscount + ($orderInfo->InvoiceDiscount1 + $orderInfo->InvoiceDiscount2 + $orderInfo->InvoiceDiscount3 + $orderInfo->InvoiceDiscount4);
        } else if ($orderInfo->InvoiceDiscount2 < 1) {

            $fieldDiscount1 = 'OrderDiscount2';
            $fieldDiscount2 = 'InvoiceDiscount2';
            $fieldIsPercent1 = 'IsOrderDiscount2Percent';
            $fieldIsPercent2 = 'IsInvoiceDiscount2Percent';
            $fieldType1 = 'OrderDiscount2Type';
            $fieldType2 = 'InvoiceDiscount2Type';
            $fieldAmount1 = 'OrderDiscount2Amount';
            $fieldAmount2 = 'InvoiceDiscount2Amount';
            $fieldScheme1 = 'OrderDiscount2Scheme';
            $fieldScheme2 = 'InvoiceDiscount2Scheme';
            $fieldScheme1Value = 'Gross';
            $fieldScheme2Value = 'Gross';
            $itemDiscount = $itemDiscount + ($orderInfo->InvoiceDiscount1 + $orderInfo->InvoiceDiscount2 + $orderInfo->InvoiceDiscount3 + $orderInfo->InvoiceDiscount4);
        } else if ($orderInfo->InvoiceDiscount3 < 1) {
            $fieldDiscount1 = 'OrderDiscount3';
            $fieldDiscount2 = 'InvoiceDiscount3';
            $fieldIsPercent1 = 'IsOrderDiscount3Percent';
            $fieldIsPercent2 = 'IsInvoiceDiscount3Percent';
            $fieldType1 = 'OrderDiscount3Type';
            $fieldType2 = 'InvoiceDiscount3Type';
            $fieldAmount1 = 'OrderDiscount3Amount';
            $fieldAmount2 = 'InvoiceDiscount3Amount';
            $fieldScheme1 = 'OrderDiscount3Scheme';
            $fieldScheme2 = 'InvoiceDiscount3Scheme';
            $fieldScheme1Value = 'Gross';
            $fieldScheme2Value = 'Gross';

            $itemDiscount = $itemDiscount + ($orderInfo->InvoiceDiscount1 + $orderInfo->InvoiceDiscount2 + $orderInfo->InvoiceDiscount3 + $orderInfo->InvoiceDiscount4);
        } else {
            $fieldDiscount1 = 'OrderDiscount4';
            $fieldDiscount2 = 'InvoiceDiscount4';
            $fieldIsPercent1 = 'IsOrderDiscount4Percent';
            $fieldIsPercent2 = 'IsInvoiceDiscount4Percent';
            $fieldType1 = 'OrderDiscount4Type';
            $fieldType2 = 'InvoiceDiscount4Type';
            $fieldAmount1 = 'OrderDiscount4Amount';
            $fieldAmount2 = 'InvoiceDiscount4Amount';
            $fieldScheme1 = 'OrderDiscount4Scheme';
            $fieldScheme2 = 'InvoiceDiscount4Scheme';
            $fieldScheme1Value = 'Gross';
            $fieldScheme2Value = 'Gross';
            if (is_null($orderInfo->InvoiceDiscount4Type))
                $oldDiscountType = $orderInfo->InvoiceDiscount4Type;
            else
                $oldDiscountType = $orderInfo->InvoiceDiscount4Type . ',';

            $itemDiscount = $itemDiscount + ($orderInfo->InvoiceDiscount1 + $orderInfo->InvoiceDiscount2 + $orderInfo->InvoiceDiscount3 + $orderInfo->InvoiceDiscount4);
            $lastSubtractedDiscount = $orderInfo->InvoiceDiscount4;
        }

        $currentTotalDiscount = $orderInfo->InvoiceTotalDiscount;    //current total discount            
        (float)$currentTotalAmount = SifFunction::amountNoCommaFormat($orderInfo->InvoiceTotal); //current total amount

        //compute the new discount of order (discountAmount + currentTotalDiscount)
        $newDiscount = SifFunction::amountNoCommaFormat($this->discountAmount) + SifFunction::amountNoCommaFormat($currentTotalDiscount);

        //compute the new totalamount of order
        $newTotalAmount = $currentTotalAmount - SifFunction::amountNoCommaFormat($this->discountAmount);
        $newItemDiscount = $this->discountAmount + $lastSubtractedDiscount;

        Siforder::where('InvoiceNumber', $orderInfo->InvoiceNumber) //update the order by invoice number
            ->update([
                'InvoiceTotalDiscount' => $newDiscount,
                'OrderTotalDiscount' => $newDiscount,
                'InvoiceTotal' => $newTotalAmount,
                'OrderTotal' => $newTotalAmount
            ]);

        Siforderitem::where('ProductId', $orderInfo->ProductId) //update the item by refKeyid and matcode
            ->where(['ReferenceKeyId' => $orderInfo->KeyId])
            ->update([
                $fieldDiscount1 => $newItemDiscount,
                $fieldDiscount2 => $newItemDiscount,
                $fieldIsPercent1 => '0',
                $fieldIsPercent2 => '0',
                $fieldType1 => $oldDiscountType . $this->discountDescription,
                $fieldType2 => $oldDiscountType . $this->discountDescription,
                $fieldAmount1 => $newItemDiscount,
                $fieldAmount2 => $newItemDiscount,
                $fieldScheme1 => $fieldScheme1Value,
                $fieldScheme2 => $fieldScheme2Value
            ]);
    }

    /**
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {
        $headerArr = [];
        $headers = SifdiscountHeader::first();
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

        // $validateDiscount = new SifdiscountValidation();
        // $validateDiscount = new RuleValidation();
        // $validateOrder->headerRules = SifdiscountHeader::first();

        $validateDiscount = new RuleValidation();
        $validateDiscount->headerRules = SifdiscountValidation::first();

        $headerArr = self::getHeaderRow(); //get the header row of an every data

        foreach ($rows as $row) {
            $fieldErrors = false;

            /**
             * $key = header of a table
             * $value = the value of an header
             */
            foreach ($headerArr as $key => $value) :

                // loop one by one
                // and validate 
                $fieldErrors = $validateDiscount->validate($row[SifFunction::makeSlug($value)], $key);
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

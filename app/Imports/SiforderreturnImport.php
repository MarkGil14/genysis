<?php

namespace App\Imports;

use App\Siforderreturn;
use App\Siforderreturnitem;
use App\Sifitem;
use App\SifFunction;
use App\SiforderreturnHeader;
use App\RuleValidation;
use App\SiforderreturnValidation;
use App\FormatName;

use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Spreadsheet\Date;
use App\SiforderreturnLastupload;


class SiforderreturnImport implements ToCollection, WithHeadingRow
{
    private $returnDate;
    private $invoiceNumber;
    private $accountId;
    private $sasId;
    private $dspId;
    private $returnQty;
    private $typeOfReturn;
    private $creditMemoNumber;
    private $transactionId;
    private $productId;
    private $price;
    private $discountAmount;
    private $condition;
    private $returnType;
    private $reasonOfRejection;
    private $reasonOfReturn;



    public function collection(Collection $rows)
    {
        $orderArr = []; //this will be the storage of all order
        $itemArr = []; //storage of all orderitem

        $arrDiscount = [];

        //validate and get all the error of the imported CSV;/EXCEL
        $errors = $this->validateRows($rows);
        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {
            $errorResponse = [
                'entity' => FormatName::first()->sifreturn . '.xlsx',
                'errors' => json_encode($errors)
            ];

            exit(json_encode($errorResponse));
        }

        $headerRow = SiforderreturnHeader::first();
        foreach ($rows as $row) {

            //determine if the cm# is multiple or not
            // if(SifFunction::hasMultipleInvoice($row[SifFunction::makeSlug($headerRow->credit_memo_number)]))
            // {
            //     //if has multiple invoice
            //     //seperate all multiple invoice , convert it into array
            //     $invoiceArr = array();
            //     $invoiceArr = SifFunction::seperateMultipleInvoice($row[SifFunction::makeSlug($headerRow->credit_memo_number)]);

            //     /**
            //      * determine if the invoice number is exists
            //      */
            //     $key = $this->isCmExists($invoiceArr[0], $orderArr);            

            //     if($key !== false)
            //     {
            //         /**
            //          * get one invoice randomly in the multiple invoices
            //          */
            //         $invoiceResult = $this->getOneInvoiceInMultiple($invoiceArr);
            //         $key = $this->isCmExists($invoiceResult, $orderArr);                                  
            //         $this->orderGuid = $orderArr[$key]['Guid']; //init the order guid for item
            //         $this->orderKeyid = $orderArr[$key]['KeyId']; //init the order keyid for item
            //         $this->productId = $row[SifFunction::makeSlug($headerRow->material_code)];
            //         $this->returnQty = $row[SifFunction::makeSlug($headerRow->returned_quantity)];
            //         $this->price = $row[SifFunction::makeSlug($headerRow->price)];
            //         $this->condition = $row[SifFunction::makeSlug($headerRow->condition)];
            //         $this->returnType = $row[SifFunction::makeSlug($headerRow->return_type)];
            //         $this->reasonOfRejection = $row[SifFunction::makeSlug($headerRow->reason_of_rejection)];
            //         $this->discountAmount = $row[SifFunction::makeSlug($headerRow->discount_amount)];
            //         $this->invoiceNumber = $row[SifFunction::makeSlug($headerRow->invoice_number)];

            //         $itemArr[] = $this->createItemArray();

            //     }else {

            //             //initialized the data for order without guid , keyid and invoice                     

            //            $this->sasId =  $row[SifFunction::makeSlug($headerRow->sas_id)];
            //             $this->dspId =  $row[SifFunction::makeSlug($headerRow->dsp_id)];
            //             $this->accountId =  $row[SifFunction::makeSlug($headerRow->account_id)];
            //             $this->typeOfReturn =  $row[SifFunction::makeSlug($headerRow->type_of_return)];
            //             $this->creditMemoNumber =  $row[SifFunction::makeSlug($headerRow->credit_memo_number)];
            //             $this->returnDate =  SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->return_date)]));
            //             $this->transactionId =  $row[SifFunction::makeSlug($headerRow->transaction_id)];
            //             $this->reasonOfReturn =  $row[SifFunction::makeSlug($headerRow->reason_of_return)];
            //             $this->invoiceNumber = $row[SifFunction::makeSlug($headerRow->invoice_number)];

            //             /**
            //              * store all seperated invoice to the array with unique id, 
            //              * guid and unique invoice.
            //              * @param invoiceArr //multiple invoices
            //              *  */      
            //             $multipleArr = array();              
            //             $multipleArr = $this->storeMultipleInvoice($invoiceArr); //store the multiple invoices
            //             foreach($multipleArr as $value) { $orderArr[] = $value; }

            //             $key = $this->isCmExists($invoiceArr[0], $orderArr);                     
            //             $this->orderGuid = $orderArr[$key]['Guid']; //init the order guid for item
            //             $this->orderKeyid = $orderArr[$key]['KeyId']; //init the order keyid for item
            //             $this->productId = $row[SifFunction::makeSlug($headerRow->material_code)];
            //             $this->returnQty = $row[SifFunction::makeSlug($headerRow->returned_quantity)];
            //             $this->price = $row[SifFunction::makeSlug($headerRow->price)];
            //             $this->condition = $row[SifFunction::makeSlug($headerRow->condition)];
            //             $this->returnType = $row[SifFunction::makeSlug($headerRow->return_type)];
            //             $this->reasonOfRejection = $row[SifFunction::makeSlug($headerRow->reason_of_rejection)];
            //             $this->discountAmount = $row[SifFunction::makeSlug($headerRow->discount_amount)];

            //             $itemArr[] = $this->createItemArray();


            //     }

            // }else{

            if (strtolower($row[SifFunction::makeSlug($headerRow->type_of_return)]) != 'discount') {

                //if not has multiple invoice

                //find out if the invoice number is exist in the array
                $key = $this->isCmExists($row[SifFunction::makeSlug($headerRow->credit_memo_number)], $orderArr);
                if ($key !== false) {
                    /**
                     * if exists , get the Guid , KeyId of the order header and generate new Guid for the item
                     */
                    $orderGuid = $orderArr[$key]['Guid'];
                    $orderKeyid = $orderArr[$key]['KeyId'];

                    /**
                     * get the order total invoce and total order in the array, then
                     * get the sum of the current total invoice and the total price of then new items
                     * current_total + totalprice
                     *  update the invoice total and order total of the order
                     */

                    $this->orderGuid = $orderGuid;
                    $this->orderKeyid = $orderKeyid;
                    $this->productId = $row[SifFunction::makeSlug($headerRow->material_code)];
                    $this->returnQty = $row[SifFunction::makeSlug($headerRow->returned_quantity)];
                    $this->price = $row[SifFunction::makeSlug($headerRow->price)];
                    $this->condition = $row[SifFunction::makeSlug($headerRow->condition)];
                    $this->returnType = $row[SifFunction::makeSlug($headerRow->return_type)];
                    $this->reasonOfRejection = $row[SifFunction::makeSlug($headerRow->reason_of_rejection)];
                    $this->discountAmount = $row[SifFunction::makeSlug($headerRow->discount_amount)];
                    $itemArr[] = $this->createItemArray();
                } else {

                    /**
                     * if the invoice is not exists
                     * generate new Guid , KeyId and item Guid
                     * then store it in the array (order header  and item)
                     */

                    //initialized the data for order
                    $this->orderGuid = SifFunction::generateGuid();
                    $this->orderKeyid = SifFunction::generateGuid();
                    $this->invoiceNumber = $row[SifFunction::makeSlug($headerRow->invoice_number)];
                    $this->sasId =  $row[SifFunction::makeSlug($headerRow->sas_id)];
                    $this->dspId =  $row[SifFunction::makeSlug($headerRow->dsp_id)];
                    $this->accountId =  $row[SifFunction::makeSlug($headerRow->account_id)];
                    $this->typeOfReturn =  $row[SifFunction::makeSlug($headerRow->type_of_return)];
                    $this->creditMemoNumber =  $row[SifFunction::makeSlug($headerRow->credit_memo_number)];
                    $this->returnDate =  SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->return_date)]));
                    $this->reasonOfReturn =  $row[SifFunction::makeSlug($headerRow->reason_of_return)];
                    $this->transactionId =  $row[SifFunction::makeSlug($headerRow->transaction_id)];


                    //init for item                   
                    $this->productId = $row[SifFunction::makeSlug($headerRow->material_code)];
                    $this->returnQty = $row[SifFunction::makeSlug($headerRow->returned_quantity)];
                    $this->price = $row[SifFunction::makeSlug($headerRow->price)];
                    $this->condition = $row[SifFunction::makeSlug($headerRow->condition)];
                    $this->returnType = $row[SifFunction::makeSlug($headerRow->return_type)];
                    $this->reasonOfRejection = $row[SifFunction::makeSlug($headerRow->reason_of_rejection)];
                    $this->discountAmount = $row[SifFunction::makeSlug($headerRow->discount_amount)];


                    $hasCount = Siforderreturn::where('CreditMemoNumber', $this->creditMemoNumber)->count();
                    if ($hasCount == 0) {
                        $orderArr[] = $this->createOrderArray();
                        $itemArr[] = $this->createItemArray();
                    }
                }
            } else {

                $arrDiscount[] = [
                    'discount_amount' => $row[SifFunction::makeSlug($headerRow->discount_amount)],
                    'credit_memo_number' => $row[SifFunction::makeSlug($headerRow->credit_memo_number)],
                    'material_code' => $row[SifFunction::makeSlug($headerRow->material_code)]
                ];
            }


            // }

        }

        //process return discount
        // if (!empty($arrDiscount)) {
        //     foreach ($arrDiscount as $discount) {
        //         $key = false;
        //         //determine if the cm# is exists or not
        //         $key = $this->isCmExists($discount['credit_memo_number'], $orderArr);
        //         if ($key !== false)
        //             $orderArr[$key] = $this->addDiscountItem($orderArr[$key], $itemArr, $discount['material_code'], $discount['discount_amount']); // add the discount in item
        //     }
        // }


        /**
         * chunk by 2000 per query
         */
        foreach (array_chunk($orderArr, 1500) as $order) {
            Siforderreturn::insert($order);
        }

        foreach (array_chunk($itemArr, 1500) as $item) {
            Siforderreturnitem::insert($item);
        }

        $this->saveUploadDetail($orderArr, $itemArr);
    }


    public function saveUploadDetail($orderArr, $itemArr)
    {
        $orders_count = count($orderArr);
        $line_items_count = count($itemArr);
        $total_qty = $this->getTotal($itemArr, 'ReturnedQty');
        $total_sales = $this->getTotalSales($itemArr);
        $totalDiscount = $this->getTotal($itemArr, 'DiscountAmount');
        $uploadDetails = new SiforderreturnLastupload();
        $uploadDetails->returns_count = $orders_count;
        $uploadDetails->line_items_count = $line_items_count;
        $uploadDetails->total_qty = $total_qty;
        $uploadDetails->total_sales = $total_sales;
        $uploadDetails->total_discount = $totalDiscount;
        $uploadDetails->save();
        return [
            'Orders Count' => $orders_count,
            'Line Items Count' => $line_items_count,
            'Total Qty' => $total_qty,
            'Total Sales' => $total_sales,
            'Total Discount' => $totalDiscount,
        ];
    }

    public function getTotal($arrs, $index)
    {
        $total = 0;
        foreach ($arrs as $arr)
            $total = $total + $arr[$index];

        return $total;
    }

    public function getTotalSales($items)
    {
        (float)$totalSales = 0;
        foreach ($items as $item)
            $totalSales += $item['ReturnedQty'] * $item['Price'];

        return $totalSales;
    }




    public function isCmExists($keyword, $arrFile)
    {
        $output = false;
        foreach ($arrFile as $key => $value) {
            if ($keyword === $value['CreditMemoNumber'])
                $output = $key;
        }
        return $output;
    }



    public function addDiscountItem($returnArr, $itemArr, $matcode, $discountAmount)
    {
        $itemKey = false;
        foreach ($itemArr as $item) {
            if ($item['ReferenceKeyId'] === $returnArr['KeyId'] && $item['ProductId'] === $matcode)
                $itemArr['DiscountAmount'] += $discountAmount;
        }
        return $itemArr;
    }


    /**
     * this method use to create an Order Array
     */
    public function createOrderArray()
    {
        $orderArr = array();
        $orderArr = [
            'Guid' => $this->orderGuid,
            'SASId' => $this->sasId,
            'DSPId' => $this->dspId,
            'AccountId' => $this->accountId,
            'TypeOfReturn' => $this->typeOfReturn,
            'CreditMemoNumber' => $this->creditMemoNumber,
            'ReturnDate' =>  $this->returnDate,
            'InvoiceNumber' => $this->invoiceNumber,
            'Status' => 'OUT',
            'TransactionId' => $this->transactionId,
            'ReasonOfReturn' => $this->reasonOfReturn,
            'EncodedDate' => date('Y-m-d'),
            'KeyId' => $this->orderKeyid
        ];

        return $orderArr;
    }

    /**
     * this method use to create an OrderReturnItem Array
     */
    public function createItemArray()
    {

        $itemArr = array();
        $itemArr = [
            'Guid' =>  SifFunction::generateGuid(),
            'OrderReturnGuid' => $this->orderGuid,
            'ProductId' => $this->productId,
            'ReturnedQty' => abs($this->returnQty),
            'Price' => abs($this->price),
            'DiscountAmount' => abs($this->discountAmount),
            'Condition' => $this->condition,
            'ConversionId' => $this->getMaterialConversion($this->productId),
            'TransactionId' => date('md') . SifFunction::generateRandomID(100000, 999999),
            'ReturnType' => $this->returnType,
            'ReasonOfRejection' => $this->reasonOfRejection,
            'Status' => 'OUT',
            'ReferenceKeyId' => $this->orderKeyid
        ];
        return $itemArr;
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

    public function isDspExists($dspid)
    {
        return SifDsp::find($dspid)->exists() ? true : false;
    }

    /**
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {
        $headers = SiforderreturnHeader::first();
        return  json_decode($headers);
    }



    /**
     * @param invoiecArr / Multiple Invoice array()
     * this method use to create and store all the Order in invoice
     * same SO but difference Invoices
     * @return @orderArr
     */
    public function storeMultipleInvoice($invoiceArr)
    {
        $orderArr = array();

        $start = $invoiceArr[0][strlen($invoiceArr[0]) - 2] . $invoiceArr[0][strlen($invoiceArr[0]) - 1]; //the ten'th value of the invoice number

        /**
         * seperate the letter and number
         */
        $value = SifFunction::seperateStringAndNumber($invoiceArr[0]);
        $prefix = $value[0]['letter']; //this will be the prefix of the invoice number
        $invoiceNumber = $value[0]['number']; //the number of invoice number

        /**
         * loop it .
         * @param start // the tenth of the invoice number 
         * @param invoiceArr[1] //the last invoice number in the multiple invoices
         */
        while ($start <= $invoiceArr[1]) {
            if (SifFunction::isNumber($invoiceArr[0])) { //validate if the invoice number is numeric of not

                /**
                 * if the invoice number is all numeric
                 * this process will be function
                 *
                 * =========================================
                 * generate new Guid , KeyId and item Guid
                 * then store it in the array (order header and item)
                 * unique Invoice number , Guid and KeyId are needed
                 */

                $this->orderGuid = SifFunction::generateGuid();
                $this->orderKeyid = SifFunction::generateGuid();
                $this->invoiceNumber = $invoiceArr[0];
                $orderArr[] = $this->createOrderArray();

                $invoiceArr[0] = $invoiceArr[0] + 1; //increment the invoice number 

                $invoiceNumber = $invoiceArr[0]; //copy the current invoice number

            } else {

                /**
                 * if the invoice number is not a numeric value but have a prefix
                 * this process will be function
                 *
                 * =========================================
                 * generate new Guid , KeyId and item Guid
                 * then store it in the array (order header and item)
                 * same SO but unique Invoice number , Guid and KeyId are needed
                 */

                $this->orderGuid = SifFunction::generateGuid();
                $this->orderKeyid = SifFunction::generateGuid();
                $this->invoiceNumber = $prefix . $invoiceNumber; //this will be the invoice number ( prefix + number )


                $orderArr[] = $this->createOrderArray();

                $invoiceNumber = $invoiceNumber + 1; //increment the invoicenumber

            }
            $start++;
        }

        return $orderArr;
    }



    /**
     * @param invoiceArr // multiple invoices array()
     * this method use to get the invoice number randomly
     * @return selectedInvoice
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

        $randomIndex = mt_rand(0, count($arrInvoice) - 1);
        $selectedInvoice = $arrInvoice[$randomIndex];
        return $selectedInvoice;
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

        $validateOrder = new RuleValidation();
        $validateOrder->headerRules = SiforderreturnValidation::first();


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
                if ($key == 'return_date') :
                    $fieldErrors = $validateOrder->validate(SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($value)])), $key);
                elseif ($key == 'material_code') :
                    $matcode = $this->getMaterialConversion($row[SifFunction::makeSlug($value)]);
                    if (!$matcode) {
                        $arrError[] = [
                            'row' => $rowCount,
                            'field' => $value,
                            'errors' => ' This "' . $row[SifFunction::makeSlug($value)] . '" is not Exists '
                        ];
                    }
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($value)], $key);
                else :

                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($value)], $key);
                endif;

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


    /**
     * @param value , $format = 'Y-m-d'
     * use to transform the date in the CSV/EXCEL FILE
     * @return result
     */
    public function transformDate($value, $format = 'Y-m-d')
    {
        try {
            return \Carbon\Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value));
        } catch (\ErrorException $e) {
            //validate if the value is date
            //if not , the date will return to the default 1970-01-01             
            $date = SifFunction::isDate($value);
            return \Carbon\Carbon::createFromFormat($format, $date);
        }
    }
}

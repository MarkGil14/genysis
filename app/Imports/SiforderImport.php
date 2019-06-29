<?php

namespace App\Imports;

use App\Siforder;
use App\Siforderitem;
use App\Sifitem;
use App\SifFunction;
use App\SiforderHeader;
use App\SifdiscountHeader;
use App\RuleValidation;
use App\SiforderValidation;
use App\FormatName;
use App\SiforderLastupload;
use App\Imports\SifdiscountImport;
use App\SifdiscountValidation;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Spreadsheet\Date;

class SiforderImport implements ToCollection, WithHeadingRow
{

    /**
     * upload details
     */
    private $countLineItems;
    private $countHeaders;
    private $totalQty;
    private $totalSales;
    private $totalDiscount;


    private $discountDescription;
    private $discountAmount;


    /**
     * order details
     */
    private $orderGuid;
    private $orderKeyid;
    private $salesType;
    private $accountId;
    private $sasId;
    private $dspId;
    private $requestedDate;
    private $orderDate;
    private $invoiceDate;
    private $totalAmount;
    private $paymentTerm;
    private $invoiceNumber;
    private $salesOrderNumber;
    private $transactionId;
    private $reasonOfUnequalInvoice;

    private $orderTotalAmount;
    private $invoiceTotalAmount;

    //order field
    private $materialCode;
    private $itemGuid;
    private $conversionId;
    private $orderQuantity;
    private $orderPrice;

    private $invoiceQuantity;
    private $invoicePrice;

    private $itemTransactionId;
    private $itemKeyid;
    private $actualWeightQuantity;



    public function collection(Collection $rows)
    {
        $orderArr = []; //this will be the array of orders
        $itemArr = []; //storage of all orderitem
        $diffDSPerrors = []; //error regarding in same invoice with diff dsp issue
        $discountArr = []; //this will be the array of a discount

        //validate and get all the error of the imported CSV;/EXCEL
        $errors = $this->validateRows($rows);

        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {
            $errorResponse = [
                'entity' => FormatName::first()->siforder . '.xlsx',
                'errors' => json_encode($errors)
            ];

            exit(json_encode($errorResponse));
        }

        $headerRow = SiforderHeader::first();
        $countRow = 1;
        foreach ($rows as $row) {

            $countRow++;
            if (strtolower($row[SifFunction::makeSlug($headerRow->sales_type)]) != 'discount') {
                //determine if the invoice is multiple or not
                if (SifFunction::hasMultipleInvoice($row[SifFunction::makeSlug($headerRow->invoice_number)])) {
                    //if has multiple invoice
                    //seperate all multiple invoice , convert it into array
                    $invoiceArr = array();
                    $invoiceArr = SifFunction::seperateMultipleInvoice($row[SifFunction::makeSlug($headerRow->invoice_number)]);

                    /**
                     * determine if the invoice number is exists
                     */
                    $key = SifFunction::isExists($invoiceArr[0], $orderArr);

                    if ($key !== false) {
                        /**
                         * get one invoice randomly in the multiple invoices
                         */
                        $invoiceResult = $this->getOneInvoice($invoiceArr);
                        $key = SifFunction::isExists($invoiceResult, $orderArr);
                        /**
                         * get the order total invoce and total order in the array, then
                         * get the sum of the current total invoice and the total price of then new items
                         * current_total + totalprice
                         *  update the invoice total and order total of the order
                         */
                        //compute the new invoice total
                        (float) $invoicetotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->invoice_quantity)], $row[SifFunction::makeSlug($headerRow->invoice_price)]); //compute the amount of quantity and price
                        (float) $currentInvoiceTotal = $orderArr[$key]['InvoiceTotal'];
                        (float) $newInvoiceTotal = $currentInvoiceTotal + $invoicetotalPrice;
                        $orderArr[$key]['InvoiceTotal'] = $newInvoiceTotal;
                        $this->invoiceQuantity = $row[SifFunction::makeSlug($headerRow->invoice_quantity)];
                        $this->invoicePrice = $row[SifFunction::makeSlug($headerRow->invoice_price)];

                        $this->orderGuid = $orderArr[$key]['Guid']; //init the order guid for item
                        $this->orderKeyid = $orderArr[$key]['KeyId']; //init the order keyid for item
                        $this->materialCode = $row[SifFunction::makeSlug($headerRow->material_code)];
                        $this->actualWeightQuantity = $row[SifFunction::makeSlug($headerRow->actual_weight_quantity)];

                        //validate if the sales type is Direct Invoice
                        //the order quantity and order price become 0
                        // if (($row[SifFunction::makeSlug($headerRow->sales_type)]) == 'Direct Invoice') {
                        //     $this->orderQuantity = 0;
                        //     $this->orderPrice = 0;
                        // } else {
                        (float) $ordertotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->order_quantity)], $row[SifFunction::makeSlug($headerRow->order_price)]); //compute the amount of quantity and price
                        //compute the new order total
                        (float) $currentOrderTotal = $orderArr[$key]['OrderTotal'];
                        (float) $newOrderTotal = $currentOrderTotal + $ordertotalPrice;
                        $orderArr[$key]['OrderTotal'] = $newOrderTotal;
                        $this->orderQuantity = $row[SifFunction::makeSlug($headerRow->order_quantity)];
                        $this->orderPrice = $row[SifFunction::makeSlug($headerRow->order_price)];
                        // }

                        $this->reasonOfUnequalInvoice = $row[SifFunction::makeSlug($headerRow->reason_unequal_order_invoice)];
                        // $itemArr[] = $this->createItemArray();

                        // $key = SifFunction::isExists($row[SifFunction::makeSlug($headerRow->invoice_number)], $orderArr);
                        // if($key !== false)
                        // {
                        if ($orderArr[$key]['DSPId'] == $row[SifFunction::makeSlug($headerRow->dsp_id)]) {
                            $itemArr[] = $this->createItemArray();
                        } else {
                            $errors[] = [
                                'row' => $countRow,
                                'field' => $headerRow->dsp_id,
                                'errors' => 'Same Invoice with Different DSP Found! Please check this Invoice  ' . $row[SifFunction::makeSlug($headerRow->invoice_number)]
                            ];

                            //     $errorResponse = [
                            //         'entity' => FormatName::first()->siforder.'.xlsx',
                            //         'errors' => json_encode($errors)
                            //     ];                
                            //     exit(json_encode($errorResponse));                                                                        
                        }

                        // }



                    } else {


                        $salesOrderNumber = null;
                        if ($row[SifFunction::makeSlug($headerRow->sales_type)] == 'Pre-booked' && is_null($row[SifFunction::makeSlug($headerRow->sales_order_number)])) :
                            $salesOrderNumber = SifFunction::generateSONumber(10000, 99999);
                        elseif ($row[SifFunction::makeSlug($headerRow->sales_type)] == 'Pre-booked' && !is_null($row[SifFunction::makeSlug($headerRow->sales_order_number)])) :
                            $salesOrderNumber = $row[SifFunction::makeSlug($headerRow->sales_order_number)];
                        endif;

                        //initialized the data for order without guid , keyid and invoice
                        $this->salesType = $row[SifFunction::makeSlug($headerRow->sales_type)];
                        $this->accountId = $row[SifFunction::makeSlug($headerRow->account_id)];
                        $this->sasId = $row[SifFunction::makeSlug($headerRow->sas_id)];
                        $this->dspId = $row[SifFunction::makeSlug($headerRow->dsp_id)];
                        $this->requestedDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->delivery_date)]));
                        $this->orderDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->order_date)]));
                        $this->invoiceDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->invoice_date)]));
                        $this->totalPrice = 0;
                        $this->paymentTerm = $row[SifFunction::makeSlug($headerRow->payment_term)];
                        $this->salesOrderNumber = $salesOrderNumber;
                        $this->transactionId = $row[SifFunction::makeSlug($headerRow->transaction_id)];
                        /**
                         * store all seperated invoice to the array with unique id, 
                         * guid and same salesorder number but unique invoice.
                         * @param invoiceArr //multiple invoices
                         *  */
                        $multipleArr = array();
                        $multipleArr = $this->storeInvoices($invoiceArr); //store the multiple invoices
                        if (count($multipleArr) != 0) {

                            foreach ($multipleArr as $value) {
                                $orderArr[] = $value;
                            }

                            $key = SifFunction::isExists($invoiceArr[0], $orderArr);
                            (float) $invoicetotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->invoice_quantity)], $row[SifFunction::makeSlug($headerRow->invoice_price)]); //compute the amount of quantity and price

                            /**
                             * get the order total invoice and total order in the array, then
                             * get the sum of the current total invoice and the total price of then new items
                             * current_total + totalprice
                             *  update the invoice total and order total of the order
                             */

                            //compute the new invoice total
                            (float) $currentInvoiceTotal = $orderArr[$key]['InvoiceTotal'];
                            (float) $newInvoiceTotal = $currentInvoiceTotal + $invoicetotalPrice;
                            $orderArr[$key]['InvoiceTotal'] = $newInvoiceTotal;


                            $this->orderGuid = $orderArr[$key]['Guid'];
                            $this->orderKeyid = $orderArr[$key]['KeyId'];
                            $this->materialCode = $row[SifFunction::makeSlug($headerRow->material_code)];


                            //validate if the sales type is Direct Invoice
                            //the order quantity and order price become 0
                            // if (($row[SifFunction::makeSlug($headerRow->sales_type)]) == 'Direct Invoice') {
                            //     $this->orderQuantity = 0;
                            //     $this->orderPrice = 0;
                            // } else {

                            (float) $ordertotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->order_quantity)], $row[SifFunction::makeSlug($headerRow->order_price)]); //compute the amount of quantity and price
                            //compute the new order total
                            (float) $currentOrderTotal = $orderArr[$key]['OrderTotal'];
                            (float) $newOrderTotal = $currentOrderTotal + $ordertotalPrice;
                            $orderArr[$key]['OrderTotal'] = $newOrderTotal;
                            $this->orderQuantity = $row[SifFunction::makeSlug($headerRow->order_quantity)];
                            $this->orderPrice = $row[SifFunction::makeSlug($headerRow->order_price)];
                            // }


                            $this->invoicePrice = $row[SifFunction::makeSlug($headerRow->invoice_price)];
                            $this->invoiceQuantity = $row[SifFunction::makeSlug($headerRow->invoice_quantity)];
                            $this->actualWeightQuantity = $row[SifFunction::makeSlug($headerRow->actual_weight_quantity)];
                            $this->reasonOfUnequalInvoice = $row[SifFunction::makeSlug($headerRow->reason_unequal_order_invoice)];

                            // $key = SifFunction::isExists($row[SifFunction::makeSlug($headerRow->invoice_number)], $orderArr);
                            // if($key !== false)
                            // {
                            // if($orderArr[$key]['DSPId'] == $row[SifFunction::makeSlug($headerRow->dsp_id)]){
                            $itemArr[] = $this->createItemArray();
                            // }else{
                            //     $errors[] = [
                            //         'row' => $countRow,
                            //         'field' => $headerRow->dsp_id,
                            //         'errors' => 'Same Invoice with Different DSP Found! Please check your Data '.$row[SifFunction::makeSlug($headerRow->dsp_id)]
                            //     ];

                            //         $errorResponse = [
                            //             'entity' => FormatName::first()->siforder.'.xlsx',
                            //             'errors' => json_encode($errors)
                            //         ];                
                            //         exit(json_encode($errorResponse));                                                                        
                            //     }

                            // }


                        }
                    }
                } else {
                    //if not has multiple invoice

                    //find out if the invoice number is exist in the array
                    $key = SifFunction::isExists($row[SifFunction::makeSlug($headerRow->invoice_number)], $orderArr);
                    if ($key !== false) {
                        /**
                         * if exists , get the Guid , KeyId of the order header and generate new Guid for the item
                         */
                        $orderGuid = $orderArr[$key]['Guid'];
                        $orderKeyid = $orderArr[$key]['KeyId'];
                        (float) $invoicetotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->invoice_quantity)], $row[SifFunction::makeSlug($headerRow->invoice_price)]); //compute the amount of quantity and price

                        /**
                         * get the order total invoce and total order in the array, then
                         * get the sum of the current total invoice and the total price of then new items
                         * current_total + totalprice
                         *  update the invoice total and order total of the order
                         */

                        //compute the new invoice total
                        (float) $currentInvoiceTotal = $orderArr[$key]['InvoiceTotal'];
                        (float) $newInvoiceTotal = $currentInvoiceTotal + $invoicetotalPrice;
                        $orderArr[$key]['InvoiceTotal'] = $newInvoiceTotal;


                        $this->orderGuid = $orderGuid;
                        $this->materialCode = $row[SifFunction::makeSlug($headerRow->material_code)];
                        $this->actualWeightQuantity = $row[SifFunction::makeSlug($headerRow->actual_weight_quantity)];
                        $this->invoiceQuantity = $row[SifFunction::makeSlug($headerRow->invoice_quantity)];
                        $this->invoicePrice = $row[SifFunction::makeSlug($headerRow->invoice_price)];

                        //validate if the sales type is Direct Invoice
                        //the order quantity and order price become 0
                        // if (($row[SifFunction::makeSlug($headerRow->sales_type)]) == 'Direct Invoice') {
                        //     $this->orderQuantity = 0;
                        //     $this->orderPrice = 0;
                        // } else {

                        (float) $ordertotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->order_quantity)], $row[SifFunction::makeSlug($headerRow->order_price)]); //compute the amount of quantity and price
                        //compute the new order total
                        (float) $currentOrderTotal = $orderArr[$key]['OrderTotal'];
                        (float) $newOrderTotal = $currentOrderTotal + $ordertotalPrice;
                        $orderArr[$key]['OrderTotal'] = $newOrderTotal;
                        $this->orderQuantity = $row[SifFunction::makeSlug($headerRow->order_quantity)];
                        $this->orderPrice = $row[SifFunction::makeSlug($headerRow->order_price)];
                        // }


                        $this->orderKeyid = $orderKeyid;
                        $this->reasonOfUnequalInvoice = $row[SifFunction::makeSlug($headerRow->reason_unequal_order_invoice)];


                        $key = SifFunction::isExists($row[SifFunction::makeSlug($headerRow->invoice_number)], $orderArr);
                        if ($key !== false) {
                            $itemArr[] = $this->createItemArray();

                            if ($orderArr[$key]['DSPId'] != $row[SifFunction::makeSlug($headerRow->dsp_id)]) {
                                $diffDSPerrors[] = [
                                    'row' => $countRow,
                                    'field' => $headerRow->dsp_id,
                                    'errors' => 'Same Invoice with Different DSP Found! Please check this Invoice  ' . $row[SifFunction::makeSlug($headerRow->invoice_number)]
                                ];
                            }
                        }
                    } else {

                        /**
                         * if the invoice is not exists
                         * generate new Guid , KeyId and item Guid
                         * then store it in the array (order header  and item)
                         */
                        $salesOrderNumber = null;

                        if ($row[SifFunction::makeSlug($headerRow->sales_type)] == 'Pre-booked' && is_null($row[SifFunction::makeSlug($headerRow->sales_order_number)])) :
                            $salesOrderNumber = SifFunction::generateSONumber(10000, 99999);
                        elseif ($row[SifFunction::makeSlug($headerRow->sales_type)] == 'Pre-booked' && !is_null($row[SifFunction::makeSlug($headerRow->sales_order_number)])) :
                            $salesOrderNumber = $row[SifFunction::makeSlug($headerRow->sales_order_number)];
                        endif;

                        (float) $invoicetotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->invoice_quantity)], $row[SifFunction::makeSlug($headerRow->invoice_price)]);
                        //initialized the data for order
                        $this->orderGuid = SifFunction::generateGuid();
                        $this->orderKeyid = SifFunction::generateGuid();
                        $this->salesType = $row[SifFunction::makeSlug($headerRow->sales_type)];
                        $this->accountId = $row[SifFunction::makeSlug($headerRow->account_id)];
                        $this->sasId = $row[SifFunction::makeSlug($headerRow->sas_id)];
                        $this->dspId = $row[SifFunction::makeSlug($headerRow->dsp_id)];
                        $this->requestedDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->delivery_date)]));
                        $this->orderDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->order_date)]));
                        $this->invoiceDate = SifFunction::formatDate($this->transformDate($row[SifFunction::makeSlug($headerRow->invoice_date)]));
                        $this->invoiceTotalAmount = $invoicetotalPrice;

                        $this->paymentTerm = $row[SifFunction::makeSlug($headerRow->payment_term)];
                        $this->salesOrderNumber = $salesOrderNumber;
                        $this->invoiceNumber = $row[SifFunction::makeSlug($headerRow->invoice_number)];
                        $this->transactionId = $row[SifFunction::makeSlug($headerRow->transaction_id)];

                        //init for item
                        $this->materialCode = $row[SifFunction::makeSlug($headerRow->material_code)];
                        $this->actualWeightQuantity = $row[SifFunction::makeSlug($headerRow->actual_weight_quantity)];

                        $this->invoiceQuantity = $row[SifFunction::makeSlug($headerRow->invoice_quantity)];
                        $this->invoicePrice = $row[SifFunction::makeSlug($headerRow->invoice_price)];

                        $this->reasonOfUnequalInvoice = $row[SifFunction::makeSlug($headerRow->reason_unequal_order_invoice)];

                        //validate if the sales type is Direct Invoice
                        //the order quantity and order price become 0
                        // if (($row[SifFunction::makeSlug($headerRow->sales_type)]) == 'Direct Invoice') {
                        //     $this->orderQuantity = 0;
                        //     $this->orderPrice = 0;
                        // } else {
                        (float) $ordertotalPrice = SifFunction::calculateTotalPrice($row[SifFunction::makeSlug($headerRow->order_quantity)], $row[SifFunction::makeSlug($headerRow->order_price)]); //compute the amount of quantity and price
                        //compute the new order total                            
                        $this->orderTotalAmount = $ordertotalPrice;
                        $this->orderQuantity = $row[SifFunction::makeSlug($headerRow->order_quantity)];
                        $this->orderPrice = $row[SifFunction::makeSlug($headerRow->order_price)];
                        // }


                        /**
                         * determine if the invoice is exists
                         * if the invoice is not exists
                         * then create new order and sku
                         */
                        $hasCount = Siforder::where('InvoiceNumber', $row[SifFunction::makeSlug($headerRow->invoice_number)])->count();
                        if ($hasCount == 0) {
                            $orderArr[] = $this->createOrderArray();
                            $itemArr[] = $this->createItemArray();
                        }
                    }
                }
            } else {
                $discountArr[] = [
                    'material_code' => $row[SifFunction::makeSlug($headerRow->material_code)],
                    'total_amount' => $row[SifFunction::makeSlug($headerRow->total_amount)],
                    'material_description' => $row[SifFunction::makeSlug($headerRow->material_description)],
                    'invoice_number' => $row[SifFunction::makeSlug($headerRow->invoice_number)],
                ];
            }
        }


        /**
         * check if there's any errors about the same Invoice 
         * with Difference DSP
         * if there's an error . then exit
         */
        if (count($diffDSPerrors) > 0) {
            $errorResponse = [
                'entity' => FormatName::first()->siforder . '.xlsx',
                'errors' => json_encode($diffDSPerrors)
            ];
            exit(json_encode($errorResponse));
        }


        /**
         * process the discount of every product of orders
         */
        if (count($discountArr) > 0) {
            /**
             * @param discountArr //this is all the discount coming from the uploaded excel
             * this method store the discount in the specific orders or product
             */
            $newArr = $this->processDiscount($discountArr, $orderArr, $itemArr);
            $orderArr = $newArr['orderArr'];
            $itemArr = $newArr['itemArr'];
        }

        /**
         * chunk by 2000 per query
         * todo
         */
        foreach (array_chunk($orderArr, 1000) as $order) {
            Siforder::insert($order);
        }

        foreach (array_chunk($itemArr, 1000) as $item) {
            Siforderitem::insert($item);
        }

        /**
         * save the uploaded summary detail
         */
        $this->saveUploadDetail($orderArr, $itemArr);
    }


    /**
     * method to process the discount of an order
     */
    public function processDiscount($rows, $orderArr, $itemArr)
    {
        foreach ($rows as $row) {
            //determine if the invoice discountn is multiplel or not
            if (SifFunction::hasMultipleInvoice($row['invoice_number'])) {
                //if has multiple invoice
                //seperate all multiple invoice , convert it into array
                $invoice = array();
                $invoice = SifFunction::seperateMultipleInvoice($row['invoice_number']);
            } else {
                $invoice = $row['invoice_number'];
            }

            $result = false;
            //get the invoice if the discount is for header otherwise
            //get the item detail if the discount is for item// else return false if the discount is undefined
            $result = $this->getInvoiceKeyAndItemDetail($invoice, $orderArr, $itemArr, $row['material_code']);
            /**
             * if the itemKey was false, it means the discount will go to the header discount
             * else , the discount was inserted in the specific item
             */
            if ($result != false) {
                if ($result['itemKey'] == false) {
                    //save the discount in header
                    $orderArr[$result['orderKey']] = $this->addDiscountHeader($orderArr[$result['orderKey']], abs($row['total_amount']), $row['material_description']);
                } else {
                    //save the discount in specific matcode in order/invoice
                    $discountResult = $this->addDiscountItem($orderArr[$result['orderKey']], $itemArr[$result['itemKey']], abs($row['total_amount']), $row['material_description']);
                    $orderArr[$result['orderKey']] = $discountResult['order']; //update a new order
                    $itemArr[$result['itemKey']] = $discountResult['item']; //update a specific item
                }
            }
        }
        return [
            'orderArr' => $orderArr,
            'itemArr' => $itemArr
        ];
    }

    /**
     * this method use to get the invoicekey or itemdetail 
     * to determine wether the discount is a header or an item discount else undefined
     */
    public function getInvoiceKeyAndItemDetail($invoice, $orderArr, $itemArr, $matCode)
    {
        $orderArrKey = array();
        if (is_array($invoice)) {
            for ($i = 0; $i < count($invoice); $i++) {
                //find the invoice if exists
                $key = false;
                $key = SifFunction::isExists($invoice[$i], $orderArr);
                if ($key != false) {
                    array_push($orderArrKey, $key);
                    $item = false;
                    //get the item detail using matcode and keyid
                    $item = $this->getItemDetailByMatcodeAndKeyid($orderArr[$key]['KeyId'], $itemArr, $matCode);
                    if ($item != false) //if the item was found using keyid and matcode , then return
                    {
                        return [
                            'orderKey' => $key,
                            'itemKey' => $item
                        ];
                    }
                }
            }

            //determine if the orderArrFound has a value or not
            //if has a value then return random invoiceNumber 
            //else return false
            if (!empty($orderArrKey)) {
                $randomIndex = mt_rand(0, count($orderArrKey) - 1); // get element in the random index in the array
                return [
                    'orderKey' => $orderArrKey[$randomIndex],
                    'itemKey' => false
                ];
            } else
                return false;
        } else {

            $key = false;
            $item = false;
            $key = SifFunction::isExists($invoice, $orderArr);
            if ($key != false) {
                //get the item detail using matcode and keyid
                $item = $this->getItemDetailByMatcodeAndKeyid($orderArr[$key]['KeyId'], $itemArr, $matCode);
                if ($item != false) //if the item was found using keyid and matcode , then return
                {
                    return [
                        'orderKey' => $key,
                        'itemKey' => $item
                    ];
                } else {
                    return [
                        'orderKey' => $key,
                        'itemKey' => false
                    ];
                }
            } else
                return false;
        }
    }



    /**
     * this method use to place the discount in the field
     */
    public function addDiscountHeader($orderArr, $discountAmount, $discountDescription)
    {
        if ($orderArr['InvoiceDiscountFromTotal1'] == 0) {
            //for invoice            
            $orderArr['InvoiceDiscountFromTotal1'] = $discountAmount;
            $orderArr['InvoiceDiscountFromTotal1Type'] = $discountDescription;
            $orderArr['InvoiceDiscountFromTotal1Amount'] = $discountAmount;
            $orderArr['InvoiceTotalDiscount'] += $discountAmount;
            $orderArr['InvoiceTotal'] -= $discountAmount;
            //for order
            $orderArr['OrderDiscountFromTotal1'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal1Type'] = $discountDescription;
            $orderArr['OrderDiscountFromTotal1Amount'] = $discountAmount;
            $orderArr['OrderTotalDiscount'] += $discountAmount;
            $orderArr['OrderTotal'] -= $discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal2'] == 0) {
            //for invoice            
            $orderArr['InvoiceDiscountFromTotal2'] = $discountAmount;
            $orderArr['InvoiceDiscountFromTotal2Type'] = $discountDescription;
            $orderArr['InvoiceDiscountFromTotal2Amount'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal2Scheme'] = 'Gross';
            $orderArr['InvoiceTotalDiscount'] += $discountAmount;
            $orderArr['InvoiceTotal'] -= $discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal2'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal2Type'] = $discountDescription;
            $orderArr['OrderDiscountFromTotal2Amount'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal2Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $discountAmount;
            $orderArr['OrderTotal'] -= $discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal3'] == 0) {

            //for invoice            
            $orderArr['InvoiceDiscountFromTotal3'] = $discountAmount;
            $orderArr['InvoiceDiscountFromTotal3Type'] = $discountDescription;
            $orderArr['InvoiceDiscountFromTotal3Amount'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal3Scheme'] = 'Gross';
            $orderArr['InvoiceTotalDiscount'] += $discountAmount;
            $orderArr['InvoiceTotal'] -= $discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal3'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal3Type'] = $discountDescription;
            $orderArr['OrderDiscountFromTotal3Amount'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal3Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $discountAmount;
            $orderArr['OrderTotal'] -= $discountAmount;
        } elseif ($orderArr['InvoiceDiscountFromTotal4'] == 0) {


            //for invoice            
            $orderArr['InvoiceDiscountFromTotal4'] = $discountAmount;
            $orderArr['InvoiceDiscountFromTotal4Type'] = $discountDescription;
            $orderArr['InvoiceDiscountFromTotal4Amount'] = $discountAmount;
            $orderArr['InvoiceTotalDiscount'] += $discountAmount;
            $orderArr['OrderDiscountFromTotal4Scheme'] = 'Gross';
            $orderArr['InvoiceTotal'] -= $discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal4'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal4Type'] = $discountDescription;
            $orderArr['OrderDiscountFromTotal4Amount'] = $discountAmount;
            $orderArr['OrderDiscountFromTotal4Scheme'] = 'Gross';
            $orderArr['OrderTotalDiscount'] += $discountAmount;
            $orderArr['OrderTotal'] -= $discountAmount;
        } else {
            $orderArr['InvoiceDiscountFromTotal4'] += $discountAmount;
            $orderArr['InvoiceDiscountFromTotal4Type'] = $orderArr['InvoiceDiscountFromTotal4Type'] . ', ' . $discountDescription;
            $orderArr['InvoiceDiscountFromTotal4Amount'] += $discountAmount;
            $orderArr['InvoiceTotalDiscount'] += $discountAmount;
            $orderArr['InvoiceTotal'] -= $discountAmount;

            //for order
            $orderArr['OrderDiscountFromTotal4'] += $discountAmount;
            $orderArr['OrderDiscountFromTotal4Type'] = $orderArr['InvoiceDiscountFromTotal4Type'] . ', ' . $discountDescription;
            $orderArr['OrderDiscountFromTotal4Amount'] += $discountAmount;
            $orderArr['OrderTotalDiscount'] += $discountAmount;
            $orderArr['OrderTotal'] -= $discountAmount;
        }

        return $orderArr;
    }


    public function getItemDetailByMatcodeAndKeyid($keyId, $itemArr, $materialCode)
    {
        $itemDetail = false;
        if (!is_null($materialCode)) {
            foreach ($itemArr as $key => $item) {
                if ($item['ReferenceKeyId'] === $keyId && $item['ProductId'] === $materialCode)
                    $itemDetail = $key;
            }
        }

        return $itemDetail;
    }

    /**
     * param (order, itemArr, discountAmont. discountDescription, matCode
     * this method use to add discount in specific matcode of order/invoice
     */
    public function addDiscountItem($order, $item, $discountAmount, $discountDescription)
    {
        if ($item['InvoiceDiscount1Amount'] == 0) {
            //for order
            $item['OrderDiscount1'] = $discountAmount;
            $item['OrderDiscount1Type'] = $discountDescription;
            $item['OrderDiscount1Amount'] = $discountAmount;
            $order['OrderTotalDiscount'] += $discountAmount;
            $order['OrderTotal'] -= $discountAmount;

            //for invoice
            $item['InvoiceDiscount1'] = $discountAmount;
            $item['InvoiceDiscount1Type'] = $discountDescription;
            $item['InvoiceDiscount1Amount'] = $discountAmount;
            $order['InvoiceTotalDiscount'] += $discountAmount;
            $order['InvoiceTotal'] -= $discountAmount;
        } else if ($item['InvoiceDiscount2Amount'] == 0) {
            //for order
            $item['OrderDiscount2'] = $discountAmount;
            $item['OrderDiscount2Type'] = $discountDescription;
            $item['OrderDiscount2Amount'] = $discountAmount;
            $item['OrderDiscount2Scheme'] = 'Gross';
            $order['OrderTotalDiscount'] += $discountAmount;
            $order['OrderTotal'] -= $discountAmount;

            //for invoice
            $item['InvoiceDiscount2'] = $discountAmount;
            $item['InvoiceDiscount2Type'] = $discountDescription;
            $item['InvoiceDiscount2Amount'] = $discountAmount;
            $item['InvoiceDiscount2Scheme'] = 'Gross';
            $order['InvoiceTotalDiscount'] += $discountAmount;
            $order['InvoiceTotal'] -= $discountAmount;
        } else if ($item['InvoiceDiscount3Amount'] == 0) {
            //for order
            $item['OrderDiscount3'] = $discountAmount;
            $item['OrderDiscount3Type'] = $discountDescription;
            $item['OrderDiscount3Amount'] = $discountAmount;
            $item['OrderDiscount3Scheme'] = 'Gross';
            $order['OrderTotalDiscount'] += $discountAmount;
            $order['OrderTotal'] -= $discountAmount;

            //for invoice
            $item['InvoiceDiscount3'] = $discountAmount;
            $item['InvoiceDiscount3Type'] = $discountDescription;
            $item['InvoiceDiscount3Amount'] = $discountAmount;
            $item['InvoiceDiscount3Scheme'] = 'Gross';
            $order['InvoiceTotalDiscount'] += $discountAmount;
            $order['InvoiceTotal'] -= $discountAmount;
        } else if ($item['InvoiceDiscount4Amount'] == 0) {
            //for order
            $item['OrderDiscount4'] = $discountAmount;
            $item['OrderDiscount4Type'] = $discountDescription;
            $item['OrderDiscount4Amount'] = $discountAmount;
            $item['OrderDiscount4Scheme'] = 'Gross';
            $order['OrderTotalDiscount'] += $discountAmount;
            $order['OrderTotal'] -= $discountAmount;

            //for invoice
            $item['InvoiceDiscount4'] = $discountAmount;
            $item['InvoiceDiscount4Type'] = $discountDescription;
            $item['InvoiceDiscount4Amount'] = $discountAmount;
            $item['InvoiceDiscount4Scheme'] = 'Gross';
            $order['InvoiceTotalDiscount'] += $discountAmount;
            $order['InvoiceTotal'] -= $discountAmount;
        } else {

            //for order
            $item['OrderDiscount4'] += $discountAmount;
            $item['OrderDiscount4Type'] = $item['OrderDiscount4Type'] . ', ' . $discountDescription;
            $item['OrderDiscount4Amount'] += $discountAmount;
            $order['OrderTotalDiscount'] += $discountAmount;
            $order['OrderTotal'] -= $discountAmount;

            //for invoice
            $item['InvoiceDiscount4'] += $discountAmount;
            $item['InvoiceDiscount4Type'] = $item['InvoiceDiscount4Type'] . ', ' . $discountDescription;
            $item['InvoiceDiscount4Amount'] += $discountAmount;
            $order['InvoiceTotalDiscount'] += $discountAmount;
            $order['InvoiceTotal'] -= $discountAmount;
        }

        return [
            'order' => $order,
            'item' => $item
        ];
    }




    /**
     * this method use to save the summary of last uploaded data in excel
     */
    public function saveUploadDetail($orderArr, $itemArr)
    {
        $orders_count = count($orderArr);
        $line_items_count = count($itemArr);
        $total_qty = $this->getTotal($itemArr, 'InvoiceQuantity');
        $total_sales = $this->getTotalSales($itemArr);
        $total_discount = $this->getTotal($orderArr, 'InvoiceTotalDiscount');
        $uploadDetails = new SiforderLastupload();
        $uploadDetails->orders_count = $orders_count;
        $uploadDetails->line_items_count = $line_items_count;
        $uploadDetails->total_qty = $total_qty;
        $uploadDetails->total_sales = $total_sales;
        $uploadDetails->total_discount = $total_discount;
        $uploadDetails->save();
        return [
            'Orders Count' => $orders_count,
            'Line Items Count' => $line_items_count,
            'Total Qty' => $total_qty,
            'Total Sales' => $total_sales,
            'Total Discount' => $total_discount,
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
        (float) $totalSales = 0;
        foreach ($items as $item)
            $totalSales += $item['InvoiceQuantity'] * $item['InvoicePrice'];

        return $totalSales;
    }


    /**
     * this method use to create an Order Array
     */
    public function createOrderArray()
    {
        $orderArr = array();
        $orderArr = [
            'Guid' => $this->orderGuid,
            'SalesType' => $this->salesType,
            'AccountReferenceId' => $this->accountId,
            'SASId' => $this->sasId,
            'DSPId' => $this->dspId,
            'SalesOrderNumber' => $this->salesOrderNumber,
            'OrderDate' => $this->orderDate,
            'OrderTotal' => $this->orderTotalAmount,
            'RequestedDeliveryDate' => $this->requestedDate,
            'PaymentTerm' => $this->paymentTerm,
            'InvoiceNumber' => $this->invoiceNumber,
            'InvoiceDate' => $this->invoiceDate,
            'InvoiceTotal' => $this->invoiceTotalAmount,
            'Status' => 'OUT',
            'TransactionId' => $this->transactionId,
            'CreatedById' => NULL,
            'EncodedDate' => date('Y-m-d'),
            'InvoiceTotalDiscount' => 0,
            'InvoiceDiscountFromTotal1' => 0,
            'InvoiceDiscountFromTotal2' => 0,
            'InvoiceDiscountFromTotal3' => 0,
            'InvoiceDiscountFromTotal4' => 0,
            'InvoiceDiscountFromTotal2Scheme' => NULL,
            'InvoiceDiscountFromTotal3Scheme' => NULL,
            'InvoiceDiscountFromTotal4Scheme' => NULL,
            'IsInvoiceDiscountFromTotal1Percent' => 0,
            'IsInvoiceDiscountFromTotal2Percent' => 0,
            'IsInvoiceDiscountFromTotal3Percent' => 0,
            'IsInvoiceDiscountFromTotal4Percent' => 0,
            'InvoiceDiscountFromTotal1Type' => null,
            'InvoiceDiscountFromTotal2Type' => null,
            'InvoiceDiscountFromTotal3Type' => null,
            'InvoiceDiscountFromTotal4Type' => null,
            'InvoiceDiscountFromTotal1Amount' => 0,
            'InvoiceDiscountFromTotal2Amount' => 0,
            'InvoiceDiscountFromTotal3Amount' => 0,
            'InvoiceDiscountFromTotal4Amount' => 0,
            'OrderTotalDiscount' => 0,
            'OrderDiscountFromTotal1' => 0,
            'OrderDiscountFromTotal2' => 0,
            'OrderDiscountFromTotal3' => 0,
            'OrderDiscountFromTotal4' => 0,
            'OrderDiscountFromTotal2Scheme' => NULL,
            'OrderDiscountFromTotal3Scheme' => NULL,
            'OrderDiscountFromTotal4Scheme' => NULL,
            'IsOrderDiscountFromTotal1Percent' => 0,
            'IsOrderDiscountFromTotal2Percent' => 0,
            'IsOrderDiscountFromTotal3Percent' => 0,
            'IsOrderDiscountFromTotal4Percent' => 0,
            'OrderDiscountFromTotal1Type' => null,
            'OrderDiscountFromTotal2Type' => null,
            'OrderDiscountFromTotal3Type' => null,
            'OrderDiscountFromTotal4Type' => null,
            'OrderDiscountFromTotal1Amount' => 0,
            'OrderDiscountFromTotal2Amount' => 0,
            'OrderDiscountFromTotal3Amount' => 0,
            'OrderDiscountFromTotal4Amount' => 0,
            'KeyId' => $this->orderKeyid
        ];

        return $orderArr;
    }

    /**
     * this method use to create an OrderItem Array
     */
    public function createItemArray()
    {

        $itemArr = array();
        $conversionId = $this->getMaterialConversion($this->materialCode);
        $itemArr = [
            'Guid' =>  SifFunction::generateGuid(),
            'OrderReferenceGuid' => $this->orderGuid,
            'ProductId' => $this->materialCode,
            'WeightUOM' => $conversionId,
            'ConversionId' => $conversionId,
            'OrderQuantity' => $this->orderQuantity,
            'OrderPrice' => $this->orderPrice,
            'InvoiceQuantity' => $this->invoiceQuantity,
            'InvoicePrice' => $this->invoicePrice,
            'TransactionId' => date('md') . SifFunction::generateRandomID(100000, 999999),
            'ReasonUnequalOrderInvoice' => $this->reasonOfUnequalInvoice,
            'ActualWeightQuantity' => $this->actualWeightQuantity,
            'Status' => 'OUT',
            'OrderDiscount1' => 0,
            'OrderDiscount2' => 0,
            'OrderDiscount3' => 0,
            'OrderDiscount4' => 0,
            'IsOrderDiscount1Percent' => 0,
            'IsOrderDiscount2Percent' => 0,
            'IsOrderDiscount3Percent' => 0,
            'IsOrderDiscount4Percent' => 0,
            'OrderDiscount1Type' => null,
            'OrderDiscount2Type' => null,
            'OrderDiscount3Type' => null,
            'OrderDiscount4Type' => null,
            'OrderDiscount1Amount' => 0,
            'OrderDiscount2Amount' => 0,
            'OrderDiscount3Amount' => 0,
            'OrderDiscount4Amount' => 0,
            'InvoiceDiscount1' => 0,
            'InvoiceDiscount2' => 0,
            'InvoiceDiscount3' => 0,
            'InvoiceDiscount4' => 0,
            'IsInvoiceDiscount1Percent' => 0,
            'IsInvoiceDiscount2Percent' => 0,
            'IsInvoiceDiscount3Percent' => 0,
            'IsInvoiceDiscount4Percent' => 0,
            'InvoiceDiscount1Type' => null,
            'InvoiceDiscount2Type' => null,
            'InvoiceDiscount3Type' => null,
            'InvoiceDiscount4Type' => null,
            'InvoiceDiscount1Amount' => 0,
            'InvoiceDiscount2Amount' => 0,
            'InvoiceDiscount3Amount' => 0,
            'InvoiceDiscount4Amount' => 0,
            'InvoiceDiscount2Scheme' => NULL,
            'InvoiceDiscount3Scheme' => NULL,
            'InvoiceDiscount4Scheme' => NULL,
            'OrderDiscount2Scheme' => NULL,
            'OrderDiscount3Scheme' => NULL,
            'OrderDiscount4Scheme' => NULL,

            'ReferenceKeyId' => $this->orderKeyid
        ];
        return $itemArr;
    }

    /**
     * @param matcode //material code of an item
     * this method use to get the conversion id of the material
     * @return conversion_id : false
     */
    public function getMaterialConversion($matCode)
    {
        $item = Sifitem::select('ConversionId')->where('MaterialCode', $matCode)->get();
        return count($item) > 0 ? $item[0]['ConversionId'] : false;
    }


    /**
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {
        $headers = SiforderHeader::first();
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
        $start = $invoiceArr[0][strlen($invoiceArr[0]) - 2] . $invoiceArr[0][strlen($invoiceArr[0]) - 1];
        //the ten'th value of the invoice number

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
                 * same SO but unique Invoice number , Guid and KeyId are needed
                 */

                $this->orderGuid = SifFunction::generateGuid();
                $this->orderKeyid =  SifFunction::generateGuid();
                // $this->orderKeyid = date('dmY') . SifFunction::generateRandomID(100000, 999999);
                $this->invoiceNumber = $invoiceArr[0];

                /**
                 * determine if the invoice is exists
                 * if the invoice is not exists
                 * then create new order and sku
                 */
                $hasCount = Siforder::where('InvoiceNumber', $this->invoiceNumber)->count();
                if ($hasCount == 0) {
                    $orderArr[] = $this->createOrderArray();
                }




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

                /**
                 * determine if the invoice is exists
                 * if the invoice is not exists
                 * then create new order and sku
                 */
                $hasCount = Siforder::where('InvoiceNumber', $this->invoiceNumber)->count();
                if ($hasCount == 0) {
                    $orderArr[] = $this->createOrderArray();
                }

                $invoiceNumber = $invoiceNumber + 1; //increment the invoicenumber

            }
            $start++;
        }

        return $orderArr;
    }



    public function storeInvoices($invoiceArr)
    {

        //store first invoice        
        $this->orderGuid = SifFunction::generateGuid();
        $this->orderKeyid =  SifFunction::generateGuid();
        // $this->orderKeyid = date('dmY') . SifFunction::generateRandomID(100000, 999999);
        $this->invoiceNumber = $invoiceArr[0];
        $firstInvoice = $invoiceArr[0];
        $orderArr = [];

        /**
         * determine if the invoice is exists
         * if the invoice is not exists
         * then create new order and sku
         */
        $hasCount = Siforder::where('InvoiceNumber', $this->invoiceNumber)->count();
        if ($hasCount == 0) {
            $this->orderTotalAmount = 0;
            $this->invoiceTotalAmount = 0;
            $this->transactionId = $this->invoiceNumber;
            $orderArr[] = $this->createOrderArray();
        }


        //store second invoice

        $suffixLength = strlen($invoiceArr[1]);
        for ($i = 0; $i < $suffixLength; $i++) {
            $invoiceArr[0][strlen($invoiceArr[0]) - ($suffixLength - $i)] = $invoiceArr[1][$i];
            // $invoiceArr[0][strlen($invoiceArr[0]) -2] = $invoiceArr[1][0];
            // $invoiceArr[0][strlen($invoiceArr[0]) - 1] = $invoiceArr[1][1];
        }

        // $this->orderKeyid = date('dmY') . SifFunction::generateRandomID(100000, 999999);
        $this->invoiceNumber = $invoiceArr[0];

        if ($firstInvoice != $invoiceArr[0]) {

            $hasCount = Siforder::where('InvoiceNumber', $this->invoiceNumber)->count();
            if ($hasCount == 0) {
                $this->orderTotalAmount = 0;
                $this->invoiceTotalAmount = 0;
                $this->orderGuid = SifFunction::generateGuid();
                $this->orderKeyid =  SifFunction::generateGuid();
                $this->transactionId = $this->invoiceNumber;
                $orderArr[] = $this->createOrderArray();
            }
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

        $randomIndex = mt_rand(0, count($arrInvoice) - 1); // get element in the random index in the array
        $selectedInvoice = $arrInvoice[$randomIndex];

        //return the random selected invoice
        return $selectedInvoice;
    }


    public function getOneInvoiceForDiscunt($invoiceArr)
    {

        $arrInvoice = array();


        //store first invoice        
        array_push($arrInvoice, $invoiceArr[0]);

        $suffixLength = strlen($invoiceArr[1]);

        for ($i = 0; $i < $suffixLength; $i++) {
            $invoiceArr[0][strlen($invoiceArr[0]) - ($suffixLength - $i)] = $invoiceArr[1][$i];
        }

        array_push($arrInvoice, $invoiceArr[0]);

        $randomIndex = mt_rand(0, count($arrInvoice) - 1); // get element in the random index in the array
        $selectedInvoice = $arrInvoice[$randomIndex];

        return $selectedInvoice;
    }


    public function getOneInvoice($invoiceArr)
    {

        $arrInvoice = array();

        //store first invoice        
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

        $headerRow = SiforderHeader::first();

        foreach ($rows as $row) {
            $fieldErrors = false;
            /**
             * $key = header of an table
             * $value = the value of an header
             */
            if (strtolower($row[SifFunction::makeSlug($headerRow->sales_type)])  != 'discount') :
                $headerArr = self::getHeaderRow(); //get the header row of an every data
                foreach ($headerArr as $key => $value) :
                    $validateOrder->headerRules = SiforderValidation::first();
                    // loop one by one
                    // and validate 
                    if ($key == 'order_date' || $key == 'delivery_date' || $key == 'invoice_date') :
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
            else :
                $headerArr = self::getDiscountHeaderRow();
                foreach ($headerArr as $key => $value) :

                    $validateOrder->headerRules = SifdiscountValidation::first();
                    $fieldErrors =  $validateOrder->validate($row[SifFunction::makeSlug($value)], $key);

                    if ($fieldErrors != false) {
                        $arrError[] = [
                            'row' => $rowCount,
                            'field' => $value,
                            'errors' => $fieldErrors
                        ];
                    }

                endforeach;



            endif;

            $rowCount++; //increment the row
        }

        return $arrError;
    }


    /**
     * method use to get the header discount of every row 
     */
    public static function getDiscountHeaderRow()
    {
        $headerArr = [];
        $headers = SifdiscountHeader::first();
        return $headerArr = json_decode($headers);
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

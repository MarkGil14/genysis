<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

use App\SifFunction;
use App\SiforderHeader;
use App\RuleValidation;
use App\SiforderValidation;
use App\Sifitem;
use App\Sifdsp;
use App\Siforder;
use App\Siforderreturn;
use App\Siforderitem;
use App\FormatName;
use App\SystemSettings;
use Illuminate\Support\Facades\Input;
use Session;

class OrdersController extends Controller
{
    //

    public function updateOrder(Request $request)
    {

        $errors = $this->validateRows($request);

        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {
            Session::flash('error_data', $errors);
            return redirect()->back();
        }

        // $order = null;
        $orderInfo = Siforder::where('KeyId', $request->keyid)->first();
        if (is_null($orderInfo))
            return redirect()->back();

        $order = Siforder::find($orderInfo->Guid);
        $order->SalesType = $request->sales_type;
        $order->AccountReferenceId = $request->account_id;
        $order->SASId = $request->sas_id;
        $order->DSPId = $request->dsp_id;
        $order->SalesOrderNumber = $request->sales_order_number;
        $order->OrderDate = $request->order_date;
        $order->InvoiceDate = $request->invoice_date;
        $order->RequestedDeliveryDate = $request->delivery_date;
        $order->PaymentTerm = $request->payment_term;
        $order->InvoiceNumber = $request->invoice_number;
        $order->TransactionId = $request->transaction_id;
        $order->Status = 'OUT';
        $order->ErrorMessage = null;
        $order->Guid  = SifFunction::generateGuid();


        if ($request->invoice_discount1_description != '') {
            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal1;
            $order->InvoiceDiscountFromTotal1 = $request->invoice_discount1;
            $order->InvoiceTotal -= $order->InvoiceDiscountFromTotal1;
            $order->InvoiceDiscountFromTotal1Type = $request->invoice_discount1_description;
            $order->InvoiceDiscountFromTotal1Amount = $request->invoice_discount1;
        } else {
            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal1Amount;
            $order->InvoiceDiscountFromTotal1 = 0;
            $order->InvoiceDiscountFromTotal1Type = NULL;
            $order->InvoiceDiscountFromTotal1Amount = 0;
        }


        if ($request->invoice_discount2_description != '') {

            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal2;
            $order->InvoiceDiscountFromTotal2 = $request->invoice_discount2;
            $order->InvoiceTotal -= $order->InvoiceDiscountFromTotal2;
            $order->InvoiceDiscountFromTotal2Type = $request->invoice_discount2_description;
            $order->InvoiceDiscountFromTotal2Amount = $request->invoice_discount2;
            $order->InvoiceDiscountFromTotal2Scheme = 'Gross';
        } else {
            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal2Amount;
            $order->InvoiceDiscountFromTotal2 = 0;
            $order->InvoiceDiscountFromTotal2Scheme = NULL;
            $order->InvoiceDiscountFromTotal2Type = NULL;
            $order->InvoiceDiscountFromTotal2Amount = 0;
        }
        if ($request->invoice_discount3_description != '') {

            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal3;
            $order->InvoiceDiscountFromTotal3 = $request->invoice_discount3;
            $order->InvoiceTotal -= $order->InvoiceDiscountFromTotal3;
            $order->InvoiceDiscountFromTotal3Type = $request->invoice_discount3_description;
            $order->InvoiceDiscountFromTotal3Amount = $request->invoice_discount3;
            $order->InvoiceDiscountFromTotal3Scheme = 'Gross';
        } else {
            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal3Amount;
            $order->InvoiceDiscountFromTotal3 = 0;
            $order->InvoiceDiscountFromTotal3Scheme = NULL;
            $order->InvoiceDiscountFromTotal3Type = NULL;
            $order->InvoiceDiscountFromTotal3Amount = 0;
        }
        if ($request->invoice_discount4_description != '') {

            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal4;
            $order->InvoiceDiscountFromTotal4 = $request->invoice_discount4;
            $order->InvoiceTotal -= $order->InvoiceDiscountFromTotal4;
            $order->InvoiceDiscountFromTotal4Type = $request->invoice_discount4_description;
            $order->InvoiceDiscountFromTotal4Amount = $request->invoice_discount4;
            $order->InvoiceDiscountFromTotal4Scheme = 'Gross';
        } else {
            $order->InvoiceTotal += $order->InvoiceDiscountFromTotal4Amount;
            $order->InvoiceDiscountFromTotal4 = 0;
            $order->InvoiceDiscountFromTotal4Scheme = NULL;
            $order->InvoiceDiscountFromTotal4Type = NULL;
            $order->InvoiceDiscountFromTotal4Amount = 0;
        }

        if ($request->order_discount1_description != '') {
            $order->OrderTotal += $order->OrderDiscountFromTotal1;
            $order->OrderDiscountFromTotal1 = $request->order_discount1;
            $order->OrderTotal -= $order->OrderDiscountFromTotal1;
            $order->OrderDiscountFromTotal1Type = $request->order_discount1_description;
            $order->OrderDiscountFromTotal1Amount = $request->order_discount1;
        } else {
            $order->OrderTotal += $order->OrderDiscountFromTotal1Amount;
            $order->OrderDiscountFromTotal1 = 0;
            $order->OrderDiscountFromTotal1Type = NULL;
            $order->OrderDiscountFromTotal1Amount = 0;
        }


        if ($request->order_discount2_description != '') {

            $order->OrderTotal += $order->OrderDiscountFromTotal2;
            $order->OrderDiscountFromTotal2 = $request->order_discount2;
            $order->OrderTotal -= $order->OrderDiscountFromTotal2;
            $order->OrderDiscountFromTotal2Type = $request->order_discount2_description;
            $order->OrderDiscountFromTotal2Amount = $request->order_discount2;
            $order->OrderDiscountFromTotal2Scheme = 'Gross';
        } else {
            $order->OrderTotal += $order->OrderDiscountFromTotal2Amount;
            $order->OrderDiscountFromTotal2 = 0;
            $order->OrderDiscountFromTotal2Scheme = NULL;
            $order->OrderDiscountFromTotal2Type = NULL;
            $order->OrderDiscountFromTotal2Amount = 0;
        }
        if ($request->order_discount3_description != '') {

            $order->OrderTotal += $order->OrderDiscountFromTotal3;
            $order->OrderDiscountFromTotal3 = $request->order_discount3;
            $order->OrderTotal -= $order->OrderDiscountFromTotal3;
            $order->OrderDiscountFromTotal3Type = $request->order_discount3_description;
            $order->OrderDiscountFromTotal3Amount = $request->order_discount3;
            $order->OrderDiscountFromTotal3Scheme = 'Gross';
        } else {
            $order->OrderTotal += $order->OrderDiscountFromTotal3Amount;
            $order->OrderDiscountFromTotal3 = 0;
            $order->OrderDiscountFromTotal3Scheme = NULL;
            $order->OrderDiscountFromTotal3Type = NULL;
            $order->OrderDiscountFromTotal3Amount = 0;
        }
        if ($request->order_discount4_description != '') {

            $order->OrderTotal += $order->OrderDiscountFromTotal4;
            $order->OrderDiscountFromTotal4 = $request->order_discount4;
            $order->OrderTotal -= $order->OrderDiscountFromTotal4;
            $order->OrderDiscountFromTotal4Type = $request->order_discount4_description;
            $order->OrderDiscountFromTotal4Amount = $request->order_discount4;
            $order->OrderDiscountFromTotal4Scheme = 'Gross';
        } else {
            $order->OrderTotal += $order->OrderDiscountFromTotal4Amount;
            $order->OrderDiscountFromTotal4 = 0;
            $order->OrderDiscountFromTotal4Scheme = NULL;
            $order->OrderDiscountFromTotal4Type = NULL;
            $order->OrderDiscountFromTotal4Amount = 0;
        }


        $orderDiscountTotal = $request->order_discount1 + $request->order_discount2 + $request->order_discount3 + $request->order_discount4;
        $invoiceDiscountTotal = $request->invoice_discount1 + $request->invoice_discount2 + $request->invoice_discount3 + $request->invoice_discount4;
        // $order->InvoiceTotal = $order->InvoiceTotal - $invoiceDiscountTotal;
        // $order->OrderTotal = $order->OrderTotal - $orderDiscountTotal;
        $order->InvoiceTotalDiscount = $invoiceDiscountTotal;
        $order->OrderTotalDiscount =  $orderDiscountTotal;
        $result = $order->save();


        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        if ($result) :
            Session::flash('success', 'Orders was Successfully Updated');
        else :
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;


        return redirect()->back();
    }

    /**
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {

        $headers = SiforderHeader::first();
        return json_decode($headers);
    }


    /**
     * @param rows 
     * this method use to validate all the rows of the import EXCEL/CSV
     * @return ArrError array()
     */
    public function validateRows($row)
    {
        $arrError = []; //this will be the storage of all error msges
        $validateOrder = new RuleValidation();
        $validateOrder->headerRules = SiforderValidation::first();


        $headerArr = self::getHeaderRow(); //get the header row of an every data

        $fieldErrors = false;
        /**
         * $key = header of an table
         * $value = the value of an header
         */
        foreach ($headerArr as $key => $value) :

            // loop one by one
            // and validate 

            if (($key != 'material_code') && ($key != 'invoice_quantity') && ($key != 'invoice_price') && ($key != 'order_quantity') && ($key != 'order_price') && ($key != 'invoice_quantity') && ($key != 'actual_weight_quantity') && ($key != 'total_amount')) {
                if ($key == 'delivery_date' || $key == 'order_date' || $key == 'invoice_date') :
                    $fieldErrors = $validateOrder->validate(SifFunction::formatDate($row[SifFunction::makeSlug($key)]), $key);
                else :
                    //todo dsp and sas validation
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                endif;

                if ($fieldErrors != false) {
                    $arrError[] = [
                        'field' => $value,
                        'errors' => $fieldErrors
                    ];
                }
            }

        endforeach;



        return $arrError;
    }

    public function validateItemRows($row)
    {

        $arrError = []; //this will be the storage of all error msges

        $validateOrder = new RuleValidation();
        $validateOrder->headerRules = SiforderValidation::first();


        $headerArr = self::getHeaderRow(); //get the header row of an every data

        $fieldErrors = false;
        /**
         * $key = header of an table
         * $value = the value of an header
         */
        // exit(json_encode($headerArr));
        foreach ($headerArr as $key => $value) :

            // loop one by one
            // and validate 

            if (($key == 'actual_weight_quantity') || ($key == 'material_code') || ($key == 'order_quantity') || ($key == 'order_price') || ($key == 'invoice_quantity') || ($key == 'invoice_price')) {

                if ($key == 'material_code') :
                    $matcode = $this->getMaterialConversion($row[SifFunction::makeSlug($key)]);
                    if (!$matcode) {
                        $arrError[] = [
                            'field' => $value,
                            'errors' => ' This "' . $row[SifFunction::makeSlug($key)] . '" is not Exists '
                        ];
                    }
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                else :
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                endif;

                if ($fieldErrors != false) {
                    $arrError[] = [
                        'field' => $value,
                        'errors' => $fieldErrors
                    ];
                }
            }

        endforeach;



        return $arrError;
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

    public function getItemDataByGuid(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $item = Siforderitem::find($request->id);
        $itemArr = array();
        $itemArr = [
            'product_id' => $item->ProductId,
            'conversion_id' => $item->ConversionId,
            'weight_uom' => $item->WeightUOM,
            'actual_weight_qty' => $item->ActualWeightQuantity,
            'quantity' => $item->InvoiceQuantity,
            'price' => $item->InvoicePrice,

            'discount1' => $item->InvoiceDiscount1,
            'discount2' => $item->InvoiceDiscount2,
            'discount3' => $item->InvoiceDiscount3,
            'discount4' => $item->InvoiceDiscount4,
            'discount1_description' => $item->InvoiceDiscount1Type,
            'discount2_description' => $item->InvoiceDiscount2Type,
            'discount3_description' => $item->InvoiceDiscount3Type,
            'discount4_description' => $item->InvoiceDiscount4Type,

            'order_quantity' => $item->OrderQuantity,
            'order_price' => $item->OrderPrice,
            'order_discount1' => $item->OrderDiscount1,
            'order_discount2' => $item->OrderDiscount2,
            'order_discount3' => $item->OrderDiscount3,
            'order_discount4' => $item->OrderDiscount4,
            'order_discount1_description' => $item->OrderDiscount1Type,
            'order_discount2_description' => $item->OrderDiscount2Type,
            'order_discount3_description' => $item->OrderDiscount3Type,
            'order_discount4_description' => $item->OrderDiscount4Type,

            'reason_unequal_order_invoice' => $item->ReasonUnequalOrderInvoice
        ];

        return json_encode($itemArr);
    }


    public function updateItem(Request $request)
    {


        $errors = $this->validateItemRows($request);
        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {

            Session::flash('error_data', $errors);
            return redirect()->back();
        }

        $item = Siforderitem::find($request->item_id);
        $order_detail = $item->order_detail();

        //get the current total discount of this item
        $currentTotalItemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4;
        $OrdercurrentTotalItemDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4;

        //compute the total discount without the discount of this item 
        //by subtracting the current total discount in the item current total discount
        $discountAmount = $order_detail->InvoiceTotalDiscount - $currentTotalItemDiscount;

        $OrderdiscountAmount = $order_detail->OrderTotalDiscount - $OrdercurrentTotalItemDiscount;

        //compute the new item total discount
        (float) $newTotalItemDiscount = $request->discount1 + $request->discount2 + $request->discount3  + $request->discount4;
        (float) $OrdernewTotalItemDiscount = $request->order_discount1 + $request->order_discount2 + $request->order_discount3  + $request->order_discount4;

        //compute the new overall total order discount  *
        //by adding the current total discount of the order and the new item discount
        $newOrderDiscount = $discountAmount + $newTotalItemDiscount;
        $OrdernewOrderDiscount = $OrderdiscountAmount + $OrdernewTotalItemDiscount;


        //compute the total amount without discount and the discount of this item
        $currentTotalAmount = $order_detail->InvoiceTotal + $currentTotalItemDiscount;
        $OrdercurrentTotalAmount = $order_detail->OrderTotal + $OrdercurrentTotalItemDiscount;

        //get the current item amount
        $currentItemAmount = $item->InvoicePrice * $item->InvoiceQuantity;
        $OrdercurrentItemAmount = $item->OrderPrice * $item->OrderQuantity;

        //get the total amount of this item
        $itemAmount = $request->invoice_price * $request->invoice_quantity;
        $orderitemAmount = $request->order_price * $request->order_quantity;

        //compute the total order amount without the amount of this item
        $currentTotalAmount = $currentTotalAmount - $currentItemAmount;
        $OrdercurrentTotalAmount = $OrdercurrentTotalAmount - $OrdercurrentItemAmount;

        //compute the new total order amount with the new amount of this item
        $currentTotalAmount = $currentTotalAmount + $itemAmount;
        $OrdercurrentTotalAmount = $OrdercurrentTotalAmount + $orderitemAmount;


        //compute the new total amount with discount and the new discount of this item
        $newTotalInvoiceAmount = $currentTotalAmount - $newTotalItemDiscount;
        $OrdernewTotalAmount = $OrdercurrentTotalAmount - $OrdernewTotalItemDiscount;

        $order = Siforder::find($order_detail->Guid);
        $order->InvoiceTotalDiscount = $newOrderDiscount;
        $order->OrderTotalDiscount = $OrdernewOrderDiscount;
        $order->OrderTotal = $OrdernewTotalAmount;
        $order->InvoiceTotal = $newTotalInvoiceAmount;
        $order->Guid = SifFunction::generateGuid();
        $order->save();


        $item->ProductId = $request->material_code;
        $item->ConversionId = $request->conversion_id;
        $item->OrderQuantity = $request->order_quantity;
        $item->InvoiceQuantity = $request->invoice_quantity;

        $item->OrderPrice = $request->order_price;
        $item->InvoicePrice = $request->invoice_price;


        if ($request->discount1_description != '') {
            $item->InvoiceDiscount1 = $request->discount1;
            $item->InvoiceDiscount1Type = $request->discount1_description;
            $item->InvoiceDiscount1Amount = $request->discount1;
        } else {
            $item->InvoiceDiscount1 = 0;
            $item->InvoiceDiscount1Type = NULL;
            $item->InvoiceDiscount1Amount = 0;
        }


        if ($request->discount2_description != '') {
            $item->InvoiceDiscount2 = $request->discount2;
            $item->InvoiceDiscount2Scheme = 'Gross';
            $item->InvoiceDiscount2Type = $request->discount2_description;
            $item->InvoiceDiscount2Amount = $request->discount2;
        } else {
            $item->InvoiceDiscount2 = 0;
            $item->InvoiceDiscount2Scheme = 'Gross';
            $item->InvoiceDiscount2Type = NULL;
            $item->InvoiceDiscount2Amount = 0;
        }

        if ($request->discount3_description != '') {
            $item->InvoiceDiscount3 = $request->discount3;
            $item->InvoiceDiscount3Scheme = 'Gross';
            $item->InvoiceDiscount3Type = $request->discount3_description;
            $item->InvoiceDiscount3Amount = $request->discount3;
        } else {
            $item->InvoiceDiscount3 = 0;
            $item->InvoiceDiscount3Scheme = 'Gross';
            $item->InvoiceDiscount3Type = NULL;
            $item->InvoiceDiscount3Amount = 0;
        }


        if ($request->discount4_description != '') {
            $item->InvoiceDiscount4 = $request->discount4;
            $item->InvoiceDiscount4Scheme = 'Gross';
            $item->InvoiceDiscount4Type = $request->iscount4_description;
            $item->InvoiceDiscount4Amount = $request->discount4;
        } else {
            $item->InvoiceDiscount4 = 0;
            $item->InvoiceDiscount4Scheme = 'Gross';
            $item->InvoiceDiscount4Type = NULL;
            $item->InvoiceDiscount4Amount = 0;
        }





        if ($request->order_discount1_description != '') {
            $item->OrderDiscount1 = $request->order_discount1;
            $item->OrderDiscount1Type = $request->order_discount1_description;
            $item->OrderDiscount1Amount = $request->order_discount1;
        } else {
            $item->OrderDiscount1 = 0;
            $item->OrderDiscount1Type = NULL;
            $item->OrderDiscount1Amount = 0;
        }


        if ($request->order_discount2_description != '') {
            $item->OrderDiscount2 = $request->order_discount2;
            $item->OrderDiscount2Scheme = 'Gross';
            $item->OrderDiscount2Type = $request->order_discount2_description;
            $item->OrderDiscount2Amount = $request->order_discount2;
        } else {
            $item->OrderDiscount2 = 0;
            $item->OrderDiscount2Scheme = 'Gross';
            $item->OrderDiscount2Type = NULL;
            $item->OrderDiscount2Amount = 0;
        }

        if ($request->order_discount3_description != '') {
            $item->OrderDiscount3 = $request->order_discount3;
            $item->OrderDiscount3Scheme = 'Gross';
            $item->OrderDiscount3Type = $request->order_discount3_description;
            $item->OrderDiscount3Amount = $request->order_discount3;
        } else {
            $item->OrderDiscount3 = 0;
            $item->OrderDiscount3Scheme = 'Gross';
            $item->OrderDiscount3Type = NULL;
            $item->OrderDiscount3Amount = 0;
        }

        if ($request->order_discount4_description != '') {
            $item->OrderDiscount4 = $request->order_discount4;
            $item->OrderDiscount4Scheme = 'Gross';
            $item->OrderDiscount4Type = $request->order_discount4_description;
            $item->OrderDiscount4Amount = $request->order_discount4;
        } else {
            $item->OrderDiscount4 = 0;
            $item->OrderDiscount4Scheme = 'Gross';
            $item->OrderDiscount4Type = NULL;
            $item->OrderDiscount4Amount = 0;
        }

        $item->ActualWeightQuantity = $request->actual_weight_quantity;
        $item->WeightUOM = $request->weight_uom;
        $item->ReasonUnequalOrderInvoice = $request->reason_unequal_order_invoice;
        $item->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Order Item Details was Successfully Updated');
        return redirect()->back();
    }


    public function getFilterOrderReport(Request $request)
    {
        $this->validate($request, [
            'time' => 'required'
        ]);

        $arrQuery = [];

        if (isset($request->status)) {

            if (Input::get('status') === 'FAILED')
                $arrQuery[] = " siforder.ErrorMessage IS NOT NULL AND siforder.Status = 'PROCESSED' ";
            else
                $arrQuery[] = " siforder.Status = '" . Input::get('status') . "' AND siforder.ErrorMessage IS NULL ";
        }


        $now = date('Y-m-d');
        $arrQuery[] = " siforder.EncodedDate BETWEEN DATE_ADD('" . $now . "', " . $request->time . ") AND '" . $now . "' ";

        $filterQuery = "SELECT
                         siforder.Status as OrderStatus, siforder.ErrorMessage as OrderErrorMessage , siforderitem.Status as ItemStatus, siforderitem.ErrorMessage as ItemErrorMessage , siforder.Id as SifId , siforderitem.Id as SifitemId, siforder.* , siforderitem.* 
                        FROM siforder INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId WHERE " . implode(' AND ', $arrQuery);


        $resultQuery = DB::select($filterQuery);

        return view('app.report')
            ->with('all_dsp', Sifdsp::all())
            ->with('results', $resultQuery)
            ->with('formats', FormatName::first())
            ->with('company', SystemSettings::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }


    public function filterOrders()
    {
        $arrQuery = [];

        if (!empty(Input::get('dsp')))
            $arrQuery[] = " siforder.DSPId = '" . Input::get('dsp') . "' ";

        if (!empty(Input::get('status'))) {
            if (Input::get('status') === 'FAILED')
                $arrQuery[] = " siforder.ErrorMessage IS NOT NULL AND siforder.Status = 'PROCESSED' ";
            else
                $arrQuery[] = " siforder.Status = '" . Input::get('status') . "' AND siforder.ErrorMessage IS NULL ";
        }

        $now = date('Y-m-d');
        if (Input::get('time'))
            $arrQuery[] = " siforder.EncodedDate BETWEEN DATE_ADD('" . $now . "', " . Input::get('time') . ") AND '" . $now . "' ";

        $filterQuery = "SELECT * FROM siforder  WHERE " . implode(' AND ', $arrQuery) . " order by siforder.Guid desc";

        $resultQuery = DB::select($filterQuery);

        return view('app.orders-filter')
            ->with('orders', $resultQuery)
            ->with('orderProcessedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
            ->with('orderOutCount', Siforder::where('Status', 'OUT')->count())
            ->with('orderProcessingCount', Siforder::where('Status', 'PROCESSING')->count())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('formats', FormatName::first())
            ->with('company', SystemSettings::first());
    }



    public function addOrderItem(Request $request, $id)
    {
        $errors = $this->validateItemRows($request);
        //if has an error found
        //then exit and return all the error messages
        if (count($errors) > 0) {

            Session::flash('error_data', $errors);
            return redirect()->back();
        }

        $order = Siforder::find($id);

        if (!$order)
            return redirect()->back();

        $item = new Siforderitem();
        $item->OrderReferenceGuid = $id;
        $item->ReferenceKeyId = $order->KeyId;
        $item->ProductId = $request->material_code;
        $item->ConversionId = $request->conversion_id;
        $item->OrderQuantity = $request->order_quantity;
        $item->InvoiceQuantity = $request->invoice_quantity;
        $item->ErrorMessage = NULL;
        $item->Status = 'OUT';
        $item->Guid = SifFunction::generateGuid();
        $item->TransactionId = date('md') . SifFunction::generateRandomID(100000, 999999);
        $item->OrderPrice = $request->order_price;
        $item->InvoicePrice = $request->invoice_price;


        if ($request->discount1_description != '') {
            $item->InvoiceDiscount1 = $request->discount1;
            $item->InvoiceDiscount1Type = $request->discount1_description;
            $item->InvoiceDiscount1Amount = $request->discount1;
        } else {
            $item->InvoiceDiscount1 = 0;
            $item->InvoiceDiscount1Type = NULL;
            $item->InvoiceDiscount1Amount = 0;
        }


        if ($request->discount2_description != '') {
            $item->InvoiceDiscount2 = $request->discount2;
            $item->InvoiceDiscount2Scheme = 'Gross';
            $item->InvoiceDiscount2Type = $request->discount2_description;
            $item->InvoiceDiscount2Amount = $request->discount2;
        } else {
            $item->InvoiceDiscount2 = 0;
            $item->InvoiceDiscount2Scheme = 'Gross';
            $item->InvoiceDiscount2Type = NULL;
            $item->InvoiceDiscount2Amount = 0;
        }

        if ($request->discount3_description != '') {
            $item->InvoiceDiscount3 = $request->discount3;
            $item->InvoiceDiscount3Scheme = 'Gross';
            $item->InvoiceDiscount3Type = $request->discount3_description;
            $item->InvoiceDiscount3Amount = $request->discount3;
        } else {
            $item->InvoiceDiscount3 = 0;
            $item->InvoiceDiscount3Scheme = 'Gross';
            $item->InvoiceDiscount3Type = NULL;
            $item->InvoiceDiscount3Amount = 0;
        }


        if ($request->discount4_description != '') {
            $item->InvoiceDiscount4 = $request->discount4;
            $item->InvoiceDiscount4Scheme = 'Gross';
            $item->InvoiceDiscount4Type = $request->iscount4_description;
            $item->InvoiceDiscount4Amount = $request->discount4;
        } else {
            $item->InvoiceDiscount4 = 0;
            $item->InvoiceDiscount4Scheme = 'Gross';
            $item->InvoiceDiscount4Type = NULL;
            $item->InvoiceDiscount4Amount = 0;
        }


        if ($request->order_discount1_description != '') {
            $item->OrderDiscount1 = $request->order_discount1;
            $item->OrderDiscount1Type = $request->order_discount1_description;
            $item->OrderDiscount1Amount = $request->order_discount1;
        } else {
            $item->OrderDiscount1 = 0;
            $item->OrderDiscount1Type = NULL;
            $item->OrderDiscount1Amount = 0;
        }


        if ($request->order_discount2_description != '') {
            $item->OrderDiscount2 = $request->order_discount2;
            $item->OrderDiscount2Scheme = 'Gross';
            $item->OrderDiscount2Type = $request->order_discount2_description;
            $item->OrderDiscount2Amount = $request->order_discount2;
        } else {
            $item->OrderDiscount2 = 0;
            $item->OrderDiscount2Scheme = 'Gross';
            $item->OrderDiscount2Type = NULL;
            $item->OrderDiscount2Amount = 0;
        }

        if ($request->order_discount3_description != '') {
            $item->OrderDiscount3 = $request->order_discount3;
            $item->OrderDiscount3Scheme = 'Gross';
            $item->OrderDiscount3Type = $request->order_discount3_description;
            $item->OrderDiscount3Amount = $request->order_discount3;
        } else {
            $item->OrderDiscount3 = 0;
            $item->OrderDiscount3Scheme = 'Gross';
            $item->OrderDiscount3Type = NULL;
            $item->OrderDiscount3Amount = 0;
        }

        if ($request->order_discount4_description != '') {
            $item->OrderDiscount4 = $request->order_discount4;
            $item->OrderDiscount4Scheme = 'Gross';
            $item->OrderDiscount4Type = $request->order_discount4_description;
            $item->OrderDiscount4Amount = $request->order_discount4;
        } else {
            $item->OrderDiscount4 = 0;
            $item->OrderDiscount4Scheme = 'Gross';
            $item->OrderDiscount4Type = NULL;
            $item->OrderDiscount4Amount = 0;
        }

        $item->ActualWeightQuantity = $request->actual_weight_quantity;
        $item->WeightUOM = $request->weight_uom;
        $item->ReasonUnequalOrderInvoice = $request->reason_unequal_order_invoice;

        $item->save();
        //save new order item

        $order_detail = $item->order_detail();

        //get the current total discount of this item
        $currentTotalItemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4;
        $OrdercurrentTotalItemDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4;


        $newOrderDiscount =  $order_detail->InvoiceTotalDiscount + $currentTotalItemDiscount;
        $OrdernewOrderDiscount =  $order_detail->OrderTotalDiscount + $OrdercurrentTotalItemDiscount;

        $OrdernewTotalAmount = $order_detail->OrderTotal + (($request->order_price * $request->order_quantity) - $OrdercurrentTotalItemDiscount);
        $newTotalInvoiceAmount = $order_detail->InvoiceTotal + (($request->invoice_price * $request->invoice_quantity) - $currentTotalItemDiscount);

        $order = Siforder::find($order_detail->Guid);
        $order->InvoiceTotalDiscount = $newOrderDiscount;
        $order->OrderTotalDiscount = $OrdernewOrderDiscount;

        $order->OrderTotal = $OrdernewTotalAmount;
        $order->InvoiceTotal = $newTotalInvoiceAmount;
        $order->Guid = SifFunction::generateGuid();
        $order->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Order Item was Successfully Added');
        return redirect()->back();
    }


    public function deleteOrder(Request $request)
    {
        $this->validate($request, [
            'keyid' => 'required'
        ]);

        $result2 = Siforderitem::where('ReferenceKeyId', $request->keyid)->delete();
        // if(!$result2)
        //     exit('false');

        $result1 = Siforder::where('KeyId', $request->keyid)->delete();
        if (!$result1)
            exit('false');
    }

    public function changeStatus($keyid, $status)
    {

        Siforder::where('KeyId', $keyid)->update(['Guid' =>  SifFunction::generateGuid(), 'Status' => $status, 'ErrorMessage' => NULL]);
        $order = Siforder::where('KeyId', $keyid)->first();

        $orderitems = $order->items();

        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = $status;
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Order Status Was Successfully Changed to ' . $status);
        return redirect()->back();
    }

    public function orderItemDelete($orderitemid)
    {

        $request = Siforderitem::find($orderitemid);

        $item = Siforderitem::find($orderitemid);
        $order_detail = $item->order_detail();

        //get the current total discount of this item
        $currentTotalItemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4;
        $OrdercurrentTotalItemDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4;

        //compute the total discount without the discount of this item 
        //by subtracting the current total discount in the item current total discount
        $discountAmount = $order_detail->InvoiceTotalDiscount - $currentTotalItemDiscount;

        $OrderdiscountAmount = $order_detail->OrderTotalDiscount - $OrdercurrentTotalItemDiscount;

        $currentTotalAmount = $order_detail->InvoiceTotal +  $currentTotalItemDiscount;
        $OrdercurrentTotalAmount = $order_detail->OrderTotal + $OrdercurrentTotalItemDiscount;


        //get the current item amount
        $currentItemAmount = $item->InvoicePrice * $item->InvoiceQuantity;
        $OrdercurrentItemAmount = $item->OrderPrice * $item->OrderQuantity;

        //compute the total order amount without the amount of this item
        $currentTotalAmount = $currentTotalAmount - $currentItemAmount;
        $OrdercurrentTotalAmount = $OrdercurrentTotalAmount - $OrdercurrentItemAmount;

        $order = Siforder::find($order_detail->Guid);
        $order->InvoiceTotalDiscount = $discountAmount;
        $order->OrderTotalDiscount = $OrderdiscountAmount;
        $order->OrderTotal = $OrdercurrentTotalAmount;
        $order->InvoiceTotal = $currentTotalAmount;
        $order->Guid = SifFunction::generateGuid();
        $order->save();


        $item->IsDelete = 1;
        $item->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Order Item was Successfully set to Delete');
        return redirect()->back();
    }


    public function orderItemCancelDelete($orderitemid)
    {

        $item = Siforderitem::find($orderitemid);
        $order_detail = $item->order_detail();

        //get the current total discount of this item
        $currentTotalItemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4;
        $OrdercurrentTotalItemDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4;

        //compute the total discount without the discount of this item 
        //by subtracting the current total discount in the item current total discount
        $discountAmount = $order_detail->InvoiceTotalDiscount + $currentTotalItemDiscount;

        $OrderdiscountAmount = $order_detail->OrderTotalDiscount + $OrdercurrentTotalItemDiscount;

        $currentTotalAmount = $order_detail->InvoiceTotal -  $currentTotalItemDiscount;
        $OrdercurrentTotalAmount = $order_detail->OrderTotal - $OrdercurrentTotalItemDiscount;


        //get the current item amount
        $currentItemAmount = $item->InvoicePrice * $item->InvoiceQuantity;
        $OrdercurrentItemAmount = $item->OrderPrice * $item->OrderQuantity;

        //compute the total order amount without the amount of this item
        $currentTotalAmount = $currentTotalAmount + $currentItemAmount;
        $OrdercurrentTotalAmount = $OrdercurrentTotalAmount + $OrdercurrentItemAmount;

        $order = Siforder::find($order_detail->Guid);
        $order->InvoiceTotalDiscount = $discountAmount;
        $order->OrderTotalDiscount = $OrderdiscountAmount;
        $order->OrderTotal = $OrdercurrentTotalAmount;
        $order->InvoiceTotal = $currentTotalAmount;
        $order->Guid = SifFunction::generateGuid();
        $order->save();


        $item->IsDelete = 0;
        $item->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Order Item was Successfully cancel the Delete');
        return redirect()->back();
    }


    public function orderIsDelete($guid)
    {

        $order = Siforder::find($guid);
        $order->IsDelete = 1;
        $order->Status = 'OUT';
        $order->ErrorMessage = null;
        $order->Guid  = SifFunction::generateGuid();
        $result = $order->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        if ($result) :
            Session::flash('success', 'Orders was Successfully set to delete');
        else :
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;


        return redirect()->back();
    }



    public function orderCancelDelete($guid)
    {
        $order = Siforder::find($guid);
        $order->IsDelete = 0;
        $order->Status = 'OUT';
        $order->ErrorMessage = null;
        $order->Guid  = SifFunction::generateGuid();
        $result = $order->save();

        $orderitems = $order->items();
        foreach ($orderitems as $item) {
            $item = Siforderitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }

        if ($result) :
            Session::flash('success', 'Orders was Successfully Cancel delete');
        else :
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;

        return redirect()->back();
    }


    public function metricRates()
    {
        $arrQuery = [];

        if (!empty(Input::get('status'))) {
            if (Input::get('status') === 'FAILED')
                $arrQuery[] = " siforder.ErrorMessage IS NOT NULL AND siforder.Status = 'PROCESSED' ";
            else
                $arrQuery[] = " siforder.Status = '" . Input::get('status') . "' AND siforder.ErrorMessage IS NULL ";
        }

        $now = date('Y-m-d');
        if (Input::get('time'))
            $arrQuery[] = " siforder.OrderDate BETWEEN DATE_ADD('" . $now . "', " . Input::get('time') . ") AND '" . $now . "' ";
        else
            $arrQuery[] = " siforder.OrderDate BETWEEN DATE_ADD('" . $now . "', INTERVAL -" . date('j', strtotime(date('Y-m-d'))) . " DAY) AND '" . $now . "' ";

        $filterQuery = "SELECT siforderitem.InvoiceQuantity, siforder.AccountReferenceId, siforderitem.OrderQuantity, siforder.InvoiceNumber, siforder.RequestedDeliveryDate,
                            siforder.InvoiceDate  FROM siforder INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId  WHERE siforder.Guid != '0' order by siforder.Guid desc";
        // siforder.InvoiceDate  FROM siforder INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId  WHERE ".implode(' AND ' , $arrQuery)." order by siforder.Guid desc";
        $resultQuery = DB::select($filterQuery);


        $order = new Siforder();
        // return response()->json([
        //     "data" => $order->MetricRates($resultQuery)
        // ], 200);

        return $order->MetricRates($resultQuery);
    }


    public function orders_vue_test()
    {
        $orders = Siforder::orderBy('id', 'desc')->paginate(50);
        return $orders;
    }
}

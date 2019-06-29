<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Siforder;
use App\Siforderreturn;
use App\Sifdsp;
use App\Sifcustomer;
use App\SiforderValidation;
use App\SiforderHeader;
use App\SifdiscountValidation;
use App\SifdiscountHeader;
use App\SiforderreturnValidation;
use App\SiforderreturnHeader;
use App\SifitemValidation;
use App\SifitemHeader;
use App\SifcustomerValidation;
use App\SifcustomerHeader;
use App\SifdspValidation;
use App\SifdspHeader;
use App\SystemSettings;
use App\Siforderitem;
use App\Siforderreturnitem;
use App\SiforderLastupload;
use App\SiforderreturnLastupload;
use App\Sifitem;

use Illuminate\Support\Facades\Input;
use App\Http\Controllers\Controller;
use App\FormatName;
use App\SifFunction;

class PagesController extends Controller
{
    //

    public function vue_test()
    {
        return view('app.spa_vue_test')
            // ->with('company', SystemSettings::first())
            // ->with('formats', FormatName::first())
            // ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            // ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
        ;
    }


    public function dashboard()
    {

        $arrQuery = [];

        if (!empty(Input::get('status'))) {
            if (Input::get('status') === 'FAILED')
                $arrQuery[] = " siforder.ErrorMessage IS NOT NULL AND siforder.Status = 'PROCESSED' ";
            else
                $arrQuery[] = " siforder.Status = '" . Input::get('status') . "' AND siforder.ErrorMessage IS NULL ";
        } else
            $arrQuery[] = " siforder.ErrorMessage IS NULL AND siforder.Status = 'PROCESSED' ";

        $now = date('Y-m-d');
        if (Input::get('time'))
            $arrQuery[] = " siforder.OrderDate BETWEEN DATE_ADD('" . $now . "', " . Input::get('time') . ") AND '" . $now . "' ";
        else
            $arrQuery[] = " siforder.OrderDate BETWEEN DATE_ADD('" . $now . "', INTERVAL -1 WEEK) AND '" . $now . "' ";

        $filterQuery = "SELECT siforderitem.InvoiceQuantity, siforder.AccountReferenceId, siforderitem.OrderQuantity, siforder.InvoiceNumber, siforder.RequestedDeliveryDate,
                        siforder.InvoiceDate  FROM siforder INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId  WHERE " . implode(' AND ', $arrQuery) . " order by siforder.Guid desc";


        $resultQuery = DB::select($filterQuery);

        $orderSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforder.InvoiceNumber)),0) as TotalOrders,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity * siforderitem.InvoicePrice), 0),4) as TotalSales,
                            FORMAT((SELECT IFNULL(SUM(InvoiceTotalDiscount), 0) FROM siforder WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL),4) as TotalDiscount,
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'PROCESSING') as countPROCESSING,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'OUT') as countOUT,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'PROCESSED' AND ErrorMessage IS NOT NULL) as countFAILED                                                            
                            FROM siforder
                            INNER JOIN siforderitem 
                            ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                            ";

        $returnSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforderreturn.InvoiceNumber)),0) as TotalReturns,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty * siforderreturnitem.Price), 0),4) as TotalReturnAmount,
                            FORMAT(IFNULL(SUM(siforderreturnitem.DiscountAmount),0),4) as TotalDiscount,
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'PROCESSING') as countPROCESSING,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'OUT') as countOUT,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'PROCESSED' AND ErrorMessage IS NOT NULL) as countFAILED                                                            
                            FROM siforderreturn
                            INNER JOIN siforderreturnitem 
                            ON siforderreturn.KeyId = siforderreturnitem.ReferenceKeyId
                            WHERE siforderreturn.Status = 'PROCESSED' AND siforderreturn.ErrorMessage IS NULL

                            ";

        $mertricSummaryQuery = "                           
									 SELECT 
                            
									 FORMAT(IFNULL((SELECT ((SUM(siforderitem.InvoiceQuantity)/SUM(siforderitem.OrderQuantity)) * 100)
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked'						
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL						
									 										 		
									 ), 0),2) as CaseFillRate,
									 									  

                            FORMAT(IFNULL((((SELECT COUNT(*)									 
                                    FROM siforder 
                                    INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                    WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0 
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL) / 
												
												(SELECT COUNT(*) FROM siforder INNER JOIN 
												 siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
												 WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL																					 
												 )
																								
												) * 100), 0),2) 												
                            as LineFillRate,                    
									 
									 									 
									 FORMAT(IFNULL((((SELECT COUNT(*)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity  = siforderitem.InvoiceQuantity 
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL) / 
												(SELECT COUNT(*) FROM siforder INNER JOIN 
												 siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
												 WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL																					 
												 )
												 ) * 100), 0),2) 												
										as OrderFillRate,
									 
									 				
									 FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate 
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL) / 
												(SELECT COUNT(*) FROM siforder 
												WHERE siforder.ErrorMessage IS NULL AND siforder.`Status` = 'PROCESSED' AND siforder.SalesType = 'Pre-booked')
												) * 100), 0),2) 												
										as Timeliness,
									 
									 					 
									 FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
												AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity) / 
												(SELECT COUNT(*) FROM siforder 
												WHERE siforder.ErrorMessage IS NULL AND siforder.`Status` = 'PROCESSED' AND siforder.SalesType = 'Pre-booked')
												) * 100), 0),2) 												
									 as OTIF,
									 									    
									 FORMAT(IFNULL((count(*) / (SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
									 			FROM siforder 
												WHERE siforder.SalesType = 'Pre-booked'
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL)), 0),2) 												
									 as EfficienyRate,
									 
									 FORMAT((SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
									 			FROM siforder 
												WHERE siforder.SalesType = 'Pre-booked' AND 
												 siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL), 0)
									as TotalCountBuyingCustomer,
									 
									 
									 FORMAT(IFNULL(
									 (SELECT SUM(siforderitem.InvoiceQuantity) FROM siforder INNER JOIN 
									 siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
									 WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL																					 
									 )
									 
									 , 0),2) 												
									 as TotalInvoiceQty,
									 
									 FORMAT(IFNULL(
									 (SELECT SUM(siforderitem.OrderQuantity) FROM siforder INNER JOIN 
									 siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
									 WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL																					 
									 )
									 , 0),2) 												
									 as TotalOrderQty,
									 
									 
									FORMAT(IFNULL(((SELECT COUNT(*)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0
												AND  siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL)												
									),0),2)									 									    
									as TotalLineFill,
									
									FORMAT(IFNULL(((SELECT COUNT(*)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity = siforderitem.InvoiceQuantity
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL)												
									),0),2)									 									    
									as TotalOrderFill,
									
									FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL),0),2)
									as TotalTimeliness,
									
									
									FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)								 
									 			FROM siforder 
												INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
												WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
												AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity
												AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL),0),2)
									as TotalOTIF
																			                           
									
																			                           
                            FROM siforder
                            INNER JOIN siforderitem 
                            ON siforder.KeyId = siforderitem.ReferenceKeyId
                                                         									 
                            WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL AND siforder.SalesType = 'Pre-booked'
                    ";

        $resultOrderSummaryQuery = DB::select($orderSummaryQuery);
        $resultReturnSummaryQuery = DB::select($returnSummaryQuery);
        $resultMetricSummaryQuery = DB::select($mertricSummaryQuery);



        $order = new Siforder();
        $return = new Siforderreturn();
        return view('app.dashboard')
            // ->with('metric_rates', $order->MetricRates($resultQuery))
            ->with('order', $order)
            ->with('return', $return)
            ->with('company', SystemSettings::first())
            ->with('orderSummary', $resultOrderSummaryQuery)
            ->with('returnSummary', $resultReturnSummaryQuery)
            ->with('metricSummary', $resultMetricSummaryQuery)
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('formats', FormatName::first());
    }

    public function uploadPage()
    {
        return view('app.upload')
            ->with('company', SystemSettings::first())
            ->with('orderProcessedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
            ->with('returnProcessedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('formats', FormatName::first())
            ->with('orderLastUpload', SiforderLastupload::orderBy('id', 'desc')->first())
            ->with('returnLastUpload', SiforderreturnLastupload::orderBy('id', 'desc')->first());
    }


    public function test()
    {

        $function = new SifFunction();
        return view('app.test2')
            ->with('function', $function);
    }


    public function orderPage()
    {
        return view('app.orders')
            ->with('company', SystemSettings::first())
            ->with('orders', Siforder::orderBy('id', 'desc')->paginate(50))
            ->with('orderProcessedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
            ->with('orderOutCount', Siforder::where('Status', 'OUT')->count())
            ->with('orderProcessingCount', Siforder::where('Status', 'PROCESSING')->count())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('formats', FormatName::first());
    }



    public function returnPage()
    {
        return view('app.returns')
            ->with('company', SystemSettings::first())
            ->with('returns', Siforderreturn::orderBy('id', 'desc')->paginate(50))
            ->with('returnProcessedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
            ->with('returnOutCount', Siforderreturn::where('Status', 'OUT')->count())
            ->with('returnProcessingCount', Siforderreturn::where('Status', 'PROCESSING')->count())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('formats', FormatName::first());
    }


    public function orderDetail($keyid)
    {
        $order_details = Siforder::where('KeyId', $keyid);

        if (!$order_details->exists()) {
            return redirect()->back();
        }

        $account = new Sifcustomer();
        $dsp = new Sifdsp();

        return view('app.order-detail')
            ->with('company', SystemSettings::first())
            ->with('order', $order_details->first())
            ->with('account', $account)
            ->with('dsp', $dsp)
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }


    public function editOrder($key_id)
    {
        $order_details = Siforder::where('KeyId', $key_id);

        if (!$order_details->exists()) {
            return redirect()->back();
        }


        return view('app.order-edit')
            ->with('company', SystemSettings::first())
            ->with('order', $order_details->first())
            ->with('orderValidation', SiforderValidation::first())
            ->with('orderHeader',  SiforderHeader::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }



    public function editReturn($key_id)
    {
        $return_details = Siforderreturn::where('KeyId', $key_id);

        if (!$return_details->exists()) {
            return redirect()->back();
        }


        return view('app.return-edit')
            ->with('company', SystemSettings::first())
            ->with('return', $return_details->first())
            ->with('returnValidation', SiforderreturnValidation::first())
            ->with('returnHeader',  SiforderreturnHeader::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }


    public function returnDetail($keyid)
    {
        $return_details = Siforderreturn::where('KeyId', $keyid);

        if (!$return_details->exists()) {
            return redirect()->back();
        }

        $account = new Sifcustomer();
        $dsp = new Sifdsp();

        return view('app.return-detail')
            ->with('company', SystemSettings::first())
            ->with('return', $return_details->first())
            ->with('account', $account)
            ->with('dsp', $dsp)
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }


    public function reportPage()
    {

        return view('app.report')
            ->with('company', SystemSettings::first())
            ->with('all_dsp', Sifdsp::all())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('results', false);
    }



    public function returnReportPage()
    {

        return view('app.return-report')
            ->with('company', SystemSettings::first())
            ->with('all_dsp', Sifdsp::all())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())

            ->with('results', false);
    }


    public function fieldSettings($field)
    {
        $formats = FormatName::first();

        $arrFormat  = json_decode($formats);
        $fieldName = null;
        $selectedField = null;
        $headers = null;
        $validations = null;
        foreach ($arrFormat as $key => $value) {
            if ($field == $value) {
                $fieldName = $formats->$key;
                $selectedField = $key;
            }
        }

        switch ($selectedField) {
            case 'siforder':
                $headers =  json_decode(SiforderHeader::first());
                $validations =  json_decode(SiforderValidation::first());
                break;
            case 'sifdiscount':
                $headers =  json_decode(SifdiscountHeader::first());
                $validations =  json_decode(SifdiscountValidation::first());
                break;
            case 'sifreturn':
                $headers =  json_decode(SiforderreturnHeader::first());
                $validations =  json_decode(SiforderreturnValidation::first());
                break;
            case 'sifitem':
                $headers =  json_decode(SifitemHeader::first());
                $validations =  json_decode(SifitemValidation::first());
                break;
            case 'sifcustomer':
                $headers =  json_decode(SifcustomerHeader::first());
                $validations =  json_decode(SifcustomerValidation::first());
                break;
            case 'sifdsp':
                $headers =  json_decode(SifdspHeader::first());
                $validations =  json_decode(SifdspValidation::first());
                break;
            default:
                return redirect()->back();
                break;
        }

        return view('app.field-settings')
            ->with('company', SystemSettings::first())
            ->with('selectedFieldHeader', $selectedField)
            ->with('formats', $formats)
            ->with('field', $fieldName)
            ->with('headers', $headers)
            ->with('validations', $validations)
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }



    public function companySettingsPage()
    {
        return view('app.company-settings')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }

    public function errorPage()
    {
        return view('app.company-settings')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }


    public function searchPage()
    {


        $keyword = Input::get('keyword');

        if (!isset($keyword))
            return redirect()->back();

        $orders = Siforder::where('Id', $keyword)->orWhere('InvoiceNumber', $keyword)->orWhere('SalesOrderNumber', $keyword)->orWhere('Guid', $keyword)->orWhere('TransactionId', $keyword)->get();
        $returns = Siforderreturn::where('Id', $keyword)->orWhere('CreditMemoNumber', $keyword)->orWhere('InvoiceNumber', $keyword)->orWhere('TransactionId', $keyword)->orWhere('Guid', $keyword)->get();
        $items = Sifitem::where('MaterialCode', $keyword)->orWhere('Description', $keyword)->get();
        $accounts = Sifcustomer::where('AccountId', $keyword)->orWhere('AccountName', $keyword)->get();
        $dsp = Sifdsp::where('AccountId', $keyword)->orWhere('AccountName', $keyword)->orWhere('FirstName', $keyword)->orWhere('LastName', $keyword)->get();

        return view('app.searchPage')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orders', $orders)
            ->with('returns', $returns)
            ->with('items', $items)
            ->with('accounts', $accounts)
            ->with('dsp', $dsp);
    }


    public function aboutSystem()
    {

        return view('app.about-system')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count());
    }



    public function orderUploadHistory()
    {
        return view('app.order-upload-list')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('uploads', SiforderLastupload::orderBy('id', 'desc')->paginate(20));
    }


    public function returnUploadHistory()
    {
        return view('app.return-upload-list')
            ->with('company', SystemSettings::first())
            ->with('formats', FormatName::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('uploads', SiforderreturnLastupload::orderBy('id', 'desc')->paginate(20));
    }

    public function summaryReport()
    {

        $to = date('Y-m-d');
        $from = date('Y-m-d', strtotime(date('Y-m-') . '1'));
        $orderSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforder.InvoiceNumber)),0) as TotalOrders,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity * siforderitem.InvoicePrice), 0),4) as TotalSales,
                            FORMAT((SELECT IFNULL(SUM(InvoiceTotalDiscount), 0) FROM siforder WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ),4) as TotalDiscount,
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'PROCESSING') as countPROCESSING,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'OUT') as countOUT,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforder WHERE Status = 'PROCESSED' AND ErrorMessage IS NOT NULL) as countFAILED                                                            
                            FROM siforder
                            INNER JOIN siforderitem 
                            ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ";

        $returnSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforderreturn.InvoiceNumber)),0) as TotalReturns,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty * siforderreturnitem.Price), 0),4) as TotalReturnAmount,
                            FORMAT(IFNULL(SUM(siforderreturnitem.DiscountAmount),0),4) as TotalDiscount,
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'PROCESSING') as countPROCESSING,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'OUT') as countOUT,                            
                            (SELECT IFNULL(COUNT(*), 0) FROM siforderreturn WHERE Status = 'PROCESSED' AND ErrorMessage IS NOT NULL) as countFAILED                                                            
                            FROM siforderreturn
                            INNER JOIN siforderreturnitem 
                            ON siforderreturn.KeyId = siforderreturnitem.ReferenceKeyId
                            WHERE siforderreturn.Status = 'PROCESSED' AND siforderreturn.ErrorMessage IS NULL
                            AND siforderreturn.ReturnDate BETWEEN " . $from . " AND " . $to . " ";

        $mertricSummaryQuery = "                           
                            SELECT                             
                            FORMAT(IFNULL((SELECT ((SUM(siforderitem.InvoiceQuantity)/SUM(siforderitem.OrderQuantity)) * 100)
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked'						
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "															 										 		
                            ), 0),2) as CaseFillRate,
                                                                  
                   FORMAT(IFNULL((((SELECT COUNT(*)									 
                           FROM siforder 
                           INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                           WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0 
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 												
                                       (SELECT COUNT(*) FROM siforder INNER JOIN 
                                        siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                                        WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                   as LineFillRate,                    
                            
                                                                 
                            FORMAT(IFNULL((((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity  = siforderitem.InvoiceQuantity 
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 
                                       (SELECT COUNT(*) FROM siforder INNER JOIN 
                                        siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                                        WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL																					 
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " )) * 100), 0),2) 												
                               as OrderFillRate,
                            
                                            
                            FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate 
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL 
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 
                                       (SELECT COUNT(*) FROM siforder 
                                       WHERE siforder.ErrorMessage IS NULL AND siforder.`Status` = 'PROCESSED' AND siforder.SalesType = 'Pre-booked'
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                               as Timeliness,
                            
                                                 
                            FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ) / 
                                       (SELECT COUNT(*) FROM siforder 
                                       WHERE siforder.ErrorMessage IS NULL AND siforder.`Status` = 'PROCESSED' AND siforder.SalesType = 'Pre-booked'
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                            as OTIF,
                                                                    
                            FORMAT(IFNULL((count(*) / (SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
                                        FROM siforder 
                                       WHERE siforder.SalesType = 'Pre-booked'
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL 
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")), 0),2) 												
                            as EfficienyRate,
                            
                            FORMAT((SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
                                        FROM siforder 
                                       WHERE siforder.SalesType = 'Pre-booked' AND 
                                        siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "), 0)
                           as TotalCountBuyingCustomer,
                            
                            
                            FORMAT(IFNULL(
                            (SELECT SUM(siforderitem.InvoiceQuantity) FROM siforder INNER JOIN 
                            siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ), 0),2) 												
                            as TotalInvoiceQty,
                            
                            FORMAT(IFNULL(
                            (SELECT SUM(siforderitem.OrderQuantity) FROM siforder INNER JOIN 
                            siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "), 0),2) 												
                            as TotalOrderQty,									 
                            
                           FORMAT(IFNULL(((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0
                                       AND  siforder.SalesType = 'Pre-booked' AND siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")),0),2)									 									    
                           as TotalLineFill,
                           
                           FORMAT(IFNULL(((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity = siforderitem.InvoiceQuantity
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL 
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")),0),2)									 									    
                           as TotalOrderFill,
                           
                           FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "),0),2)
                           as TotalTimeliness,
                                                               
                           FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)								 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity
                                       AND  siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "),0),2)
                           as TotalOTIF
                                                                                                                                  
                   FROM siforder
                   INNER JOIN siforderitem 
                   ON siforder.KeyId = siforderitem.ReferenceKeyId                                                         									 
                   WHERE siforder.Status = 'PROCESSED' AND siforder.ErrorMessage IS NULL AND siforder.SalesType = 'Pre-booked'
                   AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ";



        $resultOrderSummaryQuery = DB::select($orderSummaryQuery);
        $resultReturnSummaryQuery = DB::select($returnSummaryQuery);
        $resultMetricSummaryQuery = DB::select($mertricSummaryQuery);

        return view('app.summary-report')
            ->with('company', SystemSettings::first())
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderSummary', $resultOrderSummaryQuery)
            ->with('returnSummary', $resultReturnSummaryQuery)
            ->with('metricSummary', $resultMetricSummaryQuery)
            ->with('date', date('m/d/Y', strtotime($from)) . ' - ' . date('m/d/Y', strtotime($to)))
            ->with('status', 'PROCESSED')
            ->with('formats', FormatName::first());
    }

    public function processSummaryReport(Request $request)
    {
        $this->validate($request, [
            'dateRange' => 'required',
            'status' => 'required'
        ]);

        $arrDate = array();
        $arrDate = explode(' - ', $request->dateRange);
        $from = "'" . date('Y-m-d', strtotime($arrDate[0])) . "'";
        $to = "'" . date('Y-m-d', strtotime($arrDate[1])) . "'";

        $status = "IS NOT NULL";
        $returnStatus = "IS NOT NULL";
        if ($request->status == 'FAILED') {
            $status = " = 'PROCESSED' AND siforder.ErrorMessage IS NOT NULL";
            $returnStatus = " = 'PROCESSED' AND siforderreturn.ErrorMessage IS NOT NULL";
        } elseif ($request->status == 'PROCESSED') {
            $status = " = 'PROCESSED' AND siforder.ErrorMessage IS NULL";
            $returnStatus = " = 'PROCESSED' AND siforderreturn.ErrorMessage IS NULL";
        } else if ($request->status == "OUT") {
            $status = " = '" . $request->status . "'";
            $returnStatus = " = '" . $request->status . "'";
        }


        $orderSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforder.InvoiceNumber)),0) as TotalOrders,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderitem.InvoiceQuantity * siforderitem.InvoicePrice), 0),4) as TotalSales,
                            FORMAT((SELECT IFNULL(SUM(InvoiceTotalDiscount), 0) FROM siforder WHERE siforder.Status " . $status . "
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ),4) as TotalDiscount
                            FROM siforder
                            INNER JOIN siforderitem 
                            ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.Status " . $status . "
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ";

        $returnSummaryQuery = "
                            SELECT 
                            FORMAT((COUNT(DISTINCT siforderreturn.InvoiceNumber)),0) as TotalReturns,
                            FORMAT(COUNT(*),0) as TotalLineItems,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty), 0),4) as TotalQuantity,
                            FORMAT(IFNULL(SUM(siforderreturnitem.ReturnedQty * siforderreturnitem.Price), 0),4) as TotalReturnAmount,
                            FORMAT(IFNULL(SUM(siforderreturnitem.DiscountAmount),0),4) as TotalDiscount
                            FROM siforderreturn
                            INNER JOIN siforderreturnitem 
                            ON siforderreturn.KeyId = siforderreturnitem.ReferenceKeyId
                            WHERE 
                            siforderreturn.Status " . $returnStatus . "
                            AND siforderreturn.ReturnDate BETWEEN " . $from . " AND " . $to . " ";

        $mertricSummaryQuery = "                           
                            SELECT                             
                            FORMAT(IFNULL((SELECT ((SUM(siforderitem.InvoiceQuantity)/SUM(siforderitem.OrderQuantity)) * 100)
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked'						
                                       AND  siforder.Status " . $status . " AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "															 										 		
                            ), 0),2) as CaseFillRate,
                                                                  
                   FORMAT(IFNULL((((SELECT COUNT(*)									 
                           FROM siforder 
                           INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                           WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0 
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 												
                                       (SELECT COUNT(*) FROM siforder INNER JOIN 
                                        siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                                        WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status " . $status . "
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                   as LineFillRate,                    
                            
                                                                 
                            FORMAT(IFNULL((((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity  = siforderitem.InvoiceQuantity 
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 
                                       (SELECT COUNT(*) FROM siforder INNER JOIN 
                                        siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                                        WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status " . $status . "																					 
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " )) * 100), 0),2) 												
                               as OrderFillRate,
                            
                                            
                            FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate 
                                       AND  siforder.Status " . $status . " 
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ") / 
                                       (SELECT COUNT(*) FROM siforder 
                                       WHERE  siforder.`Status` " . $status . " AND siforder.SalesType = 'Pre-booked'
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                               as Timeliness,
                            
                                                 
                            FORMAT(IFNULL((((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND  siforder.Status " . $status . "
                                       AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ) / 
                                       (SELECT COUNT(*) FROM siforder WHERE siforder.`Status` " . $status . " AND siforder.SalesType = 'Pre-booked'
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")) * 100), 0),2) 												
                            as OTIF,
                                                                    
                            FORMAT(IFNULL((count(*) / (SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
                                        FROM siforder 
                                       WHERE siforder.SalesType = 'Pre-booked'
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")), 0),2) 												
                            as EfficienyRate,
                            
                            FORMAT((SELECT COUNT(DISTINCT  siforder.AccountReferenceId)									 
                                        FROM siforder 
                                       WHERE siforder.SalesType = 'Pre-booked' AND 
                                        siforder.Status " . $status . "
                                        AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "), 0)
                           as TotalCountBuyingCustomer,
                            
                            
                            FORMAT(IFNULL(
                            (SELECT SUM(siforderitem.InvoiceQuantity) FROM siforder INNER JOIN 
                            siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status " . $status . "
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ), 0),2) 												
                            as TotalInvoiceQty,
                            
                            FORMAT(IFNULL(
                            (SELECT SUM(siforderitem.OrderQuantity) FROM siforder INNER JOIN 
                            siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId
                            WHERE siforder.SalesType = 'Pre-booked' AND siforder.Status " . $status . "
                            AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "), 0),2) 												
                            as TotalOrderQty,									 
                            
                           FORMAT(IFNULL(((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforderitem.OrderQuantity > 0 AND siforderitem.InvoiceQuantity > 0
                                       AND  siforder.SalesType = 'Pre-booked' AND siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")),0),2)									 									    
                           as TotalLineFill,
                           
                           FORMAT(IFNULL(((SELECT COUNT(*)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforderitem.OrderQuantity = siforderitem.InvoiceQuantity
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . ")),0),2)									 									    
                           as TotalOrderFill,
                           
                           FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)									 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "),0),2)
                           as TotalTimeliness,
                                                               
                           FORMAT(IFNULL((SELECT COUNT(DISTINCT siforder.InvoiceNumber)								 
                                        FROM siforder 
                                       INNER JOIN siforderitem ON siforder.KeyId = siforderitem.ReferenceKeyId 
                                       WHERE siforder.SalesType = 'Pre-booked' AND siforder.InvoiceDate  = siforder.RequestedDeliveryDate
                                       AND siforderitem.InvoiceQuantity = siforderitem.OrderQuantity
                                       AND  siforder.Status " . $status . "
                                       AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . "),0),2)
                           as TotalOTIF
                                                                                                                                  
                   FROM siforder
                   INNER JOIN siforderitem 
                   ON siforder.KeyId = siforderitem.ReferenceKeyId                                                         									 
                   WHERE siforder.Status " . $status . " AND siforder.SalesType = 'Pre-booked'
                   AND siforder.InvoiceDate BETWEEN " . $from . " AND " . $to . " ";



        $resultOrderSummaryQuery = DB::select($orderSummaryQuery);
        $resultReturnSummaryQuery = DB::select($returnSummaryQuery);
        $resultMetricSummaryQuery = DB::select($mertricSummaryQuery);



        return view('app.summary-report')
            ->with('company', SystemSettings::first())
            ->with('date', date('m/d/Y', strtotime($to)) . ' - ' . date('m/d/Y', strtotime($from)))
            ->with('orderSummary', $resultOrderSummaryQuery)
            ->with('returnSummary', $resultReturnSummaryQuery)
            ->with('metricSummary', $resultMetricSummaryQuery)
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('date', $request->dateRange)
            ->with('status', $request->status)
            ->with('formats', FormatName::first());
    }
}

<?php

namespace App;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
class Siforder extends Model
{
    //
    protected $fillable = [
        'Guid', 'Id', 'SalesType', 'AccountReferenceId', 'SASId', 'DSPId', 'SalesOrderNumber', 'OrderDate', 'OrderTotal',
        'OrderTotalDiscount', 'RequestedDeliveryDate', 'PaymentTerm', 'InvoiceNumber', 'InvoiceDate', 'InvoiceTotal', 'InvoiceTotalDiscount',
        'OrderDiscountFromTotal1', 'IsOrderDiscountFromTotal1Percent', 'OrderDiscountFromTotal1Type', 'OrderDiscountFromTotal1Amount', 'OrderDiscountFromTotal2',
        'IsOrderDiscountFromTotal2Percent', 'OrderDiscountFromTotal2Type', 'OrderDiscountFromTotal2Scheme', 'OrderDiscountFromTotal2Amount', 'OrderDiscountFromTotal3',
        'IsOrderDiscountFromTotal3Percent', 'OrderDiscountFromTotal3Type', 'OrderDiscountFromTotal3Scheme', 'OrderDiscountFromTotal3Amount', 'OrderDiscountFromTotal4',
        'IsOrderDiscountFromTotal4Percent', 'OrderDiscountFromTotal4Type', 'OrderDiscountFromTotal4Scheme', 'OrderDiscountFromTotal4Amount', 'InvoiceDiscountFromTotal1',
        'IsInvoiceDiscountFromTotal1Percent', 'InvoiceDiscountFromTotal1Type', 'InvoiceDiscountFromTotal1Amount', 'InvoiceDiscountFromTotal2', 'IsInvoiceDiscountFromTotal2Percent',
        'InvoiceDiscountFromTotal2Type', 'InvoiceDiscountFromTotal2Scheme', 'InvoiceDiscountFromTotal2Amount', 'InvoiceDiscountFromTotal3', 'IsInvoiceDiscountFromTotal3Percent',
        'InvoiceDiscountFromTotal3Type', 'InvoiceDiscountFromTotal3Scheme', 'InvoiceDiscountFromTotal3Amount', 'InvoiceDiscountFromTotal4', 'IsInvoiceDiscountFromTotal4Percent', 'InvoiceDiscountFromTotal4Scheme',
        'InvoiceDiscountFromTotal4Amount', 'InvoiceDiscountFromTotal4Type', 'DiscountFromTotal', 'IsDiscountFromTotalPercent', 'Status', 'ErrorMessage', 'TransactionId', 'OrderNo', 'IsDelete', 'CreatedByID', 'EncodedDate', 'KeyId'
    ];

    protected $table = "siforder";
    public $timestamps = false;
    protected $primaryKey = "Guid";
    public $incrementing = false;


    public static function orderItems($invoiceNumber)
    {
        return self::join('siforderitem', 'siforderitem.OrderReferenceGuid', 'siforder.Guid')
                ->where('siforder.InvoiceNumber', $invoiceNumber);
    }
    

    public static function findInvoiceWithMatcode($invoiceNumber, $matCode)
    {

        $order = self::join('siforderitem', 'siforderitem.OrderReferenceGuid', 'siforder.Guid')
                ->where('siforder.InvoiceNumber', $invoiceNumber)
                ->where('siforderitem.ProductId', $matCode)
                ->where('siforder.Status', 'OUT')
                ;        
            
        if($order->count() > 0)
            return $order;
        else 
            return false;

    }

    

    public function order_items()
    {
        return $this
            ->join('siforderitem', 'siforderitem.OrderReferenceGuid', 'siforder.Guid')
            ->join('sifitem', 'siforderitem.ProductId', 'sifitem.MaterialCode')
                ->where('siforder.KeyId', $this->KeyId)->get();
    }

    public function items()
    {
        return $this
        ->join('siforderitem', 'siforderitem.ReferenceKeyId', 'siforder.KeyId')
            ->where('siforder.KeyId', $this->KeyId)->get();
    
    }

    /**
     * getTotalPerMonth
     * @param dates determine what specific date ( array )
     * use to get the total Order Per Month
     * return resultArray ( array ) / count of order per month
     */
    public function getTotalPerMonth($dates)
    {
        $count1 = 0;
        $count2 = 0;
        $count3 = 0;
        $count4 = 0;
        $count5 = 0;
        $count6 = 0;
        $count7 = 0;

        $count1 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[0] . "-1" , $dates[0] . "-31"])->count();
        $count2 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[1] . "-1"  , $dates[1] . "-31"])->count();
        $count3 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[2] . "-1" , $dates[2] . "-31"])->count();
        $count4 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[3] . "-1" , $dates[3] . "-31"])->count();
        $count5 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[4] . "-1" , $dates[4] . "-31"])->count();
        $count6 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[5] . "-1" , $dates[5] . "-31"])->count();
        $count7 = $this->select('Guid')->where('Status', 'PROCESSED')->whereBetween('InvoiceDate', [ $dates[6] . "-1" , $dates[6] . "-31"])->count();

          $resultArray = array(
            'month1' => $count1,
            'month2' => $count2,
            'month3' => $count3,
            'month4' => $count4,
            'month5' => $count5,
            'month6' => $count6,
            'month7' => $count7

        );

        return $resultArray;


    }


        
    public function MetricRates($orders){

        $totalOrderQty = 0;
        $totalInvoiceQty = 0;
        $totalOrderItems = count($orders);
        $totalLineFill = 0;
        $totalOrderFill = 0;
        $totalOTIF = 0;
        $totalTimeless = 0;
        $totalOrders =  0;
        $customers = array();
        $metricDetail = array();
        $count = 0;
        foreach($orders as $order)
        {                         
            // $order = Siforder::find($o->Guid);
            // $totalOrderItems = $totalOrderItems + count($order->items()); 
            $keyCustomer = $this->isExist($order->AccountReferenceId, $customers, 'account_id');
            if($keyCustomer == false)
            {
                $customers[] = ['account_id' => $order->AccountReferenceId];
            }            

            
            $key = $this->isExist($order->InvoiceNumber, $metricDetail, 'invoice_number');
            if($key != false){
                $metricDetail[$key]['total_order_qty'] = $metricDetail[$key]['total_order_qty'] + $order->OrderQuantity;
                $metricDetail[$key]['total_inv_qty'] = $metricDetail[$key]['total_inv_qty'] + $order->InvoiceQuantity;
                $metricDetail[$key]['line_fill_count'] = $metricDetail[$key]['line_fill_count'] + $this->isLineFill($order->OrderQuantity , $order->InvoiceQuantity);
                $metricDetail[$key]['order_fill_count'] = $metricDetail[$key]['order_fill_count'] + $this->isOrderFill($order->OrderQuantity , $order->InvoiceQuantity);                                                            
            }else{
                $metricDetail[] = [
                    'total_order_qty' => $order->OrderQuantity,
                    'invoice_number' => $order->InvoiceNumber,
                    'total_inv_qty' => $order->InvoiceQuantity,
                    'line_fill_count' => $this->isLineFill($order->OrderQuantity , $order->InvoiceQuantity),
                    'order_fill_count' => $this->isOrderFill($order->OrderQuantity , $order->InvoiceQuantity),
                    'rdd' => $order->RequestedDeliveryDate,
                    'invoice_date' => $order->InvoiceDate
                ];                       
            }    

        }
        

        $totalOrders = count($metricDetail) - 1;
        $do = false;
        foreach($metricDetail as $value)
        {
                $totalOrderQty = $totalOrderQty + $value['total_order_qty'];
                $totalInvoiceQty = $totalInvoiceQty + $value['total_inv_qty'];           
                $totalLineFill = $totalLineFill + $value['line_fill_count'];
                $totalOrderFill = $totalOrderFill + $value['order_fill_count'];

                if($do != false){
                    if($this->isTimeliness($value['invoice_date'], $value['rdd'])){
                        $totalTimeless = $totalTimeless + 1;
                        
                        if($value['line_fill_count'] == $value['order_fill_count'])
                            $totalOTIF++;
                    }
                }else 
                    $do = true;
        }


        return [
            'case_fill_rate' => number_format($this->getCaseFill($totalOrderQty, $totalInvoiceQty), 2, '.', ','),
            'line_fill_rate' =>  number_format($this->getLineFillRate($totalLineFill, $totalOrderItems), 2, '.', ','),
            'order_fill_rate' => number_format($this->getOrderFillRate($totalOrderFill, $totalOrderItems), 2, '.', ','),
            'timeliness' => number_format($this->getTimelessRate($totalTimeless, $totalOrders), 2, '.', ','),
            'otif' => number_format($this->getOTIFrate($totalOTIF, $totalOrders), 2, '.', ','),
            'efficiency_rate' =>  number_format($this->getEfficiencyRate(count($customers), $totalOrderItems), 2, '.', ','),
            'total_account' => count($customers),
            'total_orders' => $totalOrders,
            'total_inv_qty' => $totalInvoiceQty,
            'total_order_qty' => $totalOrderQty,
            'total_timeliness' => $totalTimeless,
            'total_otif' => $totalOTIF,
            'total_order_fill' => $totalOrderFill,
            'total_line_fill' => $totalLineFill,
            'total_order_items' => $totalOrderItems
        ];
                

    }


    public function getEfficiencyRate($totalAccount, $totalOrderItems)
    {
        $totalAccount = $totalAccount ? $totalAccount : 1;
        return  $totalOrderItems / $totalAccount;
    }

    public function getTimelessRate($totalTimeless, $totalOrder)
    {
        $totalOrder = $totalOrder ? $totalOrder : 1;

        return ($totalTimeless / $totalOrder) * 100;
    }

    public function getOTIFrate($totalOTIF, $totalOrder)
    {
        $totalOrder = $totalOrder ? $totalOrder : 1;

        return ($totalOTIF / $totalOrder) * 100;
    }

    public function getLineFillRate($totalLineFill, $totalOrderItems)
    {
        $totalOrderItems = $totalOrderItems ? $totalOrderItems : 1;
        return ($totalLineFill / $totalOrderItems) * 100;
    }

    public function getOrderFillRate($totalOrderFill, $totalInvoiceItems)
    {
        $totalInvoiceItems = $totalInvoiceItems ? $totalInvoiceItems : 1;
        return ($totalOrderFill / $totalInvoiceItems) * 100;
    }

 
    public function isLineFill($OrderQuantity , $InvoiceQuantity)
    {      
        return $OrderQuantity > 0 && $InvoiceQuantity > 0 ? 1 : 0;
    }


    public function isOrderFill($OrderQuantity , $InvoiceQuantity)
    {       
        return ($OrderQuantity == $InvoiceQuantity) && ($InvoiceQuantity > 0) ? 1 : 0;        
    }



    public function isOTIF($InvoiceDate, $RDD)
    {
        return $InvoiceDate != $RDD ? 1 : 0;
            
    }

    public function isTimeLiness($InvoiceDate, $RDD)
    {
        return $InvoiceDate == $RDD ? 1 : 0;
    }

    public function getCaseFill($orderQty, $invoiceQty)
    {
        $caseFill = 0;
        $orderQty = $orderQty ? $orderQty : 1;
        $caseFill = ($invoiceQty / $orderQty) * 100;
        return $caseFill;
    }

 
    public function isExist($keyword, $arrFile, $val)
    {            
        $output = false;
        foreach ($arrFile as $key => $value) {
                if(strcmp($keyword, $value[$val]) == 0)
                    $output = $key;
            }                                  
        return $output;                      
    }

 



}

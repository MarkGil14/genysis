<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Siforderitem extends Model
{
    protected $fillable = [
        'Guid', 'OrderReferenceGuid', 'Id', 'OrderId', 'ProductId', 'ConversionId', 'OrderQuantity', 'OrderPrice', 'OrderDiscount1', 'OrderDiscount1Percent',
        'OrderDiscount1Type', 'OrderDiscount1Amount', 'OrderDiscount2', 'IsOrderDiscount2Percent', 'OrderDiscount2Type', 'OrderDiscount2Scheme', 'OrderDiscount2Amount', 'OrderDiscount3', 'IsOrderDiscount3Percent', 'OrderDiscount3Type', 'OrderDiscount3Scheme',
        'OrderDiscount3Amount', 'OrderDiscount4', 'IsOrderDiscount4Percent', 'OrderDiscount4Type', 'OrderDiscount4Scheme', 'OrderDiscount4Amount', 'InvoiceQuantity', 'InvoicePrice', 'InvoiceDiscount1', 'IsInvoiceDiscount1Percent', 'InvoiceDiscount1Type', 'InvoiceDiscount1Amount',
        'InvoiceDiscount2', 'IsInvoiceDiscount2Percent', 'InvoiceDiscount2Type', 'InvoiceDiscount2Scheme', 'InvoiceDiscount2Amount', 'InvoiceDiscount3', 'IsInvoiceDiscount3Percent', 'InvoiceDiscount3Type', 'InvoiceDiscount3Scheme', 'InvoiceDiscount3Amount', 'InvoiceDiscount4', 'IsInvoiceDiscount4Percent',
        'InvoiceDiscount4Type', 'InvoiceDiscount4Scheme', 'InvoiceDiscount4Amount', 'WeightUOM', 'ActualWeightQuantity', 'TransactionId', 'OrderNo', 'IsDelete' , 'ReasonUnequalOrderInvoice', 'Status', 'ErrorMessage', 'ReferenceKeyId', 'backorder'
    ];
    protected $table = "siforderitem";
    public $timestamps = false;
    protected $primaryKey = "Guid";
    public $incrementing = false;

    public function order()
    {
        return $this->belongsTo('App\Siforder');
    }
    
    
    public function order_detail()
    {
        return $this
            ->join('siforder', 'siforderitem.OrderReferenceGuid', 'siforder.Guid')
            ->where('siforderitem.Guid',$this->Guid)->first();
    }


}

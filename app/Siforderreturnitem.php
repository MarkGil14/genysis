<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class siforderreturnitem extends Model
{


    protected $fillable = [
        'Guid', 'OrderReturnGuid', 'OrderReturnId', 'ProductId', 'ConversionId', 'ReturnedQuantity', 'Price', 'DiscountAmount', 'Condition', 'ReturnType',
        'ReasonOfRejection', 'Status', 'ErrorMessage', 'TransactionId'
        ];
 
        
    public $timestamps = false;
    protected $primaryKey = "Guid";
    public $incrementing = false;    
    protected $table = "siforderreturnitem";
 

    public function return_detail()
    {
        return $this
            ->join('siforderreturn', 'siforderreturnitem.OrderReturnGuid', 'siforderreturn.Guid')
            ->where('siforderreturnitem.ReferenceKeyId', $this->ReferenceKeyId)->first();
    }


}

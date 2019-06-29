<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Siforderreturn extends Model
{

    //

    protected $fillable = [
        'Guid', 'Id', 'SASId', 'DSPId', 'AccountId', 'TypeOfReturn', 'CreditMemoNumber', 'ReturnDate', 
        'InvoiceNumber', 'Status', 'ErrorMessage' , 'TransactionId', 'ReasonOfReturn','KeyId'
        ];
 
        
    public $timestamps = false;
    protected $primaryKey = "Guid";
    public $incrementing = false;    
    protected $table = "siforderreturn";
 


    public function return_items()
    {
        return $this
            ->join('siforderreturnitem', 'siforderreturnitem.OrderReturnGuid', 'siforderreturn.Guid')
            ->join('sifitem', 'siforderreturnitem.ProductId', 'sifitem.MaterialCode')
                ->where('siforderreturn.KeyId', $this->KeyId)->get();
    }



    public function items()
    {
        return $this
        ->join('siforderreturnitem', 'siforderreturnitem.ReferenceKeyId', 'siforderreturn.KeyId')
            ->where('siforderreturn.KeyId', $this->KeyId)->get();
    
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


        $count1 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[0] . "-1" , $dates[0] . "-31"])->count();
        $count2 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[1] . "-1"  , $dates[1] . "-31"])->count();
        $count3 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[2] . "-1" , $dates[2] . "-31"])->count();
        $count4 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[3] . "-1" , $dates[3] . "-31"])->count();
        $count5 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[4] . "-1" , $dates[4] . "-31"])->count();
        $count6 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[5] . "-1" , $dates[5] . "-31"])->count();
        $count7 = $this->select('Guid')->whereBetween('ReturnDate', [ $dates[6] . "-1" , $dates[6] . "-31"])->count();

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

}

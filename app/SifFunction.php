<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SifFunction extends Model
{
    //

    public static function hasMultipleInvoice($InvoiceNumber)
    {
        return count(explode('-', $InvoiceNumber)) > 1 ? true : false;
    }

    
    public static function generateSONumber($min, $max)
    {
        $length = 2;
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        return $randomString . '-' . rand($min, $max);

    }

    public static function seperateMultipleInvoice($InvoiceNumber)
    {
        $invoiceArr = array();
        return $invoiceArr = explode('-', $InvoiceNumber);
    }

    public static function isExists($keyword, $arrFile)
    {            
        $output = false;
        foreach ($arrFile as $key => $value) {
                if($keyword === $value['InvoiceNumber'])
                    $output = $key;
        }                                  
        return $output;                      
    }

 

    public static function generateGuid()
    {
        $length = 10;
        $characters = '0123456789abcdefghijklmonprstuvwxyz';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
    
        return 'LTC' . round(microtime(true)) . $randomString   . date('Ymd');

    }

    public static function generateRandomID($min, $max)
    {
        return rand($min, $max) . round(microtime(true));
    }

    public static function calculateTotalPrice($qty , $price)
    {
        (float)$totalAmount = abs($qty) * abs($price);
        return $totalAmount > 0 ? $totalAmount : 0;        
    }


    public static function formatDate($date, $format = 'Y-m-d')
    {
        $date = date($format, strtotime($date));
        return $date;
    
    }

    public static function makeSlug($string)
    {
        $string1 = strtolower(str_replace(" ", "_", $string));
        return preg_replace("/\s+/", "", $string1);    
    }


    public static function isDate($date)
    {
    
        $date = date("Y-m-d", strtotime($date));
        return $date !== '1970-01-01' ? $date : '1970-01-01';
    
    }    

    public static function amountNoCommaFormat($amount)
    {
        (float)$new_amount = str_replace(',', '', $amount);
        return $new_amount;

    }

    public static function  isContainsWord($wordtosearch, $word)
    {
        return preg_match("/" . $wordtosearch . "/", $word);
    }

    public static  function isNumber($value)
    {

        return is_numeric($value) ? true : false;

    }

    public static function has($element)
    {
        if(count($element) > 0)
            return true;
        else 
            return false;
    }


    public static function seperateStringAndNumber($value)
    {
        $word = null;
        $number = null;
        for($i = 0; $i < strlen($value); $i++)
        {
            if(self::isNumber($value[$i]))
            {
                $number = $number.$value[$i];
            }else {
                $word = $word.$value[$i];
            }
        }
    
        return array([
            'letter' => $word,
            'number' => $number
        ]);
    
    }
    

}

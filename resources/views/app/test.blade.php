<?php 
$multipleInvoice = "12234-40";

$invoiceArr = seperateMultipleInvoice($multipleInvoice);

$result= storeMultipleInvoice($invoiceArr);

function storeMultipleInvoice($invoiceArr)
    {
        echo $invoiceArr[0].'<br>'; //first invoice
        $invoiceArr[0][strlen($invoiceArr[0]) -2] = $invoiceArr[1][0];
        $invoiceArr[0][strlen($invoiceArr[0]) - 1] = $invoiceArr[1][1];
        
        echo $invoiceArr[0]; //second invoice
        
            /**
            * generate new Guid , KeyId and item Guid
            * then store it in the array (order header and item)
            * same SO but unique Invoice number , Guid and KeyId are needed
            */
            echo  $invoiceArr[0] .'<br>';

            $invoiceArr[0] = $invoiceArr[0] + 1; //increment the invoice number                 
            $invoiceNumber = $invoiceArr[0]; //copy the current invoice number

        return $orderArr;

    }






    function seperateStringAndNumber($value)
    {
        $word = null;
        $number = null;
        for($i = 0; $i < strlen($value); $i++)
        {
            if(isNumber($value[$i]))
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

    function isNumber($value)
    {

        return is_numeric($value) ? true : false;

    }

    function seperateMultipleInvoice($InvoiceNumber)
    {
        $invoiceArr = array();
        return $invoiceArr = explode('-', $InvoiceNumber);
    }

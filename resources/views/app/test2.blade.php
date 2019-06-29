<?php 
dashboard 
    -overall summary (orders, returns)
    -optimizing data count
    -company details 
    -
upload 
    -merging the template of discount and orders in uploading 
    -orders and returns last upload summary
    -orders and returns last upload page list
    -if sales type is direct invoice , the order price and qty become 0




todo {
    -after uploading , the upload button will fade and refresh button will replace 
    -last upload data overview (dashboard , sales order, returns)
    -upload detail history
}




    // calculateBox(28);
// 28 / 2  = 14
// 14 / 2 = 7

// function calculateBox($inputBox)
//     {
//         //this will the remaining box or the modulus
//         (int)$remainingBox = 0;
//         (int)$heigth = 0; //the number of heigth per box

//         $half = ($inputBox / 2);

//         $primeFactor = array();
//         $value = $inputBox;
//         for($dividend = 2; $dividend < $inputBox; $dividend++ )
//         {

//             if($inputBox % $dividend == 0)
//             {
//             }else{
//                 echo $dividend."<br>";
//             }


//         }

     
        // echo json_encode($primeFactor);


        // $remainingBox = $inputBox % 2;
        // for($heigth = 0; $heigth < (int)$side; $heigth++)
        // {
        //     for($length = 0; $length < $side; $length++)
        //     {

        //     }
        // }

        // if($remainingBox > 0)
        // {
        //     for($x = 0; $x < $remainingBox; $x++)
        //     {
        //     }            
        // }
    // }

//     $arrData = array();

//     $arrData[] = [
//         'sample1' => 'mark',
//         'sample2' => 'baterna',
//         'sample3' => 'pogi'
//     ];

//     $arrData[] = [
//         'sample1' => 'mark2',
//         'sample2' => 'baterna2',
//         'sample3' => 'pogi2'
//     ];

//     echo 'old : '.json_encode($arrData);


//     function replaceValueInArray($arrData,$selectedKey,$objectValue,$newValue)
//     {
//         $newArray = [];
//         foreach ($arrData[$selectedKey] as $key => $value) {
//             if($objectValue === $key)
//                echo $arrData[$selectedKey][$key] = $newValue;
//             else {

//             }

//                 echo $value."<br>";
//         }    
        
//         $arrData[$selectedKey] = $newArray;

//         return $arrData;
//     }

//     $new = replaceValueInArray($arrData , 1 , 'sample2', 'tangina bago to');
//     //
// echo    $arrData[1]['sample3'];
//     echo json_encode($arrData);
    // echo json_encode($arrData[1]);


    // store siforder with single invoice
    // store siforderitem
    // incrementing the total amount (qty * price)
    // get the matcode of the item
    // store siforder with multiple invoice
    //discount header
    //discount item
    // customize validation with error message 
    //select file 
    //then process 
    //validate each 
    //if has error
    // then store in errormessage
    //then ignore save 
    // view all error after the uploading process with filename



    // customize filename (csv / xlsx)
    // customize header name
    // view order / return in datatable 
    // filter order / return in datatable
    // view orderitem / return item in datatable 
    // order & return overview
    // update order , orderitem , return , return item 


$invoiceArr = array();
$invoiceArr = explode('-', '1689299-30');

multipleInvoice($invoiceArr);

function multipleInvoice($invoiceArr){

    //store second invoice
    $suffixLength = strlen($invoiceArr[1]);

    for($i = 0; $i < $suffixLength; $i++){

        $invoiceArr[0][strlen($invoiceArr[0]) - ($suffixLength - $i)] = $invoiceArr[1][$i];
        // $invoiceArr[0][strlen($invoiceArr[0]) -3] = $invoiceArr[1][0];
        // $invoiceArr[0][strlen($invoiceArr[0]) -2] = $invoiceArr[1][1];
        // $invoiceArr[0][strlen($invoiceArr[0]) - 1] = $invoiceArr[1][2];
    }

    echo $invoiceArr[0];

}
    

function fillRate(){

    $totalOrderQty = 0;
    $totalInvoiceQty = 0;
    $totalOrderItems = 0;
    $totalLineFill = 0;
    $totalOrderFill = 0;
    $totalOTIF = 0;
    $totalTimeless = 0;
    $totalOrders = 0;

    foreach($orders as $order)
    {        
        $qtyArr = array();
      
        $totalOrderItems = $totalOrderItems + count($order->orderitems); 

        $totalOTIF = $totalOTIF + $this->isOTIF($$item->InvoiceDate, $item->OrderDate);
        $totalTimeless = $totalTimeless + $this->isTimeless($$item->InvoiceDate, $item->OrderDate);

        for($order->orderitems as $item)
        {

            $totalOrderQty = $totalOrderQty + $item->OrderQuantity;
            $totalInvoiceQty = $totalInvoiceQty + $item->InvoiceQuantity;   
       
            $totalLineFill = $totalLineFill + $this->isLineFill($item->OrderQuantity , $item->InvoiceQuantity);
            if( $this->isOrderFill($item->OrderQuantity , $item->InvoiceQuantity)){
                $totalOTIF = $totalOTIF + $this->isOTIF($$item->InvoiceDate, $item->OrderDate);
                $totalOrderFill = $totalOrderFill++;
            }

        }
        

    }

    $this->getCaseFill($totalOrderQty, $totalInvoiceQty);
    $this->getLineFillRate($totalLineFill, $totalOrderItems);
    $this->getOrderFillRate($totalOrderFill, $totalInvoiceItems);
    $this->getOTIFrate($totalOTIF, $totalOrders);
    $this->getTimelessRate($totalTimeless, $totalOrders);

}

function getTimelessRate($totalTimeless, $totalOrder)
{
    return ($totalTimeless / $totalOrder) * 100;
}

function getOTIFrate($totalOTIF, $totalOrder)
{
    return ($totalOTIF / $totalOrder) * 100;
}

function getLineFillRate($totalLineFill, $totalOrderItems)
{
    return ($totalLineFill / $totalOrderItems) * 100;
}

function getOrderFillRate($totalOrderFill, $totalInvoiceItems)
{
    return ($totalOrderFill / $totalInvoiceItems) * 100;
}

 

 
    function isLineFill($OrderQuantity , $InvoiceQuantity)
    {      
        return $OrderQuantity > 0 && $InvoiceQuantity > 0 ? 1 : 0;
    }


    function isOrderFill($OrderQuantity , $InvoiceQuantity)
    {       
        return $OrderQuantity === $InvoiceQuantity) && ($InvoiceQuantity > 0) ? 1 : 0;        
    }



    function isOTIF($InvoiceDate, $OrderDate)
    {
        return $InvoiceDate != $OrderDate ? 1 : 0;
            
    }

    function isTimeLiness()
    {
        return $order->InvoiceDate === $order->OrderDate ? 1 : 0;
    }

    function getCaseFill($orderQty, $invoiceQty)
    {
        $caseFill = 0;
        $caseFill = ($invoiceQty / $orderQty) * 100;
        return $caseFill;
    }

 

 





?>

<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Siforderreturn;
use App\Siforderreturnitem;
use App\Sifdsp;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use App\FormatName;
use App\Siforder;
use App\SystemSettings;
use App\SifFunction;
use Session;
use App\SiforderreturnHeader;
use App\RuleValidation;
use App\SiforderreturnValidation;
use App\Sifitem;



class ReturnsController extends Controller
{
    //

    
    public function getFilterReturnReport(Request $request)
    {
        $this->validate($request, [
            'time' => 'required'
        ]); 

        $arrQuery = [];

        if(isset($request->dsp))
            $arrQuery[] = " siforderreturn.DSPId = '".$request->dsp."' ";

        if(isset($request->status))
        {
            if($request->status === 'FAILED')
                $arrQuery[] = " siforderreturn.ErrorMessage IS NOT NULL ";
            else
                $arrQuery[] = " siforderreturn.Status = '".$request->status."' ";
            
        }
        $now = date('Y-m-d');
        $arrQuery[] = " siforderreturn.EncodedDate BETWEEN DATE_ADD('".$now."', ".$request->time.") AND '".$now."' ";

        $filterQuery = "SELECT
                         siforderreturn.Status as ReturnStatus, siforderreturn.ErrorMessage as ReturnErrorMessage , siforderreturnitem.Status as ReturnItemStatus, siforderreturnitem.ErrorMessage as ReturnItemErrorMessage,  siforderreturnitem.Id as ItemSifId , siforderreturn.* , siforderreturnitem.* 
                        FROM siforderreturn INNER JOIN siforderreturnitem ON siforderreturn.KeyId = siforderreturnitem.ReferenceKeyId WHERE ".implode(' AND ' , $arrQuery);


        $resultQuery = DB::select($filterQuery);

        return view('app.return-report')
            ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
            ->with('all_dsp', Sifdsp::all())
            ->with('company', SystemSettings::first())    
            ->with('formats', FormatName::first())                
            ->with('results', $resultQuery);
        

    }



    
    public function filterReturns()
    {
        $arrQuery = [];
 
        if(!empty(Input::get('status')))
        {
            if(Input::get('status') === 'FAILED')
                $arrQuery[] = " siforderreturn.ErrorMessage IS NOT NULL AND siforderreturn.Status = 'PROCESSED' ";
            else
                $arrQuery[] = " siforderreturn.Status = '".Input::get('status')."' AND siforderreturn.ErrorMessage IS NULL ";
            
        }

        $now = date('Y-m-d');
        if(Input::get('time'))
            $arrQuery[] = " siforderreturn.EncodedDate BETWEEN DATE_ADD('".$now."', ".Input::get('time').") AND '".$now."' ";
         
            $filterQuery = "SELECT * FROM siforderreturn WHERE ".implode(' AND ' , $arrQuery)." order by siforderreturn.Guid desc";

        $resultQuery = DB::select($filterQuery);
        
        return view('app.returns-filter')
                ->with('returns', $resultQuery)        
                ->with('returnProcessedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', NULL)->count())
                ->with('returnOutCount', Siforderreturn::where('Status', 'OUT')->count())
                ->with('returnProcessingCount', Siforderreturn::where('Status', 'PROCESSING')->count())
                ->with('returnFailedCount', Siforderreturn::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
                ->with('orderFailedCount', Siforder::where('Status', 'PROCESSED')->where('ErrorMessage', '!=', NULL)->count())
                ->with('formats', FormatName::first())                
                ->with('company', SystemSettings::first())    
                ; 

    }


    public function deleteReturn(Request $request)
    {
        
        $this->validate($request, [
            'keyid' => 'required'
        ]);

        $result2 = Siforderreturnitem::where('ReferenceKeyId', $request->keyid)->delete();
        if(!$result2)
            exit('false');

        $result1 = Siforderreturn::where('KeyId', $request->keyid)->delete();        
        if(!$result1)
            exit('false');
    }



    
    public function changeStatus($keyid, $status)
    {        

        Siforderreturn::where('KeyId', $keyid)->update(['Guid' =>  SifFunction::generateGuid() , 'Status' => $status, 'ErrorMessage' => NULL]);
        $return = Siforderreturn::where('KeyId', $keyid)->first();
        
        $returnitems = $return->items();

        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = $status;
            $item->ErrorMessage = NULL;
            $item->save();
        }

        Session::flash('success', 'Return Status Was Successfully Changed to '.$status);
        return redirect()->back();

    }


    
    public function updateReturn(Request $request)
    {

        $errors = $this->validateRows($request);
        
        //if has an error found
        //then exit and return all the error messages
        if(count($errors) > 0){          
            Session::flash('error_data', $errors);
            return redirect()->back();
        }         

        $returnInfo = null;
        $returnInfo = Siforderreturn::where('KeyId', $request->keyid)->first();
        if(is_null($returnInfo))                
            return redirect()->back();

        $return = Siforderreturn::find($returnInfo->Guid);        
        $return->AccountId = $request->account_id;
        $return->InvoiceNumber = $request->invoice_number;
        $return->SASId = $request->sas_id;
        $return->DSPId = $request->dsp_id;
        $return->TypeOfReturn = $request->type_of_return;
        $return->CreditMemoNumber = $request->credit_memo_number;
        $return->ReturnDate = $request->return_date;
        $return->ReasonOfReturn = $request->reason_of_return;    
        $return->TransactionId = $request->transaction_id;
        $return->Status = 'OUT';
        $return->ErrorMessage = null;
        $return->Guid  = SifFunction::generateGuid();

        $result = $return->save();
        
        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        if($result): 
            Session::flash('success', 'Returns was Successfully Updated');
        else:
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;


        return redirect()->back();

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
        $validateOrder->headerRules = SiforderreturnValidation::first();


        $headerArr = self::getHeaderRow(); //get the header row of an every data

 
            $fieldErrors = false;
            /**
             * $key = header of an table
             * $value = the value of an header
             */
            foreach($headerArr as $key => $value): 

                // loop one by one
                // and validate 
                   
                if(($key != 'material_code') && ($key != 'returned_quantity') && ($key != 'price') && ($key != 'discount_amount') && ($key != 'condition') && ($key != 'return_type') && ($key != 'reason_of_rejection'))
                {

                    if($key == 'return_date'):
                    $fieldErrors = $validateOrder->validate(SifFunction::formatDate($row[SifFunction::makeSlug($key)]), $key);
                    else: 
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                    endif;

                        if($fieldErrors != false)
                        {
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
     * method use to get the header of every row
     */
    public static function getHeaderRow()
    {
      
         $headers = SiforderreturnHeader::first();
         return json_decode($headers);
    }


    public function getItemDataByGuid(Request $request)
    {
        $this->validate($request, [
            'id' => 'required'
        ]);

        $item = Siforderreturnitem::find($request->id);        
        $itemArr = array();
        $itemArr = [
            'id' => $item->Guid,
            'product_id' => $item->ProductId,
            'conversion_id' => $item->ConversionId,
            'returned_qty' => $item->ReturnedQty,
            'price' => $item->Price,
            'discount_amount' => $item->DiscountAmount,
            'condition' => $item->Condition,
            'return_type' => $item->ReturnType,
            'reason_of_rejection' => $item->ReasonOfRejection
        ];

        return json_encode($itemArr);

    }


    
    public function returnItemDelete($returnitemid)
    {     
        $item = Siforderreturnitem::find($returnitemid);
        $return_detail = $item->return_detail();
 
        $return = Siforderreturn::find($return_detail->Guid);   
        $return->Guid = SifFunction::generateGuid();
        $return->save();

        $item->IsDelete = 1;
        $item->save();

        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        Session::flash('success','Return Item was Successfully set to Delete');
        return redirect()->back();

    }



    
    
    public function returnItemCancelDelete($returnitemid)
    {     
        $item = Siforderreturnitem::find($returnitemid);
        $return_detail = $item->return_detail();
 
        $return = Siforderreturn::find($return_detail->Guid);   
        $return->Guid = SifFunction::generateGuid();
        $return->save();

        $item->IsDelete = 0;
        $item->save();

        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        Session::flash('success','Return Item was Successfully Cancel the Delete');
        return redirect()->back();

    }



    
    public function returnIsDelete($guid)
    {

        $return = Siforderreturn::find($guid);        
        $return->IsDelete = 1;
        $return->Status = 'OUT';
        $return->ErrorMessage = null;
        $return->Guid  = SifFunction::generateGuid();
        $result = $return->save();
 
        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        if($result): 
            Session::flash('success', 'Return was Successfully set to Cancell');
        else:
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;


        return redirect()->back();

    }

    public function returnCancelDelete($guid)
    {

        $return = Siforderreturn::find($guid);        
        $return->IsDelete = 0;
        $return->Status = 'OUT';
        $return->ErrorMessage = null;
        $return->Guid  = SifFunction::generateGuid();
        $result = $return->save();
 
        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        if($result): 
            Session::flash('success', 'Return was Successfully Cancel Delete');
        else:
            Session::flash('success', 'Oops ! Something went wrong Please try again');
        endif;


        return redirect()->back();

    }



    
    public function validateItemRows($row)
    {
 
        $arrError = []; //this will be the storage of all error msges

        $validateOrder = new RuleValidation();
        $validateOrder->headerRules = SiforderreturnValidation::first();


        $headerArr = self::getHeaderRow(); //get the header row of an every data
 
            $fieldErrors = false;
            /**
             * $key = header of an table
             * $value = the value of an header
             */
            foreach($headerArr as $key => $value): 
                 
                // loop one by one
                // and validate 
   
                if(($key == 'material_code') || ($key == 'returned_qty') || ($key == 'price') || ($key == 'discount_amount') || ($key == 'reason_of_return') || ($key == 'condition'))
                {
                    if($key == 'material_code'):
                        $matcode = $this->getMaterialConversion($row[SifFunction::makeSlug($key)]);                    
                        if(!$matcode)
                        {
                            $arrError[] = [                        
                                'field' => $value,
                                'errors' => ' This "'.$row[SifFunction::makeSlug($key)].'" is not Exists '
                            ];
                        }                   
                        $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                    else:                      
                    $fieldErrors = $validateOrder->validate($row[SifFunction::makeSlug($key)], $key);
                    endif;

                        if($fieldErrors != false)
                        {
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



    public function updateItem(Request $request)
    {
   
 
        $errors = $this->validateItemRows($request);
        //if has an error found
        //then exit and return all the error messages
        if(count($errors) > 0){
          
            Session::flash('error_data', $errors);
            return redirect()->back();

        }         

        $item = Siforderreturnitem::find($request->item_id);
        $order_detail = $item->return_detail();

        $item->ReasonOfRejection = $request->reason_of_rejection;
        $item->ReturnType = $request->return_type;
        $item->Condition = $request->condition;
        $item->DiscountAmount = $request->discount_amount;
        $item->Price = $request->price;
        $item->ProductId = $request->material_code;
        $item->ConversionId = $request->conversion_id;
        $item->ReturnedQty = $request->returned_qty;

        $item->save();

        $return = Siforderreturn::find($order_detail->Guid);
        $return->Guid = SifFunction::generateGuid();
        $return->save();


        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }        

        Session::flash('success','Return Item Details was Successfully Updated');
        return redirect()->back();
    }


    

    public function addReturnItem(Request $request, $id)
    {
        $errors = $this->validateItemRows($request);
        //if has an error found
        //then exit and return all the error messages
        if(count($errors) > 0){
          
            Session::flash('error_data', $errors);
            return redirect()->back();

        }         

        $return = Siforderreturn::find($id);

        if(!$return)
            return redirect()->back();

        $item = new Siforderreturnitem();
        $item->OrderReturnGuid = $id;
        $item->ReferenceKeyId = $return->KeyId;         
        $item->ReasonOfRejection = $request->reason_of_rejection;
        $item->ReturnType = $request->return_type;
        $item->Condition = $request->condition;
        $item->DiscountAmount = $request->discount_amount;
        $item->Price = $request->price;
        $item->ProductId = $request->material_code;
        $item->ConversionId = $request->conversion_id;
        $item->ReturnedQty = $request->returned_qty;
        $item->ErrorMessage = NULL;
        $item->Status = 'OUT';

        $item->TransactionId = date('md').SifFunction::generateRandomID(100000,999999);
        $item->Guid = SifFunction::generateGuid();
        $item->save();


        $returnitems = $return->items();
        foreach($returnitems as $item)
        {
            $item = Siforderreturnitem::find($item->Guid);
            $item->Guid =  SifFunction::generateGuid();
            $item->Status = 'OUT';
            $item->ErrorMessage = NULL;
            $item->save();
        }                

        Session::flash('success','Return Item was Successfully Added');
        return redirect()->back();


    }

}

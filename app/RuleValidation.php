<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RuleValidation extends Model
{
    //

   //
   public $field;    
   public $fieldValue;    
   public $headerRules;

   /**
    * this method use to get the rules of the header
    */
   public function rules()
   {
       $fieldRule = $this->field;
       return $this->headerRules->$fieldRule;
   }


   /**
    * @param fieldValue , fieldName
    * this method use to validate the data in every rules
    */
   public function validate($fieldValue,  $fieldName)
   {      
       $rules = [];
       $this->field = $fieldName;
       $this->fieldValue = $fieldValue;

       $rules = explode('|', $this->rules()); //seperate all the rules and turns it to array

       //process the validation in every rules
       $validateResult = $this->processRulesValidation($rules); 

       return $validateResult ? $validateResult : false;

   }

    /**
     * @param rules (array)
     * this method use to process the validation in every rules
     * @return arrResult (error message) / false
     */
    public function processRulesValidation($rules)
    {
        $arrResult = [];        
        foreach($rules as $rule):                      
            if($rule === 'required'):
                $result = $this->required();
                $result != false ?
                        array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'minlength'):                
                $result = $this->minLength(explode(':',$rule)[1]);
                $result != false ?
                        array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'maxlength'):                
                $result = $this->maxLength(explode(':',$rule)[1]);
                $result != false ?
                        array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'length'):                
                $result = $this->length(explode(':',$rule)[1]);
                $result != false ?
                        array_push($arrResult, $result) : false;            
            elseif(explode(':',$rule)[0] === 'enum'):                                                                
                    $result = $this->enum(explode(':',$rule)[1]);
                    $result != false ?
                            array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'numeric'):                                                                
                    $result = $this->numeric();
                    $result != false ?
                            array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'alphabet'):                                                                
                    $result = $this->alphabet();
                    $result != false ?
                            array_push($arrResult, $result) : false;                            
            elseif($rule === 'date'):            
                    $result = $this->date();
                    $result != false ?
                            array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'maxvalue'):            
                /**
                 * max value validation
                 * require for integer inputs
                 */
                    $result = $this->maxValue(explode(':',$rule)[1]);
                //validate if the input value has exits or not
                    $result != false ?
                            array_push($arrResult, $result) : false;
            elseif(explode(':',$rule)[0] === 'minvalue'):            
                //min value validation
                    $result = $this->minValue(explode(':',$rule)[1]);
                    $result != false ?
                            array_push($arrResult, $result) : false;
            elseif($rule === 'nullable'):                
                //validation for nullable fields
                $result = $this->nullable();
                if($result)
                    return false;
            endif;

        endforeach;
        // if(count($arrResult)){ exit(json_encode($arrResult)); }

        return count($arrResult) > 0 ? implode(',',$arrResult) : false;

    }

    public function required(){
        $arrResult = [];
        if(is_null($this->fieldValue))
        {
            $error =  'This Field is Required';
            return $error;
        }else 
            return false;

    }



    public function nullable(){
        return is_null($this->fieldValue) ? true : false;
    }

    public function numeric(){
        $arrResult = [];
        // if(!preg_match('/[0-9]/',$this->fieldValue))
        if(!is_numeric($this->fieldValue))
        {
            $error =  '"'.$this->fieldValue.'" is not a Numeric ';
            return $error;
        }else 
            return false;


    }    



    public function alphabet(){
        $arrResult = [];
        if(preg_match('/[0-9]/',$this->fieldValue)){
            $error =  '"'.$this->fieldValue.'" is required to not contain a number ';
            return $error;            
        }
        else 
        {
            return false;
        }
    }    
 
    public function date(){
        $arrResult = [];        
        $dateArr = [];
        $dateArr = explode('-', $this->fieldValue);
        if($this->fieldValue == '1970-01-01'){
            $error =  'The Date is Invalid';
            return $error;
        }else 
        {
            if(!checkdate($dateArr[1], $dateArr[2], $dateArr[0]))
            {
                $error =  'The Date is Invalid';
                return $error;
            }else 
                return false;
        }


    }    


    
    public function minLength($minValue){
        $arrResult = [];
        if(strlen($this->fieldValue) < str_replace(" ", "", $minValue))
        {
            $error = " The required Minimum Length is ".$minValue;
            return $error;
        }else 
            return false;

    }



    public function maxLength($maxValue){
        $arrResult = [];
        if(strlen($this->fieldValue) <= str_replace(" ", "", $maxValue))
            return false;
        else {
            $error = " The required Maximum Length is ".$maxValue;
            return $error;
        }

    }


    public function minValue($minValue){
        $arrResult = [];
        if(abs((int)$this->fieldValue) < $minValue)
        {
            $error = " The required Minimum Value is ".$minValue;
            return $error;
        }else 
            return false;

    }



    public function maxValue($maxValue){
        $arrResult = [];
        if(abs((int)$this->fieldValue) <= $maxValue)
            return false;
        else {  
            $error = " The required Maximum Value is ".$maxValue;
            return $error;
        }

    }    


    public function length($lengthValue){
        $arrResult = [];
        
        if(strlen(preg_replace('/\s/', '', $this->fieldValue)) != $lengthValue)
        {
            $error = " The required Exact Length is ".$lengthValue;
            return $error;
        }else 
            return false;

    }



    
    public function enum($enum){

        $enumArrValue = explode(',',$enum); 
        $arrResult = [];

        $output = false;

        foreach($enumArrValue as $enumValue){
            if($enumValue === $this->fieldValue)
                $output = true;
        }

        if(!$output){
            $error =  '"'.$this->fieldValue.'" is not in the list ('.implode(',',$enumArrValue).' )';
            return $error;
        }else 
            return false;
    }    



    
}

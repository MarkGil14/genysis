<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\RuleValidation;

class SifdspValidation extends Model
{
    public $field;    
    public $fieldValue;    

     

    /**
     * this method use to get the rules of the header
     */
    public function rules()
    {
        $rules = $this->first();
        $headerRule = $this->field;
        return $rules->$headerRule;
    }
                 
    /**
     * @param fieldValue , fieldName
     * this method use to validate the data in every rules
     */
    public function validate($fieldValue,  $fieldName)
    {
        $ruleValidation = new RuleValidation();
        $rules = [];
        $this->field = $fieldName;
        $ruleValidation->fieldValue = $fieldValue;

        $rules = explode('|', $this->rules()); //seperate all the rules and turns it to array

        //process the validation in every rules
        $validateResult = $ruleValidation->processRulesValidation($rules); 

        return $validateResult ? $validateResult : false;
    }
}

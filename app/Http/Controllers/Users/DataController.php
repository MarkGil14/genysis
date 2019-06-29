<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Imports\SifitemImport;
use App\Imports\SifcustomerImport;
use App\Imports\SifdspImport;
use App\Imports\SiforderImport;
use App\Imports\SifdiscountImport;
use App\Imports\SiforderreturnImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\SiforderValidateImport;
use App\FormatName;
use App\SiforderHeader;
use Session;
use App\SiforderValidation;
use App\SifdiscountValidation;
use App\SifdiscountHeader;
use App\SiforderreturnValidation;
use App\SiforderreturnHeader;
use App\SifitemValidation;
use App\SifitemHeader;
use App\SifcustomerValidation;
use App\SifcustomerHeader;
use App\SifdspValidation;
use App\SifdspHeader;



class DataController extends Controller
{

    public function importData(Request $request)
    {

        $this->validate($request, [
            'file' => 'required'
        ]);

        if (isset($request->file)) {

            // validate all the data of the uploaded files
            // $this->validateUpload($request->file); 
            // exit(dd($request->file('file')));               

            //validate the filename of the files
            $errors = $this->validateFormatName($request->file);
            if (count($errors) > 0) {
                $errorResponse = [
                    'entity' => json_encode($errors),
                    'errors' => json_encode($errors),
                    'file_error' => "true"
                ];
                exit(json_encode($errorResponse));
            }

            $formatName = FormatName::first();

            $discountFile = null;

            foreach ($request->file as $file) :
                try {
                    switch ($file->getClientOriginalName()):
                            //XLSX
                        case $formatName->sifitem . '.xlsx':
                            Excel::import(new SifitemImport, $file,  \Maatwebsite\Excel\Excel::XLSX);
                            break;
                        case $formatName->sifcustomer . '.xlsx':
                            Excel::import(new SifcustomerImport, $file,  \Maatwebsite\Excel\Excel::XLSX);
                            break;
                        case $formatName->sifdsp . '.xlsx':
                            Excel::import(new SifdspImport, $file,  \Maatwebsite\Excel\Excel::XLSX);
                            break;
                        case $formatName->siforder . '.xlsx':
                            Excel::import(new SiforderImport, $file,  \Maatwebsite\Excel\Excel::XLSX);
                            break;
                        case $formatName->sifreturn . '.xlsx':
                            Excel::import(new SiforderreturnImport, $file,  \Maatwebsite\Excel\Excel::XLSX);
                            break;

                    // case $formatName->sifdiscount . '.xlsx':
                    //     $discountFile = $file;
                    //     break;

                    endswitch;
                    // $file = Excel::toArray(new SifuploadfileImport,request()->file('file'),  \Maatwebsite\Excel\Excel::CSV);                            


                } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
                    $failures = $e->failures();
                    foreach ($failures as $failure) {
                        $failure->row(); // row that went wrong
                        $failure->attribute(); // either heading key (if using heading row concern) or column index
                        $failure->errors(); // Actual error messages from Laravel validator
                    }
                }
            endforeach;

            // if (!is_null($discountFile))
            //     Excel::import(new SifdiscountImport, $discountFile,  \Maatwebsite\Excel\Excel::XLSX);

            // foreach($request->file('file') as $file):
            //     try{
            //         switch($file->getClientOriginalName()):

            //             case $formatName->sifdiscount.'.xlsx':                                            
            //                     $file = Excel::import(new SifdiscountImport, $file,  \Maatwebsite\Excel\Excel::XLSX);            
            //             break;

            //             case $formatName->sifdiscount.'.csv':                        
            //                 $file = Excel::import(new SifdiscountImport, $file,  \Maatwebsite\Excel\Excel::CSV);  
            //             break;

            //         endswitch;
            // } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
            //     $failures = $e->failures();                     
            //     foreach ($failures as $failure) {
            //         $failure->row(); // row that went wrong
            //         $failure->attribute(); // either heading key (if using heading row concern) or column index
            //         $failure->errors(); // Actual error messages from Laravel validator
            //     }
            // }
            // endforeach;                
        }
    }


    public function updateFieldValidations(Request $request)
    {
        $this->validate($request, [
            'template_name' => 'required',
            'header' => 'required'
        ]);

        $formats = FormatName::first();
        $format_name = $request->header;
        $formats->$format_name = $request->template_name;
        $result = $formats->save();

        switch ($format_name) {
            case 'siforder':
                $header =  'siforder_headers';
                $validation = 'siforder_validations';
                break;
            case 'sifdiscount':
                $header =  'sifdiscount_headers';
                $validation = 'sifdiscount_validations';
                break;
            case 'sifreturn':
                $header =  'siforderreturn_headers';
                $validation = 'siforderreturn_validations';
                break;
            case 'sifitem':
                $header =  'sifitem_headers';
                $validation = 'sifitem_validations';
                break;
            case 'sifcustomer':
                $header =  'sifcustomer_headers';
                $validation = 'sifcustomer_validations';
                break;
            case 'sifdsp':
                $header =  'sifdsp_headers';
                $validation = 'sifdsp_validations';
                break;
            default:
                return redirect()->back();
                break;
        }

        $headerArr = array();
        $validationArr = array();
        foreach ($request->header_key as $key => $header_key) {
            array_push($headerArr, "`" . $header_key . "` = '" . $request->header_value[$key] . "'");
            array_push($validationArr, "`" . $header_key . "` = '" . $request->validation_value[$key] . "'");
        }


        $query = "UPDATE " . $header . " SET ";
        $filterQuery = $query . implode(', ', $headerArr) . ' ';
        DB::select($filterQuery);

        $query = "UPDATE " . $validation . " SET ";
        $filterQuery2 = $query . implode(', ', $validationArr) . ' ';
        DB::select($filterQuery2);

        if ($result)
            Session::flash('success', 'Field Validations was Successfully Updated');
        else
            Session::flash('error', 'Oopss ! Something went wrong Please try again');

        return redirect()->intended(route('app.field-settings', ['field' => $request->template_name]));
    }

    public function validateFormatName($files)
    {
        $notDetermineArr = array();
        $formatName = FormatName::first();
        foreach ($files as $file) :
            $isExists = false;

            switch ($file->getClientOriginalName()):
                    //XLSX
                case $formatName->sifitem . '.xlsx':
                    $isExists = true;
                    break;
                case $formatName->sifcustomer . '.xlsx':
                    $isExists = true;
                    break;

                case $formatName->sifdsp . '.xlsx':
                    $isExists = true;
                    break;

                case $formatName->sifdiscount . '.xlsx':
                    $isExists = true;
                    break;

                case $formatName->siforder . '.xlsx':
                    $isExists = true;
                    break;

                case $formatName->sifreturn . '.xlsx':
                    $isExists = true;
                    break;

            endswitch;


            if ($isExists == false)
                array_push($notDetermineArr, "This " . $file->getClientOriginalName() . " is not recognized by the System! Please check the filename.");

        endforeach;

        return $notDetermineArr;
    }
}

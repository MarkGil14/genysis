<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SystemSettings;
use Session;

class CompaniesController extends Controller
{
    //

    public function updateCompanySettings(Request $request)
    {
        $this->validate($request, [
            'company_name' => 'required',
            'company_number' => 'required',
            'company_email' => 'required|email',
            'company_address' => 'required',
            'company_color' => 'required'
        ]); 

        $company = SystemSettings::find(1);
        $company->theme_color = $request->company_color;
        $company->company_name = $request->company_name;
        $company->company_telnum = $request->company_number;
        $company->company_email = $request->company_email;
        $company->company_address = $request->company_address;
        $result = $company->save();
        if($result){
            Session::flash('success', 'Company Settings was Successfully Updated');
        }else{
            Session::flash('error', 'Oops ! Something went wrong Please try again');
        }

        return redirect()->back();

    }


    /**
    * @param request (upload_file)
    * the use of this method is to upload the avatar of the employees
    */
    public function uploadLogo(Request $request)
    {
        $this->validate($request, [
            'upload_file' => 'required'
        ]);

        $file_path = $this->uploadFile($request->upload_file,'logo/');
        $company = SystemSettings::find(1);
        $company->logo = $file_path;
        if($company->save())
            Session::flash('success', 'Company Logo was Successfully Updated');
        else 
            Session::flash('success', 'Oops ! Something went wrong, Please try again');

        return redirect()->back();

    }

    
    public function uploadFile($file, $path)
    {
        $file_path = $file;
        $file_path_new = time() . $file_path->getClientOriginalName();
        $file_path->move($path, $file_path_new);
        return $path . $file_path_new;
    }



}

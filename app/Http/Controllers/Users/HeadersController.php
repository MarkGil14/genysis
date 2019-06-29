<?php

namespace App\Http\Controllers\Users;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller; 
use Maatwebsite\Excel\Facades\Excel;
use App\FormatName;
use App\Exports\SiforderHeaderExport;
use App\Exports\SifdiscountHeaderExport;
use App\Exports\SifreturnHeaderExport;
use App\Exports\SifitemHeaderExport;
use App\Exports\SifcustomerHeaderExport;
use App\Exports\SifdspHeaderExport;

class HeadersController extends Controller 
{ 
    public function exportOrderHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SiforderHeaderExport, $format->siforder.'.xlsx');
    }
 
 
    public function exportDiscountHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SifdiscountHeaderExport, $format->sifdiscount.'.xlsx');
    }


    public function exportReturnHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SifreturnHeaderExport, $format->sifreturn.'.xlsx');
    }


    public function exportItemHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SifitemHeaderExport, $format->sifitem.'.xlsx');
    }
     

    public function exportCustomerHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SifcustomerHeaderExport, $format->sifcustomer.'.xlsx');
    }

    public function exportDspHeader()
    {
        $format = FormatName::first();
        return Excel::download(new SifdspHeaderExport, $format->sifdsp.'.xlsx');
    }
 
     
     
 




    
}

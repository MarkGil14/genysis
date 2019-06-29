@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/sweetalert.css') }}">  
 
@endsection

@section('content')

            <!-- BREADCRUMB-->
            <section style="padding-top: 48px;">
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="au-breadcrumb-content">
                                <div class="au-breadcrumb-left">
                                    <span class="au-breadcrumb-span">You are here:</span>
                                    <ul class="list-unstyled list-inline au-breadcrumb__list">
                                        <li class="list-inline-item active">
                                            <a href="#">Home</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Dashboard</li>
                                    </ul>
                                </div>
                           
                                <form class="au-form-icon--sm" action="{{ route('app.search') }}" method="GET">
                                        @csrf
                                        <input class="au-input--w300 au-input--style2" type="text" placeholder="Search for datas" name="keyword">
                                        <button class="au-btn--submit2" type="submit">
                                            <i class="zmdi zmdi-search"></i>
                                        </button>
                                    </form>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- END BREADCRUMB-->

   

    <!-- STATISTIC-->
    <section class="statistic statistic2">
        <div class="container">

            <div class="row">
                <div class="col-md-12">

                <div class="row">
                        <div class="col-md-12">
                                    <a href="{{ route('app.return-edit' , ['keyid' => $return->KeyId]) }}" class="float-right btn btn-warning text-white">Edit</a>
                                <button class="float-right btn btn-primary"  onclick="printDiv('print')">Print</button>
                        </div>                                
                    </div>

                    <div class="au-card mt-3" id="print">
                            <div class="row" style="margin:20px;">
                                    <img src="{{ asset($company->logo) }}" alt="genysis" style="height:60px;" />                            
                                </div>
                        <div class="row">
                                <div class="col-md-4 with-border">                                    
                                        <address class="mt-3">
                                            <strong>FROM</strong>
                                            <br> {{ $company->company_name }}
                                            <br> {{ $company->company_address }}
                                            <br> {{ $company->company_telnum }}            
                                            <br> {{ $company->company_email }}            
    
                                        </address>
                                    </div>

                                <div class="col-md-4 with-border">                                    
                                        <address class="mt-3">
                                            <strong>TO</strong>
                                            <?php $account_info = $account->find($return->AccountId); ?>                                 
                                            <br> Account Name: {{ $account_info ? $account_info->AccountName : $return->AccountId }}
                                            <br> Channel : {{ $account_info ?  $account_info->Channel : 'None' }}
                                            <br> Street : {{ $account_info ?  $account_info->Street : 'None' }}
                                            <br> City : {{ $account_info ? $account_info->City : 'None' }}                       
                                        </address>
                                    </div>

                                    
                                    <div class="col-md-4 with-border">                                    
                                            <address class="mt-3">
                                                <strong>Return Details</strong><br>
                                                 Return Type:  {{ $return->ReturnType }}<br/>
                                                 Invoice #: {{ $return->InvoiceNumber }}<br/>
                                                 Credit Memo #: {{ $return->CreditMemoNumber }}<br/>
                                                 Return Date:  {{ $return->ReturnDate == '0000-00-00' ? 'None' : date('F j, Y', strtotime($return->ReturnDate)) }}<br/>
                                                 Reason of return: {{ $return->ReasonOfReturn }}<br/>
                         
                                            </address>
                                    </div>
        

                        </div>
 
                        <div class="row">

                            <div class="col-md-12">
                                    <div class="table-responsive table--no-card m-b-30">
                                            <table class="table table-borderless table-striped">
                                                <thead class="bg-secondary text-white">
                                                    <tr>
                                                            <th>Product Description</th>
                                                            <th>Conversion</th>                                         
                                                            <th  class="text-right">Return Qty</th>              
                                                            <th class="text-right">Price</th>
                                                            <th class="text-right">Discount Amount</th>
                                                            <th class="text-right">Condition</th> 
                                                            <th class="text-right">Return Type</th> 
                                                            <th class="text-right">Reason Of Rejection</th> 
                                                    </tr>
                                                </thead>
                                                <tbody>
                                           
                                                    @foreach ($return->return_items() as $item)                                                        
                                                    <tr>
                                                        <td>{{ $item->Description }}</td>
                                                        <td>{{ $item->ConversionId }}</td>
                                                        <td class="text-right">{{ $item->ReturnedQty }}</td>
                                                        <td class="text-right">{{ $item->Price }}</td>
                                                        <td class="text-right">{{ $item->DiscountAmount }}</td>
                                                        <td class="text-right">{{ $item->Condition }}</td>
                                                        <td class="text-right">{{ $item->ReturnType }}</td>
                                                        <td class="text-right">{{ $item->ReasonOfRejection }}</td>
                                                    </tr> 
                                                    @endforeach

                                                  
                                                </tbody>
                                            </table>
                                        </div>

                            </div>

                        </div>
 

                    </div>
                </div>
            </div>
                       
        </div>

        
    </section>
   
@endsection


@section('modals')
    
@endsection


@section('scripts')

<script>
     function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
</script>
@endsection

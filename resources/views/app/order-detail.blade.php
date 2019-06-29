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
                                            <li class="list-inline-item">
                                                <a href="{{ route('app.dashboard') }}">Dashboard</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                <span>/</span>
                                            </li>
                                            <li class="list-inline-item">
                                                <a href="{{ route('app.orders') }}">Orders</a>
                                            </li>
                                            <li class="list-inline-item seprate">
                                                    <span>/</span>
                                                </li>
                                            <li class="list-inline-item  active">{{ $order->InvoiceNumber }}</li>                                            
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
                <div class="col-md-12" >
                    <div class="row">
                        <div class="col-md-12">
                                 <a href="{{ route('app.order-edit' , ['keyid' => $order->KeyId]) }}" class="float-right btn btn-warning text-white">Edit</a>
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
                                            <?php $account_info = $account->find($order->AccountReferenceId); ?>                                 
                                            <br> Account Name: {{ $account_info ? $account_info->AccountName : $order->AccountReferenceId }}
                                            <br> Channel : {{ $account_info ?  $account_info->Channel : 'None' }}
                                            <br> Street : {{ $account_info ?  $account_info->Street : 'None' }}
                                            <br> City : {{ $account_info ? $account_info->City : 'None' }}                       
                                        </address>
                                    </div>

                                    
                                    <div class="col-md-4 with-border">                                    
                                            <address class="mt-3">
                                                <strong>Order Details</strong><br>
                                                 SalesType:  {{ $order->SalesType }}<br/>
                                                 Invoice #: {{ $order->InvoiceNumber }}<br/>
                                                 Invoice Date:  {{ $order->InvoiceDate == '0000-00-00' ? 'None' : date('F j, Y', strtotime($order->InvoiceDate)) }}<br/>
                                                <br/>
                                                 Sales Order #:  {{ $order->SalesOrderNumber == null ? 'None' : $order->SalesOrderNumber }}<br/>
                                                 Order Date:  {{ $order->OrderDate == '0000-00-00' ? 'None' : date('F j, Y', strtotime($order->OrderDate)) }}<br>
                                                 Delivery Date:  {{ $order->RequestedDeliveryDate == '0000-00-00' ? 'None' : date('F j, Y', strtotime($order->RequestedDeliveryDate)) }}<br/>
                                                 Payment Term: {{ $order->PaymentTerm }}
                         
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
                                                            <th  class="text-right">UOM</th>              
                                                            <th  class="text-right">Order Quantity</th>              
                                                            <th class="text-right">Order Price</th>
                                                            <th class="text-right">Order Discount</th>
                                                            <th class="text-right">Order Total</th>

                                                            <th  class="text-right">Invoice Quantity</th>              
                                                            <th class="text-right">Invoice Price</th>
                                                            <th class="text-right">Invoice Discount</th>
                                                            <th class="text-right">Invoice Total</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $overallTotal = 0; ?>
                                                    <?php $overallHeaderDiscount = 0; ?>
                                                    <?php $overallItemDiscount = 0; ?>
                                                    <?php $overallVat = 0; ?>
                                                    @foreach ($order->order_items() as $item)                                                        

                                                    @if(!$item->IsDelete)

                                                        <tr>
                                                            <td>{{ $item->Description }}</td>
                                                            <td class="text-right">{{ $item->ConversionId }}</td>
                                                            <td class="text-right">{{ $item->OrderQuantity }}</td>
                                                            <td class="text-right">{{ $item->OrderPrice }}</td>
                                                            <td class="text-right"> <span class="status--out"  data-toggle="tooltip" data-placement="top" title="{{ $item->OrderDiscount1Type.' , '.$item->OrderDiscount2Type.' , '.$item->OrderDiscount3Type.' , '.$item->OrderDiscount4Type }}"> {{ $orderDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4 }} </span> </td>
                                                            <td class="text-right">{{ ($item->OrderQuantity * $item->OrderPrice) - $orderDiscount }}</td>
                                                            <td class="text-right">{{ $item->InvoiceQuantity }}</td>
                                                            <td class="text-right"> {{ $item->InvoicePrice }} </td>
                                                            <td class="text-right"> <span class="status--out"  data-toggle="tooltip" data-placement="top" title="{{ $item->InvoiceDiscount1Type.' , '.$item->InvoiceDiscount2Type.' , '.$item->InvoiceDiscount3Type.' , '.$item->InvoiceDiscount4Type }}"> {{ $itemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4 }} </span> </td>
                                                            <td class="text-right">{{ $totalAmount = ($item->InvoiceQuantity * $item->InvoicePrice) - $itemDiscount }}</td>

                                                        </tr>
                                                        <?php  
                                                            $overallTotal = $overallTotal + $totalAmount;
                                                            $overallItemDiscount =  $overallItemDiscount + $itemDiscount;
                                                            $overallVat = $overallVat + (($item->InvoiceQuantity * $item->InvoicePrice) / 1.12) * .12;
                                                        ?>

                                                    @endif

                                                    @endforeach

                                                  
                                                </tbody>
                                            </table>
                                        </div>

                            </div>

                        </div>

                        <div class="row">


                            <div class="col-md-6" style="border-right:solid 1px #d8d8d6;">
                                    <div class="table-responsive">

                                            <strong>DISCOUNT SUMMARY</strong>
                                            <table class="table table-top-campaign">
                                                <tbody>
                                                    @if($order->InvoiceDiscountFromTotal1 != 0)
                                                    <tr>
                                                        <td>{{ $order->InvoiceDiscountFromTotal1Type }}</td>
                                                        <td>{{ number_format($order->InvoiceDiscountFromTotal1, 2, '.', ',') }}</td>
                                                        <?php $overallHeaderDiscount = $overallHeaderDiscount + $order->InvoiceDiscountFromTotal1; ?>
                                                    </tr>
                                                    @endif

                                                    @if($order->InvoiceDiscountFromTotal2 != 0)
                                                    <tr>
                                                            <td>{{ $order->InvoiceDiscountFromTotal2Type }}</td>
                                                            <td>{{ number_format($order->InvoiceDiscountFromTotal2, 2, '.', ',') }}</td>
                                                            <?php $overallHeaderDiscount = $overallHeaderDiscount + $order->InvoiceDiscountFromTotal2; ?>
                                                        </tr>
                                                    @endif
                                                    
                                                    @if($order->InvoiceDiscountFromTotal3 != 0)
                                                    <tr>
                                                            <td>{{ $order->InvoiceDiscountFromTotal3Type }}</td>
                                                            <td>{{ number_format($order->InvoiceDiscountFromTotal3, 2, '.', ',') }}</td>
                                                            <?php $overallHeaderDiscount = $overallHeaderDiscount + $order->InvoiceDiscountFromTotal3; ?>
                                                        </tr>
                                                    @endif
 
                                                    @if($order->InvoiceDiscountFromTotal4 != 0)
                                                    <tr>
                                                            <td>{{ $order->InvoiceDiscountFromTotal1Type }}</td>
                                                            <td>{{ number_format($order->InvoiceDiscountFromTotal1, 2, '.', ',') }}</td>
                                                            <?php $overallHeaderDiscount = $overallHeaderDiscount + $order->InvoiceDiscountFromTotal4; ?>
                                                        </tr>
                                                    @endif
 
                                                    <tr>
                                                        <td><strong>Overall Header Discount</strong></td>
                                                        <td><strong> {{ number_format($overallHeaderDiscount, 2, '.', ',') }}</strong> </td>
                                                    </tr>
                                        
                                                    <tr>
                                                        <td><strong>Overall Item Discount</strong></td>
                                                        <td><strong> {{ number_format($overallItemDiscount, 2, '.', ',') }}</strong>
                                                    </tr>
                    
                                            
                                                </tbody>
                                            </table>
                                        </div>

                       

                            </div>

                            <div class="col-md-6">
                                    <div class="table-responsive">
                                            <strong>AMOUNT SUMMARY</strong>
                                            <table class="table table-top-campaign">
                                                <tbody>

                                                        <tr>
                                                                <td>Subtotal (Non Vat):</td>                                                                
                                                                <td>{{ number_format($order->InvoiceTotal - $overallVat + $order->InvoiceTotalDiscount, 2, '.', ',') }}</td>
                                                            </tr>


                                                              <tr>
                                                                <td>Total Vat:</td>
                                                                <td>{{ number_format($overallVat, 2, '.', ',') }}</td>
                                                              </tr>
                                                              <tr>
                                                                <td>Discount Total:</td>
                                                                <td>{{ number_format($order->InvoiceTotalDiscount, 2, '.', ',') }}</td>
                                                              </tr>
                                                       
                                                            <tr>
                                                                <td>Total:</td>                                            
                                                                <td><strong>{{ number_format($order->InvoiceTotal, 2, '.', ',') }}</strong></td>
                                                            </tr>

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

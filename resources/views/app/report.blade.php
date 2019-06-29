@extends('layouts.app')

@section('styles')
    <link href="{{ asset('vendor/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">
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
                                        <li class="list-inline-item active">Order Report</li>
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

  
    <section class="p-t-20">
        
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                            </div>
                            <div class="table-data__tool-right">                     
                            <form action="{{ route('app.filter-order-report') }}" method="POST">    
                                @csrf        
                                   <div class="rs-select2--light rs-select2--md">
                                        <select class="js-select2" name="status">
                                            <option value="">Any Status</option>
                                            <option value="PROCESSED">PROCESSED</option>
                                            <option value="OUT">OUT</option>
                                            <option value="FAILED">FAILED</option>
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                    <div class="rs-select2--light rs-select2--sm">
                                        <select class="js-select2" name="time" required>
                                            <option selected="selected" value="INTERVAL -1 DAY">Today</option>
                                            <option value="INTERVAL -3 DAY">3 Days</option>
                                            <option value="INTERVAL -1 WEEK">1 Week</option>
                                            <option value="INTERVAL -<?php echo date('j', strtotime(date('Y-m-d'))); ?> DAY">This Month</option>
                                            <option value="INTERVAL -1 MONTH">Previous Month</option>
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                    <button class="au-btn-filter bg-secondary text-white" type="submit">
                                        <i class="zmdi zmdi-filter-list"></i>filter</button>
                                </form>
                            </div>
    

                        </div>
                 
                        <div class="table-responsive m-b-40">
                                <table class="table table-borderless  table-data3" id="dt">
                                    <thead>
                                        <tr class="no-wrap">                       
                                            <th>SIF Id</th>
                                            <th>Sales Type</th>
                                            <th>Invoice Number</th>
                                            <th>SO Number</th>
                                            <th>Order Date</th>                                                  
                                            <th>Requested Delivery Date</th>                                                  
                                            <th>Invoice Date</th>                                                  
                                            <th>SAS ID</th>
                                            <th>DSP ID</th>
                                            <th>Account ID</th>
                                            <th>Order Total</th>
                                            <th>Order Total Discount</th>                                        
                                            <th>Order Discount From Total 1</th>
                                            <th>Order Discount From Total 1 Type</th>
                                            <th>Order Discount From Total 2</th>
                                            <th>Order Discount From Total 2 Type</th>
                                            <th>Order Discount From Total 3</th>
                                            <th>Order Discount From Total 3 Type</th>
                                            <th>Order Discount From Total 4</th>
                                            <th>Order Discount From Total 4 Type</th>
                                            <th>Invoice Total</th>
                                            <th>Invoice Total Discount</th>                                        
                                            <th>Invoice Discount From Total 1</th>
                                            <th>Invoice Discount From Total 1 Type</th>
                                            <th>Invoice Discount From Total 2</th>
                                            <th>Invoice Discount From Total 2 Type</th>
                                            <th>Invoice Discount From Total 3</th>
                                            <th>Invoice Discount From Total 3 Type</th>
                                            <th>Invoice Discount From Total 4</th>
                                            <th>Invoice Discount From Total 4 Type</th>
                                            <th>Transaction ID</th>
                                            <th>Order Status</th>
                                            <th>Order Error Message</th>
                                            <th>SIF Item ID</th>
                                            <th>Product ID</th>
                                            <th>Conversion ID</th>
                                            <th>Order Quantity</th>
                                            <th>Order Price</th>
                                            <th>Order Item Discount 1</th>
                                            <th>Order Item Discount 1 Type</th>
                                            <th>Order Item Discount 2</th>
                                            <th>Order Item Discount 2 Type</th>
                                            <th>Order Item Discount 3</th>
                                            <th>Order Item Discount 3 Type</th>
                                            <th>Order Item Discount 4</th>
                                            <th>Order Item Discount 4 Type</th>
                                            <th>Invoice Quantity</th>
                                            <th>Invoice Price</th>
                                            <th>Invoice Item Discount 1</th>
                                            <th>Invoice Item Discount 1 Type</th>
                                            <th>Invoice Item Discount 2</th>
                                            <th>Invoice Item Discount 2 Type</th>
                                            <th>Invoice Item Discount 3</th>
                                            <th>Invoice Item Discount 3 Type</th>
                                            <th>Invoice Discount 4</th>
                                            <th>Invoice Item Discount 4 Type</th>
                                            <th>Weight UOM</th>
                                            <th>Actual Weight Quantity</th>
                                            <th>Item Status</th>
                                            <th>Item Error Message</th>    
                                        </tr>
                                    </thead>
                                    <tbody>
    
                                @if($results)
                                    @if(count($results) > 0)
                                        @foreach ($results as $result)
                                            
                                        <tr class="tr-shadow">
                                        <td>{{ $result->SifId }}</td>    
                                        <td>{{ $result->SalesType }}</td>
                                        <td>{{ $result->InvoiceNumber }}</td>
                                        <td>{{ $result->SalesOrderNumber }}</td>
                                        <td>{{ $result->OrderDate }}</td>
                                        <td>{{ $result->RequestedDeliveryDate }}</td>
                                        <td>{{ $result->InvoiceDate }}</td>
                                        <td>{{ $result->SASId }}</td>
                                        <td>{{ $result->DSPId }}</td>
                                        <td>{{ $result->AccountReferenceId }}</td>
                                        <td>{{ $result->OrderTotal }}</td>
                                        <td>{{ $result->OrderTotalDiscount }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal1 }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal1Type }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal2 }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal2Type }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal3 }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal3Type }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal4 }}</td>
                                        <td>{{ $result->OrderDiscountFromTotal4Type }}</td>
                                        <td>{{ $result->InvoiceTotal }}</td>
                                        <td>{{ $result->InvoiceTotalDiscount }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal1 }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal1Type }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal2 }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal2Type }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal3 }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal3Type }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal4 }}</td>
                                        <td>{{ $result->InvoiceDiscountFromTotal4Type }}</td>                                    
                                        <td>{{ $result->TransactionId }}</td>
                                        <td>{{ $result->OrderStatus }}</td>
                                        <td>{{ $result->OrderErrorMessage }}</td>
                                        <td>{{ $result->SifitemId }}</td>
                                        <td>{{ $result->ProductId }}</td>
                                        <td>{{ $result->ConversionId }}</td>
                                        <td>{{ $result->OrderQuantity }}</td>
                                        <td>{{ $result->OrderPrice }}</td>
                                        <td>{{ $result->OrderDiscount1 }}</td>
                                        <td>{{ $result->OrderDiscount1Type }}</td>
                                        <td>{{ $result->OrderDiscount2 }}</td>
                                        <td>{{ $result->OrderDiscount2Type }}</td>
                                        <td>{{ $result->OrderDiscount3 }}</td>
                                        <td>{{ $result->OrderDiscount3Type }}</td>
                                        <td>{{ $result->OrderDiscount4 }}</td>
                                        <td>{{ $result->OrderDiscount4Type }}</td>
    
                                        <td>{{ $result->InvoiceQuantity }}</td>
                                        <td>{{ $result->InvoicePrice }}</td>
                                        <td>{{ $result->InvoiceDiscount1 }}</td>
                                        <td>{{ $result->InvoiceDiscount1Type }}</td>
                                        <td>{{ $result->InvoiceDiscount2 }}</td>
                                        <td>{{ $result->InvoiceDiscount2Type }}</td>
                                        <td>{{ $result->InvoiceDiscount3 }}</td>
                                        <td>{{ $result->InvoiceDiscount3Type }}</td>
                                        <td>{{ $result->InvoiceDiscount4 }}</td>
                                        <td>{{ $result->InvoiceDiscount4Type }}</td>                                    
                                        <td>{{ $result->WeightUOM }}</td>
                                        <td>{{ $result->ActualWeightQuantity }}</td>
                                        <td>{{ $result->ItemStatus }}</td>
                                        <td>{{ $result->ItemErrorMessage }}</td>
    
    
                                        </tr>
                           
                                        @endforeach
                                    @else   
                                    <tr>
                                        <td colspan="60">
                                                <div class="alert alert-warning text-left"> No Data Found</div>                                        
                                        </td>                                    
                                    </tr>                                
                                    @endif
                                @else 
                                <tr>
                                        <td colspan="60">
                                                <div class="alert alert-info text-left">Please filter report</div>                                        
                                        </td>                                    
                                    </tr>                                
                                @endif
    
                                   
                                    </tbody>
                                </table>
        
                            </div>


                    </div>
                </div>
            </div>
        </section>
        <!-- END DATA TABLE-->

@endsection


@section('modals')
    
@endsection


@section('scripts')
   
    <script src="{{ asset('vendor/jquery-datatable/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/buttons.flash.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/jszip.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/pdfmake.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/vfs_fonts.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('vendor/jquery-datatable/extensions/export/buttons.print.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            $('#dt').DataTable({
                dom : 'Bfrtip',
                buttons: [
                    'copy', 'csv', 'excel', 'pdf', 'print'
                ]
            });
        });
    </script>


@endsection

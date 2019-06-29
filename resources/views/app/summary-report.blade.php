@extends('layouts.app')

@section('styles')
    <link href="{{ asset('vendor/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css') }}" rel="stylesheet">

    <link href="{{ asset('vendor/daterangepicker/daterangepicker-bs3.css') }}" rel="stylesheet">
    
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
                            <form action="{{ route('app.process-summary-report') }}" method="POST">    
                                @csrf        
                                   <div class="rs-select2--light rs-select2--md">
                                        <select class="js-select2" name="status">
                                            <option value="ANY">Any Status</option>
                                            <option value="PROCESSED">PROCESSED</option>
                                            <option value="OUT">OUT</option>
                                            <option value="FAILED">FAILED</option>
                                        </select>
                                        <div class="dropDownSelect2"></div>
                                    </div>
                                    <div class="rs-select2--light rs-select2--sm" style="width:300px;">
                                            <div class="input-group input-group-date" >        
                                                    <input
                                                        type="text"
                                                        class="form-control"
                                                        placeholder="Select Date Range"
                                                        id="txtDateRange"
                                                        style="border:none !important;padding:8px;"                                                        
                                                        required
                                                        value="{{ $date }}"
                                                        name="dateRange"/>
                                                </div>        
                                    </div>
                                    <button class="au-btn-filter bg-secondary text-white" type="submit">
                                        <i class="zmdi zmdi-filter-list"></i>filter</button>
                                </form>
                            </div>
    



                        </div>


                        <div class="au-card recent-report">
                                <button class="float-right btn btn-primary"  onclick="printDiv('print')">Print</button>
                                <div class="au-card-inner" id="print">
                                    <h3 class="title-5">Summary Report</h3>
                                    <small class="text-success"> Status : [{{ $status }}] ,  Date : [{{ $date }}]</small>
                                        <div class="row"> 
                                            <div class="col-md-6">
                                                <h3 class="title-3 mt-5 mb-2">Total Count</h3>                                                                                                
                                                <strong>Orders</strong>                
                                                <div class="summary-list">
                                                <span>{{ $orderSummary[0]->TotalOrders }}</span>
                                                    <div class="pull-right">
                                                        <strong>Orders Count</strong>
                                                    </div>
                                                </div>
                                                <div class="summary-list">
                                                    <span>{{ $orderSummary[0]->TotalLineItems }}</span>
                                                            <div class="pull-right">
                                                            <strong>Line Items Count</strong>
                                                        </div>
                                                    </div>
                                                    <div class="summary-list">
                                                            <span>{{ $orderSummary[0]->TotalQuantity }}</span>
                                                            <div class="pull-right">
                                                                <strong>Total Qty</strong>
                                                            </div>
                                                        </div>
                                                        <div class="summary-list">
                                                                <span>{{ $orderSummary[0]->TotalSales }}</span>
                                                                <div class="pull-right">
                                                                    <strong>Total Sales</strong>
                                                                </div>
                                                            </div>
                                                            <div class="summary-list">
                                                                    <span>{{ $orderSummary[0]->TotalDiscount }}</span>
                                                                    <div class="pull-right">
                                                                        <strong>Total Discount</strong>
                                                                    </div>
                                                                </div>
                                                                                        
                                                <strong>Returns</strong>                
                                                    <div class="summary-list">
                                                        <span>{{ $returnSummary[0]->TotalReturns }}</span>
                                                        <div class="pull-right">
                                                            <strong>Returns Count</strong>
                                                        </div>
                                                    </div>
                                                    <div class="summary-list">
                                                            <span>{{ $returnSummary[0]->TotalLineItems }}</span>
                                                            <div class="pull-right">
                                                                <strong>Line Items Count</strong>
                                                            </div>
                                                        </div>
                                                        <div class="summary-list">
                                                                <span>{{ $returnSummary[0]->TotalQuantity ? $returnSummary[0]->TotalQuantity : 0  }}</span>
                                                                <div class="pull-right">
                                                                    <strong>Total Qty</strong>
                                                                </div>
                                                            </div>
                                                            <div class="summary-list">
                                                                    <span>{{ $returnSummary[0]->TotalReturnAmount ? $returnSummary[0]->TotalReturnAmount : 0 }}</span>
                                                                    <div class="pull-right">
                                                                        <strong>Total Returns</strong>
                                                                    </div>
                                                                </div>
                                                                <div class="summary-list">
                                                                        <span>{{ $returnSummary[0]->TotalDiscount }}</span>
                                                                        <div class="pull-right">
                                                                            <strong>Total Discount</strong>
                                                                        </div>
                                                                    </div>
                                                                                            
                                                </div>


                                                <div class="col-md-6">

                                                        <h3 class="title-3 mt-5">Efficiency Metric</h3>                                                                                                
                                                        <div class="table-responsive mt-3">
                                                                <table class="table table-top-campaign">
                                                                    <tbody>
                                                                        <tr>
                                                                            <td> {{ $orderSummary[0]->TotalLineItems }} </td>
                                                                            <td>Total Count of Line Items</td>
                                                                        </tr>
                                                                        <tr>
                                                                            <td> {{ $metricSummary[0]->TotalCountBuyingCustomer }} </td>
                                                                                <td>Count of Buying Customers</td>
                                                                        </tr>
                                                                        <tr >
                                                                            <td class="text-danger"><strong> {{ $metricSummary[0]->EfficienyRate }} </strong></td>
                                                                                <td class="text-danger"><strong>Efficiency Rate</strong></td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                            </div>
                        
                
                                                            <h3 class="title-3 mb-3 mt-5">Service Metric</h3>
                        
                                                            <div class="table-wrap">
                                                                    <div class="table-responsive">
                                                                        <table class="table table-borderless table-striped">
                                                                            <thead class="bg-secondary text-white">
                                                                                <th>Description</th>
                                                                                <th width="10%">Total</th>
                                                                                <th width="10%">Actual</th>
                                                                            </thead>
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>                                                                                        
                                                                                        <div class="au-progress">
                                                                                            <span class="au-progress__title">Case Fill Rate</span>
                                                                                            <span class="au-progress__title pull-right">{{ $metricSummary[0]->CaseFillRate }} %</span>
                                                                                            <div class="au-progress__bar">
                                                                                                <div class="au-progress__inner js-progressbar-simple bg-success" role="progressbar" data-transitiongoal="{{ $metricSummary[0]->CaseFillRate }}">
                                                                                                </div>
                                                                                            </div>                                        
                                                                                        </div>                                                                                                                        
                                                                                    </td>
                                                                                    <td>
                                                                                            <div class="au-progress">
                                                                                            <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalOrderQty }} </strong>
                                                                                            </div>
                                                                                    </td>
                                                                                    <td>
                                                                                            <div class="au-progress">
                                                                                            <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalInvoiceQty }}</strong>
                                                                                            </div>
                                                                                    </td>
                                            
                                                                                </tr>
                                            
                                                                                <tr>
                                                                                        <td>                                                                                        
                                                                                            <div class="au-progress">
                                                                                                <span class="au-progress__title">Line Fill Rate</span>
                                                                                                <span class="au-progress__title pull-right">{{ $metricSummary[0]->LineFillRate }} %</span>
                                                                                                <div class="au-progress__bar">
                                                                                                    <div class="au-progress__inner js-progressbar-simple bg-info" role="progressbar" data-transitiongoal="{{ $metricSummary[0]->LineFillRate }}">
                                                                                                    </div>
                                                                                                </div>                                        
                                                                                            </div>                                                                                                                        
                                                                                        </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $orderSummary[0]->TotalLineItems }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalLineFill }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                            
                                                                                    </tr>
                                                                                    <tr>
                                                                                            <td>                                                                                        
                                                                                                <div class="au-progress">
                                                                                                    <span class="au-progress__title">Order Fill Rate</span>
                                                                                                <span class="au-progress__title pull-right">{{ $metricSummary[0]->OrderFillRate }} %</span>
                                                                                                    <div class="au-progress__bar">
                                                                                                        <div class="au-progress__inner js-progressbar-simple bg-warning  " role="progressbar" data-transitiongoal="{{ $metricSummary[0]->OrderFillRate }}" >
                                                                                                        </div>
                                                                                                    </div>                                        
                                                                                                </div>                                                                                                                        
                                                                                            </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalOrderQty }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalOrderFill }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                            
                                                                                        </tr>
                                                                                        <tr>
                                                                                                <td>                                                                                        
                                                                                                    <div class="au-progress">
                                                                                                        <span class="au-progress__title">Timeliness</span>
                                                                                                        <span class="au-progress__title pull-right">{{ $metricSummary[0]->Timeliness }} %</span>
                                                                                                        <div class="au-progress__bar">
                                                                                                            <div class="au-progress__inner js-progressbar-simple bg-danger  " role="progressbar" data-transitiongoal="{{ $metricSummary[0]->Timeliness }}">
                                                                                                            </div>
                                                                                                        </div>                                        
                                                                                                    </div>                                                                                                                        
                                                                                                </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $orderSummary[0]->TotalOrders }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalTimeliness }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                    <td>                                                                                        
                                                                                                        <div class="au-progress">
                                                                                                            <span class="au-progress__title">OTIF</span>
                                                                                                            <span class="au-progress__title pull-right">{{ $metricSummary[0]->OTIF }} %</span>
                                                                                                            <div class="au-progress__bar">
                                                                                                                <div class="au-progress__inner js-progressbar-simple bg-primary " role="progressbar" data-transitiongoal="{{ $metricSummary[0]->OTIF }}">
                                                                                                                </div>
                                                                                                            </div>                                        
                                                                                                        </div>                                                                                                                        
                                                                                                    </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $orderSummary[0]->TotalOrders }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                                                                                    <td>
                                                                                                            <div class="au-progress">
                                                                                                                <strong class="au-progress__title pull-right">{{ $metricSummary[0]->TotalOTIF }}</strong>
                                                                                                            </div>
                                                                                                    </td>
                                            
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
                </div>
            </div>
        </section>
        <!-- END DATA TABLE-->

@endsection


@section('modals')
    
@endsection


@section('scripts')
   
    <script src="{{ asset('vendor/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ asset('vendor/daterangepicker/moment.min.js') }}"></script>
    <script src="{{ asset('vendor/datepicker/js/bootstrap-datepicker.js') }}"></script>    

    <script>
             $(document).ready(function(){
                $('#txtDateRange').daterangepicker();
            });

     function printDiv(divName){
			var printContents = document.getElementById(divName).innerHTML;
			var originalContents = document.body.innerHTML;
			document.body.innerHTML = printContents;
			window.print();
			document.body.innerHTML = originalContents;
		}
</script>
@endsection

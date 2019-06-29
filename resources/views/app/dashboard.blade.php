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
                                    <li class="list-inline-item active">Dashboard</li>
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

                <div class="row m-t-25">
                       
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c2 ">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="zmdi zmdi-check-square"></i>
                                        </div>
                                        <div class="text">
                                        <h2>{{ $orderSummary[0]->TotalOrders }}</h2>
                                            <span>Processed Orders</span>
                                            <hr>
                                            <h2>{{ $returnSummary[0]->TotalReturns }}</h2>
                                            <span>Processed Returns</span>
                                        </div>
                                        </div>
                              
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                                <div class="overview-item overview-item--c1">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="fa fa-recycle"></i>
                                            </div>
                                            <div class="text">
                                                {{-- <div class="progress mb-3">
                                                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>             --}}
                                                        <h2>{{ $orderSummary[0]->countPROCESSING }}</h2>

                                                    <span>Processing Orders</span>
                                                <hr>
                                                {{-- <div class="progress mb-3">
                                                        <div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 75%" aria-valuenow="75"
                                                            aria-valuemin="0" aria-valuemax="100"></div>
                                                    </div>             --}}
                                                    <h2>{{ $returnSummary[0]->countPROCESSING }}</h2>
                                                    <span>Processing Returns</span>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c4">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fa fa-reply"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{ $orderSummary[0]->countOUT }}</h2>
                                            <span>OUT Orders</span>
                                            <hr>
                                            <h2>{{ $returnSummary[0]->countOUT }}</h2>
                                            <span>OUT Returns</span>
                                        </div>
                                    </div>
                              
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6 col-lg-3">
                            <div class="overview-item overview-item--c3">
                                <div class="overview__inner">
                                    <div class="overview-box clearfix">
                                        <div class="icon">
                                            <i class="fa fa-warning"></i>
                                        </div>
                                        <div class="text">
                                            <h2>{{ $orderSummary[0]->countFAILED }}</h2>
                                            <span>Failed Orders</span>
                                            <hr>
                                            <h2>{{ $returnSummary[0]->countFAILED }}</h2>
                                            <span>Failed Returns</span>

                                        </div>
                                    </div>
                                 
                                </div>
                            </div>
                        </div>
                

                    </div>
          
        </div>



        <?php
            
        $allProcessOrder = 1222;        
        $allProcessReturnOrder = 233;
        $totalProcessData = $allProcessOrder  +  $allProcessReturnOrder;
        if($totalProcessData == 0)
            $totalProcessData = 1;

        (int)$orderPercentage = ($allProcessOrder / $totalProcessData) * 100;
        
        (int)$orderReturnPercentage = ($allProcessReturnOrder / $totalProcessData) * 100;
        
        
        $dateNow = date('Y-m-d');
        
        $yearNow = date('Y');
        
        $monthNow = date('m');
        
        $dateUse = $yearNow."-".$monthNow;
        
        $newDate = array();
        $newDateMonth = array();
        $decrement = false;
        
        $newMonth = $monthNow;
        
        for($i = 1; $i <= 7; $i++){
        
        
            if($newMonth == 1 && !$decrement){
                $monthNow = 12;                                
                $yearNow--;
                $decrement = true;
                $decrementMonth = 1;
                $newMonth = 12;
            }else if($i > 1 && $decrement){
                 $newMonth  = $monthNow - $decrementMonth;
                 $decrementMonth++;
            }else
                $newMonth = $monthNow - $i;
        
            
            array_push($newDate, $yearNow."-".$newMonth);
            array_push($newDateMonth, date('M - Y', strtotime($yearNow."-".$newMonth)));
        
        }
        
        
        $revNewDateMonth = array_reverse($newDateMonth);
        $revNewDate = array_reverse($newDate);
        
        
        for($i = 0; $i < 7; $i++){
        
            echo '<div id="monthName'.($i + 1).'" data-title="'.$revNewDateMonth[$i].'"></div>';
        
        }
        
        
        $totalSumOfOrderPercentage = 0;
        $totalSumOfOrderReturnPercentage = 0;
        $totalSumOfOrder = 0;
        $totalSumOfOrderReturn = 0;
        
        $totalPercentageOfOrderInMonth1 = 0;
        $totalPercentageOfOrderReturnInMonth1 = 0;
        $totalSumOfOrderInMonth1 = 0;
        $totalSumOfOrderReturnInMonth1 = 0;
        $totalPercentageOfOrderInMonth7 = 0;
        $totalPercentageOfOrderReturnInMonth7 = 0;
        $totalSumOfOrderInMonth7 = 0;
        $totalSumOfOrderReturnInMonth7 = 0;
        
        
        $totalCountInEveryMonthInOrder = $order->getTotalPerMonth($revNewDate);
        
        
        $totalCountInEveryMonthInOrderReturn = $return->getTotalPerMonth($revNewDate);
        
         
        foreach($totalCountInEveryMonthInOrder as $key => $values){
        
            foreach($totalCountInEveryMonthInOrderReturn as $key2 => $values2){
        
                if($key === $key2){
        
        
                    if($values != 0 && $values2 != 0){
        
                        if($key === 'month1'){
                            $totalSumOfOrderInMonth1 = $values;
                            $totalSumOfOrderReturnInMonth1 = $values2;                    
                            $totalSumOfOrderAndReturnInMonth1 = $values + $values2;
                            $totalPercentageOfOrderInMonth1 = ($values / $totalSumOfOrderAndReturnInMonth1) * 100;
                            $totalPercentageOfOrderReturnInMonth1 = ($values2 / $totalSumOfOrderAndReturnInMonth1) * 100;
                        }else if($key === 'month7'){
        
                            $totalSumOfOrderInMonth7 = $values;
                            $totalSumOfOrderReturnInMonth7 = $values2;
                            
                            $totalSumOfOrderAndReturnInMonth7 = $totalSumOfOrderInMonth7 + $totalSumOfOrderReturnInMonth7;
        
                            $totalPercentageOfOrderInMonth7 = number_format(($values / $totalSumOfOrderAndReturnInMonth7) * 100 ,2,'.','');
                            $totalPercentageOfOrderReturnInMonth7 = number_format(($values2 / $totalSumOfOrderAndReturnInMonth7) * 100,2,'.','');
                            echo '<div id="totalPercentageOfOrder'.$key.'" data-value="'.$totalPercentageOfOrderInMonth7.'"></div>';
        
                            echo '<div id="totalPercentageOfOrderReturn'.$key.'" data-value="'.$totalPercentageOfOrderReturnInMonth7.'"></div>';
        
        
                            echo '<div id="totalSumOfOrderIn'.$key.'" data-value="'.$values.'"></div>';
        
                            echo '<div id="totalSumOfOrderReturnIn'.$key.'" data-value="'.$values2.'"></div>';
        
        
                            
                        }else{
        
                            $totalSumOfOrder =  $totalSumOfOrder + $values;
                            $totalSumOfOrderReturn = $totalSumOfOrderReturn + $values2;
        
                            $totalSumOfOrderAndReturn = $values + $values2;
        
        
                            //total percenrage of order per month
                            $totalPercentageOfOrder = ($values / $totalSumOfOrderAndReturn) * 100;
        
                            //add the total percentage of order per month
                            $totalSumOfOrderPercentage = $totalSumOfOrderPercentage + $totalPercentageOfOrder;
        
        
                            //total percentage of order return per month
                            $totalPercentageOfOrderReturn = ($values2 / $totalSumOfOrderAndReturn) * 100;
        
                            //add the total percentage of order return per month
                            $totalSumOfOrderReturnPercentage = $totalSumOfOrderReturnPercentage + $totalPercentageOfOrderReturn;
        
        
                            echo '<div id="totalPercentageOfOrder'.$key.'" data-value="'.$totalPercentageOfOrder.'"></div>';
        
                            echo '<div id="totalPercentageOfOrderReturn'.$key.'" data-value="'.$totalPercentageOfOrderReturn.'"></div>';
        
        
                            echo '<div id="totalSumOfOrderIn'.$key.'" data-value="'.$values.'"></div>';
        
                            echo '<div id="totalSumOfOrderReturnIn'.$key.'" data-value="'.$values2.'"></div>';
        
        
                        }
        
        
                    }else{
        
        
                        echo '<div id="totalPercentageOfOrder'.$key.'" data-value="0"></div>';
        
                        echo '<div id="totalPercentageOfOrderReturn'.$key.'" data-value="0"></div>';
        
        
                        echo '<div id="totalSumOfOrderIn'.$key.'" data-value="'.$values.'"></div>';
        
                        echo '<div id="totalSumOfOrderReturnIn'.$key.'" data-value="'.$values2.'"></div>';
        
                    } 
        
                }
        
            }
            
        
        }
        
        
        
        
        
        
        
        // ================== COMPUTATION DISPLAY RESULT OF PERCENTAGE OF ORDER AND RETURN ORDER IN 6 MONTHS =========        
        //order average in 5 months and in month 7
        $orderAverageIn5monthsAndMonth7 =  number_format(( ($totalSumOfOrderPercentage + $totalPercentageOfOrderInMonth7) / 600 ) * 100,2,'.','');
        
        //order return average in 5 months and in month 7
        $orderReturnAverageIn5monthsAndMonth7 =  number_format((( $totalSumOfOrderReturnPercentage + $totalPercentageOfOrderReturnInMonth7 )/ 600 ) * 100,2,'.','');
        
        
        //order average in 5 months and in month 1
        $orderAverageIn5monthsAndMonth1 =  number_format(( ($totalSumOfOrderPercentage + $totalPercentageOfOrderInMonth1) / 600 ) * 100,2,'.','');
        
        
        //order average in 5 months and in month 1
        $orderReturnAverageIn5monthsAndMonth1 =  number_format(( ($totalSumOfOrderPercentage + $totalPercentageOfOrderReturnInMonth1) / 600 ) * 100,2,'.','');
        
        
        //result percentage in order
        $resultPercentageInOrder = $orderAverageIn5monthsAndMonth7 - $orderAverageIn5monthsAndMonth1;
        if($resultPercentageInOrder > 0){
            $displayOrderResultPercentage = '<i class="fa fa-arrow-up" style="color:#4caf50;"></i> '.$resultPercentageInOrder."%";
        }else{
            $displayOrderResultPercentage = '<i class="fa fa-arrow-down" style="color:red;"></i> '.$resultPercentageInOrder."%";
        }
        
        
        //result percentage in order return
        $resultPercentageInOrderReturn = $orderReturnAverageIn5monthsAndMonth7 - $orderReturnAverageIn5monthsAndMonth1;
        if($resultPercentageInOrderReturn > 0){
            $displayOrderReturnResultPercentage = '<i class="fa fa-arrow-up" style="color:#4caf50;"></i> '.$resultPercentageInOrderReturn."%";
        }else{
            $displayOrderReturnResultPercentage = '<i class="fa fa-arrow-down" style="color:red;"></i> '.$resultPercentageInOrderReturn."%";
        }
        
        // ================== COMPUTATION DISPLAY RESULT OF TOTAL SUM IN ORDER AND RETURN ORDER IN 6 MONTHS =========
        
        $orderTotalIn5monthsAndMonth7 =  $totalSumOfOrder + $totalSumOfOrderInMonth7;
        
        $orderReturnTotalIn5monthsAndMonth7 =  $totalSumOfOrderReturn + $totalSumOfOrderReturnInMonth7;
        
        
        $orderTotalIn5monthsAndMonth1 =  $totalSumOfOrder + $totalSumOfOrderInMonth1;
        
        $orderReturnTotalIn5monthsAndMonth1 =  $totalSumOfOrderReturn + $totalSumOfOrderReturnInMonth1;
        
        $resultTotalInOrder = $orderTotalIn5monthsAndMonth7 - $orderTotalIn5monthsAndMonth1;
        
        if($resultTotalInOrder > 0){
            $displayOrderResultTotal = '<i class="fa fa-arrow-up" style="color:#4caf50;"></i> '.$resultTotalInOrder;
        }else{
            $displayOrderResultTotal = '<i class="fa fa-arrow-down" style="color:red;"></i> '.$resultTotalInOrder;
        }
        
        
        
        $resultTotalInOrderReturn = $orderReturnTotalIn5monthsAndMonth7 - $orderReturnTotalIn5monthsAndMonth1;
        
        if($resultTotalInOrderReturn > 0){
            $displayOrderReturnResultTotal = '<i class="fa fa-arrow-up" style="color:#4caf50;"></i> '.$resultTotalInOrderReturn;
        }else{
            $displayOrderReturnResultTotal = '<i class="fa fa-arrow-down" style="color:red;"></i> '.$resultTotalInOrderReturn;
        }
        
        
          
?>
    


        <div class="container">
            <div class="row">

                <div class="col-lg-8">

                        <div class="au-card recent-report">
                                <div class="au-card-inner">
                                        <h3 class="title-5">Overall Summary <small class="text-success"> [PROCESEED]</small></h3>
                                        <div class="row">
                                            <div class="col-lg-6" style="border-right:solid 1px #d8d8d6;">
                                                <small>Orders</small>                
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
                                                                                        
                                            </div>
                                            <div class="col-lg-6">
                                                    <small>Returns</small>                
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
            
                                            
                                        </div>
                                </div>
                            </div>
                    
        
                </div>

                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fa fa-home"></i>
                            <strong class="card-title pl-2">Company Detail</strong>
                        </div>
                        <div class="card-body">
                            <div class="mx-auto d-block">
                            <img class="rounded-circle mx-auto d-block" src="{{ asset($company->logo) }}" style="width:100px;" alt="Card image cap">
                            <h5 class="text-sm-center mt-2 mb-1">{{ $company->company_name }}</h5>
                                <div class="location text-sm-center">
                                <i class="fa fa-envelope"></i> {{ $company->company_email }}</div>
                            </div>
                            <hr>
                            <div class="card-text text-sm-center">
                            <a href="{{ route('app.company-settings') }}">
                                    Update Details
                                </a>
                            </div>
                        </div>
                    </div>                
                
                </div>

                
                <div class="col-lg-6">
                    <form method="GET">
                        <div class="au-card recent-report">
                                <h3 class="title-3">Efficiency Metric</h3>                                                            
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
                        </div>
                    </form>
        
        
                    <div class="au-card recent-report">
                        <h3 class="title-3 mb-3">Service Metric</h3>
                        
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


                <div class="col-lg-6">
                    <div class="au-card recent-report">
                        <div class="au-card-inner">
                            <h3 class="title-2">Orders</h3>
                            <div class="chart-info">
                                <div class="chart-info__left">
                             
                                    <div class="chart-note mr-0">
                                        <span class="dot dot--green"></span>
                                        <span>Orders Count</span>
                                    </div>
                                </div>
                                <div class="chart-info__right">
                                    <div class="chart-statis">
                                        <span class="index incre">
                                        {{-- <i class="zmdi zmdi-long-arrow-up"></i> --}}
                                        <i class="zmdi zmdi-plus"></i>
                                        {{ $orderTotalIn5monthsAndMonth7 }}                                    
                                    </span>
                                        <span class="label">Total Orders in 6 Months</span>
                                    </div>
                                    <div class="chart-statis mr-0">
                                        <span class="index decre">
                                            <?php echo $displayOrderResultTotal; ?> %
                                        </span>
                                        <span class="label">Percentage</span>
                                    </div>
                                </div>
                            </div>
                            <div class="recent-report__chart">
                                <canvas id="order-chart"></canvas>
                            </div>
                        </div>
                    </div>
            
                    <div class="au-card recent-report">
                        <div class="au-card-inner">
                            <h3 class="title-2">Returns</h3>
                            <div class="chart-info">
                                <div class="chart-info__left">
                   
                                    <div class="chart-note mr-0">
                                        <span class="dot dot--red"></span>
                                        <span>Returns Count</span>
                                    </div>
                                </div>
                                <div class="chart-info__right">
                                    <div class="chart-statis">
                                        <span class="index incre">
                                        <i class="zmdi zmdi-plus"></i>{{ $orderReturnTotalIn5monthsAndMonth7 }}</span>
                                        <span class="label">Total Returns in 6 Months</span>
                                    </div>
                                    <div class="chart-statis mr-0">
                                        <span class="index decre">
                                            <?php echo $displayOrderReturnResultTotal; ?> %   
                                        </span>
                                        <span class="label">Percentage </span>
                                    </div>
                                </div>
                            </div>
                            <div class="recent-report__chart">
                                <canvas id="return-chart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

              
            </div>

        </div>


        

        
    </section>
    <!-- END STATISTIC-->



@endsection


@section('modals')
    
@endsection


@section('scripts')
    <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>

@endsection

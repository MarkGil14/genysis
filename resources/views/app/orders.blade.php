@extends('layouts.app')

@section('styles')
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
                                        <li class="list-inline-item active">Orders</li>
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
                                <div class="overview-item overview-item--c2 pb-3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="zmdi zmdi-check-square"></i>
                                            </div>
                                            <div class="text">
                                                <h2>{{ $orderProcessedCount }}</h2>
                                                <span>Processed Orders</span>
                                        
                                            </div>
                                        </div>
                     
                                    </div>
                                </div>
                            </div>
    
                            <div class="col-sm-6 col-lg-3">
                                    <div class="overview-item overview-item--c1 pb-3">
                                        <div class="overview__inner">
                                            <div class="overview-box clearfix">
                                                <div class="icon">
                                                    <i class="fa fa-recycle"></i>
                                                </div>
                                                <div class="text">
                                                        <h2>{{ $orderProcessingCount }}</h2>
                                                        <span>Processing Orders</span>                                        
                                                </div>
                                            </div>
                       
                                        </div>
                                    </div>
                                </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="overview-item overview-item--c4 pb-3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="fa fa-reply"></i>
                                            </div>
                                            <div class="text">
                                                <h2>{{ $orderOutCount }}</h2>
                                                <span>OUT Orders</span>
                                
                                            </div>
                                        </div>
                          
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="overview-item overview-item--c3 pb-3">
                                    <div class="overview__inner">
                                        <div class="overview-box clearfix">
                                            <div class="icon">
                                                <i class="fa fa-warning"></i>
                                            </div>
                                            <div class="text">
                                                <h2>{{ $orderFailedCount }}</h2>
                                                <span>Failed Orders</span>                         
                                            </div>
                                        </div>
                       
                                    </div>
                                </div>
                            </div>
                        </div>
              
            </div>
    
            
        </section>
        <!-- END STATISTIC-->
    

    <!-- DATA TABLE-->
    <section class="p-t-20">
        
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-data__tool">
                            <div class="table-data__tool-left">
                                <button class="au-btn au-btn-icon bg-danger au-btn--small"  id="deleteOrder" type="button">
                                    <i class="zmdi zmdi-delete"></i>Delete Order</button>                             
                            </div>
                        <form action="{{ route('app.filter-orders') }}" method="GET">
                            @csrf
                            <div class="table-data__tool-right">                                  
                                                                      
                                    <div class="rs-select2--light rs-select2--md">
                                        <select class="js-select2" name="status">
                                            <option value="">Any Status</option>
                                            <option value="PROCESSED">PROCESSED</option>
                                            <option value="PROCESSING">PROCESSING</option>
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
                                    <button class="au-btn-filter bg-secondary text-white">
                                        <i class="zmdi zmdi-filter-list"></i>filters</button>
                                </div>
                            </form>
    

                        </div>
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>
                                            <label class="au-checkbox" >
                                                <input type="checkbox"  onclick="toggle(this);">
                                                <span class="au-checkmark"></span>
                                            </label>
                                        </th>
                                        <th>Sales Type</th>
                                        <th>Invoice Number</th>
                                        <th>SO Number</th>
                                        <th>Invoice Date</th>                                                  
                                        <th>Status</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if(count($orders) > 0)
                                    @foreach ($orders as $order)                                        
                                    <tr class="tr-shadow" id="orderRow-{{ $order->KeyId }}">
                                        <td>
                                                <label class="au-checkbox ">
                                                    <input type="checkbox"  name="orders[]" value="{{ $order->Guid }}" id="{{ $order->KeyId }}" class="orderCheckbox">
                                                    <span class="au-checkmark"></span>
                                                </label>
                                        </td>
                                        <td>{{ $order->SalesType }}</td>
                                        <td>
                                            <span class="block-email">{{ $order->InvoiceNumber }}</span>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ $order->SalesOrderNumber }}</span>
                                        </td>
                                        <td>{{ date('F d, Y', strtotime($order->InvoiceDate)) }}</td>
                            
                                        <td>

                                            @if ($order->Status == 'PROCESSED' && $order->ErrorMessage == null)
                                                <span class="status--process">PROCESSED</span>
                                            @elseif($order->Status == 'OUT')                                             
                                                <span class="status--out">{{ $order->Status }}</span>                                                
                                            @elseif($order->Status == 'PROCESSING')                                             
                                                <span class="status--out">{{ $order->Status }}</span>                                                
                                            @elseif($order->ErrorMessage != null)
                                                <span class="status--denied">FAILED</span>                                                
                                            @else 
                                                <span class="status--denied">UNKNOWN</span>                                                
                                            @endif


                                        </td>
                
                                        <td>
                                            <div class="table-data-feature">

                                                <a href="{{ route('app.order-detail', ['key_id' => $order->KeyId]) }}" class="item" data-toggle="tooltip" data-placement="top" title="View Order" target="_blank">
                                                    <i class="fa fa-book"></i>
                                                </a>

                        

                                                <a href="{{ route('app.order-edit', ['key_id' => $order->KeyId]) }}" class="item" data-toggle="tooltip" data-placement="top" title="{{ $order->ErrorMessage }}" target="_blank">
                                                        @if(!is_null($order->ErrorMessage))
                                                        <i class="fa fa-circle text-danger alert-icon-failed-order"></i>
                                                        @endif
                                                        <i class="zmdi zmdi-wrench"></i>
                                                    </a>                                                

                                                    @if($order->Status != 'OUT')
                                                        <a class="item" data-toggle="tooltip" data-placement="top" title="OUT" href="{{ route('app.order-change-status', ['keyid' => $order->KeyId , 'status' => 'OUT']) }}">
                                                            <i class="fa fa-share"></i>
                                                        </a>
                                                    @endif


                                                    @if(!is_null($order->Id) && !$order->IsDelete)
                                                    <a href="{{ route('app.order-is-delete', ['guid' => $order->Guid]) }}" class="item" data-toggle="tooltip" data-placement="top" title="Set to delete">
                                                        <i class="zmdi zmdi-delete"></i>
                                                    </a>
                                                    @endif
                                                    
                                                    @if(!is_null($order->Id) && $order->IsDelete)
                                                    <a href="{{ route('app.order-cancel-delete', ['guid' => $order->Guid]) }}" class="item" data-toggle="tooltip" data-placement="top" title="Cancel delete">
                                                        <i class="zmdi zmdi-check"></i>
                                                    </a>
                                                    @endif
    


                                            </div>
                                        </td>
                                    </tr>
                                    <tr class="spacer"></tr>

                                    @endforeach
                                @else   
                                <tr>
                                    <td colspan="8">
                                            <div class="alert alert-warning"> No Data Found</div>                                        
                                    </td>                                    
                                </tr>                                
                                @endif

                               
                                </tbody>
                            </table>
                            {{ $orders->links() }}

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
   

<script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}"></script>

<script>
     $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });     

            function toggle(source) {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i] != source)
                        checkboxes[i].checked = source.checked;
                }
            }




    $('#deleteOrder').click(function () {
        swal({
            title: "Are you sure?",
            text: "This Orders will be Deleted!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#4caf50",
            confirmButtonText: "Yes, Delete it!",
            cancelButtonText: "No, cancel please!",
            showLoaderOnConfirm: false,
            closeOnConfirm: true,
            closeOnCancel: false
        }, function (isConfirm) {
            if (isConfirm) {
                $(".orderCheckbox:checkbox:checked").each(function (i) {

                    keyId = $(this).attr('id');
                    $.ajax({
                        url: "{{ route('app.delete-order') }}",
                        type: 'POST',
                        data: {
                            keyid : keyId
                        },
                        cache: false,
                        dataType: 'text',
                        beforeSend: function (i) {
                            $('#orderRow-' + keyId).empty();
                        },
                        error : function(err){
                            console.log(err);
                        },
                        success: function (data) {
                            if (data == 'false') {
                                swal("Cancelled", "Delete Order Failed ! Something wrong", "error");
                            }
                        }
                    });

                });

                // showNotification('alert-success', $(".orderCheckbox:checkbox:checked").length+' Orders was Successfully Deleted !', 'bottom', 'left', 'animated fadeInLeft', 'animated fadeOutLeft');

            } else {
                swal("Cancelled", "Your Delete Request was Cancelled", "error");
            }
        });



    });



        </script>

@endsection

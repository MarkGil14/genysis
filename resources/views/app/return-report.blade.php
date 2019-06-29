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
                                            <li class="list-inline-item active">Return Report</li>
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
                            <form action="{{ route('app.filter-return-report') }}" method="POST">    
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
                                            <th>Sif ID</th>
                                            <th>Type of return</th>
                                            <th>Credit Memo Number</th>
                                            <th>Invoice Number</th>
                                            <th>Return Date</th>
                                            <th>SAS ID</th>
                                            <th>DSP ID</th>
                                            <th>Account ID</th>
                                            <th>Reason Of Return</th>
                                            <th>Transaction ID</th>
                                            <th>Return Status</th>
                                            <th>Return ErrorMessage</th>
                                            <th>Return Item Sif ID</th>
                                            <th>Product ID</th>
                                            <th>Conversion ID</th>
                                            <th>Return Qty</th>
                                            <th>Price</th>
                                            <th>Discount Amount</th>
                                            <th>Condition</th>
                                            <th>Return Type</th>
                                            <th>Reason of Rejection</th>
                                            <th>Return Item Status</th>
                                            <th>Return Item ErrorMessage</th>
                                        </tr>
                                    </thead>
                                    <tbody>
    
     
                                    @if($results)
                                    @if(count($results) > 0)
                                        @foreach ($results as $result)
                                            
                                        <tr class="tr-shadow">
                                        <td>{{ $result->Id }}</td>     
                                        <td>{{ $result->TypeOfReturn }}</td> 
                                        <td>{{ $result->CreditMemoNumber }}</td>
                                        <td>{{ $result->InvoiceNumber }}</td>
                                        <td>{{ $result->ReturnDate }}</td>
                                        <td>{{ $result->SASId }}</td>
                                        <td>{{ $result->DSPId }}</td>
                                        <td>{{ $result->AccountId }}</td>
                                        <td>{{ $result->ReasonOfReturn }}</td>
                                        <td>{{ $result->TransactionId }}</td>
                                        <td>{{ $result->ReturnStatus }}</td>
                                        <td>{{ $result->ReturnErrorMessage }}</td>
                                        <td>{{ $result->ItemSifId }}</td>
                                        <td>{{ $result->ProductId }}</td>
                                        <td>{{ $result->ConversionId }}</td>
                                        <td>{{ $result->ReturnedQty }}</td>
                                        <td>{{ $result->Price }}</td>
                                        <td>{{ $result->DiscountAmount }}</td>
                                        <td>{{ $result->Condition }}</td>
                                        <td>{{ $result->ReturnType }}</td>
                                        <td>{{ $result->ReasonOfRejection }}</td>
                                        <td>{{ $result->ReturnItemStatus }}</td>
                                        <td>{{ $result->ReturnItemErrorMessage }}</td>
    
                                        </tr>
                           
                                        @endforeach
       
                                    @else
                                    <tr>
                                        <td colspan="21">
                                                <div class="alert alert-warning text-left"> No Data Found</div>                                        
                                        </td>                                    
                                    </tr>                                
                                    @endif

                                    @else
                                    <tr>
                                        <td colspan="21">
                                                <div class="alert alert-info text-left"> Please Filter report</div>                                        
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

@extends('layouts.app')

@section('styles')
 
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
                                        <li class="list-inline-item active">Search</li>
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


    <!-- DATA TABLE-->
    <section class="p-t-20">
        
            <div class="container">
                <div class="row">
                    <div class="col-md-12">

                    <div class="au-card">
                            <div class="card-header bg-secondary text-white">         
                                {{ $_GET['keyword'] }}
                            </div>

                                <div class="card-body">
                                        <div class="default-tab">
                                            <nav>
                                                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                    <a class="nav-item nav-link active" id="orders-tab" data-toggle="tab" href="#orders" role="tab" aria-controls="orders"
                                                    aria-selected="true">Orders
                                                        @if(count($orders) > 0)
                                                            <span class="badge badge-secondary">{{ count($orders) }}</span>
                                                        @endif
                                                
                                                    </a>

                                                    <a class="nav-item nav-link" id="returns-tab" data-toggle="tab" href="#returns" role="tab" aria-controls="returns"
                                                    aria-selected="false">Returns
                                                        @if(count($returns) > 0)
                                                            <span class="badge badge-secondary">{{ count($returns) }}</span>
                                                        @endif
                                                
                                                    </a>

                                                    <a class="nav-item nav-link" id="items-tab" data-toggle="tab" href="#items" role="tab" aria-controls="items"
                                                    aria-selected="false">Item
                                                    @if(count($items) > 0)
                                                        <span class="badge badge-secondary">{{ count($items) }}</span>
                                                    @endif
                                        
                                                    </a>

                                                    <a class="nav-item nav-link" id="accounts-tab" data-toggle="tab" href="#accounts" role="tab" aria-controls="accounts"
                                                    aria-selected="false">Account
                                                    @if(count($accounts) > 0)
                                                        <span class="badge badge-secondary">{{ count($accounts) }}</span>
                                                    @endif

                                                    </a>

                                                    <a class="nav-item nav-link" id="dsp-tab" data-toggle="tab" href="#dsp" role="tab" aria-controls="dsp"
                                                    aria-selected="false">DSP 
                                                        @if(count($dsp) > 0)
                                                            <span class="badge badge-secondary">{{ count($dsp) }}</span>
                                                        @endif
                                                    </a>


                                                </div>
                                            </nav>
                                            <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                                <div class="tab-pane fade show active" id="orders" role="tabpanel" aria-labelledby="orders-tab">
                                                        <div class="table-responsive m-b-40">
                                                                <table class="table table-borderless table-data3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>SalesType</th>
                                                                            <th>InvoiceNumber</th>
                                                                            <th>SalesOrder Number</th>
                                                                            <th>Account ID</th>
                                                                            <th>Invoice Date</th>
                                                                            <th>Status</th>
                                                                            <th>View</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                @if(count($orders) > 0)
                                                                    @foreach ($orders as $order)
                                                                        <tr>
                                                                            <td>{{ $order->SalesType }}</td>
                                                                            <td>{{ $order->InvoiceNumber }}</td>
                                                                            <td>{{ $order->SalesOrderNumber }}</td>
                                                                            <td>{{ $order->AccountReferenceId }}</td>
                                                                            <td>{{ $order->InvoiceDate }}</td>
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
                                                                                <a href="{{ route('app.order-detail', ['key_id' => $order->KeyId]) }}" class="item" data-toggle="tooltip" data-placement="top" title="View Order" target="_blank">
                                                                                        <i class="fa fa-book"></i>
                                                                                    </a>                                        
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                    @endforeach
                                                                @else 
                                                                        <tr>
                                                                                <td colspan="7">
                                                                                <div class="alert alert-warning text-center">No data Found</div>    
                                                                                </td>
                                                                        </tr>
                                                                @endif
                                                                                                                                            
                                                                    </tbody>
                                                                </table>
                                                            </div>
                                                </div>

                                                <div class="tab-pane fade" id="returns" role="tabpanel" aria-labelledby="returns-tab">
                                                        <div class="table-responsive m-b-40">
                                                                <table class="table table-borderless table-data3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Type of Return</th>
                                                                            <th>InvoiceNumber</th>
                                                                            <th>Account ID</th>
                                                                            <th>Return Date</th>
                                                                            <th>Reason of return</th>
                                                                            <th>Status</th>
                                                                            <th>View</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    @if(count($returns) > 0)
                                                                    @foreach ($returns as $return)                                                                    
                                                                        <tr>
                                                                            <td>{{ $return->TypeOfReturn }}</td>
                                                                            <td>{{ $return->InvoiceNumber }}</td>
                                                                            <td>{{ $return->AccountId }}</td>
                                                                            <td>{{ $return->ReturnDate }}</td>
                                                                            <td>{{ $return->ReasonOfReturn }}</td>
                                                                            <td>

                                                                                @if ($return->Status == 'PROCESSED' && $return->ErrorMessage == null)
                                                                                    <span class="status--process">PROCESSED</span>
                                                                                @elseif($return->Status == 'OUT')                                             
                                                                                    <span class="status--out">{{ $return->Status }}</span>                                                
                                                                                @elseif($return->Status == 'PROCESSING')                                             
                                                                                    <span class="status--out">{{ $return->Status }}</span>                                                
                                                                                @elseif($return->ErrorMessage != null)
                                                                                    <span class="status--denied">FAILED</span>                                                
                                                                                @else 
                                                                                    <span class="status--denied">UNKNOWN</span>                                                
                                                                                @endif                                                                                

                                                                            </td>
                                                                            <td>
                                                                                <a href="{{ route('app.return-detail', ['keyid' => $return->KeyId]) }}" class="item" data-toggle="tooltip" data-placement="top" title="View return" target="_blank">
                                                                                    <i class="fa fa-book"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                            <td colspan="8">
                                                                            <div class="alert alert-warning text-center">No data Found</div>    
                                                                            </td>
                                                                        </tr>
                                                                    @endif
                                                                      
                                                                    </tbody>
                                                                </table>
                                                            </div>


                                                </div>

                                                <div class="tab-pane fade" id="items" role="tabpanel" aria-labelledby="items-tab">

                                                        <div class="table-responsive m-b-40">
                                                                <table class="table table-borderless table-data3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Material Code</th>
                                                                            <th>Description</th>
                                                                            <th>Material Group</th>
                                                                            <th>Conversion ID</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    @if(count($items) > 0)
                                                                    @foreach ($items as $item)                                                                    
                                                                        <tr>
                                                                            <td>{{ $item->MaterialCode }}</td>
                                                                            <td>{{ $item->Description }}</td>
                                                                            <td>{{ $item->MaterialGroup }}</td>
                                                                            <td>{{ $item->ConversionId }}</td>
                                                                        </tr>                                                                        
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                                <td colspan="7">
                                                                                <div class="alert alert-warning text-center">No data Found</div>    
                                                                                </td>
                                                                        </tr>
                                                                    @endif
                                                                      
                                                                    </tbody>
                                                                </table>
                                                            </div>



                                                </div>

                                                <div class="tab-pane fade" id="accounts" role="tabpanel" aria-labelledby="accounts-tab">

                                                        <div class="table-responsive m-b-40">
                                                                <table class="table table-borderless table-data3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Account ID</th>
                                                                            <th>Account Name</th>
                                                                            <th>Channel</th>
                                                                            <th>Street</th>
                                                                            <th>City</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    @if(count($accounts) > 0)
                                                                    @foreach ($accounts as $account)                                                                    
                                                                        <tr>
                                                                            <td>{{ $account->AccountId }}</td>
                                                                            <td>{{ $account->AccountName }}</td>
                                                                            <td>{{ $account->Channel }}</td>
                                                                            <td>{{ $account->Street }}</td>
                                                                            <td>{{ $account->City }}</td>
                                                                        </tr>                                                                        
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                                <td colspan="5">
                                                                                <div class="alert alert-warning text-center">No data Found</div>    
                                                                                </td>
                                                                        </tr>
                                                                    @endif
                                                                      
                                                                    </tbody>
                                                                </table>
                                                            </div>



                                                    
                                                </div>


                                                <div class="tab-pane fade" id="dsp" role="tabpanel" aria-labelledby="dsp-tab">

                                                        <div class="table-responsive m-b-40">
                                                                <table class="table table-borderless table-data3">
                                                                    <thead>
                                                                        <tr>
                                                                            <th>Account ID</th>
                                                                            <th>Account Name</th>
                                                                            <th>First Name</th>
                                                                            <th>Last Name</th>
                                                                        </tr>
                                                                    </thead>
                                                                    <tbody>

                                                                    @if(count($dsp) > 0)
                                                                    @foreach ($dsp as $account)                                                                    
                                                                        <tr>
                                                                            <td>{{ $account->AccountId }}</td>
                                                                            <td>{{ $account->AccountName }}</td>
                                                                            <td>{{ $account->FirstName }}</td>
                                                                            <td>{{ $account->LastName }}</td>
                                                                        </tr>                                                                        
                                                                    @endforeach
                                                                    @else 
                                                                        <tr>
                                                                                <td colspan="5">
                                                                                <div class="alert alert-warning text-center">No data Found</div>    
                                                                                </td>
                                                                        </tr>
                                                                    @endif
                                                                      
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
   

        <script>
   
            function toggle(source) {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                for (var i = 0; i < checkboxes.length; i++) {
                    if (checkboxes[i] != source)
                        checkboxes[i].checked = source.checked;
                }
            }


        </script>

@endsection

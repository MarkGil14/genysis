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

                                        <li class="list-inline-item">
                                            <a href="{{ route('app.upload') }}">Import Data</a>
                                        </li>

                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item active">uploads uploads History</li>
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
                        <div class="table-data__tool">
                        <div class="table-responsive table-responsive-data2">
                            <table class="table table-data2">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Orders Count</th>
                                        <th>Line Items Count</th>
                                        <th>Total Qty</th>
                                        <th>Total Sales</th>                                                  
                                        <th>Total Discount</th>
                                        <th>Date uploaded</th>
                                    </tr>
                                </thead>
                                <tbody>

                                @if(count($uploads) > 0)
                                    @foreach ($uploads as $upload)                                        
                                    <tr class="tr-shadow">
                                        <td>{{ $upload->id }}</td>
                                        <td>
                                            <span class="block-email">{{ number_format($upload->orders_count,0,'.',',') }}</span>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ number_format($upload->line_items_count,0,'.',',') }}</span>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ number_format($upload->total_qty,2,'.',',') }}</span>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ number_format($upload->total_sales,4,'.',',') }}</span>
                                        </td>
                                        <td>
                                            <span class="block-email">{{ number_format($upload->total_discount,4,'.',',') }}</span>
                                        </td>

                                        <td>{{ date('F d, Y / h:i a', strtotime($upload->created_at)) }}</td>
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
                            {{ $uploads->links() }}

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

@endsection

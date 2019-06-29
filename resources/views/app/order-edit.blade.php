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
                                        <li class="list-inline-item active">
                                        <a href="{{ route('app.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Edit Order</li>
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
                                <div class="float-right">                        
                                    <a href="{{ route('app.order-detail' , ['keyid' => $order->KeyId]) }}" class="float-right btn btn-secondary"><i class="fa fa-book"></i> Order Detail</a>
                                </div>
                            </div>

                        </div>
            

                <div class="row mt-3">                
                    <div class="col-md-12">
         
                        <div class="au-card">
                            <div class="card-header bg-secondary text-white">
                                <i class="fa fa-pencil-square-o"></i>
                                <strong class="card-title pl-2">Edit Order                                      
                                </strong>
                            </div>

                            <div class="card-body">
                                    <form action="{{ route('user.update-order') }}" method="post">
                                            @csrf
                                    <input type="hidden" value="{{ $order->KeyId }}" name="keyid">
                                    <div class="card">
                                            <div class="card-header">
                                                Order Details

                                                @if(!is_null($order->Id) && !$order->IsDelete)
                                                <a href="{{ route('app.order-is-delete', ['guid' => $order->Guid]) }}" class="btn btn-danger float-right">Set to Delete</a>
                                                @endif
                                                
                                                @if(!is_null($order->Id) && $order->IsDelete)
                                                <a href="{{ route('app.order-cancel-delete', ['guid' => $order->Guid]) }}" class="btn btn-info float-right">Cancel Delete</a>
                                                @endif


                                            </div>                                            

                                            <div class="card-body">
                                                <div class="card-title">
                                                @if(!is_null($order->ErrorMessage))
                                                <div class="alert alert-danger">
                                                    <h3 class="text-center title-2 text-danger">{{ $order->ErrorMessage }}</h3>
                                                </div>                                            
                                                @endif



                                                @if(Session::has('error_data'))        
                                         
                                                        @foreach (Session::get('error_data') as $err)            
                                                                <div class="alert alert-danger">   {{ $err['field'] }} => {{ $err['errors'] }} </div>
                                                        @endforeach
                                      
                                                @endif



                                                </div>
                                                <hr>
                                                        <div class="col-md-12 row">


                                                                <div class="col-md-4">
                            
                                                                        <div class="form-group">
                                                                            <label   class="control-label mb-1">{{ $orderHeader->account_id }}</label>
                                                                            <input   name="account_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->AccountReferenceId }}">
                                                                            <small class="help-block form-text text-danger">{{ $orderValidation->account_id }}</small>                                                                                                                                                                        
                                                                        </div>
                                
                                                                    </div>                                    
                                                                         

                                                                    <div class="col-md-4">
                            
                                                                            <div class="form-group">
                                                                                <label   class="control-label mb-1">{{ $orderHeader->dsp_id }}</label>
                                                                                <input   name="dsp_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->DSPId }}">
                                                                                <small class="help-block form-text text-danger">{{ $orderValidation->dsp_id }}</small>                                                                                                                                                                        
                                                                            </div>
                                    
                                                                        </div>                                    
                                                                         
                                                                        <div class="col-md-4">
                            
                                                                                <div class="form-group">
                                                                                    <label   class="control-label mb-1">{{ $orderHeader->sas_id }}</label>
                                                                                    <input name="sas_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->SASId }}">
                                                                                    <small class="help-block form-text text-danger">{{ $orderValidation->sas_id }}</small>                                                                                                                                                                        
                                                                                </div>
                                        
                                                                            </div>                  
                                                                            
                                                                            

                                                            <div class="col-md-3">
            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $orderHeader->sales_type }}</label>
                                                                        <select name="sales_type"  class="form-control">
                                                                            <option value="{{ $order->SalesType  }}">{{ $order->SalesType  }}</option>  
                                                                            <option value="Pre-booked">Pre-booked</option>
                                                                            <option value="Direct Invoice">Direct Invoice</option>
                                                                        </select>
                                                                        <small class="help-block form-text text-danger">{{ $orderValidation->sales_type }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                                                                
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $orderHeader->invoice_number }}</label>
                                                                        <input   name="invoice_number" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceNumber }}">
                                                                        <small class="help-block form-text text-danger">{{ $orderValidation->invoice_number }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                            
                            
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $orderHeader->sales_order_number }}</label>
                                                                        <input   name="sales_order_number" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->SalesOrderNumber }}">
                                                                        <small class="help-block form-text text-danger">{{ $orderValidation->sales_order_number }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                            
                            
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $orderHeader->order_date }}</label>
                                                                        <input   name="order_date" type="date" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDate }}">
                                                                        <small class="help-block form-text text-danger">{{ $orderValidation->order_date }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                            
                                                                <div class="col-md-3">
                            
                                                                        <div class="form-group">
                                                                            <label   class="control-label mb-1">{{ $orderHeader->invoice_date }}</label>
                                                                            <input   name="invoice_date" type="date" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDate }}">
                                                                            <small class="help-block form-text text-danger">{{ $orderValidation->invoice_date }}</small>                                                                                                                                                                        
                                                                        </div>
                                
                                                                    </div>

                                                                <div class="col-md-3">
                            
                                                                        <div class="form-group">
                                                                            <label   class="control-label mb-1">{{ $orderHeader->delivery_date }}</label>
                                                                            <input   name="delivery_date" type="date" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->RequestedDeliveryDate }}">
                                                                            <small class="help-block form-text text-danger">{{ $orderValidation->delivery_date }}</small>                                                                                                                                                                        
                                                                        </div>
                                
                                                                    </div>                                    

                                                                    

                                                                            <div class="col-md-3">
                            
                                                                                    <div class="form-group">
                                                                                        <label   class="control-label mb-1">{{ $orderHeader->payment_term }}</label>
                                                                                        <input   name="payment_term" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->PaymentTerm }}">
                                                                                        <small class="help-block form-text text-danger">{{ $orderValidation->payment_term }}</small>                                                                                                                                                                        
                                                                                    </div>
                                            
                                                                                </div>         
                                                                                
                                                                                <div class="col-md-3">                    
                                                                                        <div class="form-group">
                                                                                            <label   class="control-label mb-1">{{ $orderHeader->transaction_id }}</label>
                                                                                            <input   name="transaction_id" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->TransactionId }}">
                                                                                            <small class="help-block form-text text-danger">{{ $orderValidation->transaction_id }}</small>                                                                                                                                                                        
                                                                                        </div>                                            
                                                                                </div>     
                                                                                
                                                                                
                                                                                {{-- order discount --}}

                                                                            
                                                                            <div class="col-md-6 row">                    

                                                                                <div class="col-md-4">                    
                                                                                        <div class="form-group">
                                                                                            <label   class="control-label mb-1">Order Discount 1</label>
                                                                                            <input  name="order_discount1" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal1 }}">
                                                                                        </div>                                            
                                                                                </div>              
                                                                                <div class="col-md-8">                    
                                                                                        <div class="form-group">
                                                                                            <label   class="control-label mb-1">Order Discount 1 Description</label>
                                                                                            <input   name="order_discount1_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal1Type }}">
                                                                                        </div>                                            
                                                                                </div>              
                                                                            </div>


         
                                                                            <div class="col-md-6 row">                    
                                                                                    <div class="col-md-4">                    
                                                                                            <div class="form-group">
                                                                                                <label   class="control-label mb-1">Invoice Discount 1</label>
                                                                                                <input  name="invoice_discount1" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal1 }}">
                                                                                            </div>                                            
                                                                                    </div>              
                                                                                    <div class="col-md-8">                    
                                                                                            <div class="form-group">
                                                                                                <label   class="control-label mb-1">Invoice Discount 1 Description</label>
                                                                                                <input   name="invoice_discount1_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal1Type }}">
                                                                                            </div>                                            
                                                                                    </div>              
                                                                                </div>                                                                            

 

                                                                                <div class="col-md-6 row">                    

                                                                                        <div class="col-md-4">                    
                                                                                                <div class="form-group">
                                                                                                    <label   class="control-label mb-1">Order Discount 2</label>
                                                                                                    <input  name="order_discount2" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal2 }}">
                                                                                                </div>                                            
                                                                                        </div>              
                                                                                        <div class="col-md-8">                    
                                                                                                <div class="form-group">
                                                                                                    <label   class="control-label mb-1">Order Discount 2 Description</label>
                                                                                                    <input   name="order_discount2_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal2Type }}">
                                                                                                </div>                                            
                                                                                        </div>              
                                                                                    </div>
        
        
                 
                                                                                    <div class="col-md-6 row">                    
                                                                                            <div class="col-md-4">                    
                                                                                                    <div class="form-group">
                                                                                                        <label   class="control-label mb-1">Invoice Discount2</label>
                                                                                                        <input  name="invoice_discount2" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal2 }}">
                                                                                                    </div>                                            
                                                                                            </div>              
                                                                                            <div class="col-md-8">                    
                                                                                                    <div class="form-group">
                                                                                                        <label   class="control-label mb-1">Invoice Discount 2 Description</label>
                                                                                                        <input   name="invoice_discount2_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal2Type }}">
                                                                                                    </div>                                            
                                                                                            </div>              
                                                                                        </div>                                                                            
        


                                                                                        <div class="col-md-6 row">                    

                                                                                                <div class="col-md-4">                    
                                                                                                        <div class="form-group">
                                                                                                            <label   class="control-label mb-1">Order Discount3</label>
                                                                                                            <input  name="order_discount3" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal3 }}">
                                                                                                        </div>                                            
                                                                                                </div>              
                                                                                                <div class="col-md-8">                    
                                                                                                        <div class="form-group">
                                                                                                            <label   class="control-label mb-1">Order Discount 3 Description</label>
                                                                                                            <input   name="order_discount3_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal3Type }}">
                                                                                                        </div>                                            
                                                                                                </div>              
                                                                                            </div>
                                                                                                        
         
                                                                                        <div class="col-md-6 row">                    
                                                                                                <div class="col-md-4">                    
                                                                                                        <div class="form-group">
                                                                                                            <label   class="control-label mb-1">Invoice Discount3</label>
                                                                                                            <input  name="invoice_discount3" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal3 }}">
                                                                                                        </div>                                            
                                                                                                </div>              
                                                                                                <div class="col-md-8">                    
                                                                                                        <div class="form-group">
                                                                                                            <label   class="control-label mb-1">Invoice Discount 3 Description</label>
                                                                                                            <input   name="invoice_discount3_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal3Type }}">
                                                                                                        </div>                                            
                                                                                                </div>              
                                                                                            </div>                                                                            
            
             
                                                                                                           
                                                                                            


                                                                                            <div class="col-md-6 row">                    

                                                                                                    <div class="col-md-4">                    
                                                                                                            <div class="form-group">
                                                                                                                <label   class="control-label mb-1">Order Discount4</label>
                                                                                                                <input  name="order_discount4" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal4 }}">
                                                                                                            </div>                                            
                                                                                                    </div>              
                                                                                                    <div class="col-md-8">                    
                                                                                                            <div class="form-group">
                                                                                                                <label   class="control-label mb-1">Order Discount 4 Description</label>
                                                                                                                <input   name="order_discount4_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->OrderDiscountFromTotal4Type }}">
                                                                                                            </div>                                            
                                                                                                    </div>              
                                                                                                </div>
                                                                                                            
             
                                                                                            <div class="col-md-6 row">                    
                                                                                                    <div class="col-md-4">                    
                                                                                                            <div class="form-group">
                                                                                                                <label   class="control-label mb-1">Invoice Discount4</label>
                                                                                                                <input  name="invoice_discount4" type="number" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal4 }}">
                                                                                                            </div>                                            
                                                                                                    </div>              
                                                                                                    <div class="col-md-8">                    
                                                                                                            <div class="form-group">
                                                                                                                <label   class="control-label mb-1">Invoice Discount 4 Description</label>
                                                                                                                <input   name="invoice_discount4_description" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $order->InvoiceDiscountFromTotal4Type }}">
                                                                                                            </div>                                            
                                                                                                    </div>              
                                                                                                </div>                                                                            
                                                                                                            
        
                                                                                                                                                            
                                                            </div>

                                             
 
                                            </div>
                                                <div class="card-footer">
                                                    <button type="submit" class="btn btn-success pull-right btn-sm">
                                                        <i class="fa fa-dot-circle-o"></i> Update Changes
                                                    </button>                                                 
                                                </div>        
                                        </div>
                                    </form>                                    

                                
                            </div>

                            <div class="card-body">

                                <div class="col-md-12">
                                        <button class="btn btn-secondary" data-toggle="modal" data-target="#add-modal-item">Add Item</button>
                                </div>
                             
                                    <div class="table-responsive table--no-card m-b-30 mt-3">
                                            <table class="table table-borderless table-striped">
                                                <thead class="bg-secondary text-white">
                                                    <tr>
                                                            <th>Material Code</th>               
                                                            <th  class="text-right">Order Quantity</th>              
                                                            <th class="text-right">Order Price</th>
                                                            <th class="text-right">Order Discount</th>
                                                            <th class="text-right">Order Total</th>
                                                            <th  class="text-right">Invoice Quantity</th>              
                                                            <th class="text-right">Invoice Price</th>
                                                            <th class="text-right">Invoice Discount</th>
                                                            <th class="text-right">Invoice Total</th>
                                                            <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                 
                                                    @foreach ($order->order_items() as $item)                                                        
                                                    <tr>
                                                        <td>{{ $item->ProductId }}</td>
                                                        <td class="text-right">{{ $item->OrderQuantity }}</td>
                                                        <td class="text-right">{{ $item->OrderPrice }}</td>
                                                        <td class="text-right">{{ $orderDiscount = $item->OrderDiscount1 + $item->OrderDiscount2 + $item->OrderDiscount3 + $item->OrderDiscount4 }}</td>
                                                        <td class="text-right">{{ ($item->OrderQuantity * $item->OrderPrice) - $orderDiscount }}</td>
                                                        <td class="text-right">{{ $item->InvoiceQuantity }}</td>
                                                        <td class="text-right">{{ $item->InvoicePrice }}</td>
                                                        <td class="text-right"> {{  $itemDiscount = $item->InvoiceDiscount1 + $item->InvoiceDiscount2 + $item->InvoiceDiscount3 + $item->InvoiceDiscount4 }} </td>
                                                        <td class="text-right">{{ $totalAmount = ($item->InvoiceQuantity * $item->InvoicePrice) - $itemDiscount }}</td>
                                                        <td class="text-right" style="display:inline-flex;">

                                                            @if(!is_null($item->Id) && !$item->IsDelete)
                                                                <a href="{{ route('app.order-item-isdeleted', ['orderitemid' => $item->Guid]) }}" class="btn btn-danger">
                                                                    <i class="zmdi zmdi-delete"  data-toggle="tooltip" data-placement="top" title="set to delete"></i>
                                                                </a>                                                                
                                                            @endif

                                                            @if($item->IsDelete)
                                                                <a href="{{ route('app.order-item-cancel-deleted', ['orderitemid' => $item->Guid]) }}" class="btn btn-info">
                                                                    <i class="zmdi zmdi-check"  data-toggle="tooltip" data-placement="top" title="cancel the delete"></i>
                                                                </a>                                                                
                                                            @endif
        
                                                             <a href="javascript:void(0)" class="btn btn-secondary btn-edit-item" data-toggle="modal" data-target="#edit-modal-item" id="{{ $item->Guid }}">
                                                                <i class="zmdi zmdi-wrench"></i>
                                                                @if(!is_null($item->ErrorMessage))
                                                                    <i class="fa fa-circle text-danger alert-icon-failed-item-error"  data-toggle="tooltip" data-placement="top" title="{{ $item->ErrorMessage }}" target="_blank"></i>
                                                                @endif
                                                            </a>

    
                                                        </td>
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
        </section>
        <!-- END DATA TABLE-->

@endsection


@section('modals')
    

<div class="modal fade" id="edit-modal-item" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel">Update Item Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
     
                 <form action="{{ route('user.item-update-detail') }}" method="POST">
                     @csrf
                     <input type="hidden" name="item_id" id="item-id">
                     <div class="modal-body">
     
                         <div class="col-md-12 row">
     
                             <div class="col-md-3">                            
                                 <div class="form-group">                    
                                     <label>Product Id</label>
                                     <input type="text" class="form-control" name="material_code" id="product-id">
                                 </div>
                             </div>
                             
                             <div class="col-md-3">                            
                                 <div class="form-group">                    
                                     <label>Conversion Id</label>
                                     <input type="text" class="form-control" name="conversion_id" id="conversion-id">
                                 </div>
                             </div>
     
                             <div class="col-md-3">                            
                                     <div class="form-group">                    
                                         <label>Weight UOM</label>
                                         <input type="text" class="form-control" name="weight_uom" id="weight-uom">
                                     </div>
                                 </div>
     
                                 <div class="col-md-3">                            
                                         <div class="form-group">                    
                                             <label>Actual Weigth Qty</label>
                                             <input type="text" class="form-control" name="actual_weight_quantity" id="actual-weight-qty">
                                         </div>
                                     </div>
         
     
     
                                     <div class="col-md-6">                            
                                             <div class="form-group">                    
                                                 <label>Order Quantity</label>
                                                 <input type="number" class="form-control" name="order_quantity" id="order-quantity"  step="any">
                                             </div>
                                         </div>
             
                                         <div class="col-md-6">                            
                                                 <div class="form-group">                    
                                                     <label>Order Price</label>
                                                     <input type="number" class="form-control" name="order_price" id="order-price" step="any">
                                                 </div>
                                             </div>
                                             
                                     <div class="col-md-4">                            
                                         <div class="form-group">                    
                                             <label>Order Item Discount 1</label>
                                             <input type="number" class="form-control" name="order_discount1" id="order-discount1"  step="any" >
                                         </div>
                                     </div>
                                     <div class="col-md-8">                            
                                             <div class="form-group">                    
                                                 <label>Order Item Discount 1 Description</label>
                                                 <input type="text" class="form-control" name="order_discount1_description" id="order-discount1-description">
                                             </div>
                                         </div>  
             
             
                                     <div class="col-md-4">                            
                                             <div class="form-group">                    
                                                 <label>Order Item Discount 2</label>
                                                 <input type="number" class="form-control" name="order_discount2" id="order-discount2">
                                             </div>
                                         </div>
             
             
                                         <div class="col-md-8">                            
                                                 <div class="form-group">                    
                                                     <label>Order Item Discount 2 Description</label>
                                                     <input type="text" class="form-control" name="order_discount2_description" id="order-discount2-description">
                                                 </div>
                                             </div>                            
                         
                                         <div class="col-md-4">                            
                                                 <div class="form-group">                    
                                                     <label>Order Item Discount 3</label>
                                                     <input type="number" class="form-control" name="order_discount3" id="order-discount3">
                                                 </div>
                                             </div>
                             
                                             <div class="col-md-8">                            
                                                     <div class="form-group">                    
                                                         <label>Order Item Discount 3 Description</label>
                                                         <input type="text" class="form-control" name="order_discount3_description" id="order-discount3-description">
                                                     </div>
                                                 </div>                            
             
             
                                             <div class="col-md-4">                            
                                                     <div class="form-group">                    
                                                         <label>Order Item Discount 4</label>
                                                         <input type="number" class="form-control" name="order_discount4" id="order-discount4">
                                                     </div>
                                                 </div>
             
                                                 <div class="col-md-8">                            
                                                         <div class="form-group">                    
                                                             <label>IOrder Item Discount 4 Description</label>
                                                             <input type="text" class="form-control" name="order_discount4_description" id="order-discount4-description">
                                                         </div>
                                                     </div>                        
     
         
     
                             <div class="col-md-6">                            
                                     <div class="form-group">                    
                                         <label>Invoice Quantity</label>
                                         <input type="number" class="form-control" name="invoice_quantity" id="quantity"  step="any" >
                                     </div>
                                 </div>
     
                                 <div class="col-md-6">                            
                                         <div class="form-group">                    
                                             <label>Invoice Price</label>
                                             <input type="number" class="form-control" name="invoice_price" id="price" step="any" >
                                         </div>
                                     </div>
     
                             <div class="col-md-4">                            
                                 <div class="form-group">                    
                                     <label>Invice Discount 1</label>
                                     <input type="number" class="form-control" name="discount1" id="discount1"  step="any" >
                                 </div>
                             </div>
                             <div class="col-md-8">                            
                                     <div class="form-group">                    
                                         <label>Invoice Item Discount 1 Description</label>
                                         <input type="text" class="form-control" name="discount1_description" id="discount1-description">
                                     </div>
                                 </div>  
     
     
                             <div class="col-md-4">                            
                                     <div class="form-group">                    
                                         <label>Invoice Item Discount 2</label>
                                         <input type="number" class="form-control" name="discount2" id="discount2">
                                     </div>
                                 </div>
     
     
                                 <div class="col-md-8">                            
                                         <div class="form-group">                    
                                             <label>Invoice Item Discount 2 Description</label>
                                             <input type="text" class="form-control" name="discount2_description" id="discount2-description">
                                         </div>
                                     </div>                            
                 
                                 <div class="col-md-4">                            
                                         <div class="form-group">                    
                                             <label>Invoice Item Discount 3</label>
                                             <input type="number" class="form-control" name="discount3" id="discount3">
                                         </div>
                                     </div>
                     
                                     <div class="col-md-8">                            
                                             <div class="form-group">                    
                                                 <label>Invoice Item Discount 3 Description</label>
                                                 <input type="text" class="form-control" name="discount3_description" id="discount3-description">
                                             </div>
                                         </div>                            
     
     
                                     <div class="col-md-4">                            
                                             <div class="form-group">                    
                                                 <label>Invoice Item Discount 4</label>
                                                 <input type="number" class="form-control" name="discount4" id="discount4">
                                             </div>
                                         </div>
     
                                         <div class="col-md-8">                            
                                                 <div class="form-group">                    
                                                     <label>Invoice Item Discount 4 Description</label>
                                                     <input type="text" class="form-control" name="discount4_description" id="discount4-description">
                                                 </div>
                                             </div>                            
     
     
     
         
     
     
                                         <div class="col-md-12">                            
                                                 <div class="form-group">                    
                                                     <label>Reason Unequal Order Invoice</label>
                                                     <input type="text" class="form-control" name="reason_unequal_order_invoice" id="reason-unequal-order-invoice">
                                                 </div>
                                             </div>
         
                         
                                         
                         </div>
     
     
                             
     
                     </div>
                     <div class="modal-footer">
                         <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                         <button  type="submit" class="btn btn-success">Save Changes</button>
                     </div>
                 </form>
     
            </div>
        </div>
     </div>
     
     
     
     
     
     
     <div class="modal fade" id="add-modal-item" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
             <div class="modal-dialog modal-lg" role="document">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h5 class="modal-title" id="staticModalLabel">Add Item Form</h5>
                         <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                             <span aria-hidden="true">&times;</span>
                         </button>
                     </div>
          
                      <form action="{{ route('app.add.order-item', ['guid' => $order->Guid ]) }}" method="POST">
                          @csrf
                          <div class="modal-body">
                              <div class="col-md-12 row">
          
                                  <div class="col-md-3">                            
                                      <div class="form-group">                    
                                          <label>Product Id</label>
                                          <input type="text" class="form-control" name="material_code" >
                                      </div>
                                  </div>
                                  
                                  <div class="col-md-3">                            
                                      <div class="form-group">                    
                                          <label>Conversion Id</label>
                                          <input type="text" class="form-control" name="conversion_id"  >
                                      </div>
                                  </div>
          
                                  <div class="col-md-3">                            
                                          <div class="form-group">                    
                                              <label>Weight UOM</label>
                                              <input type="text" class="form-control" name="weight_uom" >
                                          </div>
                                      </div>
          
                                      <div class="col-md-3">                            
                                              <div class="form-group">                    
                                                  <label>Actual Weigth Qty</label>
                                                  <input type="text" class="form-control" name="actual_weight_quantity"  >
                                              </div>
                                          </div>
              
          
          
                                          <div class="col-md-6">                            
                                                  <div class="form-group">                    
                                                      <label>Order Quantity</label>
                                                      <input type="number" class="form-control" name="order_quantity"   step="any">
                                                  </div>
                                              </div>
                  
                                              <div class="col-md-6">                            
                                                      <div class="form-group">                    
                                                          <label>Order Price</label>
                                                          <input type="number" class="form-control" name="order_price"  step="any">
                                                      </div>
                                                  </div>
                                                  
                                          <div class="col-md-4">                            
                                              <div class="form-group">                    
                                                  <label>Order Item Discount 1</label>
                                                  <input type="number" class="form-control" name="order_discount1"  step="any" >
                                              </div>
                                          </div>
                                          <div class="col-md-8">                            
                                                  <div class="form-group">                    
                                                      <label>Order Item Discount 1 Description</label>
                                                      <input type="text" class="form-control" name="order_discount1_description"  >
                                                  </div>
                                              </div>  
                  
                  
                                          <div class="col-md-4">                            
                                                  <div class="form-group">                    
                                                      <label>Order Item Discount 2</label>
                                                      <input type="number" class="form-control" name="order_discount2"  >
                                                  </div>
                                              </div>
                  
                  
                                              <div class="col-md-8">                            
                                                      <div class="form-group">                    
                                                          <label>Order Item Discount 2 Description</label>
                                                          <input type="text" class="form-control" name="order_discount2_description" >
                                                      </div>
                                                  </div>                            
                              
                                              <div class="col-md-4">                            
                                                      <div class="form-group">                    
                                                          <label>Order Item Discount 3</label>
                                                          <input type="number" class="form-control" name="order_discount3" >
                                                      </div>
                                                  </div>
                                  
                                                  <div class="col-md-8">                            
                                                          <div class="form-group">                    
                                                              <label>Order Item Discount 3 Description</label>
                                                              <input type="text" class="form-control" name="order_discount3_description"  >
                                                          </div>
                                                      </div>                            
                  
                  
                                                  <div class="col-md-4">                            
                                                          <div class="form-group">                    
                                                              <label>Order Item Discount 4</label>
                                                              <input type="number" class="form-control" name="order_discount4"  >
                                                          </div>
                                                      </div>
                  
                                                      <div class="col-md-8">                            
                                                              <div class="form-group">                    
                                                                  <label>IOrder Item Discount 4 Description</label>
                                                                  <input type="text" class="form-control" name="order_discount4_description" >
                                                              </div>
                                                          </div>                        
          
              
          
                                  <div class="col-md-6">                            
                                          <div class="form-group">                    
                                              <label>Invoice Quantity</label>
                                              <input type="number" class="form-control" name="invoice_quantity" step="any" >
                                          </div>
                                      </div>
          
                                      <div class="col-md-6">                            
                                              <div class="form-group">                    
                                                  <label>Invoice Price</label>
                                                  <input type="number" class="form-control" name="invoice_price"  step="any" >
                                              </div>
                                          </div>
          
                                  <div class="col-md-4">                            
                                      <div class="form-group">                    
                                          <label>Invice Discount 1</label>
                                          <input type="number" class="form-control" name="discount1"   step="any" >
                                      </div>
                                  </div>
                                  <div class="col-md-8">                            
                                          <div class="form-group">                    
                                              <label>Invoice Item Discount 1 Description</label>
                                              <input type="text" class="form-control" name="discount1_description"  >
                                          </div>
                                      </div>  
          
          
                                  <div class="col-md-4">                            
                                          <div class="form-group">                    
                                              <label>Invoice Item Discount 2</label>
                                              <input type="number" class="form-control" name="discount2"  >
                                          </div>
                                      </div>
          
          
                                      <div class="col-md-8">                            
                                              <div class="form-group">                    
                                                  <label>Invoice Item Discount 2 Description</label>
                                                  <input type="text" class="form-control" name="discount2_description" >
                                              </div>
                                          </div>                            
                      
                                      <div class="col-md-4">                            
                                              <div class="form-group">                    
                                                  <label>Invoice Item Discount 3</label>
                                                  <input type="number" class="form-control" name="discount3" >
                                              </div>
                                          </div>
                          
                                          <div class="col-md-8">                            
                                                  <div class="form-group">                    
                                                      <label>Invoice Item Discount 3 Description</label>
                                                      <input type="text" class="form-control" name="discount3_description" >
                                                  </div>
                                              </div>                            
          
          
                                          <div class="col-md-4">                            
                                                  <div class="form-group">                    
                                                      <label>Invoice Item Discount 4</label>
                                                      <input type="number" class="form-control" name="discount4" >
                                                  </div>
                                              </div>
          
                                              <div class="col-md-8">                            
                                                      <div class="form-group">                    
                                                          <label>Invoice Item Discount 4 Description</label>
                                                          <input type="text" class="form-control" name="discount4_description" >
                                                      </div>
                                                  </div>                            
          
                                              <div class="col-md-12">                            
                                                      <div class="form-group">                    
                                                          <label>Reason Unequal Order Invoice</label>
                                                          <input type="text" class="form-control" name="reason_unequal_order_invoice"  >
                                                      </div>
                                                  </div>
                              </div>                             
                          </div>
                          <div class="modal-footer">
                              <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                              <button  type="submit" class="btn btn-success">Add Item</button>
                          </div>
                      </form>
          
                 </div>
             </div>
          </div>
          
     
     @endsection


@section('scripts')
   

        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });     

      
            $(document).on('click', '.btn-edit-item', function(){
                let id = $(this).attr('id');                
                console.log(id);
                $.ajax({
                    url: "{{ route('user.get.order-item') }}",
                    method: 'POST',
                    dataType: 'json',
                    data: {
                        id : id
                    },
                    error : function(err){
                        console.log(err);
                    },
                    success: function (data) {
                        $('#price').val(data.price);
                        $('#conversion-id').val(data.conversion_id);
                        $('#quantity').val(data.quantity);
                        $('#product-id').val(data.product_id);
                        $('#weight-uom').val(data.weight_uom);
                        $('#actual-weight-qty').val(data.actual_weight_qty);
                        $('#item-id').val(id);
                        $('#reason-unequal-order-invoice').val(data.reason_unequal_order_invoice);
                        $('#discount1').val(data.discount1);
                        $('#discount1-description').val(data.discount1_description);
                        $('#discount2').val(data.discount2);
                        $('#discount2-description').val(data.discount2_description);
                        $('#discount3').val(data.discount3);
                        $('#discount3-description').val(data.discount3_description);
                        $('#discount4').val(data.discount4);
                        $('#discount4-description').val(data.discount4_description);


                        $('#order-price').val(data.order_price);
                        $('#order-quantity').val(data.order_quantity);
                        $('#order-discount1').val(data.order_discount1);
                        $('#order-discount1-description').val(data.order_discount1_description);
                        $('#order-discount2').val(data.order_discount2);
                        $('#order-discount2-description').val(data.order_discount2_description);
                        $('#order-discount3').val(data.order_discount3);
                        $('#order-discount3-description').val(data.order_discount3_description);
                        $('#order-discount4').val(data.order_discount4);
                        $('#order-discount4-description').val(data.order_discount4_description);


                    }
                });

            });




        </script>

@endsection

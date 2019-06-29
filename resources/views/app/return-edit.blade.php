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
                                        <li class="list-inline-item">Edit Return</li>
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
                                    <a href="{{ route('app.return-detail' , ['keyid' => $return->KeyId]) }}" class="float-right btn btn-secondary"><i class="fa fa-book"></i> Return Detail</a>


                                </div>
                            </div>

                        </div>
            

                <div class="row mt-3">                
                    <div class="col-md-12">
         
                        <div class="au-card">
                            <div class="card-header bg-secondary text-white">
                                <i class="fa fa-pencil-square-o"></i>
                                <strong class="card-title pl-2">Edit Return                                     
                                </strong>
                            </div>

                            <div class="card-body">
                                    <form action="{{ route('user.update-return') }}" method="post">
                                            @csrf
                                    <input type="hidden" value="{{ $return->KeyId }}" name="keyid">
                                    <div class="card">
                                            <div class="card-header">
                                                Return Details                                  
                                                
                                                
                                                @if(!is_null($return->Id) && !$return->IsDelete)
                                                <a href="{{ route('app.return-is-delete', ['guid' => $return->Guid]) }}" class="btn btn-danger float-right">Set to Delete</a>
                                                @endif
                                                
                                                @if(!is_null($return->Id) && $return->IsDelete)
                                                <a href="{{ route('app.return-cancel-delete', ['guid' => $return->Guid]) }}" class="btn btn-info float-right">Cancel Delete</a>
                                                @endif


                                            </div>                                            

                                            <div class="card-body">
                                                <div class="card-title">
                                                @if(!is_null($return->ErrorMessage))
                                                <div class="alert alert-danger">
                                                    <h3 class="text-center title-2 text-danger">{{ $return->ErrorMessage }}</h3>
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
                                                                            <label   class="control-label mb-1">{{ $returnHeader->account_id }}</label>
                                                                            <input   name="account_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->AccountId }}">
                                                                            <small class="help-block form-text text-danger">{{ $returnValidation->account_id }}</small>                                                                                                                                                                        
                                                                        </div>
                                
                                                                    </div>                                    
                                                                         

                                                                    <div class="col-md-4">
                            
                                                                            <div class="form-group">
                                                                                <label   class="control-label mb-1">{{ $returnHeader->dsp_id }}</label>
                                                                                <input   name="dsp_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->DSPId }}">
                                                                                <small class="help-block form-text text-danger">{{ $returnValidation->dsp_id }}</small>                                                                                                                                                                        
                                                                            </div>
                                    
                                                                        </div>                                    
                                                                         
                                                                        <div class="col-md-4">
                            
                                                                                <div class="form-group">
                                                                                    <label   class="control-label mb-1">{{ $returnHeader->sas_id }}</label>
                                                                                    <input name="sas_id" type="input" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->SASId }}">
                                                                                    <small class="help-block form-text text-danger">{{ $returnValidation->sas_id }}</small>                                                                                                                                                                        
                                                                                </div>
                                        
                                                                            </div>                  
                                                                            
                                                                            

                                                            <div class="col-md-3">
            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $returnHeader->type_of_return }}</label>
                                                                        <select name="type_of_return"  class="form-control">
                                                                            <option value="{{ $return->TypeOfReturn  }}">{{ $return->TypeOfReturn  }}</option>  
                                                                            <option value="Trade">Trade</option>
                                                                            <option value="Outright">Outright</option>
                                                                        </select>
                                                                        <small class="help-block form-text text-danger">{{ $returnValidation->type_of_return }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                                                                
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $returnHeader->invoice_number }}</label>
                                                                        <input   name="invoice_number" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->InvoiceNumber }}">
                                                                        <small class="help-block form-text text-danger">{{ $returnValidation->invoice_number }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                            
                            
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $returnHeader->credit_memo_number }}</label>
                                                                        <input   name="credit_memo_number" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->CreditMemoNumber }}">
                                                                        <small class="help-block form-text text-danger">{{ $returnValidation->credit_memo_number }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                            
                            
                                                                <div class="col-md-3">
                            
                                                                    <div class="form-group">
                                                                        <label   class="control-label mb-1">{{ $returnHeader->return_date }}</label>
                                                                        <input   name="return_date" type="date" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->ReturnDate }}">
                                                                        <small class="help-block form-text text-danger">{{ $returnValidation->return_date }}</small>                                                                                                                                                                        
                                                                    </div>
                            
                                                                </div>
                                      
                                                                
                                                                <div class="col-md-3">                    
                                                                        <div class="form-group">
                                                                            <label   class="control-label mb-1">{{ $returnHeader->reason_of_return }}</label>
                                                                            <input   name="reason_of_return" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->ReasonOfReturn }}">
                                                                            <small class="help-block form-text text-danger">{{ $returnValidation->reason_of_return }}</small>                                                                                                                                                                        
                                                                        </div>                                            
                                                                </div>                                                                     
                                                                
                                                                <div class="col-md-3">                    
                                                                        <div class="form-group">
                                                                            <label   class="control-label mb-1">{{ $returnHeader->transaction_id }}</label>
                                                                            <input   name="transaction_id" type="text" class="form-control" aria-required="true" aria-invalid="false" value="{{ $return->TransactionId }}">
                                                                            <small class="help-block form-text text-danger">{{ $returnValidation->transaction_id }}</small>                                                                                                                                                                        
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
                                            <table class="table table-breturnless table-striped">
                                                <thead class="bg-secondary text-white">
                                                    <tr>
                                                            <th>Porduct Id</th>               
                                                            <th  class="text-right">Return Quantity</th>              
                                                            <th class="text-right">Price</th>
                                                            <th class="text-right">Discount Amount</th>
                                                            <th class="text-right">Condition</th>
                                                            <th class="text-right">Return Type</th>
                                                            <th class="text-right">Reason Of Rejection</th>                                                     
                                                            <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                 
                                                    @foreach ($return->return_items() as $item)                                                        
                                                    <tr>
                                                        <td>{{ $item->ProductId }}</td>
                                                        <td class="text-right">{{ $item->ReturnedQty }}</td>
                                                        <td class="text-right">{{ $item->Price }}</td>
                                                        <td class="text-right">{{ $item->DiscountAmount }}</td>
                                                        <td class="text-right">{{ $item->Condition }}</td>
                                                        <td class="text-right">{{ $item->ReturnType }}</td>
                                                        <td class="text-right">{{ $item->ReasonOfRejection }}</td>
                                                        <td class="text-right" style="display:inline-flex;">

                                                     
                                                            @if(!is_null($item->Id) && !$item->IsDelete)
                                                                <a href="{{ route('app.return-item-isdeleted', ['returnitemid' => $item->Guid]) }}" class="btn btn-danger">
                                                                    <i class="zmdi zmdi-delete"  data-toggle="tooltip" data-placement="top" title="set to delete"></i>
                                                                </a>                                                                
                                                            @endif

                                                            @if($item->IsDelete)
                                                                <a href="{{ route('app.return-item-cancel-deleted', ['returnitemid' => $item->Guid]) }}" class="btn btn-info">
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


<div class="modal fade" id="add-modal-item" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel">Add Item Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
     
                <form action="{{ route('app.add.return-item', ['guid' => $return->Guid ]) }}" method="POST">
                        @csrf
                     <div class="modal-body">
                            <div class="col-md-12 row">
                                
                             <div class="col-md-3">                            
                                    <div class="form-group">                    
                                        <label>Product Id</label>
                                        <input type="text" class="form-control" name="material_code" required>
                                    </div>
                                </div>
                                
                                <div class="col-md-3">                            
                                    <div class="form-group">                    
                                        <label>Conversion Id</label>
                                        <input type="text" class="form-control" name="conversion_id" required>
                                    </div>
                                </div>
        
                                <div class="col-md-3">                            
                                        <div class="form-group">                    
                                            <label>Returned Qty</label>
                                            <input  type="number"  step="any" class="form-control" name="returned_qty" required>
                                        </div>
                                    </div>
        
                                    <div class="col-md-3">                            
                                            <div class="form-group">                    
                                                <label>Price</label>
                                                <input  type="number"  step="any" class="form-control" name="price"  required>
                                            </div>
                                        </div>


                                        <div class="col-md-3">                            
                                                <div class="form-group">                    
                                                    <label>Discount Amount</label>
                                                    <input  type="number"  step="any" class="form-control" name="discount_amount" required>
                                                </div>
                                            </div>                        
                                            
                                            

                                            <div class="col-md-4">                            
                                                    <div class="form-group">                    
                                                        <label>Condition</label>
                                                        <select  name="condition" class="form-control" required>
                                                                <option ></option>  
                                                                <option value="Good">Good</option>
                                                                <option value="Defect">Defect</option>
                                                            </select>
                                                    </div>
                                                </div>     
                                                
                                                
                                                
                                                
                                                <div class="col-md-4">                            
                                                        <div class="form-group">                    
                                                            <label>Return Type</label>
                                  
                                                            <select name="return_type"   class="form-control" required>
                                                                    <option  ></option>  
                                                                    <option value="Devuelto">Devuelto</option>
                                                                    <option value="Outright">Outright</option>
                                                                </select>

                                                        </div>
                                                    </div>                                                
            
                                                    <div class="col-md-12">                            
                                                            <div class="form-group">                    
                                                                <label>Reason of Rejection</label>
                                                                <input type="text" class="form-control" name="reason_of_rejection" >
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





<div class="modal fade" id="edit-modal-item" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel">Update Item Form</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
     
                 <form action="{{ route('user.return-item-update-detail') }}" method="POST">
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
                                            <label>Returned Qty</label>
                                            <input  type="number"  step="any" class="form-control" name="returned_qty" id="returned-qty">
                                        </div>
                                    </div>
        
                                    <div class="col-md-3">                            
                                            <div class="form-group">                    
                                                <label>Price</label>
                                                <input  type="number"  step="any" class="form-control" name="price" id="price">
                                            </div>
                                        </div>


                                        <div class="col-md-3">                            
                                                <div class="form-group">                    
                                                    <label>Discount Amount</label>
                                                    <input  type="number"  step="any" class="form-control" name="discount_amount" id="discount-amount">
                                                </div>
                                            </div>                        
                                            
                                            

                                            <div class="col-md-4">                            
                                                    <div class="form-group">                    
                                                        <label>Condition</label>
                                                        <select  name="condition" class="form-control" >
                                                                <option id="condition"></option>  
                                                                <option value="Good">Good</option>
                                                                <option value="Defect">Defect</option>
                                                            </select>
                                                    </div>
                                                </div>     
                                                
                                                
                                                
                                                
                                                <div class="col-md-4">                            
                                                        <div class="form-group">                    
                                                            <label>Return Type</label>
                                  
                                                            <select name="return_type"   class="form-control" >
                                                                    <option id="return-type"></option>  
                                                                    <option value="Devuelto">Devuelto</option>
                                                                    <option value="Outright">Outright</option>
                                                                </select>

                                                        </div>
                                                    </div>                                                
            
                                                    <div class="col-md-12">                            
                                                            <div class="form-group">                    
                                                                <label>Reason of Rejection</label>
                                                                <input type="text" class="form-control" name="reason_of_rejection" id="reason-of-rejection">
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
            $.ajax({
                url: "{{ route('user.get.return-item') }}",
                method: 'POST',
                dataType: 'json',
                data: {
                    id : id
                },
                error : function(err){
                    console.log(err);
                },
                success: function (data) {
                    $('#item-id').val(data.id);           
                    $('#price').val(data.price);
                    $('#conversion-id').val(data.conversion_id);
                    $('#returned-qty').val(data.returned_qty);
                    $('#product-id').val(data.product_id);
                    $('#discount-amount').val(data.discount_amount);
                    $('#condition').val(data.condition);
                    $('#condition').text(data.condition);
                    $('#return-type').val(data.return_type);
                    $('#return-type').text(data.return_type);

                    $('#reason-of-rejection').val(data.reason_of_rejection);                  
                }
            });

        });




    </script>


@endsection
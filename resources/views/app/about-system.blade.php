@extends('layouts.app')

@section('styles')
    <link rel="stylesheet" href="{{ asset('vendor/dropzone/dropzone.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/sweetalert/sweetalert.css') }}">  
    <link rel="stylesheet" href="{{ asset('vendor/fileupload/bootstrap-fileupload.min.css') }}">  
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/jquery-minicolors/jquery.minicolors.css') }}">
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
                                            <li class="list-inline-item active">About System</li>
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
                <div class="col-md-12 col-lg-8 offset-lg-2">
                        <div class="au-card">
                                <div class="card-header bg-secondary text-white">
                                    <i class="fa fa-info-circle"></i> About System      
                                </div>
                                <div class="card-body">
                                        <div class="default-tab">
                                                <nav>
                                                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                                        <a class="nav-item nav-link active" id="logs-tab" data-toggle="tab" href="#logs" role="tab" aria-controls="logs"
                                                        aria-selected="true">Update Logs                                                   
                                                        </a>

                                                        <a class="nav-item nav-link" id="developer-tab" data-toggle="tab" href="#developer" role="tab" aria-controls="developer"
                                                        aria-selected="true">Developer                                             
                                                        </a>
                                                    </div>
                                                </nav>                                                    
                                                <div class="tab-content pl-3 pt-2" id="nav-tabContent">
                                                        <div class="tab-pane fade show active" id="logs" role="tabpanel" aria-labelledby="logs-tab">                                                                                                    

                                                            <div class="row">
                                                                    <div class="col-md-12">
                                                                            <div class="mt-3">
                                                                                    <h3>Genysis v.2.1.4</h3>    
                                                                                    <strong class="text-danger">June 22, 2019 (Update notes)</strong> <small class="badge badge-success">new</small>
                                                                                    <ul>
                                                                                        <li>
                                                                                        <strong>Overall Summary in Dashbaord:</strong> Can View the Overall Processed Data in SFA (orders count , line items count , total sales , total discount)
                                                                                        </li>
                                                                                        <li>
                                                                                        <strong>Uploaded Summary in Import Data page:</strong> Can View the Overall Processed Data in SFA (orders count , line items count , total sales , total discount)
                                                                                        </li>

                                                                                        <li>
                                                                                        <strong>Company Details in Dashboard</strong> 
                                                                                        </li>                               
                                                                                        
                                                                                        
                                                                                        <li>
                                                                                        <strong>Order and Discount Template:</strong> Order and Discount is merge in one template
                                                                                        </li>


                                                                                        <li>
                                                                                        <strong>Upload Summary:</strong>  Orders and Returns last upload summary can view in Import Page
                                                                                        </li>

                                                                                        <li>
                                                                                        <strong>Upload History Page list:</strong>  Orders and Returns Upload history list
                                                                                        </li>                                                                                            
        

                                                                                        <li>
                                                                                        <strong>Summary Report Page:</strong> Viewing of Uploaded Summary Report
                                                                                        </li>                                                                                            


                                                                                    </ul>
                                                                                </div>

                                                                            <div class="mt-3">
                                                                                    <h3>Genysis v.2.1.3</h3>    
                                                                                    <strong class="text-danger">April 25, 2019 (Update notes)</strong>
                                                                                    <ul>
                                                                                        <li>
                                                                                        <strong>Dashboard (Service Metrics and Efficiency Metrics):</strong> View the rate results of Orders.
                                                                                        </li>
                                                                                        <li>
                                                                                        <strong>Dashboard (Service Metrics and Efficiency Metrics):</strong> Can filter the data by status and order date.
                                                                                        </li>
                                                                                        <li>
                                                                                        <strong>Validation of Unrecognized excel file:</strong> Validate the filename of excel before uploading
                                                                                        </li>
                                                                                    </ul>
                                                                                </div>





                                                                            <div class="mt-3">
                                                                                    <h3>Genysis v.2.1.2</h3>    
                                                                                    <strong class="text-danger">April 14, 2019 (Update notes)</strong>  
                                                                                    <ul>
                                                                                        <li>
                                                                                        <strong>Database Backup:</strong> Can backup the database using console (Ongoing fixing of automatically backup every day with specific time).
                                                                                        </li>
                                                                                        <li>
                                                                                        <strong>Edit Return Detail:</strong> Can update the details of return
                                                                                        </li>
                                                                                        <li>
                                                                                        <strong>Edit Return Item Detail:</strong> Can update the details of return items
                                                                                        </li>

                                                                                        <li>
                                                                                            <strong>Return Out Status :</strong> change the Status of Returns to Out
                                                                                        </li>     
                                                                                        <li>
                                                                                                <strong>Delete Returns :</strong> can Delete the returns <i class="fa fa-info"></i> <strong> Note : PROCESSED Returns cannot be deleted (has a SIF Id)</strong>
                                                                                        </li>
                                                                                            
                                                                                            <li>
                                                                                                <strong>Add New Return Item :</strong> Can Add new Item of Return
                                                                                            </li>
                                                                                            
                                                                                    
            
                                                                                            <li>
                                                                                                <strong> Set to Cancel Return :</strong> Cancel in SFA by setting the Return to Cancel <strong> Note: </strong>  Return that has a SIF ID only can be set to cancel</li>
                                                                                            </li>
        
                                                                                            <li>
                                                                                                <strong> Set to Delete Return Item:</strong> Delete in SFA by setting the Return to delete <strong> Note: </strong> Return item that has a SIF ID only can be set to delete</li>
                                                                                            </li>                                                                                        

                                                                                    </ul>
                                                                                </div>



                                                                            <div class="mt-3">
                                                                                    <h3>Genysis v.2.1</h3>    
                                                                                    <strong class="text-danger">March 30, 2019 (Update notes)</strong>
                                                                                    <ul>
                                                                                        <li>
                                                                                        <strong>Edit Order and Item :</strong> Fixing some bugs and Enhancement
                                                                                        </li>

                                                                                        <li>
                                                                                        <strong>Pre-booked field for fill rate :</strong> Adding Order price , Order Quantity and fixing validations in this field
                                                                                        </li>     

                                                                                        <li>
                                                                                            <strong>Out Status :</strong> can change the Status of Orders to Out
                                                                                        </li>     

                                                                                        <li>
                                                                                            <strong>Enhancement of Order Details : </strong> Adding some field in order and item details (Order Price, Order Quantity, Discount per item and Order Total)
                                                                                        </li>
                            
                                                                                    <li>
                                                                                        <strong>Delete Orders in Filtered Result :</strong> can Delete the orders <i class="fa fa-info"></i> <strong> Note : PROCESSED Orders cannot be deleted</strong>
                                                                                    </li>
                                                                                    <li>
                                                                                        <strong>Developer Profile :</strong> Information about the developer of this system
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong>Add New Order Item :</strong> Can Add new Item of an Order
                                                                                    </li>
                                                                                    
                                                                                    <li>
                                                                                        <strong>Importing Data Enhancement :</strong> Alert Messages of the result in uploading
                                                                                    </li>
    
                                                                                    <li>
                                                                                        <strong> Set to Cancel Order :</strong> Cancel in SFA by setting the Order to delete <strong> Note: </strong>  Order that has a SIF ID can be set to delete</li>
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong> Set to Delete Order Item:</strong> delete in SFA by setting the Order to delete <strong> Note: </strong> Order item that has a SIF ID can be set to delete</li>
                                                                                    </li>
    
                                                                                    <li>
                                                                                        <strong>Validation of Same Invoice With Different DSP:</strong> if this scenario detected, the uploading cancelled. </li>
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong>Validation of Same Invoice:</strong> if this scenario detected, the duplicate invoice data upon uploading was ignore. </li>
                                                                                    </li>



                                                                                            

                                                                                    </ul>
                                                                                </div>


                                                                            <div class="mt-3">
                                                                                    <h3>Genysis v.2.0</h3>                                                            
                                                                                <strong class="text-danger">March 27, 2019 (Update notes)</strong>
                                                                                <ul>
                                                                                    <li>
                                                                                        <strong> Importing Data Enhancement : </strong>
                                                                                        <ul class="vue-list-inner" style="margin-left:20px;">
                                                                                                <li>Fasten the uploading of data</li>
                                                                                                <li>Multiple File uploading (Limit by 4) <strong> Note: </strong> Discount file is always last to upload</li>
                                                                                        </ul>            

                                                                                    </li>

                                                                                    <li>
                                                                                        <strong> Field Validation Customization </strong> 
                                                                                        <ul class="vue-list-inner" style="margin-left:20px;">
                                                                                                <li> upon uploading, each data under go  has a validation to lessen the error in Middleware</li>
                                                                                                <li>Can customize all the validation in the fields</li>
                                                                                                <li>Can customize the Header Column and the Filename of an excel format</li>
                                                                                        </ul>            
                                                                                    
                                                                                    
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong> Sas Automation :  </strong>Distributor have the option to input the EmployeeNumber of the Sas instead of it’s Salesforce Id.
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong> Orders and Returns Report Enhancement : </strong> can generate all the field in report
                                                                                    </li>
                                                                                    
                                                                                    <li>
                                                                                        <strong>    Multiple Discount </strong>
                                                                                            <ul class="vue-list-inner" style="margin-left:20px;">
                                                                                                    <li>Separated the discounts for order details + invoice details</li>
                                                                                                    <li>Discount in Order Header can view Seperately (Discount Amount , Description).</li>
                                                                                                    <li>Discount in Product/Item can view Seperately (Discount Amount , Description).</li>                                                                        
                                                                                            </ul>            
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong>  Catchweight : </strong> Capture the actual quantity received by Distributor for specific Order Item.
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong> Seperated Multiple Invoices : </strong> can seperate the Invoices in Multiple with the same Sales Order Number
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong>Company Settings :</strong> can Manage the Details of the company
                                                                                    </li>

                                                                                    <li>
                                                                                        <strong>Delete Orders :</strong> can Delete the orders <i class="fa fa-info"></i> <strong> Note : PROCESSED Orders cannot be deleted (has a SIF Id)</strong>
                                                                                    </li>




                                                                                </ul>
                                                                            </div>              
                                                                            
                                                                
                                                                    </div>
                                                            </div>
                                                            
                                                        </div>

                                                        <div class="tab-pane fade" id="developer" role="tabpanel" aria-labelledby="developer-tab">                                                                                                    

                                                            <div class="row">

                                                                <div class="col-md-12">

                                                                        <div class="card">
                                                                                <div class="card-header">
                                                                                    <strong class="card-title mb-3">Developer Profile</strong>
                                                                                </div>
                                                                                <div class="card-body">
                                                                                    <div class="mx-auto d-block">
                                                                                    <img class="rounded-circle mx-auto d-block"  width="140px" src="{{ asset('images/genysis/developer.jpg') }}" alt="Card image cap">
                                                                                        <h5 class="text-sm-center mt-2 mb-1">Mark Gil Baterna</h5>
                                                                                        <div class="location text-sm-center">
                                                                                            <i class="fa fa-envelope"></i> Baterna.mark@gmail.com</div>
                                                                                    </div>
                                                                                    <hr>
                                                                                    <div class="card-text text-sm-center">
                                                                                        All things are <strong>possible</strong> if you <strong>believe</strong>. <br>
                                                                                         <span style="    margin-left: 30%;"> Mark 9:23</span>
                                                                                    </div>
                                                                                </div>
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

@endsection


@section('modals')
    
@endsection


@section('scripts')
    <script src="{{ asset('vendor/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('vendor/sweetalert/sweetalert.min.js') }}"></script>

    <script src="{{ asset('vendor/fileupload/bootstrap-fileupload.js') }}"></script>
    <script src="{{ asset('vendor/jquery-minicolors/jquery.minicolors.min.js') }}"></script>


    <script>
         $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });     


            $('#color-picker').each(function() {
                $(this).minicolors({
                    control: $(this).attr('data-control') || 'hue',
                    position: $(this).attr('data-position') || 'bottom left',

                    change: function(value, opacity) {
                        if (!value) return;
                        console.log(value);
                        if (typeof console === 'object') {
                            $('#header-bg').css('background-color', value);
                        }
                    },
                    theme: 'bootstrap'
                });
            });     
    </script>    
@endsection

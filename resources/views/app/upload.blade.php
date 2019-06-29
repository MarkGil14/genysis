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
                                        <li class="list-inline-item">
                                            <a href="{{ route('app.dashboard') }}">Dashboard</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item active">Import Data</li>
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
                    <div class="col-md-12 col-lg-8">
                        
                        {{-- <div class="col-md-12 col-lg-8 offset-lg-2"> --}}
                        <div class="au-card">
                                <div class="card-header bg-secondary text-white">
                                    <i class="fa fa-upload"></i>
                                    <strong class="card-title pl-2">Import Data                                       
                                    </strong>

                          

                                    <div class='btn-group pull-right'>
                                            <button
                                                type="button"
                                                class="btn btn-info  btn-sm dropdown-toggle"
                                                data-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false">Download Format<span class="caret"></span></button>
                                            <ul class="dropdown-menu pd-10">                                            
                                            <li><a href="{{  route('format.siforder') }}"  >{{ $formats->siforder }}</a></li>
                                                {{-- <li><a href="{{ route('format.sifdiscount') }}"  >{{ $formats->sifdiscount }}</a></li> --}}
                                                <li><a href="{{ route('format.sifreturn') }}" >{{ $formats->sifreturn }}</a></li>
                                                <li><a href="{{ route('format.sifitem') }}" >{{ $formats->sifitem }}</a></li>
                                                <li><a href="{{ route('format.sifcustomer') }}" >{{ $formats->sifcustomer }}</a></li>
                                                <li><a href="{{ route('format.sifdsp') }}" >{{ $formats->sifdsp }}</a></li>                
                                            </ul>
                                        </div>
             
            
            
                                </div>
                                <div class="card-body text-white">
                                    <div class="mx-auto d-block">
                                        <div class="location text-sm-center text-secondary">
                                            <i class="fa fa-arrow-down"></i> Upload your data here</div>
                                        </div>
                                        <form action="#" id="frmFileUpload" class="dropzone" method="POST" enctype="multipart/form-data" >
                                            @csrf
                                        <div class="dz-message">
                                            <div class="drag-icon-cph">
                                                <i class="fa fa-paperclip fa-3x"></i>
                                            </div>
                                            <h4>Drop files here or click to attach.</h4>
                                        </div>
                                        <div class="fallback">
                                            <input name="file" type="file" multiple />
                                        </div>
                                 
                                    </form>                                    
                                    <div class="card-text text-sm-center mt-3" id="upload-btn">
                                            <button type="submit" id="btn-submit" class="btn btn-secondary btn-sm">
                                                    <i class="fa fa-play-circle"></i>&nbsp; Upload                                                                                                          
                                            </button>                                                   
                                    </div>
                                    <div class="card-text text-sm-center mt-3 hide" id="reload-btn">
                                        <a href="{{ route('app.upload') }}" id="btn-submit" class="btn btn-info btn-sm">
                                                    <i class="fa fa-refresh"></i>&nbsp; Reload                                                                                                          
                                        </a>                                                   
                                    </div>


                                    <div class="col-md-12 mt-3">
                                            <div class="card">
                                                    <div class="card-header bg-secondary text-white">
                                                        <strong class="card-title"><i class="fa fa-exclamation-circle"></i> Message</strong>
                                                    </div>

                                                    <div class="au-task js-list-load"  id="successMsg" style="max-height:500px;overflow:overlay;">
                                                            <div class="au-task__title">
                                                                <p>Please upload your data</p>
                                                            </div>                                                     
                                                    </div>               
                                                                                                        
                                                    <div class="au-task js-list-load hide"  id="uploadErr" style="max-height:500px;overflow:overlay;">
                                                    </div>                                                    
                                                                                        
                                        
                                                </div>
                    
                                    </div>


                                </div>
                                
                            </div>                    
                </div>

                <div class="col-md-12 col-lg-4">
                        <div class="card">
                                <div class="card-header">
                                    <strong class="card-title"><i class="fa fa-backward"></i> Last Upload (Orders)</strong>
                                    <a href="{{ route('app.order-upload-history') }}" class="btn btn-sm btn-info float-right">Upload History</a>
                                </div>
                                <div class="card-body">

                                        <div class="table-wrap">
                                                <div class="table-responsive">                                                    
                                                    <table class="table table-borderless table-striped">
                                                        <thead class="bg-secondary text-white">
                                                            <tr><th>Description</th>
                                                            <th width="50%">Total</th>
                                                        </tr></thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>Date Uploaded</td>
                                                            <td id="date-uploaded" class="text-primary">{{ date('m-d-Y', strtotime($orderLastUpload->created_at)) }}</td>
                                                            </tr>
        
                                                            <tr>
                                                                <td>Orders Count</td>
                                                            <td id="orders-count" class="text-primary">{{ number_format($orderLastUpload->orders_count, 0, '.', ',') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Line Items Count</td>
                                                            <td id="line-items-count" class="text-primary">{{ number_format($orderLastUpload->line_items_count, 0, '.', ',') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Qty</td>
                                                            <td id="total-qty" class="text-primary">{{ number_format($orderLastUpload->total_qty,4, '.', ',') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Sales</td>
                                                            <td id="total-sales" class="text-primary">{{ number_format($orderLastUpload->total_sales,4, '.', ',') }}</td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Discount</td>
                                                            <td id="total-discount" class="text-primary">{{ number_format($orderLastUpload->total_discount ,2, '.', ',') }}</td>
                                                            </tr>
    

                                                        </tbody>
                                                    </table>
                                                </div>
                                        </div>
                
                                </div>
                                
                                        

                        </div>

                        <div class="card">
                                <div class="card-header">
                                    <strong class="card-title"><i class="fa fa-backward"></i> Last Upload (Returns)</strong>
                                    <a href="{{ route('app.return-upload-history') }}" class="btn btn-sm btn-info float-right">Upload History</a>
                                </div>
                                <div class="card-body">


                                    <div class="table-wrap">
                                        <div class="table-responsive">                                                    
                                            <table class="table table-borderless table-striped">
                                                <thead class="bg-secondary text-white">
                                                    <tr><th>Description</th>
                                                    <th width="50%">Total</th>
                                                </tr></thead>
                                                <tbody>
                                                    <tr>
                                                        <td>Date Uploaded</td>
                                                    <td id="date-uploaded" class="text-primary">{{ date('m-d-Y', strtotime($returnLastUpload->created_at)) }}</td>
                                                    </tr>

                                                    <tr>
                                                        <td>Returns Count</td>
                                                    <td id="orders-count" class="text-primary">{{ number_format($returnLastUpload->orders_count, 0, '.', ',') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Line Items Count</td>
                                                    <td id="line-items-count" class="text-primary">{{ number_format($returnLastUpload->line_items_count, 0, '.', ',') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Qty</td>
                                                    <td id="total-qty" class="text-primary">{{ number_format($returnLastUpload->total_qty,4, '.', ',') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Returns</td>
                                                    <td id="total-sales" class="text-primary">{{ number_format($returnLastUpload->total_sales,4, '.', ',') }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Total Discount</td>
                                                        <td id="total-discount" class="text-primary">{{ number_format($returnLastUpload->total_discount ,2, '.', ',') }}</td>
                                                    </tr>


                                                </tbody>
                                            </table>
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

    <script>

 


         $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });     
        Dropzone.autoDiscover = false;
        let count = 1;
        // Dropzone.options.frmFileUpload = {
        $('.dropzone').dropzone({
            url : "{{ route('user.import-data') }}",
            autoProcessQueue : false,
            paramName: "file",
            maxFilesize: 10,
            maxFiles: 4,
            acceptedFiles : '.xlsx',
            addRemoveLinks : true,
            parallelUploads: 4,
            uploadMultiple: true,            
            init: function () {

                var myDropzone = this;

                $('#btn-submit').click(function(e){
                        swal({
                            title: "Are you sure?",
                            text: "This will be save in the system database!",
                            type: "warning",
                            showCancelButton: true,
                            confirmButtonColor: "#4caf50",
                            confirmButtonText: "Yes, upload it!",
                            cancelButtonText: "No, cancel please!",
                            showLoaderOnConfirm: true,
                            closeOnConfirm: false,
                            closeOnCancel: false
                        }, function (isConfirm) {

                            if (isConfirm) {                       
                                $('#successMsg').html('');
                                //loading
                                $("#preload").fadeIn();;

                                e.preventDefault();
                                myDropzone.processQueue();
                                myDropzone.on('success', function(file, responseText){
                                   console.log(file.status);
                                   console.log(responseText);
                                    $("#preload").fadeOut();;
                                    let errArrMsg = [];
                                    if(responseText != ''){
                                        $('#uploadErr').html('');
                                        arrMsg = $.parseJSON(responseText);                                                        
                                        errArrMsg = $.parseJSON(arrMsg.errors);       
                                        if(arrMsg.file_error == "true")
                                        {
                                            $('#uploadErr').removeClass('hide');
                                                errArrMsg.forEach(element => {
                                                    $('#uploadErr').append(                                            
                                                        '<div class="au-task__item au-task__item--danger">'+
                                                        '<div class="au-task__item-inner">'+
                                                        '<p class="task status--denied"> '+element+' </p>'+
                                                        '</div></div>');                                            
                                                    });                                     

                                                swal("Failed!", "Oops Something went wrong.", "error");
                                                // myDropzone.removeAllFiles(true);

                                        }else{                                    

                                            if(file.name != arrMsg.entity)
                                            {
                                                $('#successMsg').append(                                            
                                                '<div class="au-task__item au-task__item--success">'+
                                                '<div class="au-task__item-inner">'+
                                                '<p class="task text-success"><i class="fa fa-check-circle"> </i> '+file.name+' was Successfully Uploaded!  </p>'+
                                                '</div></div>');
                                                // myDropzone.removeFile(file);    
                                            } 

                                                $('#uploadErr').removeClass('hide');
                                                $('#uploadErr').append(
                                                    '<div class="au-task__title">'+
                                                    '<p class="status--denied"><i class="fa fa-exclamation-circle"> </i> '+arrMsg.entity+' was Failed to upload! Please fix <span class="badge badge-danger"> '+ errArrMsg.length +'</span> error(s) above</p>'+
                                                    '</div>');

                                                    errArrMsg.forEach(element => {

                                                        $('#uploadErr').append(                                            
                                                            '<div class="au-task__item au-task__item--danger">'+
                                                            '<div class="au-task__item-inner">'+
                                                            'Header : <span class="badge badge-secondary"> '+element.field+' </span> | '+
                                                            'Row : <span class="badge badge-secondary"> '+element.row+' </span>'+
                                                            '<hr>'+
                                                            '<p class="task status--denied"> '+element.errors+' </p>'+
                                                            '</div></div>');                                            
                                                        });                                     

                                                    swal("Failed!", "Oops Something went wrong.", "error");
                                                    // myDropzone.removeAllFiles(true);

                                            }
                                    }else{
                                        $('#uploadErr').html('');
                                        $('#uploadErr').addClass('hide');   
                                        // let orderDetail = $.parseJSON(responseText);
                                        // const entries = Object.entries(orderDetail);
                                        $('#successMsg').append(
                                                    '<div class="au-task__title">'+
                                                    '<p class="text-success"><i class="fa fa-check-circle"> </i> '+file.name+' was Successfully Uploaded! </p>'+
                                                    '</div>');     

                                        // for (const [desc, count] of entries) {                                                    
                                        //     $('#successMsg').append(                                            
                                        //     '<div class="au-task__item au-task__item--primary">'+
                                        //     '<div class="au-task__item-inner">'+
                                        //        desc + ' : <span class="text-primary"> '+count+' </span> '+
                                        //     '</div></div>');                                            
                                        // }
                                        
                                        myDropzone.removeFile(file);                                                                                       
                                       
                                        swal("Successfull!", "The File was Successfully Upload.", "success");
                                    }
                                            $('#upload-btn').addClass('hide');
                                            $('#reload-btn').removeClass('hide');                                    
                                });                               
                                
                                myDropzone.on('error', function(file, response){
                                    $("#preload").fadeOut();;
                                    swal.close();
                                    console.log(response);
                                    $('#successMsg').html('');
                                    $('#uploadErr').removeClass('hide');                                    
                                    $('#uploadErr').append(
                                        '<div class="au-task__title">'+
                                        '<p class="status--denied"><i class="fa fa-exclamation-circle"> </i> Error Found in '+file.name+'</p>'+
                                        '</div>'+
                                        '<div class="au-task__item au-task__item--warning">'+
                                        '<div class="au-task__item-inner">'+
                                        '<h5 class="task"><a href="#">'+response.message+'</a></h5>'+
                                        '</div></div>');
                                        // myDropzone.removeAllFiles(true);
                                        $('#upload-btn').addClass('hide');
                                        $('#reload-btn').removeClass('hide');
                                });
                                                        
                            } else {
                            swal("Cancelled", "Your Upload file was Cancelled", "error");
                            }
                        });

                    });                

                    // this.on('error', function(file, response){
                    //     console.log(file);
                    //     console.log(response);
                    // })
                    this.on('addedfile', function(file){
                        if(this.files.length){
                            var _i, _len;
                            for(_i = 0, _len = this.files.length; _i < _len - 1; _i++)
                            {
                                if(this.files[_i].name === file.name && this.files[_i].size === file.size && this.files[_i].lastModifiedDate.toString() === file.lastModifiedDate.toString())
                                {
                                    this.removeFile(file);
                                }
                            }
                        }
                    });
              
                 
                 

                }
            
        });
     
        // Dropzone.autoDiscover = false;
    </script>    
@endsection

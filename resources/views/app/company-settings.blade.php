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
                                            <li class="list-inline-item active">Company Settings</li>
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
                                    <i class="fa fa-cogs"></i> Company Settings      
                                </div>
                                <div class="card-body">

                                        <div class="form-group">
                                                <form method="POST" enctype="multipart/form-data" action="{{ route('app.update.company-logo') }}">  
                                                    @csrf                                                  
                                                    <div class="fileupload fileupload-new" data-provides="fileupload">
                                                        <center>                                                        
                                                        <div class="fileupload-new thumbnail" style="width:100%; height: 150px;"><img src="{{ asset($company->logo) }}"/></div>
                                         
                               
                                                        <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                                        <div>
                                                            <span class="btn btn-file btn-secondary"><span class="fileupload-new">Select logo</span><span class="fileupload-exists">Change</span><input type="file" accept=".jpg, .png" name="upload_file" required/></span>
                                                            <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload">Remove</a>
                                                        </div>
                                       
                                                        <hr>
                                                                                       
                                                            <button class="btn btn-warning btn-sm " type="submit">Save Logo</button>
                                       
                                              
                                                        </center>
                                                    </div>
                                                </form>

                                            </div>

                            <form action="{{ route('app.update.company-settings') }}" method="POST">
                                @csrf

                                <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>Navigation Tab Color</label>
                                        </div>
                                        <div class="col-md-8">                                        
                                        <input type="text" id="color-picker" class="form-control" data-control="hue" value="{{ $company->theme_color }}" name="company_color">
                                        </div>
                                    </div>
    
                                    <div class="row mt-3">
                                        <div class="col-md-4">
                                            <label>Company Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <input type="text" class="form-control" value="{{ $company->company_name }}" name="company_name">
                                        </div>
                                    </div>

                                    <div class="row mt-3">
                                            <div class="col-md-4">
                                                <label>Company Address</label>
                                            </div>
                                            <div class="col-md-8">
                                                <input type="text" class="form-control" value="{{ $company->company_address }}" name="company_address">
                                            </div>
                                        </div>

                       
                                            <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <label>Company Telnum/Mobile</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="text" class="form-control" value="{{ $company->company_telnum }}" name="company_number">
                                                    </div>
                                                </div>
                                            <div class="row mt-3">
                                                    <div class="col-md-4">
                                                        <label>Company Email</label>
                                                    </div>
                                                    <div class="col-md-8">
                                                        <input type="email" class="form-control" value="{{ $company->company_email }}" name="company_email">
                                                    </div>
                                                </div>                                        

                                                <div class="row mt-3">
                                                    <div class="col-md-12">
                                                        <button class="btn btn-success float-right" name="btn_update" type="submit">Update</button>
                                                    </div>
                                            </div>

                                    </form>


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

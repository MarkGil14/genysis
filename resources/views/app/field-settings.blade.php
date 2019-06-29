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
                                            <li class="list-inline-item active">Field Settings</li>
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
                                    <i class="fa fa-cogs"></i> {{ $field }} Fields Settings                                          

                                </div>
                                <form action="{{ route('app.update.field-validations') }}" method="POST">
                                    @csrf
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                            <label>Template Name</label>
                                            </div>
                                            <div class="col-md-8">
                                                    <input type="text" class="form-control" value="{{ $field }}" id="template-name" name="template_name" required>
                                                    <input type="hidden" value="{{ $selectedFieldHeader }}" name="header">
                                                    <div class="alert alert-info mt-3">
                                                        This will be the file name of the Template
                                                    </div>
                                            </div>
                                        </div>



                                        <div class="card-header bg-secondary text-white mt-3">
                                            <i class="fa fa-pencil-square-o"></i> Fields Validations      
                                            <div class="float-right">
                                                    <a href="javascript::void(0);" class="text-white btn btn-info btn-sm" data-target="#help-modal" data-toggle="modal">
                                                            <i class="fa fa-question-circle"></i>
                                                            Help
                                                        </a>
                                            </div>
                                        </div>                                    
                                    
                                        @foreach ($headers as $head_key => $head_value)
                                            @foreach ($validations as $valid_key => $valid_value)
                                                    @if($head_key === $valid_key)                                                  
                                                    <div class="card mt-3">
                                                            <div class="card-header">
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                            <strong class="card-title">{{ $head_key }}</strong>
                                                                            <input type="hidden" value="{{ $head_key }}" name="header_key[]">
                                                                    </div>
                                                                    <div class="col-md-6">
                                                                        <input type="text" class="form-control" value="{{ $head_value }}" name="header_value[]" required>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <div class="card-body">                                                        
                                                                <div class="row">                                                            
                                                                        <div class="col-md-12">                                                                        
                                                                            <div class="input-group mt-3">
                                                                                <input type="text" class="form-control" value="{{ $valid_value }}" name="validation_value[]" required>                                                                           
                                                                            </div>                                                                                                                                                                                                                                
                                                                        </div>


                                                                </div>

                                                        
                                                            </div>
                                                        </div>  

                                            
                                                        
                                                    @endif
                                            @endforeach
                                        @endforeach

                                        <div class="row">
                                            <div class="col-md-12">
                                                <button class="btn btn-success float-right" type="submit">Update Validations</button>
                                            </div>
                                        </div>




                                    </div>
                                </form>
                                
                            </div>                    
                </div>


            </div>
        </div>
    </section>

@endsection


@section('modals')

<div class="modal fade" id="help-modal" tabindex="-1" role="dialog" aria-labelledby="staticModalLabel" aria-hidden="true" data-backdrop="static">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticModalLabel">Field Validations</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                    <div class="modal-body">                                                                              
                        <div class="col-md-12">
                            <h4>Validation Rule</h4>
                            <p class="mt-3">All validation must be seperated by "|" (Vertical Bar)</p>
                            <p> <i class="fa fa-info"></i> <strong> Reminder : </strong> Discount is always last in uploading</p>
                            <hr>
                            <h4>Validations</h4>
                                <ul class="mt-3">
                                        <li>
                                            <strong> required </strong> => 'The field must have a value'
                                        </li>
                                        <li>
                                            <strong> minlength : integer </strong> => 'The required Minimum length of the field'
                                        </li>
                                        <li>
                                            <strong> maxlength : integer </strong> => 'The required Maximum length of the field'
                                        </li>

                                        <li>
                                            <strong> length : integer </strong> => 'The required exact length of the field'
                                        </li>

                                        <li>
                                            <strong> enum : string </strong> (seperated by comma ',' in adding a choices) ) => 'The required value of the field only'
                                        </li>

                                        <li>
                                            <strong> numeric </strong> => 'Validate if the field is only a numeric value'
                                        </li>
 

                                        <li>
                                            <strong> alphabet </strong> => 'Validate if the field is only a letter'
                                        </li>

                                        <li>
                                            <strong> date </strong> => 'The validation required the field is only a date value'
                                        </li>

                                        <li>
                                            <strong> maxvalue : integer </strong> => 'The required Maximum value of the field'
                                        </li>

                                        <li>
                                            <strong> minvalue : integer </strong> => 'The required Minimum value of the field'
                                        </li>


                                        <li>
                                            <strong> nullable </strong> => 'The field is okey to be null or empty'
                                        </li>
 

                                      </ul>

                        </div>
                        
    
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>     
            </div>
        </div>
     </div>
     


    
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

      
        $('#help').popover();

    </script>    
@endsection

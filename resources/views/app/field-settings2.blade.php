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
                                        <li class="list-inline-item active">
                                            <a href="#">Home</a>
                                        </li>
                                        <li class="list-inline-item seprate">
                                            <span>/</span>
                                        </li>
                                        <li class="list-inline-item">Dashboard</li>
                                    </ul>
                                </div>
                                <form class="au-form-icon--sm" action="" method="post">
                                    <input class="au-input--w300 au-input--style2" type="text" placeholder="Search for datas &amp; reports...">
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
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                        <label>Template Name</label>
                                        </div>
                                        <div class="col-md-8">
                                            <form action="{{ route('app.update.format-name') }}" method="POST">
                                                @csrf
                                                <input type="text" class="form-control" value="{{ $field }}" id="template-name" name="template_name">
                                                <input type="hidden" value="{{ $selectedFieldHeader }}" name="header">
                                                <div class="alert alert-info mt-3">
                                                    This will be the file name of the Template
                                                </div>
                                                <button class="btn btn-success float-right" type="submit" name="btn_format_update">Update</button>
                                            </form>

                                        </div>
                                    </div>



                                    <div class="card-header bg-secondary text-white mt-3">
                                        <i class="fa fa-pencil-square-o"></i> Fields Validations      
                                    </div>                                    
                                
                                    @foreach ($headers as $head_key => $head_value)
                                        @foreach ($validations as $valid_key => $valid_value)
                                                @if($head_key === $valid_key)
                                                    <?php
                                                    $validationArr = [];
                                                    $validationArr = explode('|' , $valid_value);

                                                    ?>
                                                 <div class="card mt-3">
                                                        <div class="card-header">
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                        <strong class="card-title">{{ $head_key }}</strong>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <input type="text" class="form-control" value="{{ $head_value }}">
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="card-body">                                                        
                                                            <div class="row">                                                            
                                                                    <div class="col-md-12">

                                                                        @foreach ($validationArr as $rule)
                                                                        <?php  if($rule === 'required'): ?>
                                                                            <div class="input-group mt-3">
                                                                                <input type="text" class="form-control" value="{{ $rule }}">                                                                           
                                                                            </div>
                                                                        <?php 
                                                                                elseif(explode(':',$rule)[0] === 'minlength'):                
                                                                                    $result =  explode(':',$rule)[1];
                                                                        ?>

                                                                            <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                
                                                                        <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'maxlength'):                
                                                                                        $result = explode(':',$rule)[1];
                                                                        ?>
                                                                            <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                
                                                                        
                                                                        <?php 
                                                                        elseif(explode(':',$rule)[0] === 'length'):                
                                                                                        $result =  explode(':',$rule)[1];
                                                                            ?>
                                                                            <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                


                                                                            <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'enum'):                                                                
                                                                                        $result =  explode(':',$rule)[1];
                                                                            ?>
                                                                                <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                                                                                                        
                                                                            <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'numeric'):                                                                
                                                                            ?>

                                                                            <div class="input-group mt-3">
                                                                                    <input type="text" class="form-control" value="{{ $rule }}" disabled>
                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>


                                                                            <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'alphabet'):                                                                
                                                                            ?>

                                                                            <div class="input-group mt-3">
                                                                                    <input type="text" class="form-control" value="{{ $rule }}" disabled>
                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            <?php 
                                                                                    elseif($rule === 'date'):            
                                                                            ?>

                                                                            <div class="input-group mt-3">
                                                                                    <input type="text" class="form-control" value="{{ $rule }}" disabled>
                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>

                                                                            <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'maxvalue'):            
                                                                                            $result = explode(':',$rule)[1];
                                                                            ?>
                                                                            <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                

                                                                            <?php 
                                                                                    elseif(explode(':',$rule)[0] === 'minvalue'):            
                                                                                            $result = explode(':',$rule)[1];
                                                                            ?>
                                                                            <div class="input-group mt-3">
                                                                                    <div class="input-group-btn">
                                                                                            <button class="btn btn-secondary">
                                                                                                {{ explode(':',$rule)[0] }}
                                                                                            </button>
                                                                                        </div>                                                                                
                                                                                    <input type="text" class="form-control" value="{{ $result }}">

                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>                                                                        
                                                                                


                                                                            <?php 
                                                                                    elseif($rule === 'nullable'):                
                                                                                ?>


                                                                            <div class="input-group mt-3">
                                                                                    <input type="text" class="form-control" value="{{ $rule }}" disabled>
                                                                                    <div class="input-group-btn">
                                                                                        <button class="btn btn-danger">
                                                                                            <i class="fa fa-times"></i>
                                                                                        </button>
                                                                                    </div>
                                                                                </div>


                                                                            <?php
                                                                                    endif;
                                                                            ?>

                                                                        @endforeach                                                                                                            
                                                                        <button class="btn btn-sm btn-secondary pull-right mt-3"><i class="fa fa-plus"></i> Validation</button>

                                                                    </div>


                                                            </div>

                                                    
                                                        </div>
                                                    </div>  
                                          



                                                @endif
                                        @endforeach
                                    @endforeach


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

        // $(document).on('focusout', '#template-name', function(){            
        //     let val = $(this).val();
        //     $.ajax({
        //             url:"{{ route('app.update.format-name') }}",
        //             type : 'POST',
        //             data : {
        //                 'template_name' : val,
        //                 'header' : '{{ $selectedFieldHeader }}'
        //             },
        //             error : function(err){
        //                 console.log(err);
        //             }
        //         });
        //     });

      
     
    </script>    
@endsection

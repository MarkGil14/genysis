@extends('layouts.app_vue')
 
@section('content')
   <div id="app">
       <navbar></navbar>
       <div class="container">
           <articles></articles>
       </div>
    </div>                

@endsection
@section('scripts')
    <script src="{{ asset('js/app.js') }}"></script>
@endsection

 
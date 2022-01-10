@extends('layouts.backend.app')

@section('title','Message')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<!-- <link href="{{asset('public/assets/backend/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" /> -->
@endpush

@section('content')

<div class="container-fluid">
	<!-- Tabs With Icon Title -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Send Message
                    </h2>                                      
                </div>
                <div class="body">
                <div class="row clearfix">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="body">
                            <form method="POST" action="{{ route('send_message') }}" class="form-horizontal" enctype="multipart/form-data">
                                @csrf   
                                
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="mobile">Select User </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <select class="form-control mobile" name="mobile[]" id="mobile" multiple="multiple" >
                                            <!-- <option value="">Select Support Engg</option> -->
                                            @foreach($users as $user)                                                                
                                                <option value="{{ $user->phone }}">{{ $user->name }} ({{ $user->email }})</option>
                                            @endforeach                                                               
                                            </select>
                                        </div>
                                    </div>
                                    
                                </div>

                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="message">Meesage</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea style="border:1px solid black" rows="5" name="message" class="form-control" required autofocus></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                
                                <div class="row clearfix">
                                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>                                                      
                                    </div>                                                    
                                </div>
                            </form>
                                            
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Tabs With Icon Title -->
</div>
    	
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.mobile').select2({
        placeholder: "Select Users to send messages",
        multiple: true,        
        });
   
});
</script>
@endpush
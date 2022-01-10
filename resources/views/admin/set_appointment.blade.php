@extends('layouts.backend.app')

@section('title','Set Appointment')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
@endpush

@section('content')

<div class="container-fluid">
	<!-- Tabs With Icon Title -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Set Appointment
                    </h2>                    
                </div>
                <div class="body">
                <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <form method="POST" action="{{ route('appointments.store') }}" class="form-horizontal" enctype="multipart/form-data">
                                            	@csrf

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="patient_id">Patients Name </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control patient" name="patient_id" id="patient_id" required >
                                                            <option value="">Select</option>        
                                                                <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->phone }})</option>
                                                                                                               
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="doctor_id">Assigned Doctor </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control doctor" name="doctor_id" id="doctor_id" required >
                                                                <option value="">Select</option>
                                                                @foreach($doctor as $data)            
                                                                <option value="{{ $data->id }}">{{ $data->name }}</option>
                                                                @endforeach                                            
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="patient_type">Patient Type </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control ptype" name="patient_type" id="patient_type" required >
                                                                <option value="">Select</option>
                                                                <option value="Existing">Existing</option>
                                                                <option value="New">New</option>                                                                                         
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                            
                                            	

                                                
                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="patient_symptoms">Symptoms</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <textarea style="border:1px solid gray" rows="5" name="patient_symptoms" class="form-control"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="visit_date">Visit Date </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="date" id="visit_date" class="form-control" placeholder="" name="visit_date" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="slot_id">Time Schedule </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control slot" name="slot_id" id="slot_id" required >
                                                                <option value="">Select</option>
                                                                @foreach($slot as $data)            
                                                                <option value="{{ $data->id }}">{{ $data->start_time }} - {{ $data->end_time }}</option>
                                                                @endforeach                                            
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                                <input type="hidden" name="phone" value="{{ $user->phone }}">
                                                <!-- <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="task_end_date">Approx. end visit Date </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="date" id="task_end_date" class="form-control" placeholder="" name="task_end_date" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  -->

                                                <!-- <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="estimated_task_hour">Forecast hour </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="number" id="estimated_task_hour" class="form-control" placeholder="" name="estimated_task_hour" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  -->
                                                <!-- <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="task_comments">Comments </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <input type="text" id="task_comments" class="form-control" placeholder="" name="task_comments" value="">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>  -->

                                               
                                                
                                                <div class="row clearfix">
                                                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect">SAVE</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script>
$(document).ready(function() {
    $('.doctor').select2({
        placeholder: "Select Doctor",
    });
    $('.ptype').select2({
        placeholder: "Select Type",
    });
    $('.patient').select2({
        placeholder: "Select Patient",
    });
   
});

@endpush
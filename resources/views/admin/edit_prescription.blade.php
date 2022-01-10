@extends('layouts.backend.app')

@section('title','Update Prescription')

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
                        Update Prescription
                    </h2>                    
                </div>
                <div class="body">
                <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="body">
                                            <form method="POST" action="{{ route('prescription_update',$appointment->id) }}" class="form-horizontal" enctype="multipart/form-data">
                                            	@csrf
                                               
                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="patient_id">Patients Name </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control patient" name="patient_id" id="patient_id" disabled >
                                                                             
                                                                <option value="{{ $appointment->users->id }}">{{ $appointment->users->name }} ({{ $appointment->users->phone }})</option>
                                                                                                               
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="doctor_id">Assigned Doctor</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control doctor" name="doctor_id" id="doctor_id" disabled> 
                                                                <option value="">{{ $appointment->doctors->name }}</option>                                                                         }                                                            
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
                                                                <textarea style="border:1px solid gray" rows="5" name="patient_symptoms" class="form-control" disabled>{{ $appointment->patient_symptoms }}</textarea>
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
                                                                <input type="date" id="visit_date" class="form-control" placeholder="" name="visit_date" value="{{ $appointment->visit_date }}" disabled>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="slot_id">Time Slot </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <select class="form-control doctor" name="slot_id" id="slot_id" disabled >                                                                
                           
                                                                <option value="">{{ $appointment->slots->start_time }} - {{ $appointment->slots->end_time }}</option>
                                                                  
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div> 


                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="prescribe_medicines">Prescribe Medicines</label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                                <textarea style="border:1px solid gray" rows="5" name="prescribe_medicines" class="form-control" autofocus>{{ $appointment->prescribe_medicines }}</textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                               
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('.doctor').select2({
        placeholder: "Select Doctor",
    });
   
});

@endpush
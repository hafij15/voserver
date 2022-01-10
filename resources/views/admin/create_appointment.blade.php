@extends('layouts.backend.app')

@section('title','Create Appointment')

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
                        Create Appointment
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
                                                            <select class="form-control doctor" name="doctor_id" onchange="doctorinfo()" id="doctor_id" required >
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
                                                            <select class="form-control patient" name="patient_type" id="patient_type" required >
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
                                                                <input onchange="doctorinfo()" type="date" id="visit_date" class="form-control" placeholder="" name="visit_date" value="" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

                                                <input type="hidden" name="" id="appointId" value="{{ $id }}">

                                                <div class="row clearfix">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="slot_id">Time Schedule </label>
                                                    </div>
                                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7" id="slotlist">
                                                        <div class="form-group">
                                                            <select class="form-control schedule" onkeyup="createSchedule()" onchange="createSchedule()" name="slot_id" id="slot_id" required >
                                                                <option value="">Select</option>

                                                                <option value="create_schedule">Create Time Schedule</option>
                                                                @foreach($slot as $data)            
                                                                <option value="{{ $data->id }}">{{ date('h:i:s A', strtotime($data->start_time)) }} - {{ date('h:i:s A', strtotime($data->end_time)) }}</option>
                                                                @endforeach                                            
                                                            </select>
                                                        </div>
                                                    </div>                                                   
                                                </div>
                                                <input type="hidden" name="phone" value="{{ $user->phone }}">


                                                <div class="row clearfix schedulTime" style="display: none;">
                                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                        <label for="task_end_date">Create Schedule </label>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-3">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                               <input type="Time" id="start_time" class="form-control" onchange="timeCheck()" name="start_time" value="" data-toggle="tooltip" data-placement="top" title="Start Time" >
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-5 col-md-5 col-sm-4 col-xs-3">
                                                        <div class="form-group">
                                                            <div class="form-line">
                                                               <input type="time" id="end_time" class="form-control" name="end_time" value=""  onchange="lastTimeCheck()" onkeyup="lastTimeCheck()" onclick="lastTimeCheck()" data-toggle="tooltip" data-placement="top" title="End Time">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> 

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
                                                        <a href="{{route('dashboard')}}" style="text-decoration: none;">
                                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">CANCEL</button>
                                                    </a>
                                                        <button type="submit" class="btn btn-primary m-t-15 waves-effect saveInfo">SAVE</button>
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
<script type="text/javascript">
        var dtToday = new Date();
        
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if(month < 10)
            month = '0' + month.toString();
        if(day < 10)
            day = '0' + day.toString();
        
        var maxDate = year + '-' + month + '-' + day;
        
         $('#visit_date').attr('min', maxDate);

    function createSchedule()
    {
        var slot_id = $('#slot_id').val();
        if(slot_id == 'create_schedule')
        {
            $('.schedulTime').css('display','block');
        }
        else
        {
            $('.schedulTime').css('display','none');
        }
        
    }

    function doctorinfo(){
        $('.saveInfo').prop('disabled', true);
        var doc_id=$('#doctor_id').val();
        var visit_date=$('#visit_date').val();


        if (visit_date == '') {
            $('.saveInfo').prop('disabled', true);
        } else {
            $('.saveInfo').prop('disabled', false);
        }

        
        var appointId = $('#appointId').val();
        // alert(doc_id);
        targetUrl='/teleassist-amarlab/set_appointment/'+appointId+'/doctor_slot/'+doc_id+'/'+visit_date;


        $.get(targetUrl,function(data){

            $('#slotlist').html(data);
        });

    }

    function timeCheck()
    {
        var start_time = $('#start_time').val();


         minutes = 30; 
        var end_time = moment(start_time, "hh:mm")
        .add(minutes, 'minutes')
        .format('HH:mm');
        
        $('#end_time').val(end_time);
    } 


    function lastTimeCheck()
    {
        var end_time = $('#end_time').val();

        var start_time = $('#start_time').val();

        minutes = 30; 
        var new_start_time = moment(start_time, "hh:mm")
        .add(minutes, 'minutes')
        .format('HH:mm');


        minutes = 60; 
        var new_end_time = moment(start_time, "hh:mm")
        .add(minutes, 'minutes')
        .format('HH:mm');

        if(new_start_time < end_time)
        {
            $('#end_time').val(new_end_time);
        }

        if(start_time > end_time)
        {
            $('#end_time').val(new_start_time);
        }

    }

</script>
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script>
$(document).ready(function() {

    $('.doctor').select2({
        placeholder: "Select Doctor",
    });

    $('.schedule').select2({
        placeholder: "Select Time Schedule",
    });

    $('.patient').select2({
        placeholder: "Select Patient Type",
    });
    $('[data-toggle="tooltip"]').tooltip();
});


</script>

@endpush
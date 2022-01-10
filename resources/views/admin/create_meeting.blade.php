@extends('layouts.backend.app')

@section('title','CREATE MEETING')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{asset('public/assets/backend/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--multiple {
        border: 0;
        min-height: 34px;
    }

    .select2-container--default.select2-container--focus .select2-selection--multiple {
        border: 0;
    }

    .select2-dropdown {
        border-radius: 0;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected],
    .select2-results__option[aria-selected] {
        min-height: 34px;
    }

    .select-multi-div .form-group .form-line:after {
        border: 0 !important;
    }

    .select2-container--open .select2-dropdown--above,
    .select2-container--open .select2-dropdown--below {
        border: 1px solid #aaa !important;
        box-shadow: 0 0px 10px rgba(0, 0, 0, .25) !important;
    }

    .select2-container--default .select2-results__option--highlighted[aria-selected]:hover,
    .select2-results__option[aria-selected]:hover {
        border: 1.75px solid #333;
    }

    .custom-alert {
        color: red;
        margin-left: -15px;
    }

    #closeTdoInput,
    #closeProjectInput,
    #closePlanInput,
    #closeWpInput {
        position: absolute;
        top: 7px;
        right: 0;
        cursor: pointer;
    }

    #tdoInputDiv input,
    #projectInputDiv input,
    #planTitleInputDiv input,
    #wpInputDiv input {
        padding-right: 25px;
    }

    #tdoInputDiv,
    #projectInputDiv,
    #planTitleInputDiv,
    #wpInputDiv,
    #work_package_alert {
        display: none;
    }

</style>
@endpush

@section('content')

<div class="container-fluid">
    <!-- Tabs With Icon Title -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Create Meeting
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <form method="POST" action="{{ route('meetings.store') }}" class="form-horizontal"
                                    enctype="multipart/form-data">
                                    @csrf
                                    {{-- <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="tdo">Select Project </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    
                                                    <select class="form-control" name="tdo_select" id="tdo" required>
                                                        <option selected disabled value="">Select one</option>
                                                        <option value="New">Casual/Other</option>
                                                        @foreach($tdos as $tdo)
                                                        <option value="{{ $tdo->id }}">{{ $tdo->title }}</option>
                                    @endforeach
                                    </select>

                                    <div id="tdoInputDiv">
                                        <input type="text" class="form-control"
                                            placeholder="Type Task and Delivery Order" name="tdo_input" value="">
                                        <span class="material-icons" id="closeTdoInput">
                                            highlight_off
                                        </span>
                                    </div>
                            </div>
                        </div>
                    </div>
                </div> --}}
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="project_plan">Select Project Name</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                {{-- select task title  --}}
                                <select class="form-control" name="subtask_id" id="subtask_id" onchange="getEmpList();"
                                    required>
                                    <option selected disabled value="">Select one</option>
                                    <option value="0">General Meeting</option>
                                    {{-- @foreach($tdos as $tdo)
                                    @foreach ($tdo->subTask as $task)
                                    <option value="{{ $task->id }}">
                                        {{ $task->sub_task_name }}</option>
                                    @endforeach
                                    @endforeach --}}
                                    {{-- @foreach ($subtaskAll_ as $task)
                                    <option value="{{ $task[0]->id }}">
                                        {{ $task[0]->sub_task_name }}</option>
                                    @endforeach --}}
                                    @if (auth()->user()->hasRole('power-user'))
                                    @foreach ($all_subtask as $task)
                                    <option value="{{ $task->id }}">
                                        {{ $task->sub_task_name }}</option>
                                    @endforeach
                                    @else
                                        @foreach ($subtaskAll as $task)
                                    <option value="{{ $task[0]->id }}">
                                        {{ $task[0]->sub_task_name }}</option>
                                    @endforeach
                                    @endif
                                     
                                </select>
                                {{-- <input type="hidden" name="" id="project_plan_assignee"
                                                             value="{{ $projectPlan->project_plan_assignee }}"> --}}
                                {{-- input sub task --}}
                                {{-- <div id="planTitleInputDiv">
                                                    <input type="text" class="form-control"
                                                        placeholder="Type Task Title" name="project_plan_input"
                                                        value="">
                                                    <span class="material-icons" id="closePlanInput">
                                                        highlight_off
                                                    </span>
                                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="agenda">Meeting Agenda</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                <textarea rows="5" id="agenda" placeholder="Write about meeting details..."
                                    name="agenda" class="form-control" required></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row clearfix">
                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                        <label for="assignee">Invite To</label>
                    </div>
                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                        <div class="form-group">
                            <div class="form-line">
                                {{-- select assignee --}}
                                <select class="js-example-basic-multiple" name="assignee[]" id="assignee"
                                    multiple="multiple" required>
                                    @php
                                    $tot_row = count($users);
                                    @endphp
                                    @if($tot_row != 1){
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} - ({{$user->email}})</option>
                                    @endforeach
                                    } @else {
                                    @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }} - ({{$user->email}})</option>
                                    @endforeach
                                    }
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- <div class="row clearfix" >
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="tdo_details">Paticipants</label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea rows="5" id="meeting_paticipants" placeholder=""
                                                        name="meeting_paticipants" class="form-control"
                                                        readonly></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                {{-- <div class="row clearfix">
                                         <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                             <label for="task_details">Add Paticipant</label>
                                         </div>
                                         <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                             <div class="form-group">
                                                 <div class="form-line">
                                                     <input type="text" id="task_details" class="form-control"
                                                         placeholder="Type task title" name="task_details" value="">
                                                 </div>
                                             </div>
                                         </div>
                                     </div> --}}
                <div style="display:block" class="dvinfo">
                    <div class="row clearfix">
                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="meeting_date">Meeting Date</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input onchange="" type="date" id="meeting_date" class="form-control"
                                        placeholder="" name="meeting_date" value="" required>
                                </div>
                            </div>
                        </div>

                         <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                            <label for="meeting_date">Meeting Time</label>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                            <div class="form-group">
                                <div class="form-line">
                                    <input onchange="" type="time" id="meeting_time" class="form-control"
                                        placeholder="" name="meeting_time" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row clearfix">
                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                        <a href="{{route('dashboard')}}" style="text-decoration: none;">
                            <button type="button" class="btn btn-danger m-t-15 waves-effect">CANCEL</button>
                        </a>
                        <button type="submit" class="btn btn-primary m-t-15 waves-effect" id="SaveBtn">CREATE</button>
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
{{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script type="text/javascript">
    $(document).ready(function () {
        $(".js-example-basic-multiple").select2();

        var baseUrl = window.location.href.split("/meetings/");
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            }
        });
    });

// $( "#checkId" ).click(function() {
//   alert( "Handler for .click() called."+$("#assignee").val());
// });
    //     $("#assignee").change(function () {
    //    alert($(this).val());
    // //    var prevSelect = $("#MultiSelect_Preview").select2();
    // //    prevSelect.val($(this).val()).trigger('change');
    // });

    function getEmpList() {
        $('#assignee').val(null);
        var task_id = $('#subtask_id').val();
        var baseUrl = window.location.href.split("/meetings/");
        $options = $('#assignee option');
        $.get(baseUrl[0] + '/assigned_emp/' + task_id, function (response) {
            for (i = 0; i < response.length; i++) {
                $options.filter('[value="' + response[i].id + '"]').prop('selected', true);
                $(".js-example-basic-multiple").select2();
            }
        });
    }
</script>
@endpush

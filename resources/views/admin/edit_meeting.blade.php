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
                        Update Meeting
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <form method="POST" action="{{ route('meetings.update',$meeting_info[0]->id) }}"
                                    class="form-horizontal" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="project_plan">Project Name</label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {{-- select task title  --}}
                                                    <select class="form-control" name="subtask_id" id="subtask_id"
                                                        required>
                                                        <option selected disabled value="">Select one</option>
                                                        {{-- <option value="New">New Task Title</option> --}}
                                                        @foreach($tdos as $tdo)
                                                        @foreach ($tdo->subTask as $task)
                                                        {{-- <option value="">{{$task->id}}</option> --}}
                                                        @if($meeting_info[0]->subtask_id == $task->id)
                                                        <option value="{{ $task->id }}" selected>
                                                            {{ $task->sub_task_name }}</option>
                                                        @endif
                                                        @endforeach
                                                        @endforeach
                                                    </select>
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
                                                    <textarea rows="5" id="agenda" name="agenda" class="form-control"
                                                        required>{{$meeting_info[0]->agenda }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- <div style="display:block" class="dvinfo">
                                        <div class="row clearfix">
                                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                <label for="meeting_date">Meeting Date-Time</label>
                                            </div>
                                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input onchange="" type="datetime-local" id="meeting_date1"
                                                            class="form-control" name="meeting_date"
                                                            value="{{ date('Y-m-d\TH:i', strtotime($meeting_info[0]->meeting_dateTime)) }}"
                                                            required>
                                                    </div>
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
                                                        <input onchange="" type="date" id="meeting_date"
                                                            class="form-control" placeholder="" name="meeting_date"
                                                            value="{{ date('Y-m-d', strtotime($meeting_info[0]->meeting_dateTime)) }}" required>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                <label for="meeting_date">Meeting Time</label>
                                            </div>
                                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                <div class="form-group">
                                                    <div class="form-line">
                                                        <input onchange="" type="time" id="meeting_time"
                                                            class="form-control" placeholder="" name="meeting_time"
                                                            value="{{ date('H:i:s', strtotime($meeting_info[0]->meeting_dateTime)) }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <a href="{{route('dashboard')}}" style="text-decoration: none;">
                                                <button type="button"
                                                    class="btn btn-danger m-t-15 waves-effect">CANCEL</button>
                                            </a>
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                id="SaveBtn">UPDATE</button>
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
        $(document).ready(function () {
            var baseUrl = window.location.href.split("/meetings/");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });


        });

    </script>
    @endpush

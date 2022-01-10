@extends('layouts.backend.app') @if(auth()->check() && auth()->user()->hasRole('super-admin'))
@section('title',"ADMIN DASHBOARD")
@elseif(auth()->check() && auth()->user()->hasRole('admin'))
@section('title',"MODERATOR DASHBOARD") @elseif(auth()->check() && auth()->user()->hasRole('power-user'))
@section('title',"PM DASHBOARD") @else
@section('title',"EMPLOYEE DASHBOARD") @endif
@push('css')
<link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
<link href="{{ asset('public/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('public/assets/backend/plugins/bootstrap-select/css/bootstrap-select.css')}}" rel="stylesheet" />

<style>
    table,
    th {
        font-size: small;
    }
    .td-long-text-a {
        white-space: nowrap;
        word-break: break-word;
        display: inline-block;
        width: 150px;
        text-overflow: ellipsis;
        overflow: hidden;
    }
   
    .modal .modal-header {
        border-bottom: 1px solid #e5e5e5 !important;
    }
    .modal .modal-header .modal-title {
        display: inline;
    }
    .modal-footer {
        border-top: 1px solid #e5e5e5 !important;
    }
    .modal-open .modal {
        padding-right: 0 !important;
    }
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
    span.select2,
    .select2-container--default .select2-search--inline .select2-search__field {
        width: 100% !important;
        border: 0 !important;
    }
    .select-multi-div .form-group .form-line:after {
        border: 0 !important;
    }
    .select2-container--default .select2-selection--single{
        border: none;
    }
    .modal-holder .body {
        padding: 0;
    }
    .modal-holder .card {
        min-height: 0;
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
    .modal-dialog {
        width: 80% !important;
    }
    #added_project_doc {
        margin-left: -15px;
        margin-right: -15px;
    }
    .proDocEditDiv {
        border: 1px solid gray;
        border-radius: 4px;
        display: inline-block;
        padding: 0px 15px;
        margin-right: 40px;
        position: relative;
        margin-top: 7px;
    }
    .proDocEditDiv span {
        position: absolute;
        right: -25px;
        top: -2px;
        cursor: pointer;
    }
    .d-none {
        display: none;
    }
    #pro-name,
    #plan-title,
    #task-title,
    #reporters {
        border-bottom: 2px solid teal;
        padding-bottom: 5px;
    }
</style>
@endpush
@section('content')
<div class="container-fluid">
    <div style="font-size: 20px; font-weight: bold;" class="text-center">Welcome @if(auth()->check() && auth()->user()->hasRole('super-admin')) {{ Auth::user()->name }} ! @endif @if(auth()->check()
        && auth()->user()->hasRole('admin')) {{ Auth::user()->name }} ! @endif @if(auth()->check() && auth()->user()->hasRole('power-user'))
        {{ Auth::user()->name }} ! @endif @if(auth()->check() && auth()->user()->hasRole('user')) {{ Auth::user()->name }}
        ! @endif
    </div>
    @if(auth()->check() && auth()->user()->hasRole('super-admin'))
    <div class="block-header">
        <h2 class="text-left">ADMIN DASHBOARD</h2>
    </div>
    @endif @if(auth()->check() && auth()->user()->hasRole('admin'))
    <div class="block-header">
        <h2 class="text-left">MODERATOR DASHBOARD</h2>
    </div>
    @endif @if(auth()->check() && auth()->user()->hasRole('power-user'))
    <div class="block-header">
        <h2 class="text-left">PM DASHBOARD</h2>
    </div>
    @endif @if(auth()->check() && auth()->user()->hasRole('user'))
    <div class="block-header">
        <h2 class="text-left">EMPLOYEE DASHBOARD</h2>
    </div>
    @endif
    <!-- Widgets -->
    @if (auth()->user()->hasRole('super-admin'))
    <div class="row clearfix">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;">
                <div class="info-box bg-teal hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">how_to_reg</i>
                    </div>
                    <div class="content">
                        <div class="text">Approved User</div>
                        <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['employees'] }}" data-speed="1000" data-fresh-interval="20">
                            {{ $dashboard_info['employees'] }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;">
                <div class="info-box bg-orange hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">pending_actions</i>
                    </div>
                    <div class="content">
                        <div class="text">Pending Request</div>
                        <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['approval_users'] }}" data-speed="1000" data-fresh-interval="20">
                            {{ $dashboard_info['approval_users'] }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif @if(auth()->user()->hasRole('admin'))
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="moderator-projects-card">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">architecture</i>
                    </div>
                    <div class="content">
                        <div class="text">Projects</div>
                        {{-- data-to="{{ $dashboard_info['projects'] }}" --}}
                        <div class="number count-to" data-from="0" data-speed="1000" data-to="{{ $dashboard_info['projects'] }}" data-fresh-interval="20">
                            {{ $dashboard_info['projects'] }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="moderator-wbs-card">
                <div class="info-box bg-teal hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">fact_check</i>
                    </div>
                    <div class="content">
                        <div class="text">WBS</div>
                        <div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">
                            {{$all_complete_wbs  }}/{{ $all_wbs }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="moderator-meetings-card">
                <div class="info-box bg-red hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">event_available</i>
                    </div>
                    <div class="content">
                        <div class="text">Meetings</div>
                        <div class="number count-to" data-from="0" data-speed="15" data-fresh-interval="20">
                            {{ count($dashboard_info['meeting_today']) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>

    @endif @if(auth()->user()->hasRole('power-user'))
    <div class="row clearfix">
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="pm-projects-card">
                <div class="info-box bg-light-green hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">architecture</i>
                    </div>
                    <div class="content">
                        <div class="text">Projects</div>
                        <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['projects'] }}" data-speed="1000" data-fresh-interval="20">{{ $dashboard_info['projects'] }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="employees-card">
                <div class="info-box bg-teal hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">fact_check</i>
                    </div>
                    <div class="content">
                        <div class="text">WBS</div>
                        <div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">{{ $total_complete_wbs_pm }}/{{ $total_wbs_pm }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <a href="#" style="text-decoration: none;" id="pm-meetings-card">
                <div class="info-box bg-red hover-expand-effect">
                    <div class="icon">
                        <i class="material-icons">event_available</i>
                    </div>
                    <div class="content">
                        <div class="text">Meeting Today</div>
                        <div class="number count-to" data-from="0" data-speed="15" data-fresh-interval="20">
                            {{ count($dashboard_info['meeting_today']) }}
                        </div>
                    </div>
                </div>
            </a>
        </div>
    </div>
    @endif @if (auth()->user()->hasRole('user'))
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="#" style="text-decoration: none;" id="emp-projects-card">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">architecture</i>
                </div>
                <div class="content">
                    <div class="text">Assigned Projects</div>
                    <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['projects'] }}" data-speed="1000" data-fresh-interval="20">{{ $dashboard_info['projects'] }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="#" style="text-decoration: none;" id="emp-wbs-card">
            <div class="info-box bg-teal hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">fact_check</i>
                </div>
                <div class="content">
                    <div class="text">WBS</div>
                    <div class="number count-to" data-from="0" data-to="" data-speed="1000" data-fresh-interval="20">{{ $total_complete_wbs }}/{{ $total_wbs }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <a href="#" style="text-decoration: none;" id="emp-meetings-card">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">event_available</i>
                </div>
                <div class="content">
                    <div class="text">Meeting Today</div>
                    <div class="number count-to" data-from="0" data-speed="15" data-fresh-interval="20">
                        {{ count($dashboard_info['meeting_today']) }}
                    </div>
                </div>
            </div>
        </a>
    </div>
    @endif
    <!-- Widgets End -->
    @if(auth()->check() && auth()->user()->hasRole('admin'))
    <!-- MODERATOR Tab -->
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    {{-- <a class="btn btn-primary waves-effect" href="{{ route('users.create') }}">
                        <i class="material-icons">add</i>
                        <span>Add New Employee</span>
                    </a> --}}
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs tab-nav-right" role="tablist" id="moderatorTab">
                        <li role="presentation" class="active"><a href="#all-projects" id="moderator-projects-tab" data-toggle="tab">PROJECTS</a>
                        </li>
                        <li role="presentation"><a href="#all-employee" id="moderator-employees-tab" data-toggle="tab">EMPLOYEES</a></li>
                        <li role="presentation"><a href="#all-wbs" id="moderator-wbs-tab" data-toggle="tab">WBS</a></li>
                        <li role="presentation"><a href="#all-meetings" id="moderator-meeting-tab" data-toggle="tab">MEETINGS</a></li>
                    </ul>
                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="all-projects">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        {{--
                                        <div class="header">
                                            <h2>
                                                PM
                                                <span class="badge bg-blue"></span>
                                            </h2>
                                        </div> --}}
                                        <div class="row clearfix">
                                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>TDO</th>
                                                                <th>Project Name</th>
                                                                <th>Work Package</th>
                                                                <th>Task Title</th>
                                                                <th>Task Details</th>
                                                                <th>Assigned By</th>
                                                                <th>Assignee/s</th>
                                                                <th>Planned Delivery Date</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $index = 0;
                                                        @endphp
                                                        @foreach($tdos as $key=>$data) 
                                                        @foreach($data->subTask as $key1=>$data1) 
                                                        @foreach($data1->workPackage as $key2=>$data2) 
                                                        @foreach($data2->projectPlan as $key3=>$data3) 
                                                        @foreach($data3->projectPlanDetails as $key4=>$data4)
                                                        <tr>
                                                            <td>{{ $index = $index + 1 }}</td>
                                                            {{-- $key + 1 --}}
                                                            <td>{{$data->title}}</td>
                                                            <td>{{$data1->sub_task_name}}</td>
                                                            <td>{{$data2->work_package_number}}
                                                            </td>
                                                            <td>{{$data3->plan_title}}</td>
                                                            <td><a class="td-long-text-a" type="button" data-toggle="modal" data-target="#editProject"
                                                                    data-tdos="{{$data}}" data-subtask="{{$data1}}" data-workpackage="{{$data2}}"
                                                                    data-projectplan="{{$data3}}" data-projectplandetails="{{$data4}}"
                                                                    data-user="{{$data3->user}}" data-files="{{$data1->files}}"
                                                                    style="cursor:pointer;text-decoration:none;">{{$data4->project_task_details}}</a>
                                                            </td>
                                                            <td>{{$data->users->name}}</td>
                                                            <td>
                                                                @php
                                                                $inv_id = explode(',', $data3->project_plan_assignee);
                                                                @endphp
                                                               
                                                                @foreach($inv_id as $val)
                                                                    @foreach($users as $emp)
                                                                        @if($val == $emp->id)
                                                                            {{$emp->name}}<br>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </td>
                                                            <td>{{$data3->planned_delivery_date}}</td>
                                                            {{--
                                                            <td>{{$data->projectPlan->user->name}}</td> --}} {{--
                                                            <td>2020-06-23</td>
                                                            <td>20% completed</td>
                                                            <td>N/A</td>
                                                            <td>
                                                                <a href="#">virtual_office_requirements.ppt</a>
                                                                <br>
                                                                <a href="#">virtual office DFD.docx</a>
                                                            </td> --}}
                                                        </tr>
                                                        @endforeach @endforeach @endforeach @endforeach @endforeach
                                                        </tbody>
                                                    </table>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="all-employee">
                            <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            {{-- <th>Gender</th> --}}
                                                            {{-- <th>Designation</th> --}}
                                                            <th>Email</th>
                                                            <th>Phone</th>
                                                            <th>Time Card</th>
                                                            {{-- <th>Status</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($users as $key=>$user)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            {{-- <td>{{ $user->gender }}</td> --}}
                                                            {{-- @if($user->designation != Null)
                                                            <td>{{ $user->designation->name }}</td>
                                                            @else
                                                            <td>Employee</td>
                                                            @endif --}}
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->phone }}</td>
                                                            {{-- @if ($user->is_active)
                                                            <td><span class="badge bg-green">Active</span></td>
                                                            @else
                                                            <td><span class="badge bg-orange">Pending</span></td>
                                                            @endif --}}
                                                            <td><a class="btn btn-info waves-effect" type="button" data-toggle="modal" data-target="#showTimeCard" data-user="{{$user->id}}">
                                                                    <i class="material-icons">grading</i>
                                                                </a></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade " id="all-wbs">
                            <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('wbs.create') }}">
                                <i class="material-icons">add</i>
                                <span>CREATE WBS</span>
                            </a>
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="table-responsive">
                                          <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Project Name</th>
                                                            <th>Task title</th>
                                                            {{--
                                                            <th>Plan Title</th> --}}
                                                            <th>Actual Work <small>(weekending)</small></th>
                                                            <th>Assigned By</th>
                                                            <th>Assignee/s</th>
                                                            <th>Status(%)</th>
                                                            {{--
                                                            <th>Planed Hours</th> --}} {{--
                                                            <th>Actual Hours <small>(worked)</small></th> --}} {{--
                                                            <th>Actual Hours <small>(today)</small></th> --}}
                                                            <th>Start Date</th>
                                                            <th>Stop Date</th>
                                                            <th>Planed Delivery Date</th>
                                                            {{--
                                                            <th>Product Deliverable</th> --}}
                                                            <th>Actual Hours</th>
                                                            <th>Today's Hours</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $index = 0;
                                                    @endphp

                                                    @foreach ($WbsMasters as $item)
                                                        @php
                                                            if (!$item->wbs->status && !$item->wbs->task_start_date) {
                                                               $rowColor = "red";
                                                            } else {
                                                               $rowColor = "#555";
                                                            }
                                                        @endphp
                                                        <tr style="color:{{$rowColor}}">
                                                            <td>{{ $index = $index+1 }}</td>
                                                            <span style="display: none">{{$item->wbs->id}}</span>
                                                            <td>{{$item->projectPlanDetails->projectPlan->workPackage->subTask->sub_task_name}}
                                                            </td>
                                                            <td>{{$item->projectPlanDetails->projectPlan->plan_title}}</td>
                                                            <span class="d-none">{{$item->projectPlanDetails->project_task_details}}
                                                    </span>
                                                            <td>
                                                                <a class="td-long-text-a" type="button" data-toggle="modal" data-target="#editWbs" data-wbs="{{$item}}" data-user="{{$loggedInUser}}" data-reporter="{{$item->reporters}}"
                                                                    data-tdo="{{$item->projectPlanDetails->projectPlan->workPackage->subTask->tdo}}"
                                                                    data-assignee="{{$item->projectPlanDetails->projectPlan->project_plan_assignee}}"
                                                                    data-workpackage="{{$item->projectPlanDetails->projectPlan->workPackage}}"
                                                                    style="cursor:pointer;text-decoration:none;">
                                                                    <p style="margin:0;">{{$item->wbs->wbs_task_details}}
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td>{{$item->reporters->name}}
                                                            </td>
                                                            <td>{{$item->users->name}}
                                                            </td>
                                                            <td>@if ($item->wbs->status !=null)
                                                                {{$item->wbs->status}} %
                                                            @endif
                                                            </td>
                                                            
                                                            <td>{{$item->wbs->task_start_date}}</td>
                                                            <td>{{$item->wbs->task_end_date}}</td>
                                                            <td>{{$item->projectPlanDetails->projectPlan->planned_delivery_date}}</td>
                                                            <td>{{$item->wbs->actual_hours_worked}}</td>
                                                            @php
                                                              $today = date("Y-m-d");
                                                              $update_day = date('Y-m-d', strtotime($item->wbs->updated_at));
                                                            @endphp
                                                            <td>
                                                            @if ($update_day == $today)
                                                                {{$item->wbs->actual_hours_today}}</td>
                                                            @else  
                                                                0
                                                            @endif
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                       
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade " id="all-meetings">
                            <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('meetings.create') }}">
                                        <i class="material-icons">add</i>
                                        <span>SET MEETING</span>
                                    </a>
                                    <a style="margin-bottom:15px; background:#030bf4 !important;" class="btn btn-primary waves-effect" href="{{ route('meeting_history') }}">
                                        <i class="material-icons">history</i>
                                        <span>MEETING HISTORY</span>
                                    </a>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Project Name</th>
                                                            {{--
                                                            <th>PM</th> --}}
                                                            <th>Agenda</th>
                                                            {{--
                                                            <th>Shared Docs</th> --}}
                                                            <th>Meeting Date/Time</th>
                                                            {{--
                                                            <th>Project Status</th> --}}
                                                            <th>Virtual Session</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($meeting_info as $key=>$data)
                                                        <tr>
                                                          
                                                            <td>{{ $key + 1 }}</td>
                                                            <td> @if ($data[0]->subtask_id == null) General Meeting 
                                                                @else {{$data[0]->subTask->sub_task_name}}
                                                                @endif
                                                            </td>
                                                            
                                                            <td>{{$data[0]->agenda}}</td>
                                                            <td>{{ date('Y-m-d', strtotime($data[0]->meeting_dateTime)) }}
                                                                <br>
                                                                <strong>{{ date('h:i A', strtotime($data[0]->meeting_dateTime)) }}</strong>
                                                            </td>
                                                            <td>
                                                                @if($data[0]->isServiced == 0)
                                                                <b>
                                                                    <i class="text-success" id="<?php echo "txtStartSession" . $data[0]->id ?>"></i></b>
                                                                    <a id="<?php echo "btnStartSession" . $data[0]->id ?>" href="{{ route('chat',  [$data[0]->id, $data[0]->room_id, $data[0]->pm_info->name]) }}"
                                                                    class="btn btn-info waves-effect">Start Meeting
                                                                    </a>
                                                            </td>
                                                            @else
                                                            <strong class="text">N/A<strong>
                                                            @endif
                                                            
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                        </div>
                        {{-- unused tab --}}
                        <div role="tabpanel" class="tab-pane fade " id="wbs">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="card">
                                        {{-- <div class="header">
                                            <h2>
                                                WBS
                                                <span class="badge bg-blue"></span>
                                                                    </h2>
                                                </div> --}}
                                                <div class="body">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                            <thead>
                                                                <tr>
                                                                    <th>ID</th>
                                                                    <th>Employee</th>
                                                                    <th>Gender</th>
                                                                    <th>Designation</th>
                                                                    <th>Meeting Date</th>
                                                                    <th>Next Meeting Date</th>
                                                                    <th>WBS</th>
                                                                    <th>Assigned PM</th>
                                                                    <th>SpentHour</th>
                                                                    {{--
                                                                    <th>PatientType</th> --}}
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                {{-- @foreach($appoinments as $key=>$data)
                                                                <tr>
                                                                    <td>{{ $key + 1 }}</td>
                                                                    <td>{{ $data->users->name }}</td>
                                                                    <td>{{ $data->users->gender }}</td>
                                                                    <td>{{ $data->patient_symptoms }}</td>
                                                                    <td><span style="font-weight:bold">{{ $data->visit_date }}</span>
                                                                        <br><span style="color:blue">{{ date('h:i:s A', strtotime($data->slots->start_time)) }}
                                                                -
                                                                {{ date('h:i:s A', strtotime($data->slots->end_time)) }}</span>
                                                                    </td>
                                                                    <td>{{ $data->prescribe_medicines }}</td>
                                                                    <td>{{ $data->follow_up_visit_date }}</td>
                                                                    <td><span class="badge">{{ $data->doctors->name }}</span>
                                                                    </td>
                                                                    <td>{{ $data->spent_hour }}</td>
                                                                    <td>
                                                                        {{ $data->patient_type }} Patient
                                                                    </td>
                                                                </tr>
                                                                @endforeach --}}
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# MODERATOR Tab -->
            @endif @if(auth()->check() && auth()->user()->hasRole('power-user'))
            <!-- PM role tab -->
            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="body">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs tab-nav-right" role="tablist" id="pmTab">
                                <li role="presentation" class="active"><a href="#pm-projects" id="pm-projects-tab" data-toggle="tab">PROJECTS</a>
                                </li>
                                {{-- onClick="window.location.reload();" --}}
                                <li role="presentation"><a href="#employees" id="employees-tab" data-toggle="tab">EMPLOYEES</a>
                                </li>
                                <li role="presentation"><a id="pm_wbs_tab" href="#pm-wbs" data-toggle="tab">WBS</a></li>
                                <li role="presentation"><a href="#pm-meetings" data-toggle="tab" id="pm-meetings-tab">MEETINGS</a>
                                </li>
                                <li role="presentation"><a href="#evms" data-toggle="tab">EVMS</a></li>
                            </ul>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane fade in active" id="pm-projects">
                                    <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('projects.create') }}">
                                        <i class="material-icons">add</i>
                                        <span>CREATE PROJECTS</span>
                                    </a>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable table-pm-project">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>TDO</th>
                                                            <th>Project Name</th>
                                                            <th>Work Package</th>
                                                            <th>Task Title</th>
                                                            <th>Task Details</th>
                                                            <th>Assigned By</th>
                                                            <th>Assignee/s</th>
                                                            <th>Planned Delivery Date</th>
                                                            {{--
                                                            <th>Due Date</th>
                                                            <th>Project Status</th>
                                                            <th>Comments</th> --}} {{--
                                                            <th>Project Doccuments</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $index = 0;
                                                    @endphp
                                                        @foreach($tdos as $key=>$data) 
                                                        @foreach($data->subTask as $key1=>$data1) 
                                                        @foreach($data1->workPackage as $key2=>$data2) 
                                                        @foreach($data2->projectPlan as $key3=>$data3) 
                                                        @foreach($data3->projectPlanDetails as $key4=>$data4)
                                                        <tr>
                                                            <td>{{ $index = $index + 1 }}</td>
                                                            {{-- $key + 1 --}}
                                                            <td>{{$data->title}}</td>
                                                            <td>{{$data1->sub_task_name}}</td>
                                                            <td>{{$data2->work_package_number}}
                                                            </td>
                                                            <td>{{$data3->plan_title}}</td>
                                                            <td><a class="td-long-text-a" type="button" data-toggle="modal" data-target="#editProject"
                                                                    data-tdos="{{$data}}" data-subtask="{{$data1}}" data-workpackage="{{$data2}}"
                                                                    data-projectplan="{{$data3}}" data-projectplandetails="{{$data4}}"
                                                                    data-user="{{$data3->user}}" data-files="{{$data1->files}}"
                                                                    style="cursor:pointer;text-decoration:none;">{{$data4->project_task_details}}</a>
                                                            </td>
                                                            <td>{{$data->users->name}}</td>
                                                            <td>
                                                                @php
                                                                $inv_id = explode(',', $data3->project_plan_assignee);
                                                                @endphp
                                                               
                                                                @foreach($inv_id as $val)
                                                                    @foreach($users as $emp)
                                                                        @if($val == $emp->id)
                                                                            {{$emp->name}}<br>
                                                                        @endif
                                                                    @endforeach
                                                                @endforeach
                                                            </td>
                                                            <td>{{$data3->planned_delivery_date}}</td>
                                                            {{--
                                                            <td>{{$data->projectPlan->user->name}}</td> --}} {{--
                                                            <td>2020-06-23</td>
                                                            <td>20% completed</td>
                                                            <td>N/A</td>
                                                            <td>
                                                                <a href="#">virtual_office_requirements.ppt</a>
                                                                <br>
                                                                <a href="#">virtual office DFD.docx</a>
                                                            </td> --}}
                                                        </tr>
                                                        @endforeach @endforeach @endforeach @endforeach @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="employees">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Name</th>
                                                            {{-- <th>Gender</th> --}}
                                                            {{-- <th>Designation</th> --}}
                                                            <th>Email</th>
                                                            <th>Phone</th>
                                                            <th>Time Card</th>
                                                            {{-- <th>Status</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($users as $key=>$user)
                                                        <tr>
                                                            <td>{{ $key + 1 }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            {{-- <td>{{ $user->gender }}</td> --}}
                                                            {{-- @if($user->designation != Null)
                                                            <td>{{ $user->designation->name }}</td>
                                                            @else
                                                            <td>Employee</td>
                                                            @endif --}}
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->phone }}</td>
                                                            {{-- @if ($user->is_active)
                                                            <td><span class="badge bg-green">Active</span></td>
                                                            @else
                                                            <td><span class="badge bg-orange">Pending</span></td>
                                                            @endif --}}
                                                            <td><a class="btn btn-info waves-effect" type="button" data-toggle="modal" data-target="#showTimeCard" data-user="{{$user->id}}">
                                                                    <i class="material-icons">grading</i>
                                                                </a></td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="pm-wbs">
                                    <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('wbs.create') }}">
                                        <i class="material-icons">add</i>
                                        <span>CREATE WBS</span>
                                    </a>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Project Name</th>
                                                            <th>Task title</th>
                                                            {{--
                                                            <th>Plan Title</th> --}}
                                                            <th>Actual Work <small>(weekending)</small></th>
                                                            <th>Assigned By</th>
                                                            <th>Assignee/s</th>
                                                            <th>Status(%)</th>
                                                            {{--
                                                            <th>Planed Hours</th> --}} {{--
                                                            <th>Actual Hours <small>(worked)</small></th> --}} {{--
                                                            <th>Actual Hours <small>(today)</small></th> --}}
                                                            <th>Start Date</th>
                                                            <th>Stop Date</th>
                                                            <th>Planed Delivery Date</th>
                                                            {{--
                                                            <th>Product Deliverable</th> --}}
                                                            <th>Actual Hours</th>
                                                            <th>Today's Hours</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $index = 0;
                                                    @endphp

                                                    @foreach ($WbsMasters as $item)
                                                        @php
                                                            if (!$item->wbs->status && !$item->wbs->task_start_date) {
                                                               $rowColor = "red";
                                                            } else {
                                                               $rowColor = "#555";
                                                            }
                                                        @endphp
                                                        <tr style="color:{{$rowColor}}">
                                                            <td>{{ $index = $index+1 }}</td>
                                                            <span style="display: none">{{$item->wbs->id}}</span>
                                                            <td>{{$item->projectPlanDetails->projectPlan->workPackage->subTask->sub_task_name}}
                                                            </td>
                                                            <td>{{$item->projectPlanDetails->projectPlan->plan_title}}</td>
                                                            <span class="d-none">{{$item->projectPlanDetails->project_task_details}}
                                                    </span>
                                                            <td>
                                                                <a class="td-long-text-a" type="button" data-toggle="modal" data-target="#editWbs" data-wbs="{{$item}}" data-user="{{$loggedInUser}}" data-reporter="{{$item->reporters}}"
                                                                    data-tdo="{{$item->projectPlanDetails->projectPlan->workPackage->subTask->tdo}}"
                                                                    data-assignee="{{$item->projectPlanDetails->projectPlan->project_plan_assignee}}"
                                                                    data-workpackage="{{$item->projectPlanDetails->projectPlan->workPackage}}"
                                                                    style="cursor:pointer;text-decoration:none;">
                                                                    <p style="margin:0;">{{$item->wbs->wbs_task_details}}
                                                                    </p>
                                                                </a>
                                                            </td>
                                                            <td>{{$item->reporters->name}}
                                                            </td>
                                                            <td>{{$item->users->name}}
                                                            {{-- {{$item->wbs_master_assignee_id}} --}}
                                                            </td>
                                                            <td>@if ($item->wbs->status !=null)
                                                                {{$item->wbs->status}} %
                                                            @endif
                                                            </td>
                                                            
                                                            <td>{{$item->wbs->task_start_date}}</td>
                                                            <td>{{$item->wbs->task_end_date}}</td>
                                                            <td>{{$item->projectPlanDetails->projectPlan->planned_delivery_date}}</td>
                                                            <td>{{$item->wbs->actual_hours_worked}}</td>
                                                            @php
                                                              $today = date("Y-m-d");
                                                              $update_day = date('Y-m-d', strtotime($item->wbs->updated_at));
                                                            @endphp
                                                            <td>
                                                            @if ($update_day == $today)
                                                                {{$item->wbs->actual_hours_today}}</td>
                                                            @else  
                                                                0
                                                            @endif
                                                        </tr>
                                                    @endforeach

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="pm-meetings">
                                    <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('meetings.create') }}">
                                        <i class="material-icons">add</i>
                                        <span>SET MEETING</span>
                                    </a>
                                    <a style="margin-bottom:15px; background:#030bf4 !important;" class="btn btn-primary waves-effect" href="{{ route('meeting_history') }}">
                                        <i class="material-icons">history</i>
                                        <span>MEETING HISTORY</span>
                                    </a>
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>Project Name</th>
                                                            {{--
                                                            <th>PM</th> --}}
                                                            <th>Agenda</th>
                                                            {{--
                                                            <th>Shared Docs</th> --}}
                                                            <th>Meeting Date/Time</th>
                                                            {{--
                                                            <th>Project Status</th> --}}
                                                            <th>Virtual Session</th>
                                                            {{-- <th>Action</th> --}}
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($meeting_info as $key=>$data)
                                                        <tr>
                                                            {{--
                                                            <td>{{$data->id}}</td> --}}
                                                            <td>{{ $key + 1 }}</td>
                                                            <td> @if ($data[0]->subtask_id == null) General Meeting 
                                                                @else {{$data[0]->subTask->sub_task_name}}
                                                                @endif
                                                            </td>
                                                            {{--
                                                            <td>{{$data->pm_info->name}}</td> --}}
                                                            <td>{{$data[0]->agenda}}</td>
                                                            <td>{{ date('Y-m-d', strtotime($data[0]->meeting_dateTime)) }}
                                                                <br>
                                                                <strong>{{ date('h:i A', strtotime($data[0]->meeting_dateTime)) }}</strong>
                                                            </td>
                                                            <td>
                                                                @if($data[0]->isServiced == 0)
                                                                <b>
                                                                    <i class="text-success" id="<?php echo "txtStartSession" . $data[0]->id ?>"></i></b>
                                                                    <a id="<?php echo "btnStartSession" . $data[0]->id ?>" href="{{ route('chat',  [$data[0]->id, $data[0]->room_id, $data[0]->pm_info->name]) }}"
                                                                    class="btn btn-info waves-effect">Start Meeting
                                                                    </a>
                                                            </td>
                                                            @else
                                                            <strong class="text">N/A<strong>
                                                            @endif
                                                            {{-- <td>
                                                                <a href="{{ route('meetings.edit',$data[0]->id)}}"
                                                                    class="btn btn-info waves-effect">
                                                                    <i class="material-icons">edit</i>
                                                                </a>
                                                                <button class="btn btn-danger waves-effect"
                                                                    onclick="deleteMeeting('{{ $data[0]->id }}')"
                                                                type="button"
                                                                id="deleteMeeting_+{{ $data[0]->id }}">
                                                                <i class="material-icons">delete</i>
                                                                </button>
                                                                <form id="delete-meeting-{{ $data[0]->id }}"
                                                                    action="{{ route('meetings.destroy', $data[0]->id) }}"
                                                                    method="POST" style="display:none">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </td> --}}
                                                            {{-- {{ route('users.edit',$user->id)}} --}}
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div role="tabpanel" class="tab-pane fade" id="evms">
                                    <div class="row clearfix">
                                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                            Under Development...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
           
        </div>
        @endif @if(auth()->check() && auth()->user()->hasRole('user'))
        <!-- Employee role tab -->
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">
                        <!-- Nav tabs -->
                        <ul class="nav nav-tabs tab-nav-right" role="tablist" id="employeeTab">
                            <li role="presentation"><a id="emp-projects-tab" href="#emp-projects" data-toggle="tab">PROJECTS</a>
                            </li>
                            <li role="presentation" class="active"><a id="emp-ebs-tab" href="#emp-wbs" data-toggle="tab">WBS</a></li>
                            <li role="presentation"><a id="emp-meetings-tab" href="#emp-meetings" data-toggle="tab">MEETINGS</a>
                                <li role="presentation"><a href="#emp-documents" data-toggle="tab">UPLOADED DOCUMENTS</a>
                                </li>
                        </ul>
                        <!-- Tab panes -->
                        <div class="tab-content">
                            <div role="tabpanel" class="tab-pane fade" id="emp-projects">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Project Name</th>
                                                        <th>Work Package</th>
                                                        <th>Task Title</th>
                                                        <th>Task Details</th>
                                                        <th>Assigned By</th> 
                                                        <th>Assignee/s</th>
                                                        <th>Planned Delivery Date</th>
                                                        {{--
                                                        <th>Due Date</th>
                                                        <th>Project Status</th>
                                                        <th>Comments</th> --}} {{--
                                                        <th>Project Doccuments</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php
                                                        $index = 0;
                                                    @endphp
                                                    @foreach($subTasks as $key1=>$data1) 
                                                    @foreach($data1->workPackage as $key2=>$data2) 
                                                    @foreach($data2->projectPlan as $key3=>$data3)
                                                    @foreach($data3->projectPlanDetails as $key4=>$data4)
                                                    <tr>
                                                        <td>{{$index = $index+1}}</td>
                                                        {{-- $key1+1 {{$data1->id}} --}}
                                                        <td>{{$data1->sub_task_name}}</td>
                                                        <td>{{$data2->work_package_number}}
                                                        </td>
                                                        <td>{{$data3->plan_title}}</td>
                                                        <td><a class="td-long-text-a" type="button" data-toggle="modal" data-target="#employeeProjectView"
                                                                data-subtask="{{$data1}}" data-workpackage="{{$data2}}" data-projectplan="{{$data3}}"
                                                                data-projectplandetails="{{$data4}}" data-user="{{$data3->user}}"
                                                                data-files="{{$data1->files}}" style="cursor:pointer;text-decoration:none;">{{$data4->project_task_details}}</a>
                                                        </td>
                                                        <td>{{$data1->tdo->users->name}}</td>
                                                        <td>
                                                            @php $inv_id = explode(',', $data3->project_plan_assignee); @endphp @foreach($inv_id as $val) @foreach($users as $emp) @if($val
                                                            == $emp->id) {{$emp->name}}
                                                            <br> @endif @endforeach @endforeach
                                                        </td>
                                                        <td>{{$data3->planned_delivery_date}}</td>
                                                        {{--
                                                        <td>{{$data->projectPlan->user->name}}</td> --}} {{--
                                                        <td>2020-06-23</td>
                                                        <td>20% completed</td>
                                                        <td>N/A</td>
                                                        <td>
                                                            <a href="#">virtual_office_requirements.ppt</a>
                                                            <br>
                                                            <a href="#">virtual office DFD.docx</a>
                                                        </td> --}}
                                                    </tr>
                                                    @endforeach 
                                                    @endforeach 
                                                    @endforeach 
                                                    @endforeach
                                                   
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade in active" id="emp-wbs">
                                <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('wbs.create') }}">
                            <i class="material-icons">add</i>
                            <span>CREATE WBS</span>
                        </a>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Project Name</th>
                                                        <th>Task Title</th>
                                                        {{--
                                                        <th>Project Plan Title</th>
                                                        <th>Project Plan Task Details</th> --}}
                                                        <th>Actual Work <small>(weekending)</small></th>
                                                        <th>Assigned By</th>
                                                        <th>Assignee/s</th>
                                                        <th>Status(%)</th>
                                                        {{--
                                                        <th>Planed Hours</th> --}} {{--
                                                        <th>Actual Hours <small>(worked)</small></th> --}} {{--
                                                        <th>Actual Hours <small>(today)</small></th> --}}
                                                        <th>Start Date</th>
                                                        <th>Stop Date</th>
                                                       <th>Planed Delivery Date</th>
                                                        {{--<th>Product Deliverable</th> --}}
                                                        <th>Actual Hours</th>
                                                        <th>Today's Hours</th>  
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($WbsMasters as $key=>$WbsMaster)
                                                    @php
                                                        if (!$WbsMaster->wbs->status && !$WbsMaster->wbs->task_start_date) {
                                                           $rowColor = "red";
                                                        } else {
                                                           $rowColor = "#555";
                                                        }
                                                    @endphp
                                                    <tr style="color:{{$rowColor}}">
                                                        <td>{{$key + 1}}</td>
                                                        <span style="display: none">{{$WbsMaster->wbs->id}}</span>
                                                        <td>{{$WbsMaster->projectPlanDetails->projectPlan->workPackage->subTask->sub_task_name}}
                                                        </td>
                                                        <td>{{$WbsMaster->projectPlanDetails->projectPlan->plan_title}}</td>
                                                        <span class="d-none">{{$WbsMaster->projectPlanDetails->projectPlan->plan_title}}
                                                </span>
                                                        <span class="d-none">{{$WbsMaster->projectPlanDetails->project_task_details}}
                                                </span>
                                                        <td>
                                                            <a class="td-long-text-a" type="button" data-toggle="modal" data-target="#editWbs" data-wbs="{{$WbsMaster}}" data-user="{{$loggedInUser}}" data-reporter="{{$WbsMaster->reporters}}"
                                                                data-tdo="{{$WbsMaster->projectPlanDetails->projectPlan->workPackage->subTask->tdo}}" data-assignee="{{$WbsMaster->projectPlanDetails->projectPlan->project_plan_assignee}}"
                                                                data-workpackage="{{$WbsMaster->projectPlanDetails->projectPlan->workPackage}}"
                                                                style="cursor:pointer;text-decoration:none;">
                                                                <p style="margin:0;">
                                                                    {{$WbsMaster->wbs->wbs_task_details}}
                                                                </p>
                                                            </a>
                                                        </td>
                                                        <td>{{$WbsMaster->reporters->name}}</td>
                                                        <td>{{$WbsMaster->users->name}}
                                                        </td>
                                                        <td>@if ($WbsMaster->wbs->status != null)
                                                            {{$WbsMaster->wbs->status}} %
                                                        @endif
                                                        
                                                        </td>
                                                        {{--
                                                        <td>{{$wbs->projects->planed_hours}}</td> --}} {{--
                                                        <td>{{$wbs->actual_hours_worked}}</td> --}} {{--
                                                        <td>{{$wbs->Actual_hours_today}}</td> --}}
                                                        <td>{{$WbsMaster->wbs->task_start_date}}</td>
                                                        <td>{{$WbsMaster->wbs->task_end_date}}</td>
                                                        <td>{{$WbsMaster->projectPlanDetails->projectPlan->planned_delivery_date}}</td>
                                                        <td>{{$WbsMaster->wbs->actual_hours_worked}}</td>
                                                    
                                                        @php
                                                        $today = date("Y-m-d");
                                                        $update_day = date('Y-m-d', strtotime($WbsMaster->wbs->updated_at));
                                                        @endphp
                                                        <td>
                                                            @if ($update_day == $today)
                                                            {{$WbsMaster->wbs->actual_hours_today}}</td>
                                                        @else
                                                        0
                                                        @endif
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div role="tabpanel" class="tab-pane fade" id="emp-meetings">
                                <a style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect" href="{{ route('meetings.create') }}">
                            <i class="material-icons">add</i>
                            <span>SET MEETING</span>
                        </a>
                                <a style="margin-bottom:15px; background:#030bf4 !important;" class="btn btn-primary waves-effect" href="{{ route('meeting_history') }}">
                            <i class="material-icons">history</i>
                            <span>MEETING HISTORY</span>
                        </a>
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Project Name</th>
                                                        <th>Host Name</th>
                                                        <th>Agenda</th>
                                                        <th>Meeting Date/Time</th>
                                                        {{--
                                                        <th>Project Status</th> --}}
                                                        <th>Virtual Session</th>
                                                        {{-- <th>Action</th> --}}
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @if(count($meeting_info)>0) 
                                                    @foreach($meeting_info as $key=>$data) {{-- @foreach ($item as $key=>$data) --}}
                                                    <tr>
                                                        <td>{{ $key + 1 }}</td>
                                                        <td> @if ($data[0]->subtask_id == null) General Meeting @else {{$data[0]->subTask->sub_task_name}}
                                                            @endif
                                                        </td>
                                                        <td>{{$data[0]->pm_info->name}}</td>
                                                        <td>{{$data[0]->agenda}}</td>
                                                        <td>{{ date('Y-m-d', strtotime($data[0]->meeting_dateTime)) }}
                                                            <br>
                                                            <strong>{{ date('h:i A', strtotime($data[0]->meeting_dateTime)) }}</strong>
                                                            </td>
                                                            <td>
                                                                @if($data[0]->isServiced == 0)
                                                                <b><i class="text-success"
                                                            id="<?php echo "txtJoinSession" . $data[0]->id ?>"></i></b>
                                                                <a id="<?php echo "btnJoinSession" . $data[0]->id ?>" href="{{ route('chat',  [$data[0]->id, $data[0]->room_id, $data[0]->pm_info->name]) }}"
                                                                    class="btn btn-info waves-effect">
                                                        Start Meeting
                                                    </a>
                                                            </td>
                                                            @else
                                                            <strong class="text">N/A<strong>
                                                        @endif
                                                        </td>
                                                        {{-- <td>
                                                                <a href="{{ route('meetings.edit',$data[0]->id)}}"
                                                                    class="btn btn-info waves-effect">
                                                                    <i class="material-icons">edit</i>
                                                                </a>
                                                                <button class="btn btn-danger waves-effect"
                                                                    onclick="deleteMeeting('{{ $data[0]->id }}')"
                                                                type="button"
                                                                id="deleteMeeting_+{{ $data[0]->id }}">
                                                                <i class="material-icons">delete</i>
                                                                </button>
                                                                <form id="delete-meeting-{{ $data[0]->id }}"
                                                                    action="{{ route('meetings.destroy', $data[0]->id) }}"
                                                                    method="POST" style="display:none">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                </form>
                                                            </td> --}}
                                                            {{-- {{ route('users.edit',$user->id)}} --}}
                                            </tr>
                                            {{-- @endforeach --}}
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div role="tabpanel" class="tab-pane fade" id="emp-documents">
                        <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <a href="{{ route('viewfile') }}" class="btn btn-primary">UPLOAD</a>
                                    <br><br>
                                    <table
                                        class="table table-bordered table-striped table-hover dataTable js-exportable">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>File</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($my_file as $key=>$data)
                                            <tr>
                                                <td><span>ID: </span>{{ $key + 1 }}</td>
                                                <td><span>File: </span>
                                                    {{ $data->title }}
                                                </td>
                                                <td><span>Action: </span>
                                                    <a href="{{ route('downloadfile', $data->id) }}"
                                                        class="btn btn-primary"><i
                                                            class="material-icons">file_download</i></a>
                                                    <!-- <a
                                                        href="{{ route('viewfile',$data->id)}}"
                                                        class="btn btn-info waves-effect" target="_blank">
                                                        <i class="material-icons">file_upload</i>
                                                    </a> -->
                                                    <!-- <input type="file" name="image"> -->
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal-holder">
    <div class="card">
        <div class="body">
            <div class="modal" id="employeeProjectView" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Project Plan Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            {{-- project plan detail view --}}
                            <div class="row clearfix">
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12"
                                    style="border-right: 1px solid gray;">
                                    <h5>Sub Task</h5>
                                    <p id="empsubtaskView"></p>
                                    <h5>Work Package</h5>
                                    <p id="empwpView"></p>
                                    <h5>Task Title</h5>
                                    <p id="emptaskTitleView"></p>
                                    <h5>Task Details</h5>
                                    <p id="emptaskdetailsView"></p>
                                </div>
                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                    <h5>Planned EP</h5>
                                    <p id="empepView"></p>
                                    <h5>Planned Labor Hours</h5>
                                    <p id="emplaborHourView"></p>
                                    <h5>Planned Delivery Date</h5>
                                    <p id="empdeliveryDateView"></p>
                                    <h5>Assignee/s</h5>
                                    <p id="empassigneeView"></p>
                                    <h5 id="empproDocViewHeader">Project Documents</h5>
                                    <p id="empproDocView"></p>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@if(auth()->check() && auth()->user()->hasRole('super-admin'))
<!-- Approval Requests -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Registration Approval Requests
                    <span class="badge bg-blue"></span>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($approval_users as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>
                                    @foreach($user->roles as $item)
                                    {{ $item->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-red">Pending</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-warning waves-effect" type="button"
                                        onclick="approveCategory('{{ $user->id }}')">
                                        Approve
                                    </button>
                                    <form id="approve-form-{{ $user->id }}"
                                        action="{{ route('approve_user', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Approval -->
<!-- Moderator -->
{{-- <div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Moderators
                    <span class="badge bg-blue"></span>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moderators as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
<td>{{ $user->name }}</td>
<td>{{ $user->email }}</td>
<td>{{ $user->phone }}</td>
<td>{{ $user->gender }}</td>
<td>
    @foreach($user->roles as $item)
    {{ $item->name }}
    @endforeach
</td>
<td>
    <span class="badge bg-green">Active</span>
</td>
<td class="text-center">
    <button class="btn btn-danger waves-effect" type="button" onclick="pendingCategory('{{ $user->id }}')">
        Make Pending
    </button>
    <form id="pending-form-{{ $user->id }}" action="{{ route('pending_user', $user->id) }}" method="POST"
        style="display:none">
        @csrf
    </form>
</td>
<td>
    <a href="{{ route('users.edit',$user->id)}}" class="btn btn-info waves-effect">
        <i class="material-icons">edit</i>
    </a>
    <button class="btn btn-danger waves-effect" type="button" onclick="deleteModerator('{{ $user->id }}')">
        <i class="material-icons">delete</i>
    </button>
    <form id="delete-moderator-{{ $user->id }}" action="{{ route('users.destroy', $user->id) }}" method="POST"
        style="display:none">
        @csrf
        @method('DELETE')
    </form>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
</div>
</div>
</div>
</div> --}}
<!-- Moderator -->
<!-- PM -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    PMs
                    <span class="badge bg-blue"></span>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pm as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>
                                    @foreach($user->roles as $item)
                                    {{ $item->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-green">Active</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteCategory('{{ $user->id }}')">
                                        Make Pending
                                    </button>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('pending_user', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit',$user->id)}}" class="btn btn-info waves-effect">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteDoctor('{{ $user->id }}')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form id="delete-doctor-{{ $user->id }}"
                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- PM -->
<!-- Employee -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Employees
                    <span class="badge bg-blue"></span>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Gender</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($employees as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>{{ $user->gender }}</td>
                                <td>
                                    @foreach($user->roles as $item)
                                    {{ $item->name }}
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge bg-green">Active</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteCategory('{{ $user->id }}')">
                                        Make Pending
                                    </button>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('pending_user', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit',$user->id)}}" class="btn btn-info waves-effect">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteDoctor('{{ $user->id }}')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form id="delete-doctor-{{ $user->id }}"
                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Employee -->
<!-- Moderator -->
<div class="row clearfix">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="card">
            <div class="header">
                <h2>
                    Moderators
                    <span class="badge bg-blue"></span>
                </h2>
            </div>
            <div class="body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Company</th>
                                <th>Status</th>
                                <th>Approval</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($moderators as $key=>$user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->phone }}</td>
                                <td>DataSoft Manufacturing & Assembly Inc. Limited</td>
                                {{-- <td>{{ $user->gender }}</td>
                                <td>
                                    @foreach($user->roles as $item)
                                    {{ $item->name }}
                                    @endforeach
                                </td> --}}
                                <td>
                                    <span class="badge bg-green">Active</span>
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteCategory('{{ $user->id }}')">
                                        Make Pending
                                    </button>
                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('pending_user', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                    </form>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('users.edit',$user->id)}}" class="btn btn-info waves-effect">
                                        <i class="material-icons">edit</i>
                                    </a>
                                    <button class="btn btn-danger waves-effect" type="button"
                                        onclick="deleteDoctor('{{ $user->id }}')">
                                        <i class="material-icons">delete</i>
                                    </button>
                                    <form id="delete-doctor-{{ $user->id }}"
                                        action="{{ route('users.destroy', $user->id) }}" method="POST"
                                        style="display:none">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Moderator -->
@endif
</div>
{{-- project edit modal for pm and moderator --}}
@if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('power-user'))
<div class="modal-holder">
                <div class="card">
                    <div class="body">
                        <div class="modal" id="editProject" tabindex="-1" role="dialog">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Project Plan Details</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                    </div>
                                    <div class="modal-body">
                                        {{-- project plan detail view --}}
                                        <div id="proViewDiv">
                                            <div class="row clearfix">
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12" style="border-right: 1px solid gray;">
                                                    <h5>Task and Delivery Order</h5>
                                                    <p id="tdoView"></p>
                                                    <h5>TDO Details</h5>
                                                    <p id="tdoDetailView"></p>
                                                    <h5>Sub Task</h5>
                                                    <p id="subtaskView"></p>
                                                    <h5>Work Package</h5>
                                                    <p id="wpView"></p>
                                                    <h5>Task Title</h5>
                                                    <p id="taskTitleView"></p>
                                                </div>
                                                <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                                                    <h5>Task Details</h5>
                                                    <p id="taskdetailsView"></p>
                                                    <h5>Planned EP</h5>
                                                    <p id="epView"></p>
                                                    <h5>Planned Labor Hours</h5>
                                                    <p id="laborHourView"></p>
                                                    <h5>Planned Delivery Date</h5>
                                                    <p id="deliveryDateView"></p>
                                                    <h5>Assignee/s</h5>
                                                    <p id="assigneeView"></p>
                                                    <h5 id="proDocViewHeader">Project Documents</h5>
                                                    <p id="proDocView"></p>
                                                </div>
                                            </div>
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect" id="proEditBtn">EDIT</button>                                            {{-- <a href="{{route('dashboard')}}" style="text-decoration: none;">
                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">DELETE</button>
                                    </a> --}}
                                        </div>
                                        {{-- project edit form --}}
                                        <form method="POST" action="{{ route('projects.update', 'edit') }}" class="form-horizontal" enctype="multipart/form-data">
                                            @csrf @method('PUT')
                                            <input hidden type="number" id="tdo_id" name="tdo_id">
                                            <input hidden type="number" id="subtask_id" name="subtask_id">
                                            <input hidden type="number" id="project_plan_id" name="project_plan_id">
                                            <input hidden type="number" id="project_plan_details_id" name="project_plan_details_id">
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="tdo">Task and Delivery Order </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            {{-- select TDO --}}
                                                            <select class="form-control" name="tdo_select" id="tdo" disabled>
                                                        <option selected disabled value="">Select one</option>
                                                        {{-- <option value="New">New TDO</option> --}}
                                                        @foreach($tdos as $tdo)
                                                        <option value="{{ $tdo->id }}">{{ $tdo->title }}</option>
                                                        @endforeach
                                                    </select>                                                            {{-- input TDO --}} {{--
                                                            <div id="tdoInputDiv">
                                                                <input type="text" class="form-control" placeholder="Type Task and Delivery Order" name="tdo_input" value="">
                                                                <span class="material-icons" id="closeTdoInput">
                                                            highlight_off
                                                        </span>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="tdo_details">TDO Details</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <textarea rows="5" id="tdo_details" placeholder="Write TDo details..." name="tdo_details" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="project_name">Sub Task</label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            {{-- select sub task --}}
                                                            <select class="form-control" name="project_name_select" id="project_name" disabled>
                                                        <option selected disabled value="">Select one</option>
                                                        {{-- <option value="New">New Project</option> --}}
                                                        @foreach($subTasks as $subTask)
                                                        <option value="{{ $subTask->id }}">
                                                            {{ $subTask->sub_task_name }}</option>
                                                        @endforeach
                                                    </select>                                                            {{-- input sub task --}} {{--
                                                            <div id="projectInputDiv">
                                                                <input type="text" class="form-control" placeholder="Type Project Name" name="project_name_input" value="">
                                                                <span class="material-icons" id="closeProjectInput">
                                                            highlight_off
                                                        </span>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="work_package">Work Package</label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            {{-- select work package --}}
                                                            <select class="form-control" name="work_package_select" id="work_package" disabled>
                                                        <option selected disabled value="">Select one</option>
                                                        {{-- <option value="New">New Work Package</option> --}}
                                                        @foreach($workPackages as $workPackage)
                                                        <option value="{{ $workPackage->id }}">
                                                            {{ $workPackage->work_package_number }}</option>
                                                        @endforeach
                                                    </select>                                                            {{-- input work packagwe --}} {{--
                                                            <div id="wpInputDiv">
                                                                <input type="number" class="form-control" placeholder="Type work package number" name="work_package_input" id="work_package_input"
                                                                    value="" min="1000">
                                                                <span class="material-icons" id="closeWpInput">
                                                            highlight_off
                                                        </span>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                    <small class="custom-alert" id="work_package_alert">The number must be
                                                unique!</small>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="project_plan">Task Title</label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            {{-- select task title --}}
                                                            <select class="form-control" name="project_plan_select" id="project_plan" disabled>
                                                        <option selected disabled value="">Select one</option>
                                                        {{-- <option value="New">New Task Title</option> --}}
                                                        @foreach($projectPlans as $projectPlan)
                                                        <option value="{{ $projectPlan->id }}">
                                                            {{ $projectPlan->plan_title }}</option>
                                                        @endforeach
                                                    </select>                                                            {{-- input plan title --}} {{--
                                                            <div id="planTitleInputDiv">
                                                                <input type="text" class="form-control" placeholder="Type Task Title" name="project_plan_input" value="">
                                                                <span class="material-icons" id="closePlanInput">
                                                            highlight_off
                                                        </span>
                                                            </div> --}}
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="planned_ep">Planned EP <small>(Estimated Person)</small></label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="planned_ep" class="form-control" placeholder="" name="planned_ep" value="" step=".1" min=".1">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="planned_labor_hrs">Planned Labor Hours</label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="number" id="planned_labor_hrs" class="form-control" placeholder="" name="planned_labor_hrs" value="" step=".1"
                                                                min="0.1">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="planned_due_date">Planned Delivery Date</label>
                                                </div>
                                                <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="date" id="planned_due_date" class="form-control" placeholder="" name="planned_due_date" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="task_details">Task Details</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="task_details" class="form-control" placeholder="Type task title" name="task_details" value="">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="assignee">Assignee/s</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                        {{-- select assignee --}}
                                                            <select class="js-example-basic-multiple" name="assignee[]" id="assignee"
                                                                multiple="multiple" required>
                                                                {{-- <option selected disabled value="">Select one</option> --}}
                                                                @foreach($users as $user)
                                                                <option value="{{ $user->id }}">
                                                                    {{ $user->name }} - ({{$user->email}})</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix" id="added_project_doc_holder">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="added_project_doc">Added Project Document </label>
                                                </div>
                                                <input type="text" hidden id="delete_pro_doc" name="delete_pro_doc">
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 pr-0 pl-0">
                                                    <div id="added_project_doc">
                                                        {{-- list is here --}}
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="project_doc">Project Document </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="file" id="project_doc" class="form-control" placeholder="Type client name of project" name="project_doc[]" value=""
                                                                multiple>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                                    <button type="button" id="proEditCancelBtn" class="btn btn-danger m-t-15 waves-effect">CANCEL</button>
                                                    <button type="submit" class="btn btn-primary m-t-15 waves-effect" id="SaveBtn">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
@endif

{{-- wbs edit modal for employee --}}
<div class="modal-holder">
    <div class="card">
        <div class="body">
            <div class="modal" id="editWbs" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Edit WBS</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-12">
                                    <h5>Project Name</h5>
                                    <p id="pro-name"></p>
                                </div>
                                <div class="col-md-6 col-sm-12">
                                    <h5>Task Title</h5>
                                    <p id="plan-title"></p>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5>Task Details</h5>
                                    <p id="task-title"></p>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5>Assigned By</h5>
                                    <p id="reporters"></p>
                                </div>
                            </div>
                            {{-- wbs edit form --}}
                            <form method="POST" id="editWbsForm" action="{{ route('wbs.update', 'edit') }}"
                                class="form-horizontal" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <input type="text" hidden name="wbs_id" id="wbs_id">
                                <input type="number" hidden name="subtask_id" id="subtask_id">
                                <input type="text" hidden name="wbs_user_id" id="wbs_user_id">
                                <input type="text" hidden name="project_plan" id="edit_wbs_project_plan">
                                <input type="text" hidden name="project_plan_details_id"
                                    id="edit_wbs_project_plan_details_id">
                                <input type="text" hidden name="project_plan_details"
                                    id="edit_wbs_project_plan_details">
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="wbs_task_details">Actual Work
                                            <small>(weekending)</small></label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <textarea rows="5" id="wbs_task_details"
                                                    placeholder="Write task details..." name="wbs_task_details"
                                                    class="form-control" required></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="project_assignee_id">Assignee</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7 select-multi-div">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <select class="form-control project_lead" name="project_assignee_id"
                                                    id="project_assignee_id" required>
                                                     <option selected disabled value="">Select assignee</option>
                                                    {{-- <option selected disabled value="">Select assignee</option>
                                                    @foreach($users as $user)
                                                    <option value="{{ $user->id }}">{{ $user->name }}
                                                        ({{ $user->email }})</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="task_end_date">End
                                            Date </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" id="task_end_date" class="form-control"
                                                    placeholder="" name="task_end_date" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="planned_hours">Planned Hours</label>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" id="planned_hours" class="form-control"
                                                    placeholder="" name="planned_hours" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="planned_delivery_date">Planned Delivery Date</label>
                                    </div>
                                    <div class="col-lg-2 col-md-3 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" id="planned_delivery_date" class="form-control"
                                                    placeholder="" name="planned_delivery_date" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="actual_hours_worked">Actual
                                            Hours <small>(worked)</small></label>
                                    </div>
                                    <div class="col-lg-2 col-md-1 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input readonly type="number" id="actual_hours_worked"
                                                    class="form-control" placeholder="" name="actual_hours_worked"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="task_start_date">Start Date </label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="md-form md-outline input-with-post-icon datepicker" inline="true">
                                                <input type="date" id="task_start_date" class="form-control"
                                                    placeholder="" name="task_start_date" value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="task_start_date">Stop Date </label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="date" id="task_end_date" class="form-control"
                                                    placeholder="" name="task_end_date" value="" required>
                                            </div>
                                        </div>
                                    </div>
                                   
                                </div>
                                <div class="row clearfix">
                                     <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="actual_hours_today">Actual
                                            Hours <small>(today)</small></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" id="actual_hours_today" class="form-control"
                                                placeholder="" name="actual_hours_today" min="0" step=".1" required>
                                            </div>
                                        </div>
                                    </div>
                                     <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="status">Status <small>(%)</small></label>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="number" id="status" class="form-control" placeholder=""
                                                    name="status" value="" required min="0" max="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix" id="product_deliverable_div">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="product_deliverable">Product Deliverable</label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="product_deliverable" class="form-control"
                                                    placeholder="" name="product_deliverable" value="" maxlength="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="task_comments">Comments
                                        </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="text" id="task_comments" class="form-control"
                                                    placeholder="" name="task_comments" value="" maxlength="100">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                        <button type="submit"
                                            class="btn btn-primary m-t-15 waves-effect" id="btnUpate">UPDATE</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- time card modal for employee --}}
<div class="modal-holder">
    <div class="card">
        <div class="body">
            <div class="modal" id="showTimeCard" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Time Card</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="row clearfix">
                                <div class="col-md-6 col-sm-12">
                                    <h5>Name:  <span id="emp-name"></span></h5>
                                    
                                </div>
                                {{-- <div class="col-md-6 col-sm-12">
                                    <h5>Task Title</h5>
                                    <p id="plan-title"></p>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5>Task Details</h5>
                                    <p id="task-title"></p>
                                </div>
                                <div class="col-md-12 col-sm-12">
                                    <h5>Assigned By</h5>
                                    <p id="reporters"></p>
                                </div> --}}
                            </div>
                            <div class="row clearfix" >
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label" style="text-align: left">
                                    <label for="wbs_start_date">From Date </label>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <div class="md-form md-outline input-with-post-icon datepicker" inline="true">
                                            <input type="date" id="wbs_start_date" class="form-control" placeholder=""
                                                name="wbs_start_date" value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label" style="text-align: left">
                                    <label for="wbs_start_date">To Date </label>
                                </div>
                                <div class="col-lg-3 col-md-3 col-sm-7 col-xs-7">
                                    <div class="form-group">
                                        <div class="form-line">
                                            <input type="date" id="wbs_end_date" class="form-control" placeholder=""
                                                name="wbs_end_date" value="" required>
                                        </div>
                                    </div>
                                </div>
                                 <button type="button" class="col-lg-2 col-md-2 col-sm-2 col-xs-7 btn btn-primary btnSearch" 
                                  id="abc" style="width: auto;float: right;margin-right: 15px;">GO</button>
                            </div>
                            <div class="row clearfix">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                     <table id="timecard-table" class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Project Name</th>
                                                        <th>Actual Work</th>
                                                        <th>Updated Date</th>
                                                        <th>Daily Hour</th>
                                                        <th>Status</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                       <td></td>
                                                    </tr>
                                                </tbody>
                                                <tfoot>
                                                    <tr style="font-weight:bold">
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>Total</td>
                                                        <td id="timeCard_total">0.00</td>
                                                        <td></td>
                                                    </tr>
                                                </tfoot>
                                     </table>
                                </div>
                            </div>
                            </div>
                           
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
{{-- <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/jquery.dataTables.js')}}">
</script> --}}
{{-- <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/jquery.dataTables.min.js')}}"></script> --}}
<script src="https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}">
</script>
{{-- <script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script> --}}
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}">
</script>
{{-- <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script> --}}
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}">
</script>
{{-- <script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script> --}}


<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}">
</script>


<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}">
</script>


<script src="{{ asset('public/assets/backend/js/pages/tables/jquery-datatable.js')}}"></script>
{{-- <script src="{{ asset('public/assets/backend/js/pages/index.js') }}"></script> --}}
<!-- Jquery CountTo Plugin Js -->
<script src="{{ asset('public/assets/backend/plugins/jquery-countto/jquery.countTo.js ')}}"></script>
<!-- Morris Plugin Js -->
{{-- <script src="{{ asset('public/assets/backend/plugins/raphael/raphael.min.js ')}}"></script> --}}
{{-- <script src="{{ asset('public/assets/backend/plugins/morrisjs/morris.js ')}}"></script> --}}
<!-- ChartJs -->
{{-- <script src="{{ asset('public/assets/backend/plugins/chartjs/Chart.bundle.js ')}}"></script> --}}
<!-- Flot Charts Plugin Js -->
{{-- <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.js ')}}"></script>
--}}
{{-- <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.resize.js ')}}">
</script> --}}
{{-- <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.pie.js ')}}"></script>
--}}
{{-- <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.categories.js ')}}">
</script>
--}}
{{-- <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.time.js ')}}">
</script> --}}
<!-- Sparkline Chart Plugin Js -->
{{-- <script src="{{ asset('public/assets/backend/plugins/jquery-sparkline/jquery.sparkline.js ')}}">
</script>
--}}
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> --}}
<script src="{{ asset('public/assets/backend/js/npmsweetalert2at8.js ')}}"></script>
<script type="text/javascript">
    function deleteCategory(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure ?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-form-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
        })
    }
    function deleteModerator(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure to delete ?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-moderator-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
        })
    }
    function deleteDoctor(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure to delete ?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-doctor-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
        })
    }
    function approveCategory(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure to approve ?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('approve-form-' + id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
        })
    }
</script>
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script type="text/javascript">
    var baseUrl = window.location.href.split("/dashboard");
    $(document).ready(function () {
        // // set active tab history to session
        $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
            localStorage.setItem('activeTab', $(e.target).attr('href'));
        });
        // activate the tab from session history
        var activeTab = localStorage.getItem('activeTab');
        if (activeTab) {
            $('#employeeTab a[href="' + activeTab + '"]').tab('show');
            $('#pmTab a[href="' + activeTab + '"]').tab('show');
            $('#moderatorTab a[href="' + activeTab + '"]').tab('show');
        }
        // set placholder for multiple select
        $('.select-multi').select2({
            placeholder: "You can select multiple",
        });
        $('.support_engg').select2({
            placeholder: "Enter support employee",
            multiple: true,
        });
        function pendingCategory(id) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: 'btn btn-success',
                    cancelButton: 'btn btn-danger'
                },
                buttonsStyling: false
            })
            swalWithBootstrapButtons.fire({
                title: 'Are you sure to pending ?',
                text: "",
                type: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes!',
                cancelButtonText: 'No!',
                reverseButtons: true
            }).then((result) => {
                if (result.value) {
                    event.preventDefault();
                    document.getElementById('pending-form-' + id)
                        .submit();
                } else if (
                    /* Read more about handling dismissals below */
                    result.dismiss === Swal.DismissReason.cancel
                ) {
                    // swalWithBootstrapButtons.fire(
                    // 'Not Cancelled!',
                    // )
                }
            })
        }
    });
    // meeting delete
    function deleteMeeting(id) {
        const swalWithBootstrapButtons = Swal.mixin({
            customClass: {
                confirmButton: 'btn btn-success',
                cancelButton: 'btn btn-danger'
            },
            buttonsStyling: false
        })
        swalWithBootstrapButtons.fire({
            title: 'Are you sure to delete ?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
        }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-meeting-' + id)
                    .submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
        })
    };
</script>
{{-- <script src="https://xoma.co/nacional/consultasweb/dist/RecordRTC.js"></script> --}}
<script src="{{ asset('public/js/RecordRTC_new.js')}}"></script>
<script src="https://webrtc.github.io/adapter/adapter-latest.js"></script>
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script type="text/javascript">
// ajax setup for laravel
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
    @php
    $role_mod = auth() ->user() ->hasRole('admin');
    @endphp
   
 $(document).ready(function () {
        $(".js-example-basic-multiple").select2();
        
        // var baseUrl = window.location.href.split("/dashboard");
        // // // set active tab history to session
        // $('a[data-toggle="tab"]').on('show.bs.tab', function (e) {
        //     localStorage.setItem('activeTab', $(e.target).attr('href'));
        // });
        // // activate the tab from session history
        // var activeTab = localStorage.getItem('activeTab');
        // if (activeTab) {
        //     $('#employeeTab a[href="' + activeTab + '"]').tab('show');
        //     $('#pmTab a[href="' + activeTab + '"]').tab('show');
        // }
        // set placholder for multiple select
        $('.select-multi').select2({
            placeholder: "You can select multiple",
        });
        $('.support_engg').select2({
            placeholder: "Enter support employee",
            multiple: true,
        });

       
        // set modal form data from DB for wbs edit
        $('#editWbs').on('show.bs.modal', function (e) {
            @php
            echo "var all_emp = $users;";
            echo "var all_projects = $all_projects;"

            @endphp
            var assignee_list = [];
            let wp = JSON.parse([e.relatedTarget.dataset["workpackage"]]);
            // console.log("wp: "+JSON.stringify(wp));
            all_projects.forEach(pro => {
                // console.log("pro: "+JSON.stringify(pro.project_plan_work_package_id));
                if(wp.id == pro.project_plan_work_package_id){
                    // console.log("wp: "+JSON.stringify(pro.project_plan_assignee));
                    if (pro.project_plan_assignee.indexOf(',') > -1) {
                        assignee_list = pro.project_plan_assignee.split(",");
                    }
                    else{
                        assignee_list.push(pro.project_plan_assignee);
                    }
                }
                
            });

            // assignee_list = e.relatedTarget.dataset["assignee"].split(",");
            // if (e.relatedTarget.dataset["assignee"].indexOf(',') > -1) { 
            //                 assignee_list = e.relatedTarget.dataset["assignee"].split(",");

            //     }
            // else{
            //     assignee_list.push(e.relatedTarget.dataset["assignee"]);
            // }
            
            assignee_list = assignee_list.filter(onlyUnique);
             $("#project_assignee_id").find("option").remove();
             $("#project_assignee_id").append(
             "<option selected disabled value=''>Select assignee</option>");
             assignee_list.forEach(ele =>{
                for(var i=0;i<all_emp.length;i++) {
                    if(parseInt(ele)==all_emp[i].id){ 
                    // console.log(all_emp[i].id +" name: "+all_emp[i].name);
                    $("#project_assignee_id").append('<option value="' + all_emp[i].id + '">' +
                    all_emp[i].name+'('+all_emp[i].email+')'+ '</option>')
                    }
                }
                    });
                
            let data = JSON.parse([e.relatedTarget.dataset["wbs"]]);
            let loggedInUser = e.relatedTarget.dataset["user"];
            let reporter = JSON.parse([e.relatedTarget.dataset["reporter"]]);
            let tdo = JSON.parse([e.relatedTarget.dataset["tdo"]]);
            
            $(this).find("#pro-name").text(data.project_plan_details.project_plan.work_package.sub_task
                .sub_task_name);
            $(this).find("#subtask_id").val(data.project_plan_details.project_plan.work_package.sub_task
                .id);
            $(this).find("#plan-title").text(data.project_plan_details.project_plan.plan_title);
            $(this).find("#task-title").text(data.project_plan_details.project_task_details);
            $(this).find("#reporters").text(reporter.name);
            $(this).find("#wbs_id").val(data.wbs.id);
            $(this).find("#wbs_user_id").val(loggedInUser);
            $(this).find("#project_assignee_id").val(data.wbs_master_assignee_id);
            $(this).find("#wbs_task_details").val(data.wbs.wbs_task_details);
            $(this).find("#edit_wbs_project_plan").val(data.project_plan_details.project_plan.id);
            $(this).find("#edit_wbs_project_plan_details_id").val(data.project_plan_details.id);
            $(this).find("#edit_wbs_project_plan_details").val(data.project_plan_details.project_task_details);
            // can be edit
            if (tdo.created_by == loggedInUser) {
                $(this).find("#planned_hours").prop("readonly", false);
            } else {
                $(this).find("#planned_hours").prop("readonly", true);
            }
            $(this).find("#planned_delivery_date").prop("readonly", true);
            $(this).find("#planned_delivery_date").val(data.project_plan_details.project_plan.planned_delivery_date);
            $(this).find("#planned_hours").val(data.project_plan_details.project_plan.planned_hours);
            $(this).find("#actual_hours_today").prop('max', $(this).find("#planned_hours").val());
            $(this).find("#actual_hours_worked").val(data.wbs.actual_hours_worked);
            // $(this).find("#actual_hours_today").val(data.wbs.actual_hours_today);
            if (data.wbs.task_start_date != null) {
                // var maxDate = year + '-' + month + '-' + day;
                $('#task_start_date').attr('min', data.wbs.task_start_date);
                //$("#task_start_date").prop("disabled", true);
            } else {
                $('#task_start_date').attr('min', maxDate);
                //$("#task_start_date").prop("disabled", false);
            }
            $('#task_start_date').attr('max', data.project_plan_details.project_plan.planned_delivery_date);
            // console.log('dd: '+JSON.stringify(data.project_plan_details.project_plan.planned_delivery_date));
            if (data.wbs.task_end_date != null) {
                //$("#task_end_date").prop("disabled", true);
                $('#task_end_date').attr('min', data.wbs.task_end_date);
            }
            else {
                //$("#task_end_date").prop("disabled",false);
                $('#task_end_date').attr('min', maxDate);
                //data.project_plan_details.project_plan.planned_delivery_date
            }
            $('#task_end_date').attr('max', data.project_plan_details.project_plan.planned_delivery_date);
            // alert(data.wbs_master_assignee_id+ " by: " +data.created_by+" user:"+loggedInUser);
            if (data.wbs_master_assignee_id == loggedInUser || data.created_by == loggedInUser || data.reporter == loggedInUser) {
                $("#wbs_task_details").prop("readonly", false);
            } else {
                $("#wbs_task_details").prop("readonly", true);
            }
            $(this).find("#task_start_date").val(data.wbs.task_start_date);
            $(this).find("#task_end_date").val(data.wbs.task_end_date);
            $(this).find("#product_deliverable").val(data.wbs.product_deliverable);
            $(this).find("#task_comments").val(data.wbs.task_comments);
            $(this).find("#status").val(data.wbs.status);
          $( ".project_lead" ).change(function() {
               let asgn_id = data.wbs_master_assignee_id;
               let new_asgn_id = $(this).val();
               if(asgn_id != new_asgn_id)
               {
                    $('#actual_hours_today').prop('required',false);
                    $('#status').prop('required',false);
                    $('#status').val('');
                    // $('#wbs_task_details').val('');
                    $('#wbs_task_details').prop('readonly',false);
                    $('#btnUpate').html('SUBMIT');
               }
               else
               {
                    $('#actual_hours_today').prop('required',true);
                    $('#status').prop('required',true);
                    $('#status').val(data.wbs.status);
                    // $('#wbs_task_details').val(data.wbs.wbs_task_details);
                    $('#wbs_task_details').prop('readonly',true);
                    $('#btnUpate').html('UPDATE');
               }
            });
        });
        // set interval for checking status of WBS while editing
        setInterval(() => {
            if ($("#status").val() == 100) {
                $("#product_deliverable_div").css("display", "unset");
            } else {
                $("#product_deliverable_div").css("display", "none");
            }
        }, 1000);
        // set min date for wbs start date
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10) month = '0' + month.toString();
        if (day < 10) day = '0' + day.toString();
        var maxDate = year + '-' + month + '-' + day;
        $('#task_start_date').attr('min', maxDate);
        $('#task_end_date').attr('min', maxDate);
        // get all work package number from DB and make array
        @php
        $role = auth() ->user() ->hasRole('super-admin');
        
        if ($role != 1 && $role_mod !=1) {
            echo "var workPackages = $workPackages;";
        } else {
            echo "var workPackages = [];";
        }
        @endphp
        var wPackageArray = [];
        wPackageArray = [...new Map(wPackageArray.map(item => [item.id, item])).values()];

        workPackages.forEach(element => {
            wPackageArray.push(element.work_package_number);
        });
        $('#tdo').change(function () {
            @php
            $role = auth() ->user() ->hasRole('super-admin');
            if ($role != 1 && $role_mod !=1) {
                echo "var subTaskAraay = $subTasks;";
            }
            else {
                echo "var subTaskAraay = [];";
            }
            @endphp
            var subTaskArrayFilter = $.grep(subTaskAraay, function (e) {
                return e.subtask_tdo_id == parseInt($('#tdo').val());
            });
            if ($('#tdo').val() != '') {
                $("#project_name").find("option").remove();
                $("#project_name").append(
                    "<option selected disabled value=''>Select one</option>");
                // $("#project_name").append("<option value='New'>New Project</option>");
                subTaskArrayFilter.forEach(element => {
                    $("#project_name").append('<option value="' + element.id +
                        '">' +
                        element.sub_task_name + '</option>')
                });
            }
        });
        $('#project_name').change(function () {
            @php
            $role = auth() ->user() ->hasRole('super-admin');
            if ($role != 1 && $role_mod !=1) {
                echo "var wpAraay = $workPackages;";
            }
            else {
                echo "var wpAraay = [];";
            }
            @endphp
            var subTaskArrayFilter = $.grep(wpAraay, function (e) {
                return e.work_package_subtask_id == parseInt($('#project_name')
                    .val());
            })
            if ($('#work_package').val() != '') {
                $("#work_package").find("option").remove();
                $("#work_package").append(
                    "<option selected disabled value=''>Select one</option>");
                // $("#work_package").append("<option value='New'>New Work Package</option>");
                subTaskArrayFilter.forEach(element => {
                    $("#work_package").append('<option value="' + element.id +
                        '">' +
                        element.work_package_number + '</option>')
                });
            }
        });
        $('#work_package').change(function () {
            @php
            $role = auth() ->user() ->hasRole('super-admin');
            if ($role != 1 && $role_mod !=1) {
                echo "var projectPlanAraay = $projectPlans;";
            }
            else {
                echo "var projectPlanAraay = [];";
            }
            @endphp
            var projectPlanAraayFilter = $.grep(projectPlanAraay, function (e) {
                return e.project_plan_work_package_id == parseInt($('#work_package')
                    .val());
            });
            if ($('#project_plan').val() != '') {
                $("#project_plan").find("option").remove();
                $("#project_plan").append(
                    "<option selected disabled value=''>Select one</option>");
                // $("#project_plan").append("<option value='New'>New Task Title</option>");
                projectPlanAraayFilter.forEach(element => {
                    $("#project_plan").append('<option value="' + element.id + '">' +
                        element.plan_title + '</option>')
                });
            }
        });
        $('#editProject').on('show.bs.modal', function (e) {
            // console.log(JSON.parse([e.relatedTarget.dataset["files"]]));
            // console.log(assignee_list[0]);  
            $("#editProject form").css("display", "none");
            let tdos = JSON.parse([e.relatedTarget.dataset["tdos"]]);
            let subTask = JSON.parse([e.relatedTarget.dataset["subtask"]]);
            let workPackage = JSON.parse([e.relatedTarget.dataset["workpackage"]]);
            let projectPlan = JSON.parse([e.relatedTarget.dataset["projectplan"]]);
            var assignee_list = (projectPlan.project_plan_assignee).split(",");
            @php
            echo "var all_emp = $users;";
            @endphp
            assignees_name=[];
            assignees_id=[];
            assignee_list.forEach(ele =>{
                    for(var i=0;i<all_emp.length;i++)
                    if(parseInt(ele) == all_emp[i].id){
                     assignees_name.push(all_emp[i].name) ; 
                     assignees_id.push(all_emp[i].id);                                         
                    }
                })  
                  
            let projectPlanDetails = JSON.parse([e.relatedTarget.dataset[
                "projectplandetails"]]);
            let user = JSON.parse([e.relatedTarget.dataset["user"]]);
            let files = JSON.parse([e.relatedTarget.dataset["files"]]);
            // populate the view
            $(this).find("#tdoView").text(tdos.title);
            $(this).find("#tdoDetailView").text(tdos.details);
            $(this).find("#subtaskView").text(subTask.sub_task_name);
            $(this).find("#wpView").text(workPackage.work_package_number);
            $(this).find("#taskTitleView").text(projectPlan.plan_title);
            $(this).find("#taskdetailsView").text(projectPlanDetails.project_task_details);
           // $(this).find("#assigneeView").text(user.name);//a.join('|')
            $(this).find("#assigneeView").text(assignees_name.join(', '));
            $(this).find("#laborHourView").text(projectPlan.planned_hours);
            $(this).find("#epView").text(projectPlan.planned_ep);
            $(this).find("#deliveryDateView").text(projectPlan.planned_delivery_date);
            $(this).find("#proDocView").empty();
            if (files.length > 0) {
                $(this).find("#proDocViewHeader").css("display", "block");
                files.forEach(element => {
                    $(this).find("#proDocView").append("<a href='" + baseUrl[0] +
                        "/file/download/" + element.id + "'>" +
                        element.title + "</a><br>");
                });
            } else {
                $(this).find("#proDocViewHeader").css("display", "none");
            }
            // populate the edit form
            $(this).find("#tdo_id").val(tdos.id);
            $(this).find("#subtask_id").val(subTask.id);
            $(this).find("#project_plan_id").val(projectPlan.id);
            $(this).find("#project_plan_details_id").val(projectPlanDetails.id);
            $(this).find("#tdo").val(tdos.id);
            $(this).find("#tdo_details").val(tdos.details);
            $(this).find("#project_name").val(subTask.id);
            $(this).find("#work_package").val(workPackage.id);
            $(this).find("#project_plan").val(projectPlan.id);
            $(this).find("#planned_ep").val(projectPlan.planned_ep);
            $(this).find("#planned_labor_hrs").val(projectPlan.planned_hours);
            $(this).find("#planned_due_date").val(projectPlan.planned_delivery_date);
            $(this).find("#task_details").val(projectPlanDetails.project_task_details);
            $options = $('#assignee option');
            $('#assignee').val(null);
            for (i = 0; i < assignees_id.length; i++) {
                $options.filter('[value="' + assignees_id[i] + '"]').prop('selected', true);
                $(".js-example-basic-multiple").select2();
            }
            // $(this).find("#assignee").val(user.id);
            $(this).find("#added_project_doc").empty();
            if (files.length > 0) {
                $(this).find("#added_project_doc_holder").css("display", "block");
                files.forEach(element => {
                    $(this).find("#added_project_doc").append(
                        "<div class='proDocEditDiv' id='" +
                        element.id + "'>" + element.title +
                        "<span class='material-icons'>highlight_off</span></div>");
                });
            } else {
                $(this).find("#added_project_doc_holder").css("display", "none");
            }
            var deleteFileId = [];
            $(document).on('click', "div.proDocEditDiv span", function () {
                if (confirm("Are you sure, you want to delete this file?")) {
                    deleteFileId.push($(this).parent().attr('id'));
                    $("#delete_pro_doc").val(deleteFileId);
                    $(this).parent().remove();
                    if ($("#added_project_doc .proDocEditDiv").children().length ===
                        0) {
                        $("#added_project_doc_holder").css("display", "none");
                    }
                }
            })
        });
        $("#proEditBtn").on("click", function () {
            $("#editProject .modal-title").text("Edit Project Plan Details");
            $("#proViewDiv").css("display", "none");
            $("#editProject form").css("display", "block");
        });
        $("#proEditCancelBtn").on("click", function () {
            $("#editProject .modal-title").text("Project Plan Details");
            $("#proViewDiv").css("display", "block");
            $("#editProject form").css("display", "none");
        });
        $('#editProject').on('hide.bs.modal', function (e) {
            $("#editProject .modal-title").text("Project Plan Details");
            $("#proViewDiv").css("display", "block");
            $("#editProject form").css("display", "none");
        });
        $('#employeeProjectView').on('show.bs.modal', function (e) {
            let subTask = JSON.parse([e.relatedTarget.dataset["subtask"]]);
            let workPackage = JSON.parse([e.relatedTarget.dataset["workpackage"]]);
            let projectPlan = JSON.parse([e.relatedTarget.dataset["projectplan"]]);
            let projectPlanDetails = JSON.parse([e.relatedTarget.dataset[
                "projectplandetails"]]);
            let user = JSON.parse([e.relatedTarget.dataset["user"]]);
            let files = JSON.parse([e.relatedTarget.dataset["files"]]);

            var assignee_list = (projectPlan.project_plan_assignee).split(",");
            @php
            echo "var all_emp = $users;";
            @endphp
            assignees_name=[];
            assignee_list.forEach(ele =>{
                    for(var i=0;i<all_emp.length;i++)
                    if(parseInt(ele) == all_emp[i].id){
                     assignees_name.push(all_emp[i].name) ;                        
                    }
                }) 
            // populate the view
            $(this).find("#empsubtaskView").text(subTask.sub_task_name);
            $(this).find("#empwpView").text(workPackage.work_package_number);
            $(this).find("#emptaskTitleView").text(projectPlan.plan_title);
            $(this).find("#emptaskdetailsView").text(projectPlanDetails.project_task_details);
            //$(this).find("#empassigneeView").text(user.name);
            $(this).find("#empassigneeView").text(assignees_name.join(', '));
            $(this).find("#emplaborHourView").text(projectPlan.planned_hours);
            $(this).find("#empepView").text(projectPlan.planned_ep);
            $(this).find("#empdeliveryDateView").text(projectPlan.planned_delivery_date);
            $(this).find("#empproDocView").empty();
            if (files.length > 0) {
                $(this).find("#empproDocViewHeader").css("display", "block");
                files.forEach(element => {
                    $(this).find("#empproDocView").append("<a href='" + baseUrl[0] +
                        "/file/download/" + element.id + "'>" + element.title +
                        "</a><br>");
                });
            } else {
                $(this).find("#empproDocViewHeader").css("display", "none");
            }
        });
        //Time elasped check
        @php
        $role = auth() ->user() ->hasRole('super-admin');
        if ($role != 1 && $role_mod !=1) {
            echo "var projectPlanAraay = $projectPlans;";
        }
        else {
            echo "var projectPlanAraay = [];";
        }
        @endphp
        @php
        $role = auth() ->user() ->hasRole('super-admin');
        if ($role != 1 && $role_mod !=1) {
            echo "var allMeeting = $meeting_info;";
        }
        else {
            echo "var allMeeting = [];";
        }
        @endphp
        if (allMeeting.length > 0) {
            allMeeting.forEach(function (item) {
                console.log(item[0].meeting_dateTime);
                setInterval(() => {
                    var m_date = item[0].meeting_dateTime;
                    var date = new Date();
                    var day = date.getDate();
                    var month = date.getMonth();
                    var year = date.getFullYear();
                    var hours = date.getHours();
                    var minutes = date.getMinutes();
                    var seconds = date.getSeconds();
                    var currentTime = (month + 1) + "/" + day + "/" + year + " " + hours +
                        ":" + minutes + ":" + seconds;
                    var aptDate = m_date;
                    var newDate = changeDateFormat(aptDate);
                    var currentDate = (month + 1) + "/" + day + "/" + year;
                    var d_apt = new Date(newDate); //apt date
                    var d_current = new Date(currentDate); //current date
                    var yearDiff = d_current.getFullYear() - d_apt.getFullYear();
                    var monthDiff = (d_current.getMonth()) - d_apt.getMonth();
                    var dateDiff = (d_current.getDate()) - d_apt.getDate();
                    var aptTimeStart = d_apt;
                    var diff_start = moment.duration(moment(aptTimeStart).diff(moment(
                        currentTime)));
                    var a_milliseconds = d_apt.getTime(); //meeting date in millis
                    var c_milliseconds = d_current.getTime();
                    // console.log("----------item[0]: " + item.id +"  aptTimeStart:"+aptTimeStart+" a_milliseconds:"+a_milliseconds
                    // +" c_milliseconds:"+c_milliseconds);
                    if (yearDiff == 0 && monthDiff == 0 && dateDiff == 0) {
                        $("#btnStartSession" + (item[0].id)).show();
                        $("#btnJoinSession" + (item[0].id)).show();
                    } else if (yearDiff <= 0 && monthDiff <= 0 && dateDiff < 0) {
                        //Upcoming
                        $("#btnStartSession" + (item[0].id)).hide();
                        $('#txtStartSession' + (item[0].id)).text("Upcoming").removeClass(
                            "bg-red");
                        $("#btnJoinSession" + (item[0].id)).hide();
                        $('#txtJoinSession' + (item[0].id)).text("Upcoming").removeClass(
                            "bg-red");
                    } else {
                        // past date all missed
                        $("#btnStartSession" + (item[0].id)).hide();
                        $('#txtStartSession' + (item[0].id)).text("Schedule Expired !!!")
                            .addClass("bg-red");
                        $("#btnJoinSession" + (item[0].id)).hide();
                        // console.log("(item[0].id)" + (item[0].id));
                        $('#txtJoinSession' + (item[0].id)).text("Schedule Expired !!!")
                            .addClass("bg-red");
                    }
                }, 1000);
            });
        }
        $("#pm-projects-card").click(function () {
            $('#pm-projects-tab').trigger('click');
        });
        $("#emp-projects-card").click(function () {
            $('#emp-projects-tab').trigger('click');
        });
        $("#employees-card").click(function () {
            $('#pm_wbs_tab').trigger('click');
        });
        $("#pm-meetings-card").click(function () {
            $('#pm-meetings-tab').trigger('click');
        });
        $("#emp-meetings-card").click(function () {
            $('#emp-meetings-tab').trigger('click');
        });
        $("#emp-wbs-card").click(function () {
            $('#emp-ebs-tab').trigger('click');
        });

        $("#moderator-projects-card").click(function () {
           $('#moderator-projects-tab').trigger('click');
        });
        $("#moderator-wbs-card").click(function () {
           $('#moderator-wbs-tab').trigger('click');
        });
        $("#moderator-meetings-card").click(function () {
           $('#moderator-meeting-tab').trigger('click');
        });

        $('#pmTab li').on('click',function(){
            $('input[type=search]').val('').change();
            // $('input[type=search]').val('').trigger("click");

            // alert('this tab is already active');
            // $('.div.dataTables_filter :input[type=search]').focus();

            // var oTable = $('#table-pm-project').DataTable();
            // .dataTables_wrapper input[type="search"]
            $('#table-pm-project').DataTable().column('').search("").draw();
            // $('#table-pm-project').DataTable().draw();
            // $('#table-pm-project').DataTable().data.reload();
            
        //      $('#table-pm-project').on('search.dt', function(e) {
        //         alert(e);
        //     }); 
        //      $('#table-pm-project').DataTable({
        //         stateSave: true
        //   }); // reDraw table
           
            // oTable.search( '', true ).draw();
            //$('#table-pm-project').DataTable().column('').search('').draw();
            // var table = $('#example').DataTable();

           
        });

        // $("#tabs")
        // .tabs()
        // .on("click", '[role="tab"]', function() {
        //     $(this).closest("ul") // The current UL
        // });

        //set modal for show time card
        $('#showTimeCard').on('show.bs.modal', function (e) {
            // e.preventDefault();
            @php
            echo "var all_emp = $users;";
            @endphp
            // alert(JSON.parse([e.relatedTarget.dataset["user"]]));
            var user = JSON.parse([e.relatedTarget.dataset["user"]]);
            // alert(typeof user);
            // console.log("empId: "+user);
            //btnSearch
            $('.btnSearch').attr('id', 'btnSearch_'+user);
            // $('.btnSearch').attr('id','id_new');
            console.log($('.btnSearch').attr('id'));
            //$('.btnSearch#btnSearch_'+user).removeAttr('id');
            var fileName = '';
            var userName = '';
            var total = 0.00;
            $("#timecard-table").DataTable().clear().draw();
            $('#timeCard_total').text(total);
            
            for(var i=0;i<all_emp.length;i++) {
               if(parseInt(user)==all_emp[i].id){
                    $(this).find("#emp-name").text(all_emp[i].name);
                    userName = all_emp[i].name;
                    break;
                } 
            }
            //maxDate
            fileName = userName+"_"+maxDate;
            var timeCardTable = $("#timecard-table").DataTable();
            timeCardTable.clear().draw(false);
            $("#btnSearch_"+user).unbind('click');
            $("#btnSearch_"+user).bind('click', function (e) {
                e.preventDefault();
                // $('#timecard-table tbody').empty();
                // $('#timecard-table tbody > tr').remove();
                var responseData = [];
                if(wbs_start_date.value != '' && wbs_end_date.value != ''){
                    var dataF = new FormData();
                  
                    dataF.append("empId", user);
                    dataF.append("wbs_start_date", wbs_start_date.value);
                    dataF.append("wbs_end_date", wbs_end_date.value);
                    // console.log("data: "+JSON.stringify(dataF));
                    for (var value of dataF.values()) {
                        console.log("---------"+value);
                    }
                  timeCardTable.clear().draw(false); 
                $.ajax({
                type: "POST",
                url: baseUrl[0] +"/getEmpTimecard",
                data: dataF,
                dataType: "json",
                processData: false,
                contentType: false,
                enctype: 'multipart/form-data',
                success: function (response) {
                    // console.log("v: "+JSON.stringify(response));
                    if(response != null){
                // $('#timecard-table').DataTable().ajax.reload();
                    responseData = null;
                    responseData = response;
                    var excelTitle = "Start Date: "+wbs_start_date.value+",  Stop Date: "+wbs_end_date.value;
                    
                    timeCardTable = $('#timecard-table').DataTable( {
                    dom: 'Bfrtip',
                    "bDestroy": true,
                    buttons : [
                    {extend: 'copyHtml5',title: fileName, footer: true,messageTop: excelTitle },
                    {extend: 'csvHtml5',title: fileName, footer: true , messageTop: excelTitle},
                    {extend: 'excelHtml5',title: fileName,footer: true,
                    messageTop: excelTitle},
                    {extend: 'pdf',title: fileName,footer: true, messageTop: excelTitle},
                    {extend: 'print',title: fileName,footer: true,
                    messageTop: '<h2 style=\'float: left\'> Name:' + userName + '<p>From: ' + wbs_start_date.value 
                        +'</p>'+'<p>To: ' + wbs_end_date.value + '</p></h2>'}
                    ]
                    });
                    
                    // timeCardTable.clear().draw();

                    total = 0.00;
                        // alert(responseData.length);

                    for (var i = 0; i < responseData.length; i++) {
                        console.log("v: "+responseData[i].time_card_sub_task_name);

                        var rowData = [];
				        // id
                        rowData.push(i + 1);   
                        //Project Name
                        // console.log('na: '+responseData[i]);
                        rowData.push(responseData[i].time_card_sub_task_name);
                        //Actual Work
                        rowData.push(responseData[i].time_card_wbs_task_details);
                        //Date
                        rowData.push(responseData[i].wbs_update_date);
                        //Daily Hour
                        rowData.push(responseData[i].time_card_actual_hours_today);
                        total = total + parseFloat(responseData[i].time_card_actual_hours_today);
                        //task status
                        rowData.push(responseData[i].task_status+" %");
                        timeCardTable.row.add(rowData).draw(false);

                        // timeCardTable.clear().rows.add(rowData).draw();
                    }
                    //
                        $('#timeCard_total').text(total);
                        // var tt = $('.btnSearch').attr('id');
                        // $('#'+tt).removeAttr('id');
                        console.log("after click:  "+$('.btnSearch').attr('id'));

                    }
                    
                   
                },
                error: function (e) {
                console.log("ERROR : ", e);
                },
                });
             } 
            });
            
            // console.log('data: '+data);

        });
    

        
        
     
    });
    function changeDateFormat(inputDate) { // expects Y-m-d
        var splitDate = inputDate.split('-');
        if (splitDate.count == 0) {
            return null;
        }
        var year = splitDate[0];
        var month = splitDate[1];
        var day = splitDate[2];
        // console.log("splitDate[2]: "+splitDate[3]);
        return month + '/' + day + '/' + year;
    }
    // remove redundancy from array
    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
    // $("#timecard-table").DataTable().Destroy();
    


</script>
{{-- <script type="text/javascript">
        $(".js-example-basic-multiple").select2();
    </script>  --}}
@endpush

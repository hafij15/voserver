@extends('layouts.backend.app')

@section('title','Create WBS')

@push('css')
<link href="{{ asset('public/css/select2.min.css')}}" rel="stylesheet" />
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

    #assigned-to {
        display: none;
    }
    
    .select2-container--default .select2-selection--single{
        border: none;
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
                        Create WBS
                    </h2>
                </div>
                <div class="body">
                    <form method="POST" action="{{ route('wbs.store') }}" class="form-horizontal"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="project_assignee_id">Assignee</label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <select class="form-control project_lead js-example-basic-single" name="project_assignee_id"
                                            id="project_assignee_id"  required>
                                            <option selected disabled value="">Select assignee</option>
                                            {{-- @if (auth()->user()->hasRole('user'))
                                            @foreach($users as $user)
                                            @foreach ($fetch_assignee as $item)
                                            @if ($user->id == $item)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                            ({{ $user->email }})</option>
                                            @endif
                                            @endforeach

                                            @endforeach
                                            @else
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})</option>
                                            @endforeach
                                            @endif --}}

                                            @if (auth()->user()->hasRole('user'))
                                            @foreach($users as $user)
                                            @foreach ($fetch_assignee as $item)
                                            @if ($user->id == $item)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})</option>
                                            @endif
                                            @endforeach
                                            @endforeach
                                            @else
                                            @foreach ($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})</option>
                                            @endforeach
                                            @endif


                                            {{-- @php
                                            $tot_row = count($users);
                                            if($tot_row != 1) {
                                            @endphp
                                            <option selected disabled value="">Select assignee</option>
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                            ({{ $user->email }})</option>
                                            @endforeach
                                            @php } else { @endphp
                                            @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}
                                                ({{ $user->email }})</option>
                                            @endforeach
                                            @php }
                                            @endphp --}}
                                        </select>
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
                                        <select class="form-control" name="project_name_select" id="project_name"
                                            required>
                                            <option selected disabled value="">Select one</option>
                                            @foreach($subTaskNames as $subTaskName)
                                            <option value="{{ $subTaskName->id }}">
                                                {{ $subTaskName->sub_task_name }}</option>
                                            @endforeach
                                            {{-- @php
                                            $tot_row = count($subTaskNames);
                                            if($tot_row != 1) {
                                            @endphp
                                            <option selected disabled value="">Select one</option>
                                            @foreach($subTaskNames as $subTaskName)
                                            <option value="{{ $subTaskName->id }}">
                                            {{ $subTaskName->sub_task_name }}</option>
                                            @endforeach
                                            @php } else { @endphp
                                            @foreach($subTaskNames as $subTaskName)
                                            <option value="{{ $subTaskName->id }}">
                                                {{ $subTaskName->sub_task_name }}</option>
                                            @endforeach
                                            @php }
                                            @endphp --}}
                                        </select>
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
                                        <select class="form-control" name="work_package_select" id="work_package"
                                            required>
                                            <option selected disabled value="">Select one</option>
                                            @foreach($workPackageNumbers as $workPackageNumber)
                                            <option value="{{ $workPackageNumber->id }}">
                                                {{ $workPackageNumber->work_package_number }}</option>
                                            @endforeach
                                            {{-- @php
                                            $tot_row = count($workPackageNumbers);
                                            if($tot_row != 1) {
                                            @endphp
                                            <option selected disabled value="">Select one</option>
                                            @foreach($workPackageNumbers as $workPackageNumber)
                                            <option value="{{ $workPackageNumber->id }}">
                                            {{ $workPackageNumber->work_package_number }}</option>
                                            @endforeach
                                            @php } else { @endphp
                                            @foreach($workPackageNumbers as $workPackageNumber)
                                            <option value="{{ $workPackageNumber->id }}">
                                                {{ $workPackageNumber->work_package_number }}</option>
                                            @endforeach
                                            @php }
                                            @endphp --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="project_plan">Plan Title</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        {{-- select plan title  --}}
                                        <select class="form-control" name="project_plan" id="project_plan" required>
                                            <option selected disabled value="">Select one</option>
                                            @foreach($projectPlans as $projectPlan)
                                            <option value="{{ $projectPlan->id }}">
                                                {{ $projectPlan->plan_title }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="project_plan_details">Task Title</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        {{-- select task title  --}}
                                        <select class="form-control" name="project_plan_details"
                                            id="project_plan_details" required>
                                            <option selected disabled value="">Select one</option>
                                            @foreach($projectPlanDetails as $projectPlanDetail)
                                            <option value="{{ $projectPlanDetail->id }}">
                                                {{ $projectPlanDetail->project_task_details }}</option>
                                            @endforeach
                                            {{-- @php
                                            $tot_row = count($projectPlanDetails);
                                            if($tot_row != 1) {
                                            @endphp
                                            <option selected disabled value="">Select one</option>
                                            @foreach($projectPlanDetails as $projectPlanDetail)
                                            <option value="{{ $projectPlanDetail->id }}">
                                            {{ $projectPlanDetail->project_task_details }}</option>
                                            @endforeach
                                            @php } else { @endphp
                                            @foreach($projectPlanDetails as $projectPlanDetail)
                                            <option value="{{ $projectPlanDetail->id }}">
                                                {{ $projectPlanDetail->project_task_details }}</option>
                                            @endforeach
                                            @php }
                                            @endphp --}}
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{-- <div class="row clearfix" id="assigned-to">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="project_plan_details">Assigned To</label>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-8 col-xs-7"
                                style="padding: 6px 12px;text-transform: capitalize">
                                @if (count($allProPlan) != 0)
                                @foreach ($allProPlan[0] as $item)
                                @php
                                $name[] = $item->user->name;
                                @endphp
                                @endforeach
                                @php
                                echo implode(", ",$name);
                                @endphp
                                @endif
                            </div>
                        </div> --}}

                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="planned_hours">Planned Hours</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="number" id="planned_hours" class="form-control" placeholder=""
                                            name="planned_hours" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="planned_delivery_date">Planned Delivery Date</label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input readonly type="text" id="planned_delivery_date" class="form-control"
                                            placeholder="" name="planned_delivery_date" value="">
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
                                        <input type="date" id="task_start_date" class="form-control" placeholder=""
                                            name="task_start_date" value="">
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="task_start_date">Stop Date </label>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <input type="date" id="task_end_date" class="form-control" placeholder=""
                                            name="task_end_date" value="" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row clearfix">
                            <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                <label for="wbs_task_details">Actual Work
                                    <small>(weekending)</small></label>
                            </div>
                            <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                <div class="form-group">
                                    <div class="form-line">
                                        <textarea rows="5" placeholder="Write task details..." name="wbs_task_details"
                                            class="form-control" required></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row clearfix">
                            <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                <a href="{{ URL::previous() }}" style="text-decoration: none;">
                                    {{--   "{{route('dashboard')}}" --}}
                                    <button type="button" class="btn btn-danger m-t-15 waves-effect">CANCEL</button>
                                </a>
                                <button type="submit" class="btn btn-primary m-t-15 waves-effect">SUBMIT</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- #END# Tabs With Icon Title -->
</div>

@endsection

@push('js')
<script src="{{ asset('public/js/select2.min.js')}}"></script>
<script>
    @php
    echo "var projectPlanAraay = $projectPlans;";
    echo "var wpAraay = $workPackageNumbers;";
    echo "var projectPlanDetailAraay = $projectPlanDetails;";
    echo "var allSubtask = $all_subtask;";

    @endphp

    var filteredProjectPlanForSelectedAssignee = [];
    projectPlanDetailAraay = [...new Map(projectPlanDetailAraay.map(item => [item.id, item])).values()];

    function onlyUnique(value, index, self) {
        return self.indexOf(value) === index;
    }
    $(document).ready(function () {
        $(".js-example-basic-single").select2();
        // $('.select-multi').select2({
        //     placeholder: "You can select multiple employee",
        // });
        $(this).find("#planned_hours").prop("readonly", true);
        $(this).find("#planned_delivery_date").prop("readonly", true);
        var dtToday = new Date();
        var month = dtToday.getMonth() + 1;
        var day = dtToday.getDate();
        var year = dtToday.getFullYear();
        if (month < 10) month = '0' + month.toString();
        if (day < 10) day = '0' + day.toString();
        var minDate = year + '-' + month + '-' + day;
        $('#task_start_date').attr('min', minDate);
        $('#task_end_date').attr('min', minDate);
        $('#project_assignee_id').change(function () {
            // var projectPlanAraayFilter = $.grep(projectPlanAraay, function (e) {
            //     return e.project_plan_assignee == parseInt($('#project_assignee_id').val());
            // })

            var assignee_list;
            var projectPlanAraayFilter = [];
            var subTask_filter = [];
            var unique_subTask_filter;
            var projectPlan_filter = [];
            var unique_projectPlan;
            var assigneeProjects_wp = [];
            var assigneeProjects_subtask = [];
            projectPlanAraay = projectPlanAraay.filter(onlyUnique);
            projectPlanAraay = [...new Map(projectPlanAraay.map(item => [item.id, item])).values()];

            for (var i = 0; i < projectPlanAraay.length; i++) {
                // console.log(JSON.stringify(projectPlanAraay[i]));
                assignee_list = [];
                assignee_list = (projectPlanAraay[i].project_plan_assignee).split(",");
                // alert(projectPlanAraay.length +" :"+$('#project_assignee_id').val()+' assignee_list:'+assignee_list.length);

                assignee_list.forEach(ele => {
                    if (parseInt(ele) == parseInt($('#project_assignee_id').val())) {
                        // console.log('projectPlanAraayFilter: '+JSON.stringify(projectPlanAraay[i])+" ele:"+assignee_list.length);
                        projectPlanAraayFilter.push(projectPlanAraay[i]);
                        assigneeProjects_wp.push(projectPlanAraay[i]
                            .project_plan_work_package_id);
                    }
                })
            }
            assigneeProjects_wp = assigneeProjects_wp.filter(onlyUnique);

            if ($('#project_name').val() != '') {
                $("#project_name").find("option").remove();
                $("#project_name").append("<option selected disabled value=''>Select one</option>");

                for (var i = 0; i < assigneeProjects_wp.length; i++) {

                    for (var j = 0; j < wpAraay.length; j++) {
                        if (parseInt(wpAraay[j].id) == parseInt(assigneeProjects_wp[i])) {
                            assigneeProjects_subtask.push(wpAraay[j]);
                            // subTask_filter.push(wpAraay[j]);
                            // projectPlan_filter.push(projectPlanAraayFilter[i]);
                            break;
                        }
                    }
                }
                assigneeProjects_subtask.forEach(wp => {
                    allSubtask.forEach(task => {
                        if (parseInt(wp.work_package_subtask_id) == parseInt(task.id)) {
                            subTask_filter.push(task);
                        }
                    });
                });

                unique_subTask_filter = subTask_filter;
                //subTask_filter.filter(onlyUnique);
                unique_subTask_filter.forEach(element => {
                    //  console.log('subtask '+JSON.stringify(element));   

                    if (unique_subTask_filter.length == 1) {
                        $("#project_name").append('<option selected value="' +
                            element.id + '">' +
                            element.sub_task_name + '</option>');
                    } else {
                        $("#project_name").append('<option value="' + element.id + '">' +
                            element.sub_task_name + '</option>');
                    }


                    // var subTaskArrayFilter = $.grep(unique_subTask_filter, function (e) {
                    //     return e.work_package_subtask_id ==
                    //         parseInt($('#project_name').val());
                    // });
                    // console.log("subTaskArrayFilter: "+subTaskArrayFilter);

                    $("#work_package").find("option").remove();
                    $("#work_package").append(
                        "<option selected disabled value=''>Select one</option>");
                    if (assigneeProjects_subtask.length == 1) {
                        $("#work_package").append('<option selected value="' +
                            assigneeProjects_subtask[0].id + '">' +
                            assigneeProjects_subtask[0].work_package_number +
                            '</option>');
                    } else {
                        assigneeProjects_subtask.forEach(element => {
                            $("#work_package").append(
                                '<option value="' +
                                element.id + '">' +
                                element.work_package_number +
                                '</option>');
                        });
                    }


                    var filter_projectPlan = [];

                    projectPlanAraayFilter.forEach(element => {
                        if (element.project_plan_work_package_id == parseInt($(
                                '#work_package').val())) {
                            filter_projectPlan.push(element);
                        }
                    });
                    // var filter_projectPlan = $.grep(projectPlanAraay, function (e) {
                    //     return e.project_plan_work_package_id ==
                    //         parseInt($('#work_package').val());
                    // });
                    $("#planned_hours").val('');
                    $("#planned_delivery_date").val('');
                    $('#task_start_date').val('');
                    $('#task_end_date').val('');
                    $("#project_plan").find("option").remove();
                    $("#project_plan").append(
                        "<option selected disabled value=''>Select one</option>");
                    // alert('filter_projectPlan.length: '+filter_projectPlan.length);

                    if (filter_projectPlan.length == 1) {
                        $("#project_plan").append('<option selected value="' +
                            filter_projectPlan[0].id + '">' +
                            filter_projectPlan[0].plan_title + '</option>');
                        // console.log('date: '+minDate+" max: "+filter_projectPlan[0].planned_delivery_date);//planned_hours,planned_delivery_date

                            $("#planned_hours").val(filter_projectPlan[0].planned_hours);
                            $("#planned_delivery_date").val(filter_projectPlan[0].planned_delivery_date);
                            $('#task_start_date').attr('max', filter_projectPlan[0].planned_delivery_date);
                            $('#task_end_date').attr('max', filter_projectPlan[0].planned_delivery_date);
                    } else {
                        filter_projectPlan.forEach(element => {
                            $("#project_plan").append('<option value="' + element.id +
                                '">' +
                                element.plan_title + '</option>');
                            // $("#planned_hours").val(element.planned_hours);
                            // $("#planned_delivery_date").val(element.planned_delivery_date);
                        });
                    }

                    var projectPlanDetailAraayFilter = $.grep(projectPlanDetailAraay, function (
                        e) {
                        return e.project_plan_project_plan_id == parseInt($(
                            '#project_plan').val());
                    });
                    $("#project_plan_details").find("option").remove();
                    $("#project_plan_details").append(
                        "<option selected disabled value=''>Select one</option>");
                    projectPlanDetailAraayFilter.forEach(pp_details => {
                        $("#project_plan_details").append('<option selected value="' +
                            pp_details.id + '">' +
                            pp_details.project_task_details + '</option>');
                    });


                });


                if (projectPlanAraayFilter.length === 0) {
                    $("#work_package").find("option").remove();
                    $("#work_package").append("<option selected disabled value=''>Select one</option>");
                    $("#project_plan").find("option").remove();
                    $("#project_plan").append("<option selected disabled value=''>Select one</option>");
                    $("#project_plan_details").find("option").remove();
                    $("#project_plan_details").append(
                        "<option selected disabled value=''>Select one</option>");
                }
            }
        });

        $('#project_name').change(function () {
            @php
            echo "var wpAraay = $workPackageNumbers;";
            @endphp
            var unique_subTaskArrayFilter;
            var subTaskArrayFilter = $.grep(wpAraay, function (e) {
                return e.work_package_subtask_id == parseInt($('#project_name').val());
            });

            // make subTaskArrayFilter unique
            var arrayUniqueByKey = [...new Map(subTaskArrayFilter.map(item => [item.id, item]))
                .values()
            ];
            // filter wp based on project name(subtask)
            if ($('#work_package').val() != '') {
                $("#work_package").find("option").remove();
                $("#work_package").append("<option selected disabled value=''>Select one</option>");
                if (arrayUniqueByKey.length == 1) {
                    $("#work_package").append('<option selected value="' + arrayUniqueByKey[0].id +
                        '">' +
                        arrayUniqueByKey[0].work_package_number + '</option>');
                } else {
                    arrayUniqueByKey.forEach(element => {
                        //console.log('wp for sub task: '+element);
                        $("#work_package").append('<option value="' + element.id + '">' +
                            element.work_package_number + '</option>');
                    });
                }
            }

            var assignee_list;
            var projectPlanAraayFilter = [];
            for (var i = 0; i < projectPlanAraay.length; i++) {
                assignee_list = (projectPlanAraay[i].project_plan_assignee).split(",");
                assignee_list.forEach(ele => {
                    if (parseInt(ele) == parseInt($('#project_assignee_id').val()) &&
                        parseInt(projectPlanAraay[i].project_plan_work_package_id) == parseInt(
                            $('#work_package').val())) {
                        projectPlanAraayFilter.push(projectPlanAraay[i]);
                        // console.log('projectPlanAraayFilter '+JSON.stringify(projectPlanAraay[i]));
                    }
                })
            }
            // console.log('projectPlanAraayFilter length: '+projectPlanAraayFilter.length);

            // filter project plan based on wp
            if ($('#project_plan').val() != '') {
                $("#planned_hours").val('');
                $("#planned_delivery_date").val('');
                $("#task_start_date").val('');
                $("#task_end_date").val('');
                $("#project_plan").find("option").remove();
                $("#project_plan").append("<option selected disabled value=''>Select one</option>");
                if (projectPlanAraayFilter.length == 1) {
                    $("#project_plan").append('<option selected value="' + projectPlanAraayFilter[0]
                        .id +
                        '">' +
                        projectPlanAraayFilter[0].plan_title + '</option>');
                        $("#planned_hours").val(projectPlanAraayFilter[0].planned_hours);
                        $("#planned_delivery_date").val(projectPlanAraayFilter[0].planned_delivery_date);
                        $('#task_start_date').attr('max', projectPlanAraayFilter[0].planned_delivery_date);
                        $('#task_end_date').attr('max', projectPlanAraayFilter[0].planned_delivery_date);
                } else {
                    projectPlanAraayFilter.forEach(element => {
                        $("#project_plan").append('<option value="' + element.id + '">' +
                            element.plan_title + '</option>');
                        // $("#planned_hours").val(element.planned_hours);
                        // $("#planned_delivery_date").val(element.planned_delivery_date);
                    });
                }

            }

            // filter plan details based on project plan 
            var projectPlanDetailAraayFilter = $.grep(projectPlanDetailAraay, function (e) {
                return e.project_plan_project_plan_id == parseInt($('#project_plan').val());
            });
            if ($('#project_plan_details').val() != '') {
                $("#project_plan_details").find("option").remove();
                $("#project_plan_details").append(
                    "<option selected disabled value=''>Select one</option>");

                if (projectPlanDetailAraayFilter.length == 1) {
                    $("#project_plan_details").append('<option selected value="' +
                        projectPlanDetailAraayFilter[0].id + '">' +
                        projectPlanDetailAraayFilter[0].project_task_details + '</option>');
                } else {
                    projectPlanDetailAraayFilter.forEach(element => {
                        $("#project_plan_details").append('<option value="' + element.id +
                            '">' +
                            element.project_task_details + '</option>');
                    });
                }
                // projectPlanDetailAraayFilter.forEach(element => {
                //     // console.log('projectPlanDetailAraayFilter '+JSON.stringify(element));
                //     if (projectPlanDetailAraayFilter.length == 1) {
                //         $("#project_plan_details").append('<option selected value="' + element
                //             .id + '">' +
                //             element.project_task_details + '</option>');
                //     } else {
                //         $("#project_plan_details").append('<option value="' + element.id +
                //             '">' +
                //             element.project_task_details + '</option>');
                //     }
                // });
            }


        });
        $('#work_package').change(function () {
            // @php
            // echo "var projectPlanAraay = $projectPlans;";
            // @endphp
            // console.log('wp change:' + projectPlanAraay);
            var assignee_list;
            var projectPlanAraayFilter = [];
            for (var i = 0; i < projectPlanAraay.length; i++) {
                assignee_list = (projectPlanAraay[i].project_plan_assignee).split(",");
                assignee_list.forEach(ele => {
                    if (parseInt(ele) == parseInt($('#project_assignee_id').val())) {
                        projectPlanAraayFilter.push(projectPlanAraay[i]);
                    }
                })
            }
            // var projectPlanAraayFilter = $.grep(projectPlanAraay, function (e) {
            //     return e.project_plan_assignee == parseInt($('#project_assignee_id').val());
            // })
            if ($('#project_plan').val() != '') {
                $("#planned_hours").val('');
                $("#planned_delivery_date").val('');
                $("#task_start_date").val('');
                $("#task_end_date").val('');
                $("#project_plan").find("option").remove();
                $("#project_plan").append("<option selected disabled value=''>Select one</option>");
                projectPlanAraayFilter.forEach(element => {
                    if (projectPlanAraayFilter.length == 1) {
                        $("#project_plan").append('<option selected value="' + element.id +
                            '">' +
                            element.plan_title + '</option>');
                        $("#planned_hours").val(element.planned_hours);
                        $("#planned_delivery_date").val(element.planned_delivery_date);
                        $('#task_start_date').attr('max', element.planned_delivery_date);
                        $('#task_end_date').attr('max', element.planned_delivery_date);
                    } else {
                        $("#project_plan").append('<option value="' + element.id + '">' +
                            element.plan_title + '</option>');
                    }
                   

                });
            }
        });
        $('#project_plan').change(function () {
            // @php
            // echo "var projectPlanDetailAraay = $projectPlanDetails;";
            // @endphp
            // console.log(projectPlanDetailAraay);
            $("#planned_hours").val('');
            $("#planned_delivery_date").val('');
            $("#task_start_date").val('');
            $("#task_end_date").val('');
            projectPlanAraay.forEach(element => {
                if(element.id == parseInt($('#project_plan').val())){
                    $("#planned_hours").val(element.planned_hours);
                    $("#planned_delivery_date").val(element.planned_delivery_date);
                    $('#task_start_date').attr('max', element.planned_delivery_date);
                    $('#task_end_date').attr('max', element.planned_delivery_date);
                }
            });

            var projectPlanDetailAraayFilter = $.grep(projectPlanDetailAraay, function (e) {
                return e.project_plan_project_plan_id == parseInt($('#project_plan').val());
            });

            if ($('#project_plan_details').val() != '') {
                $("#project_plan_details").find("option").remove();
                $("#project_plan_details").append(
                    "<option selected disabled value=''>Select one</option>");
                projectPlanDetailAraayFilter.forEach(element => {
                    if (projectPlanDetailAraayFilter.length == 1) {
                        $("#project_plan_details").append('<option selected value="' + element
                            .id + '">' +
                            element.project_task_details + '</option>');
                    } else {
                        $("#project_plan_details").append('<option value="' + element.id +
                            '">' +
                            element.project_task_details + '</option>');
                    }
                });
            }
            $("#assigned-to").css("display", "block");
        });
    });

</script>
@endpush

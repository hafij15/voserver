@extends('layouts.backend.app')

@section('title','CREATE PROJECT')

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

    #infoWpInput {
        position: absolute;
        top: 34px;
        cursor: pointer;
        left: 201px;
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
                        Create Project
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <form method="POST" action="{{ route('projects.store') }}" class="form-horizontal"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="tdo">Task and Delivery Order </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {{-- select TDO --}}
                                                    <select class="form-control" name="tdo_select" id="tdo"
                                                        onchange="getTdoId();" required>
                                                        @php
                                                        $tot_row = count($tdos);
                                                        @endphp
                                                        @if($tot_row != 1){
                                                        <option selected disabled value="">Select one</option>
                                                        <option value="New">New TDO</option>
                                                        @foreach($tdos as $tdo)
                                                        <option value="{{ $tdo->id }}">{{ $tdo->title }}</option>
                                                        @endforeach
                                                        }
                                                        @else
                                                        {
                                                        @foreach($tdos as $tdo)
                                                        <option value="{{ $tdo->id }}">{{ $tdo->title }}</option>
                                                        @endforeach
                                                        <option value="New">New TDO</option>
                                                        }
                                                        @endif
                                                    </select>


                                                    {{-- input TDO --}}
                                                    <div id="tdoInputDiv">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type Task and Delivery Order" name="tdo_input"
                                                            id="tdo_input" value="">
                                                        <span class="material-icons" id="closeTdoInput">
                                                            highlight_off
                                                        </span>
                                                    </div>
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
                                                    <textarea rows="5" id="tdo_details"
                                                        placeholder="Write TDo details..." name="tdo_details"
                                                        class="form-control" required></textarea>
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
                                                    <select class="form-control" name="project_name_select"
                                                        id="project_name" required>
                                                        @php
                                                        $tot_row = count($subTasks);
                                                        @endphp
                                                        @if($tot_row != 1){
                                                        <option selected disabled value="">Select one</option>
                                                        <option value="New">New Project</option>
                                                        @foreach($subTasks as $subTask)
                                                        <option value="{{ $subTask->id }}">
                                                            {{ $subTask->sub_task_name }}</option>
                                                        @endforeach
                                                        } @else
                                                        {
                                                        @foreach($subTasks as $subTask)
                                                        <option value="{{ $subTask->id }}">
                                                            {{ $subTask->sub_task_name }}</option>
                                                        @endforeach
                                                        <option value="New">New Project</option>
                                                        }
                                                        @endif
                                                    </select>
                                                    {{-- input sub task --}}
                                                    <div id="projectInputDiv">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type Project Name" name="project_name_input"
                                                            id="project_name_input" value="">
                                                        <span class="material-icons" id="closeProjectInput">
                                                            highlight_off
                                                        </span>
                                                    </div>
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
                                                    <select class="form-control" name="work_package_select"
                                                        id="work_package" required>
                                                        @php
                                                        $tot_row = count($workPackages);
                                                        @endphp
                                                        @if($tot_row != 1){
                                                        <option selected disabled value="">Select one</option>
                                                        <option value="New">New Work Package</option>
                                                        @foreach($workPackages as $workPackage)
                                                        <option value="{{ $workPackage->id }}">
                                                            {{ $workPackage->work_package_number }}</option>
                                                        @endforeach
                                                        } @else {
                                                        @foreach($workPackages as $workPackage)
                                                        <option value="{{ $workPackage->id }}">
                                                            {{ $workPackage->work_package_number }}</option>
                                                        @endforeach
                                                        <option value="New">New Work Package</option>
                                                        }
                                                        @endif
                                                    </select>
                                                    {{-- input work packagwe --}}
                                                    <div id="wpInputDiv">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type work package number"
                                                            name="work_package_input" id="work_package_input" value=""
                                                            min="100" step="any" required onkeypress="return isNumberKey(event,this)">
                                                        <span class="material-icons" id="closeWpInput">
                                                            highlight_off
                                                        </span>

                                                    </div>
                                                </div>
                                            </div>
                                            <small class="custom-alert" id="work_package_alert">The number must be
                                                unique! <span class="material-icons tooltip-r" id="infoWpInput" data-toggle="tooltip" data-placement="left" title="Download WP List">
                                                    info
                                                </span></small>

                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="project_plan">Task Title</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {{-- select plan title  --}}
                                                    <select class="form-control" name="project_plan_select"
                                                        id="project_plan" required>
                                                        @php
                                                        $tot_row = count($projectPlans);
                                                        @endphp
                                                        @if($tot_row != 1){

                                                        <option selected disabled value="">Select one</option>
                                                        <option value="New">New Task Title</option>
                                                        @foreach($projectPlans as $projectPlan)
                                                        <option value="{{ $projectPlan->id }}">
                                                            {{ $projectPlan->plan_title }}</option>
                                                        @endforeach
                                                        } @else {
                                                        @foreach($projectPlans as $projectPlan)
                                                        <option value="{{ $projectPlan->id }}">
                                                            {{ $projectPlan->plan_title }}</option>
                                                        @endforeach
                                                        <option value="New">New Task Title</option>
                                                        }
                                                        @endif
                                                    </select>
                                                    {{-- input sub task --}}
                                                    <div id="planTitleInputDiv">
                                                        <input type="text" class="form-control"
                                                            placeholder="Type Task Title" name="project_plan_input"
                                                            id="project_plan_input" value="">
                                                        <span class="material-icons" id="closePlanInput">
                                                            highlight_off
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="task_details">Task Details</label>
                                        </div>
                                        <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" id="task_details" class="form-control"
                                                        placeholder="Type task details" name="task_details" value=""
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="planned_ep">Planned EP <small>(Estimated Person)</small></label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" id="planned_ep" class="form-control"
                                                        placeholder="" name="planned_ep" value="" step=".1" min="0.1"
                                                        required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="planned_labor_hrs">Planned Labor Hours</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="number" id="planned_labor_hrs" class="form-control"
                                                        placeholder="" name="planned_labor_hrs" value="" step=".1"
                                                        min=".1" required>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="planned_due_date">Planned Delivery Date</label>
                                        </div>
                                        <div class="col-lg-2 col-md-2 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="date" id="planned_due_date" class="form-control"
                                                        placeholder="" name="planned_due_date" value="" required>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="assignee">Assignee</label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {{-- select assignee --}}
                                                    <select class="js-example-basic-multiple" name="assignee[]"
                                                        id="assignee" multiple="multiple" required>
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
                                    {{-- <div class="row clearfix">
                                    </div> --}}
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="project_doc">Project Document </label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="file" id="project_doc" class="form-control"
                                                        name="project_doc[]" value="" multiple>
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
                                                id="SaveBtn">SUBMIT</button>
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
    <script src="{{ asset('public/js/FileSaver.js')}}"></script>
    <script>
        @php
        echo "var tdos = $tdos;";
        @endphp
        $(document).ready(function () {
            var baseUrl = window.location.href.split("/projects/");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
                }
            });
            // get all work package number from DB and make array
            @php 
                echo "var workPackages = $workPackages;";//projectPlans
                echo "var projectPlans = $projectPlans;";
            @endphp
            projectPlans = [...new Map(projectPlans.map(item => [item.id, item])).values()];

            var wPackageArray = [];
            workPackages.forEach(element => {
                wPackageArray.push(element.work_package_number);
            });

            var projectPlanArray = [];
            
            $('.support_engg').select2({
                placeholder: "Enter support employee",
                multiple: true,
            });
            // selected wp number
            var selected_wp_number;
            // toggle input for new TDO add 
            $("#tdo").on("change", function () {
                if ($("#tdo").val() == "New") {
                    $("#tdoInputDiv").css("display", "block");
                    $("#tdo").css("display", "none");
                    $("#tdo_input").prop('required', true);
                } else {
                    $("#tdo_input").removeAttr('required');
                }
            });
            $("#closeTdoInput").on("click", function () {
                $("#tdo").prop('selectedIndex', 0);
                $("#tdoInputDiv").css("display", "none");
                $("#tdo").css("display", "block");
            });
            // toggle input for new sub task add
            $("#project_name").on("change", function () {
                if ($("#project_name").val() == "New") {
                    $("#projectInputDiv").css("display", "block");
                    $("#project_name").css("display", "none");
                    $("#project_name_input").prop('required', true);
                } else {
                    $("#project_name_input").removeAttr('required');
                }
            });
            $("#closeProjectInput").on("click", function () {
                $("#project_name").prop('selectedIndex', 0);
                $("#projectInputDiv").css("display", "none");
                $("#project_name").css("display", "block");
            });
            // toggle input for new plan title add
            $("#project_plan").on("change", function () {
                if ($("#project_plan").val() == "New") {
                    $("#planTitleInputDiv").css("display", "block");
                    $("#project_plan").css("display", "none");
                    $("#project_plan_input").prop('required', true);
                    $("#project_plan").prop('required', false);
                } else {
                    $("#project_plan_input").removeAttr('required');
                    $("#project_plan").prop('required', true);  
                }
            });
            $("#closePlanInput").on("click", function () {
                // console.log('work_package_input: '+$("#work_package_input").val() +" pre val: "+selected_wp_number);
                $("#project_plan").prop('selectedIndex', 0);
                $("#planTitleInputDiv").css("display", "none");
                $("#project_plan").css("display", "block");
                $("#work_package_input").val(selected_wp_number);
                $("#work_package_alert").text("The number must be unique! ");
                $("#work_package_alert").append('<span class="material-icons tooltip-r" id="infoWpInput"  data-toggle="tooltip" data-placement="left" title="Download WP List"> info</span>');
                $("#work_package_alert").css("color", "red");
            });
            // toggle input for new work package number add
            $("#work_package").on("change", function () {
                if ($("#work_package").val() == "New") {
                    $("#wpInputDiv").css("display", "block");
                    $("#work_package_alert").css("display", "block");
                    $("#work_package").css("display", "none");
                    $("#work_package").removeAttr('required');
                    $("#work_package_input").prop('required', true);
                } else {
                    $("#work_package_input").removeAttr('required');
                    $("#work_package").prop('required', true);

                }
            });
            $("#closeWpInput").on("click", function () {
                $("#work_package").prop('selectedIndex', 0);
                $("#wpInputDiv").css("display", "none");
                $("#work_package_alert").css("display", "none");
                $("#work_package").css("display", "block");
                $("#work_package_input").val('');
                $("#work_package_alert").text("The number must be unique! ");
                $("#work_package_alert").append('<span class="material-icons tooltip-r" id="infoWpInput"  data-toggle="tooltip" data-placement="left" title="Download WP List"> info</span>');
                $("#work_package_alert").css("color", "red");
                // console.log( "visible:_____"+$("#project_plan_input").is(':visible'));
                if($("#project_plan_input").is(':visible')){
                  $("#planTitleInputDiv").css("display", "none");
                  $("#project_plan").css("display", "block");
                  $("#project_plan_input").prop('required', false);  
                  $("#project_plan").prop('required', true); 
                  $("#project_plan").val('');
                }
            });
            // work package number existance check in DB
            $("#work_package_input").bind("keyup change", function () {
                var first_chr = $("#work_package_input").val().charAt(0);
                if(first_chr == '.'){
                $("#work_package_input").val($("#work_package_input").val().substring(1)); 
                }
                // remove repeated dots
                var repeated = $("#work_package_input").val().replace(/(\.)\1+/g, '$1');
                $("#work_package_input").val(repeated); 

                var inter_wp = $("#work_package_input").val().includes('.');
                if(inter_wp){
                  $("#planTitleInputDiv").css("display", "block");
                  $("#project_plan").css("display", "none");
                  $("#project_plan_input").prop('required', true);  
                  $("#project_plan").prop('required', false);
                  $("#project_plan").val("New"); 
                }else{
                  $("#planTitleInputDiv").css("display", "none");
                  $("#project_plan").css("display", "block");
                  $("#project_plan_input").prop('required', false);  
                  $("#project_plan").prop('required', true); 
                  $("#project_plan").val(''); 
                //   console.log('project_plan:'+$("#project_plan").val() );
                }
                var last_char = ($("#work_package_input").val()[$("#work_package_input").val().length-1] === ".");
                var check_exist = '';
                if(last_char){
                    check_exist = $("#work_package_input").val().slice(0,-1);
                }else{
                    check_exist = $("#work_package_input").val();
                }
                // console.log("check_exist: "+check_exist);
                var exist = wPackageArray.includes(check_exist);
                var check_min_value = check_exist.split('.');
                console.log("check_exist: "+check_exist + ",check_min_value: "+check_min_value[0]);

                if (exist) {
                    $("#work_package_alert").text("This number is already taken!");
                    $("#work_package_alert").css("color", "red");
                } else if (!exist && $("#work_package_input").val() !== '' && 
                  check_min_value[0] >= 100) {
                    $("#work_package_alert").text("This number is available!");
                    $("#work_package_alert").css("color", "green");
                } else if ($("#work_package_input").val() == '' || check_min_value[0] <=
                    99) {
                    $("#work_package_alert").text("the number must be unique and greater than 99!");
                    $("#work_package_alert").css("color", "red");
                }else{
                console.log("else check_exist: ");

                }
                $("#work_package").val("New");
               
                
                // 
            });
            $('#tdo').change(function () {
                @php
                echo "var subTaskAraay = $subTasks;";
                @endphp
                var subTaskArrayFilter = $.grep(subTaskAraay, function (e) {
                    return e.subtask_tdo_id == parseInt($('#tdo').val());
                });
                if ($('#tdo').val() != '') {
                    $("#project_name").find("option").remove();
                    $("#project_name").append("<option selected disabled value=''>Select one</option>");
                    $("#project_name").append("<option value='New'>New Project</option>");
                    subTaskArrayFilter.forEach(element => {
                        $("#project_name").append('<option value="' + element.id + '">' +
                            element.sub_task_name + '</option>')
                    });
                }
            });
            $('#project_name').change(function () {
                @php
                echo "var wpAraay = $workPackages;";
                @endphp
                var subTaskArrayFilter = $.grep(wpAraay, function (e) {
                    return e.work_package_subtask_id == parseInt($('#project_name').val());
                });
                if ($('#work_package').val() != '') {
                    $("#work_package").find("option").remove();
                    $("#work_package").append("<option selected disabled value=''>Select one</option>");
                    $("#work_package").append(
                        "<option value='New'>New Work Package</option>");
                    subTaskArrayFilter.forEach(element => {
                        if(subTaskArrayFilter.length == 1){
                            //  $("#work_package").append('<option selected value="' + element.id + '">' +
                            // element.work_package_number + '</option>');
                            // input decimal wp
                            $("#wpInputDiv").css("display", "block");
                            $("#work_package_alert").css("display", "block");
                            $("#work_package").css("display", "none");
                            $("#work_package").removeAttr('required');
                            $("#work_package_input").prop('required', true);
                            $("#work_package_input").val(element.work_package_number);
                             selected_wp_number = $("#work_package_input").val();
                       
                        }else{
                             $("#work_package").append('<option value="' + element.id + '">' +
                            element.work_package_number + '</option>');
                        }
                    });
                    projectPlanAraay = $.grep(projectPlans, function (e) {
                     return e.project_plan_work_package_id == parseInt($('#work_package').val());
                    });
                    if ($('#project_plan').val() != '') {
                        $("#project_plan").find("option").remove();
                        $("#project_plan").append("<option selected disabled value=''>Select one</option>");
                        $("#project_plan").append("<option value='New'>New Task Title</option>");

                        if(projectPlanAraay.length == 1){
                            $("#project_plan").append('<option selected value="' + projectPlanAraay[0].id + '">' +
                            projectPlanAraay[0].plan_title + '</option>')
                        }else{
                            projectPlanAraay.forEach(element => {
                                $("#project_plan").append('<option value="' + element.id + '">' +
                                element.plan_title + '</option>')
                            });
                        }
                        

                    }
                   
                  
                }
            });
            $('#work_package').change(function () {
                // @php
                // echo "var projectPlanAraay = $projectPlans;";
                // @endphp
               
                var projectPlanAraayFilter = $.grep(projectPlans, function (e) {
                    return e.project_plan_work_package_id == parseInt($('#work_package').val());
                })
                if ($('#project_plan').val() != '') {
                    $("#project_plan").find("option").remove();
                    $("#project_plan").append("<option selected disabled value=''>Select one</option>");
                    $("#project_plan").append(
                        "<option value='New'>New Task Title</option>");
                    if(projectPlanAraayFilter.length == 1){
                        $("#project_plan").append('<option selected value="' + projectPlanAraayFilter[0].id + '">' +
                            projectPlanAraayFilter[0].plan_title + '</option>')
                    }else{
                        projectPlanAraayFilter.forEach(element => {
                        $("#project_plan").append('<option value="' + element.id + '">' +
                            element.plan_title + '</option>')
                    });
                    }
                    
                }
            });
        });
        // $('#infoWpInput').on("click", function () {
       $(document).on("click", "#infoWpInput", function() {
            @php
            echo "var wpAraay = $workPackages;";
            echo "var subTaskAraay = $subTasks;";
            @endphp
            if (!!wpAraay) {
                var sname = "Used package numbers\n";
                var list = '';
                wpAraay.forEach(wp => {
                    // console.log(wp);
                    subTaskAraay.forEach(subTask_item => {
                        if (wp.work_package_subtask_id == subTask_item.id) {
                            list = list + subTask_item.sub_task_name + " : " + wp
                                .work_package_number + "\n";
                        }
                    });
                });
                //  alert(sname + list);
                 var blob = new Blob([sname + list],
                { type: "text/plain;charset=utf-8" });
                saveAs(blob, "Used_WorkPackage.txt");
                    // console.log(sname + list);
            } else {
                alert('No package used!!!');
                
            }
        });
   

        function getTdoId() {
            var count = Object.keys(tdos).length;
            if ($('#tdo').val() != 'New') {
                for (var i = 0; i < count; i++) {
                    if ($('#tdo').val() == tdos[i].id) {
                        $('#tdo_details').val(tdos[i].details);
                    }
                }
            } else {
                $('#tdo_details').val('');
            }
        }
        // allow wp pattern(1000.1.1)
        function isNumberKey(evt, element) {
        var charCode = (evt.which) ? evt.which : event.keyCode;
        if (charCode > 31 && (charCode < 48 || charCode> 57) && !(charCode == 46 || charCode == 8))
            return false;
            else {
            var len = $(element).val().length;
            var index = $(element).val().indexOf('.');
            $(element).val().replace(/(?:\.){2,}/g, '\.');
            // not sure
            // if (index > 4 && charCode == 46) {
            // return false;
            // }
            // if (index > 0) {
            // var CharAfterdot = (len + 1) - index;
            // if (CharAfterdot > 3) {
            // return false;
            // }
            // }
            }
            return true;
            }
    </script>
    <script type="text/javascript">
        $(".js-example-basic-multiple").select2();

    </script>
    @endpush

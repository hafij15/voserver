@extends('layouts.backend.app')

@section('title','MEETING HISTORY')

@push('css')
<link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"
    rel="stylesheet">
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
                        Meeting History
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <div class="row clearfix">
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                        <div class="table-responsive">
                                            <table
                                                class="table table-bordered table-striped table-hover dataTable js-exportable">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Project Name</th>
                                                        {{-- <th>PM</th> --}}
                                                        <th>Agenda</th>
                                                        <th>Meeting Date/Time</th>
                                                        {{-- <th>Status</th> --}}
                                                        <th>Duration</th>
                                                        <th>Meeting Minutes</th>
                                                        <th>Session Recording</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($meetings as $item)
                                                    @php
                                                    $record = getMeetingRecordings($item->subtask_id);
                                                    @endphp
                                                    <tr>
                                                        <td>{{$item->id}}</td>
                                                        <td>
                                                            @if ($item->subTask != null)
                                                                {{$item->subTask->sub_task_name}}
                                                            @else
                                                                General Meeting
                                                            @endif
                                                        </td>
                                                        <td>{{$item->agenda}}</td>
                                                        <td>{{ date('Y-m-d', strtotime($item->meeting_dateTime)) }}
                                                            <br>
                                                            <strong>{{ date('h:i A', strtotime($item->meeting_dateTime)) }}</strong>
                                                        </td>
                                                        {{-- <td>
                                                            @if ($item->isServiced == 0)
                                                                Incomplete
                                                            @else
                                                                Completed
                                                            @endif
                                                        </td> --}}
                                                        <td>{{$item->spent_hour}}</td>
                                                        <td>{{$item->meeting_mins}}</td>
                                                        <td>
                                                            @foreach ($record as $data)
                                                            @if (date('Y-m-d',
                                                            strtotime($data->created_at)) ==
                                                            date('Y-m-d',
                                                            strtotime($item->meeting_dateTime)) )
                                                            <a id="downloadRecordedFile"
                                                                href="file/download/{{$data->id}}">{{$data->title}}</a>
                                                            @endif
                                                            @endforeach
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
        <!-- #END# Tabs With Icon Title -->
    </div>

    @endsection

    @push('js')
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/jquery.dataTables.js')}}">
    </script>
    <script
        src="{{ asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}">
    </script>
    <script
        src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}">
    </script>
    <script src="{{ asset('public/assets/backend/js/pages/tables/jquery-datatable.js')}}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> --}}
    <script src="{{ asset('public/js/select2.min.js')}}"></script>
    <script>
        $(document).ready(function () { });

    </script>
    @endpush

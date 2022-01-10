@extends('layouts.backend.app')

@section('title','Create Designation')

@push('css')
<link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}"
    rel="stylesheet">
<style>
    table,
    th {
        font-size: small;
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

    .modal-holder .body {
        padding: 0;
    }

    .modal-holder .card {
        min-height: 0;
    }

</style>
@endpush

@section('content')

<div class="container-fluid">
    <!-- Tabs With Icon Title -->
    <a type="button" data-toggle="modal" data-target="#addDesignation"
        style="margin-bottom:15px; background:#ff4187 !important;" class="btn btn-primary waves-effect">
        <i class="material-icons">add</i>
        <span>Add Designation</span>
    </a>
    <div class="card">
        <div class="header">
            <h2>ALL DESIGNATIONS</h2>
        </div>
        <div class="body">
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Designation Name</th>
                                    <th>Company</th>
                                    <th>Description</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($designations as $key=>$designation)
                                <tr>
                                    <td>{{$key + 1}}</td>
                                    <td>{{$designation->name}}</td>
                                    <td>{{$designation->company_info->name}}</td>
                                    <td>{{$designation->description}}</td>
                                    <td>
                                        <a type="button" data-toggle="modal" data-target="#editDesignation"
                                            class="btn btn-info waves-effect" data-designation="{{$designation}}">
                                            <i class="material-icons">edit</i>
                                        </a>
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
    <div class="modal-holder">
        <div class="card">
            <div class="body">
                <div class="modal" id="addDesignation" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Create a designation</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('designation.store') }}" class="form-horizontal"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="name">Name </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control"
                                                        placeholder="Type designation name" name="name" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="address">Select Company </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                <select class="form-control" name="company_id" id="company_id" required>
                                                        @php
                                                        $tot_row = count($company_name);
                                                        @endphp
                                                        @if($tot_row != 1){
                                                        <option selected disabled value="">Select one</option>
                                                        @foreach($company_name as $company_name_val)
                                                        <option value="{{ $company_name_val->id }}">
                                                            {{ $company_name_val->name }})</option>
                                                        @endforeach
                                                        } @else {
                                                        @foreach($company_name as $company_name_val)
                                                        <option value="{{ $company_name_val->id }}">
                                                            {{ $company_name_val->name }})</option>
                                                        @endforeach
                                                        }
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="tdo">Description </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea type="text" class="form-control"
                                                        placeholder="Type designation description" name="description"
                                                        value=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                id="SaveBtn">SAVE</button>
                                        </div>
                                    </div>
                                </form>
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
    <div class="modal-holder">
        <div class="card">
            <div class="body">
                <div class="modal" id="editDesignation" tabindex="-1" role="dialog">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit company</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form method="POST" action="{{ route('designation.update', 'edit') }}"
                                    class="form-horizontal" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <input type="number" hidden name="edit_id" id="edit-id">
                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="edit-name">Name </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="text" class="form-control" id="edit-name"
                                                        placeholder="Type company name" name="edit_name" value="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="address">Select Company </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                <select class="form-control" name="company_id" id="edit-company" required>
                                                        @php
                                                        $tot_row = count($company_name);
                                                        @endphp
                                                        @if($tot_row != 1){
                                                        <option selected disabled value="">Select one</option>
                                                        @foreach($company_name as $company_name_val)
                                                        <option value="{{ $company_name_val->id }}">
                                                            {{ $company_name_val->name }})</option>
                                                        @endforeach
                                                        } @else {
                                                        @foreach($company_name as $company_name_val)
                                                        <option value="{{ $company_name_val->id }}">
                                                            {{ $company_name_val->name }})</option>
                                                        @endforeach
                                                        }
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-5 form-control-label">
                                            <label for="edit-description">Description </label>
                                        </div>
                                        <div class="col-lg-9 col-md-9 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <textarea type="text" class="form-control" id="edit-description"
                                                        placeholder="Type company description" name="edit_description"
                                                        value=""></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row clearfix">
                                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <button type="submit" class="btn btn-primary m-t-15 waves-effect"
                                                id="SaveBtn">UPDATE</button>
                                        </div>
                                    </div>
                                </form>
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
</div>

@endsection

@push('js')
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}">
</script>
<script src="{{ asset('public/assets/backend/js/pages/tables/jquery-datatable.js')}}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> --}}
<script src="{{ asset('public/assets/backend/js/npmsweetalert2at8.js')}}"></script>
<script>
    $(document).ready(function () {
        $('#editDesignation').on('show.bs.modal', function (e) {
            // console.log(JSON.parse([e.relatedTarget.dataset["designation"]]));
            let data = JSON.parse([e.relatedTarget.dataset["designation"]]);
            $(this).find("#edit-id").val(data.id);
            $(this).find("#edit-name").val(data.name);
            $(this).find("#edit-company").val(data.company_info.id);
            $(this).find("#edit-description").val(data.description);
        })
    })

</script>

@endpush
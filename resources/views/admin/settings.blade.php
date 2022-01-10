@extends('layouts.backend.app')

@section('title','Settings')

@push('css')
<style type="text/css">
    .img-holder {
        position: relative;
    }

    .form-check-input {
        position: absolute;
        top: 10px;
        left: unset !important;
        right: 10px;
        opacity: 1 !important;
        z-index: 9999;
    }

    @media screen and (min-width: 300px) and (max-width: 575px) {
        #blah {
            max-width: 100% !important;
        }
    }

    #blah {
        max-width: 150px;
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
                        SETTINGS
                    </h2>
                    {{-- <ul class="header-dropdown m-r--5">
                        <li class="dropdown">
                            <a href="javascript:void(0);" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                                <i class="material-icons">more_vert</i>
                            </a>
                            <ul class="dropdown-menu pull-right">
                                <li><a href="javascript:void(0);">Action</a></li>
                                <li><a href="javascript:void(0);">Another action</a></li>
                                <li><a href="javascript:void(0);">Something else here</a></li>
                            </ul>
                        </li>
                    </ul> --}}
                </div>
                <div class="body">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#profile_with_icon_title" data-toggle="tab">
                                <i class="material-icons">face</i> UPDATE PROFILE
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#settings_with_icon_title" data-toggle="tab">
                                <i class="material-icons">settings</i> CHANGE PASSWORD
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane fade in active" id="profile_with_icon_title">
                            <div class="row clearfix">
                                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                    <div class="body">
                                        <form method="POST" name="editform" action="{{ route('profile-update') }}"
                                            class="form-horizontal" enctype="multipart/form-data">
                                            @csrf
                                            @method('PUT')
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="name">Name </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input type="text" id="name" class="form-control"
                                                                placeholder="Enter your name" name="name"
                                                                value="{{ Auth::user()->name }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="name">Designation </label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            @php $designation =
                                                            getDesignationInfo(Auth::user()->company_id); @endphp
                                                            <select class="form-control" name="designation_id" id=""
                                                                required>
                                                                <option value="" selected disabled>Select One</option>
                                                                @foreach($designation as $designation_value)
                                                                <option value="{{ $designation_value->id }}">
                                                                    {{ $designation_value->name }}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="email_address_2">Email Address</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input readonly="" type="text" id="email_address_2"
                                                                class="form-control"
                                                                placeholder="Enter your email address" name="email"
                                                                value="{{ Auth::user()->email }}">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="name">Profile Image </label>
                                                </div>
                                                <div class="col-lg-3 col-md-3 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                                            <div class="fileinput-new thumbnail img-holder">
                                                                <img id="blah"
                                                                    src=" @if( is_null(Auth::user()->image)) {{ url('storage/app/public/default.png') }} @else {{ url('storage/app/public/profile/'.Auth::user()->image) }} @endif "
                                                                    alt="profile image">

                                                                <input type="checkbox" class="form-check-input" checked
                                                                    name="is_image" id="exampleCheck1"
                                                                    style="height: 30px;width: 30px">
                                                            </div>
                                                            <input class="form-control" type="file" name="image"
                                                                id="imgInp">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row clearfix">
                                                <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                                    <label for="phone">Phone</label>
                                                </div>
                                                <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                                    <div class="form-group">
                                                        <div class="form-line">
                                                            <input readonly="" type="text" class="form-control"
                                                                name="phone" value="{{ Auth::user()->phone }}">
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
                                                    <button type="submit"
                                                        class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div role="tabpanel" class="tab-pane fade" id="settings_with_icon_title">
                            <form method="POST" action="{{ route('password-update') }}" class="form-horizontal">
                                @csrf
                                @method('PUT')
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="old_password">Old Password </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="password" id="old_password" class="form-control"
                                                    placeholder="Enter your old password" name="old_password" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="password">New Password </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="password" id="password" class="form-control"
                                                    placeholder="Enter your new password" name="password" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row clearfix">
                                    <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                        <label for="confirm_password">Confirm Password </label>
                                    </div>
                                    <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                        <div class="form-group">
                                            <div class="form-line">
                                                <input type="password" id="confirm_password" class="form-control"
                                                    placeholder="Confirm your new password" name="password_confirmation"
                                                    value="">
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
                                        <button type="submit"
                                            class="btn btn-primary m-t-15 waves-effect">UPDATE</button>
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

<script>
    document.forms['editform'].elements['designation_id'].value = '{{Auth::user()->designations_id}}';

    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function () {
        readURL(this);
    });

</script>

@endpush

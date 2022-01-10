@extends('layouts.backend.app')

@section('title','File Upload')

@push('css')
@endpush
@section('content')


{{--<div class="container">
	<div class="row">
		<div class="col-md-6 col-offset-md-4">
			<div class="card">
				<h5 class="card-header">File Upload</h5>
				<div class="card-body">
					<form action="{{ route('storefile') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							<input type="file" name="file[]" multiple>
						</div>
						<button type="submit" class="btn btn-primary">Upload</button>
						<a href="{{ route('dashboard') }}" class="btn btn-success">Back</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
--}}
<div class="container-fluid">
    <!-- Tabs With Icon Title -->
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        Upload Documents
                    </h2>
                </div>
                <div class="body">
                    <div class="row clearfix">
                        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                            <div class="body">
                                <form method="POST" action="{{ route('storefile') }}" class="form-horizontal"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="project_name">Sub Task</label>
                                        </div>
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    {{-- select sub task --}}
                                                    <select class="form-control" name="project_name_select"
                                                        id="project_name" required>
                                                        <option selected disabled value="">Select one</option>
                                                        @foreach($subTasks as $key=>$data)
                                                        <option value="{{ $data->id }}">
                                                            {{ $data->sub_task_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-2 col-md-2 col-sm-4 col-xs-5 form-control-label">
                                            <label for="project_name">File Upload</label>
                                        </div>
                                        <!-- <div class="col-lg-4 col-md-4 col-sm-8 col-xs-7"></div> -->
                                        <div class="col-lg-10 col-md-10 col-sm-8 col-xs-7">
                                            <div class="form-group">
                                                <div class="form-line">
                                                    <input type="file" id="project_doc" class="form-control"
                                                        name="file[]" value="" multiple>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>

                                    <div class="row clearfix">
                                        <div class="col-lg-offset-2 col-md-offset-2 col-sm-offset-4 col-xs-offset-5">
                                            <a href="{{ route('dashboard') }}" class="btn btn-success">Back</a>
                                            <button type="submit" class="btn btn-primary">Upload</button>
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

@endpush
@extends('layouts.backend.app')

@section('title','File Upload')

@push('css')
<link href="{{ asset('public/css/dropzone.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-6 col-offset-md-4">
			<div class="card">
				<h5 class="card-header">File Upload</h5>
				<div class="card-body">
					<form action="{{ route('uploadfile') }}" method="post" enctype="multipart/form-data">
						@csrf
						<div class="form-group">
							<input type="file" name="file[]" multiple>
						</div>
						<button type="submit" class="btn btn-primary">Upload</button>
						<a href="{{ route('viewfile') }}" class="btn btn-success">Back</a>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('public/js/dropzone.js') }}"></script>

@endpush
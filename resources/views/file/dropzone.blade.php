@extends('layouts.backend.app')
@section('title','File Upload')

@push('css')
<link href="{{ asset('public/css/dropzone.css') }}" rel="stylesheet">
@endpush
@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<form action="{{ route('dropzone') }}" class="dropzone" method="post" enctype="multipart/form-data">@csrf</form>
		</div>
		<a href="{{ route('viewfile') }}" class="btn btn-success">Back</a>
	</div>
</div>
@endsection
@push('js')
<script src="{{ asset('public/js/dropzone.js') }}"></script>

@endpush
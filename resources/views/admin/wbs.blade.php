@extends('layouts.backend.app')

@section('title','Prescription')

@push('css')
<!-- JQuery DataTable Css -->
    <link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
<style>
table,th{
	font-size:x-small;
	
}
</style>
@endpush

@section('content')
<div class="container-fluid">
            <div class="block-header">
                <a class="btn btn-primary waves-effect" href="https://teleassist.herokuapp.com/" target="_blank">
                	<i class="material-icons">add</i>
                	<span>Add Next Session</span>
				</a>
            </div>
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                Patient History 
                                <span class="badge bg-blue"></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th class="col-md-2">Patient</th>
                                            <th>Diagnostic <br> Symptoms</th>
                                            <th>Diagnostic <br> Symptoms Details</th>
                                            <th class="col-md-3">Visit Date</th>
                                            <th class="col-md-3">Next Visit/Time</th>
                                            <th class="col-md-3">Prescribed Medicine</th>
                                            <!-- <th>Estimated Hour </th> -->
                                            <th>Spend Hour </th>
                                            <th class="col-md-3">Action</th>
                                            <!-- <th>Action</th> -->
                                        </tr>
                                    </thead>                                   
                                    <tbody>
                                    	@foreach($wbs as $key=>$val)
                                    	<tr>
	                                    	<td>{{ $key + 1 }}</td>
                                            <td> <span class="badge bg-purple">{{ $val->users->name }}</span></td>
                                            <td>{{ $val->wbs_task_title }}</td>
	                                    	<td>{{ $val->wbs_task_details }}</td>
	                                    	<td>{{ date('d-m-Y / h:i:s A', strtotime($val->task_start_date)) }}</td>
	                                    	<td>{{ date('d-m-Y / h:i:s A', strtotime($val->task_end_date)) }}</td>
	                                    	<td>{{ $val->task_comments }}</td>
	                                    	
											<!-- <td>{{ $val->estimated_task_hour }}</td> -->
											<td>
											@php
											$h = 0;
											@endphp
											@foreach($val->wbs as $wbs)
												@php
												   $h+= $wbs->wbs_hour
												@endphp
											@endforeach
											{{ $h }}
											</td>
											<td>
												<a href="https://teleassist.herokuapp.com/" target="_blank" style="font-weight:bold" class="badge bg-cyan">+ Create Session</a>
											</td>
	                                    
	                                    	<!-- <td class="text-center">
	                                    		<a href="" class="btn btn-xs btn-info waves-effect">
	                                    			<i class="material-icons">edit</i>
	                                    		</a>
	                                    		<button class="btn btn-xs btn-danger waves-effect"  type="button" onclick="">
	                                    			<i class="material-icons">delete</i>
	                                    		</button>
	                                    		<form id="" action="" method="POST" style="display:none">
	                                    			@csrf
	                                    			@method('DELETE')
	                                    		</form>
	                                    	</td> -->
                                    	</tr>
                                    	@endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- #END# Exportable Table -->
		
        </div>
    	
@endsection

@push('js')
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/jquery.dataTables.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/js/dataTables.bootstrap.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/dataTables.buttons.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.flash.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/jszip.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/pdfmake.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/vfs_fonts.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.html5.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/plugins/jquery-datatable/extensions/export/buttons.print.min.js')}}"></script>
<script src="{{ asset('public/assets/backend/js/pages/tables/jquery-datatable.js')}}"></script>
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script> --}}
<script src="{{ asset('public/assets/backend/js/npmsweetalert2at8.js ')}}"></script>
<script type="text/javascript">
	function deleteCategory(id){
		const swalWithBootstrapButtons = Swal.mixin({
		  customClass: {
		    confirmButton: 'btn btn-success',
		    cancelButton: 'btn btn-danger'
		  },
		  buttonsStyling: false
		})

		swalWithBootstrapButtons.fire({
		  title: 'Are you sure?',
		  text: "You won't be able to revert this!",
		  type: 'warning',
		  showCancelButton: true,
		  confirmButtonText: 'Yes, delete it!',
		  cancelButtonText: 'No, cancel!',
		  reverseButtons: true
		}).then((result) => {
		  if (result.value) {
		    event.preventDefault();
		    document.getElementById('delete-form-'+ id).submit();
		  } else if (
		    /* Read more about handling dismissals below */
		    result.dismiss === Swal.DismissReason.cancel
		  ) {
		    swalWithBootstrapButtons.fire(
		      'Cancelled',
		      'Your data is safe :)',
		      'error'
		    )
		  }
		})
	}
</script>
@endpush
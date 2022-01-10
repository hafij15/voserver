@extends('layouts.backend.app')

@section('title','WBS Details')

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
                <a class="btn btn-primary waves-effect" href="{{ route('dashboard') }}">
                
                	<- Back
				</a>
            </div>
            <!-- Exportable Table -->
			<div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header" >
							<h2>
                               Patient Details<hr>                               
                            </h2>
							<!-- <h6>                               
                                <span class="badge bg-green">DataSoft Manufacturing & Assembly Inc. Limited</span>
                            </h6> -->
                            <h6>
                                Patient Name:
                                <span class="text-info"> {{$user->name}}</span>
                            </h6>
							<h6>
                                Email:
                                <span class="text-info"> {{$user->email}}</span>
                            </h6>
							<h6>
                                Phone:
                                <span class="text-info"> {{$user->phone}}</span>
                            </h6>
							<!-- <h6>
                                Projects:
                                <span class="text-info"> </span>
                            </h6> -->
							
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>                                          
                                            <th>Diagnostic/Symptoms</th>
                                            <th>Diagnostic/Symptoms Details</th>
                                            <th class="col-md-3">Visit Date</th>
                                            <th class="col-md-3">End Visit Date</th>
                                            <th class="col-md-3">Prescription</th>
                                            <th>Spend Hour </th>                                           
                                        </tr>
                                    </thead>  
									<tfoot>
                                        <tr>
											<th colspan="6">Total Hour</th>
                                            <!-- <th class="col-md-2">Project</th>
                                            <th>Task</th>
                                            <th>Details</th>
                                            <th class="col-md-3">Start</th>
                                            <th class="col-md-3">End</th>
                                            <th class="col-md-3">Comments</th> -->
                                            <th>
											@php
												$h = 0;
											@endphp
											@foreach($wbs_hours as $val)
													@php
													$h+= $val->wbs_hour
													@endphp	
											@endforeach
											{{ $h }}
											</th>    
                                        </tr>
                                    </tfoot>                                 
                                    <tbody>
									@foreach($wbs_detais as $key=>$val)
                                    	<tr>
	                                    	<td>{{ $key + 1 }}</td>                                         
                                            <td>{{ $val->wbs_task_title }}</td>
	                                    	<td>{{ $val->wbs_task_details }}</td>
	                                    	<td>{{ $val->task_start_date }}</td>
	                                    	<td>{{ $val->task_end_date }}</td>
	                                    	<td>{{ $val->task_comments }}</td>
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
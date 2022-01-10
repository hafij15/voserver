@extends('layouts.backend.app')

@section('title','Patients History')

@push('css')
<!-- JQuery DataTable Css -->
    <link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
<style>
table,th{
	font-size:small;
	
}
</style>
@endpush

@section('content')
<div class="container-fluid">
            <div class="block-header">
                <!-- <a class="btn btn-primary waves-effect" href="https://teleassist.herokuapp.com/" target="_blank">
                	<i class="material-icons">add</i>
                	<span>Add Next Session</span>
				</a> -->
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
                                            <th>Symptoms</th>
                                            <th class="col-md-3">Visit Date</th>
                                            <th class="col-md-3">Time</th>
                                            <th class="col-md-3">Spent Hour/Minute</th>
                                            <th class="col-md-3">Prescribe Medicines</th>
                                            <th class="col-md-3">Next Visit</th>
                                            <!-- <th class="col-md-3">Prescription</th> -->
                                        </tr>
                                    </thead>                                   
                                    <tbody>
                                    	@foreach($patients as $key=>$val)
                                    	<tr>
	                                    	<td>{{ $key + 1 }}</td>
                                            <td> <span class="badge bg-teal">{{ $val->users->name }}</span></td>
                                            <td>{{ $val->patient_symptoms }}</td>
	                                    	<td>{{ $val->visit_date }}</td>
	                                    	<td> {{ date('h:i:s A', strtotime($val->slots->start_time)) }} - {{ date('h:i:s A', strtotime($val->slots->end_time)) }}</td>
											<td> <span class="badge bg-purple">{{ $val->spent_hour }}</span></td>
	                                    	<td>{{ $val->prescribe_medicines }}</td>
	                                    	<td>{{ $val->follow_up_visit_date }}</td>
											@if(auth()->check() && auth()->user()->hasRole('user'))
											<td>
												<a href="#" style="font-weight:bold" class="badge bg-cyan">download </a>
											</td>
											@else
											<!-- <td>
												<a href="{{ route('prescription_edit', $val->id) }}" style="font-weight:bold" class="badge bg-cyan">update</a>
											</td> -->
											@endif
	                                    
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@8"></script>
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
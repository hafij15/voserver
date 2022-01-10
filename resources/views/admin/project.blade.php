@extends('layouts.backend.app')

@section('title','Projects')

@push('css')
<!-- JQuery DataTable Css -->
    <link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
            <div class="block-header">
                <a class="btn btn-primary waves-effect" href="{{ route('projects.create') }}">
                	<i class="material-icons">add</i>
                	<span>Add New Project</span>
				</a>
            </div>
            <!-- Exportable Table -->
            <div class="row clearfix">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="card">
                        <div class="header">
                            <h2>
                                ALL PROJECTS 
                                <span class="badge bg-blue"></span>
                            </h2>
                        </div>
                        <div class="body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Client</th>
                                            <th>Project Lead</th>
                                            <th>Support Engg</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <!-- <tfoot>
                                        <tr>
                                            <th>ID</th>
                                            <th>Name</th>
                                            <th>Email</th>
                                            <th>Role</th>
                                            <th>Created At</th>
                                            <th>Action</th>
                                        </tr>
                                    </tfoot> -->
                                    <tbody>
                                    	@foreach($projects as $key=>$project)
                                    	<tr>
	                                    	<td>{{ $key + 1 }}</td>
                                            <td>{{ $project->project_name }}</td>
	                                    	<td>{{ $project->project_client }}</td>
	                                    	<td>											
                                    			<span class="badge bg-green">{{ $project->project_lead->name }}</span>                                		
											</td>
											<td>	
												@php $users = explode(',', $project->support_engg_id);  @endphp	
												@foreach($users as $id)									
                                    			{{--<span class="badge bg-blue">												
													{{ getSupportEnggName($id)}}												
												</span>--}}
												@endforeach                                		
											</td>
	                                    	
	                                    	<td class="text-center">
	                                    		<a href="#" class="btn btn-info waves-effect">
	                                    			<i class="material-icons">edit</i>
	                                    		</a>
	                                    		<button class="btn btn-danger waves-effect"  type="button" onclick="">
	                                    			<i class="material-icons">delete</i>
	                                    		</button>
	                                    		<form id="" action="" method="POST" style="display:none">
	                                    			@csrf
	                                    			@method('DELETE')
	                                    		</form>
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
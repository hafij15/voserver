
@extends('layouts.backend.app')
@if(auth()->check() && auth()->user()->hasRole('super-admin'))
    @section('title',"ADMIN DASHBOARD")
@elseif(auth()->check() && auth()->user()->hasRole('admin'))
    @section('title',"MODERATOR DASHBOARD")
@elseif(auth()->check() && auth()->user()->hasRole('power-user'))
    @section('title',"DOCTOR DASHBOARD")
@else
    @section('title',"PATIENT DASHBOARD")
@endif
@push('css')
<link href="{{asset('public/assets/backend/plugins/jquery-datatable/skin/bootstrap/css/dataTables.bootstrap.css')}}" rel="stylesheet">
<style>
table,th{
	font-size:small;
	
}
</style>
@endpush
@section('content')

<div class="container-fluid">
    @if(auth()->check() && auth()->user()->hasRole('admin'))
        <div class="block-header">
            <h2 class="text-left">MODERATOR DASHBOARD</h2>       
        </div>
    @endif    
    @if(auth()->check() && auth()->user()->hasRole('power-user'))
        <div class="block-header">
            <h2 class="text-left">DOCTOR'S DASHBOARD</h2>       
        </div>
    @endif  

    @if(auth()->check() && auth()->user()->hasRole('user'))
        <div class="block-header">
            <h2 class="text-left">PATIENT'S DASHBOARD</h2>       
        </div>
    @endif 
  
    <!-- <div class="block-header">
        <h2>DASHBOARD</h2>
    </div> -->


    <!-- Widgets -->
    @if(auth()->user()->hasRole('admin') || auth()->user()->hasRole('power-user') || auth()->user()->hasRole('super-admin'))
    <div class="row clearfix">
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('all_patient')}}" style="text-decoration: none;">
            <div class="info-box bg-blue hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">supervised_user_circle</i>
                </div>
                <div class="content">
                    <div class="text">Total Patients</div>
                    <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['total_patient'] }}" data-speed="1000" data-fresh-interval="20">{{ $dashboard_info['total_patient'] }}</div>
                </div>
            </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('followup_patient')}}" style="text-decoration: none;">
            <div class="info-box bg-pink hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person_search</i>
                </div>
                <div class="content">
                    <div class="text">Followup Patient</div>
                    <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['followup_patient'] }}" data-speed="15" data-fresh-interval="20">{{ $dashboard_info['followup_patient'] }}</div>
                </div>
            </div>
        </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('new_patient')}}" style="text-decoration: none;">
            <div class="info-box bg-red hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">person_add</i>
                </div>
                <div class="content">
                    <div class="text">New Appointment</div>
                    <div class="number count-to" data-from="0" data-to="{{ $dashboard_info['new_patient'] }}" data-speed="1000" data-fresh-interval="20">{{ $dashboard_info['new_patient'] }}</div>
                </div>
            </div>
        </a>
        </div>
        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
            <a href="{{route('emergency')}}" style="text-decoration: none;">
            <div class="info-box bg-light-green hover-expand-effect">
                <div class="icon">
                    <i class="material-icons">airline_seat_individual_suite</i>
                </div>
                <div class="content">
                    <div class="text">Emergency</div>
                    <div class="number count-to" data-from="0" data-to="0" data-speed="1000" data-fresh-interval="20">0</div>
                </div>
            </div>
        </a>
        </div>     
    </div>
    @endif
    <!-- Widgets End -->


    @if(auth()->check() && auth()->user()->hasRole('admin'))
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                       Followup Patients
                        <span class="badge bg-blue"></span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Gender</th>
                                    <th>Symptoms</th>
                                    <th>Assigned Doctor</th>
                                    <th>Date</th>                                           
                                    <th>Time</th>                                           
                                   <!--  <th>Assigned Doctor</th>
                                    <th>Patient Type</th>
                                    <th>Appointment</th> -->
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach($followup_patient_list as $key=>$data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->users->name }}</td>
                                    <td>{{ $data->users->gender }}</td>
                                    <td>{{ $data->patient_symptoms }}</td>
                                    <td><span class="badge">{{ $data->doctors->name }}</span></td>
                                    <td>{{ $data->follow_up_visit_date }}</td>
                                    <td> {{ date('h:i:s A', strtotime($data->slots->start_time)) }} - {{ date('h:i:s A', strtotime($data->slots->end_time)) }}</td>
                                    <!-- <td><span class="badge">{{ $data->doctors->name }}</span></td>   -->
                                    <!-- <td>
                                    @if($data->patient_type==="New")
                                        <span class="badge bg-red">
                                        {{ $data->patient_type }}
                                        </span>
                                    @else
                                        <span class="badge">
                                        {{ $data->patient_type }}
                                        </span>
                                    @endif
                                    </td>  -->                                 
                                  
                                    <!-- <td><a href="{{ route('view_wbs',$data->id)}}"> {{ date('d-m-Y / h:i:s A', strtotime($data->created_at)) }} </a></td> -->
                                    
                                    <!-- <td class="text-center">
                                        <a href="{{ route('appointments.edit',$data->id)}}" class="btn btn-success waves-effect">
                                            update
                                        </a>
                                        <button class="btn btn-warning waves-effect"  type="button" onclick="deleteCategory('{{ $data->id }}')">
                                            Cancel
                                        </button>
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('appointments.destroy', $data->id) }}" method="POST" style="display:none">
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
    @endif

    @if(auth()->check() && auth()->user()->hasRole('power-user'))
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                       Followup Patients
                        <span class="badge bg-blue"></span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Gender</th>
                                    <th>Symptoms</th>
                                    <th>Date</th>                                           
                                    <th>Time</th>                                           
                                    <!-- <th>Patient Type</th>
                                    <th>Virtual Session</th> -->
                                    <!-- <th>Update</th> -->
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach($followup_patient_list as $key=>$data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->users->name }}</td>
                                    <td>{{ $data->users->gender }}</td>
                                    <td>{{ $data->patient_symptoms }}</td>  
                                    <td>{{ $data->follow_up_visit_date }}</td>
                                    <td> {{ date('h:i:s A', strtotime($data->slots->start_time)) }} - {{ date('h:i:s A', strtotime($data->slots->end_time)) }}</td>
                                    <!-- <td>
                                    @if($data->patient_type==="New")
                                        <span class="badge bg-red">
                                        {{ $data->patient_type }}
                                        </span>
                                    @else
                                        <span class="badge">
                                        {{ $data->patient_type }}
                                        </span>
                                    @endif
                                    </td>   -->                                 
                                  
                                    <!-- <td><a href="{{ route('view_wbs',$data->id)}}"> {{ date('d-m-Y / h:i:s A', strtotime($data->created_at)) }} </a></td> -->
                                    
                                    <!-- <td class="text-center">
                                        <a href="{{ route('chat', [$data->id, $data->room_id, $data->users->name]) }}" class="btn btn-info waves-effect">
                                            Start Session
                                        </a>                                        
                                    </td>
                                    <td>
                                    <button class="btn btn-warning waves-effect"  type="button" onclick="deleteCategory('{{ $data->id }}')">
                                            Cancel Appointment
                                        </button>
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('appointments.destroy', $data->id) }}" method="POST" style="display:none">
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
    @endif

    @if(auth()->check() && auth()->user()->hasRole('user'))
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                       Followup Patients
                        <span class="badge bg-blue"></span>
                    </h2>
                </div>
                <div class="body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover dataTable js-exportable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Gender</th>
                                    <th>Symptoms</th>
                                    <th>Date</th>                                           
                                    <th>Slot</th>                                           
                                    <th>Assigned Doctor</th>
                                    <th>Virtual Session</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                           
                            <tbody>
                                @foreach($followup_patient_list as $key=>$data)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $data->users->name }}</td>
                                    <td>{{ $data->users->gender }}</td>
                                    <td>{{ $data->patient_symptoms }}</td>
                                    <td>{{ $data->follow_up_visit_date }}</td>
                                    <td> {{ date('h:i:s A', strtotime($data->slots->start_time)) }} - {{ date('h:i:s A', strtotime($data->slots->end_time)) }}</td>
                                    <td><span class="badge">{{ $data->doctors->name }}</span></td>
                                   
                                  
                                    <!-- <td><a href="{{ route('view_wbs',$data->id)}}"> {{ date('d-m-Y / h:i:s A', strtotime($data->created_at)) }} </a></td> -->
                                    
                                    <td class="text-center">
                                    <a href="{{ route('chat',  [$data->id, $data->room_id, $data->users->name]) }}" class="btn btn-info waves-effect">
                                            Join Session
                                        </a>                                      
                                        
                                    </td>
                                    <td>
                                    <button class="btn btn-danger waves-effect"  type="button" onclick="deleteCategory('{{ $data->id }}')">
                                            Cancel
                                        </button>
                                        <form id="delete-form-{{ $data->id }}" action="{{ route('appointments.destroy', $data->id) }}" method="POST" style="display:none">
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
    @endif

    @if(auth()->check() && auth()->user()->hasRole('super-admin'))
    <div class="row clearfix">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <div class="card">
                <div class="header">
                    <h2>
                        New Patients
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
                                    <th>Gender | Diagnostics</th>
                                    <th>Appointment</th>                                           
                                    <!-- <th>Virtual Session</th> -->
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
                                @foreach($users as $key=>$user)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->gender }}</td>
                                    <!-- <td>
                                    @foreach($user->roles as $item)
                                        <span class="badge bg-pink">{{ $item->name }}</span>
                                    @endforeach
                                    </td> -->
                                    <td><a href="{{ route('view_wbs',$user->id)}}"> {{ date('d-m-Y / h:i:s A', strtotime($user->created_at)) }} </a></td>
                                    
                                   <!--  <td class="text-center">
                                        <a href="https://teleassist.herokuapp.com/" target="_blank" class="btn btn-info waves-effect">
                                            Create Session
                                        </a> -->
                                        
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
    @endif  
  
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
    
    <script src="{{ asset('public/assets/backend/js/pages/index.js') }}"></script>
      <!-- Jquery CountTo Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/jquery-countto/jquery.countTo.js ')}}"></script>

    <!-- Morris Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/raphael/raphael.min.js ')}}"></script>
    <script src="{{ asset('public/assets/backend/plugins/morrisjs/morris.js ')}}"></script>

    <!-- ChartJs -->
    <script src="{{ asset('public/assets/backend/plugins/chartjs/Chart.bundle.js ')}}"></script>

    <!-- Flot Charts Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.js ')}}"></script>
    <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.resize.js ')}}"></script>
    <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.pie.js ')}}"></script>
    <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.categories.js ')}}"></script>
    <script src="{{ asset('public/assets/backend/plugins/flot-charts/jquery.flot.time.js ')}}"></script>

    <!-- Sparkline Chart Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/jquery-sparkline/jquery.sparkline.js ')}}"></script>
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
            title: 'Are you sure to cancel?',
            text: "",
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes!',
            cancelButtonText: 'No!',
            reverseButtons: true
            }).then((result) => {
            if (result.value) {
                event.preventDefault();
                document.getElementById('delete-form-'+ id).submit();
            } else if (
                /* Read more about handling dismissals below */
                result.dismiss === Swal.DismissReason.cancel
            ) {
                // swalWithBootstrapButtons.fire(
                // 'Not Cancelled!',
                // )
            }
            })
        }
    </script>
@endpush

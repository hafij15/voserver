<aside id="leftsidebar" class="sidebar">
    <!-- User Info -->
    <div class="user-info">
        <div class="image">
            <img src=" @if( is_null(Auth::user()->image)) {{ url('storage/app/public/profile/no_profile.png') }} @else {{ url('storage/app/public/profile/'.Auth::user()->image) }} @endif "
                width="48" height="48" alt="User" />
        </div>
        <div class="info-container">
            <div class="name" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ Auth::user()->name }}
            </div>
            <div class="email">{{ Auth::user()->email }} </div>
            <div class="btn-group user-helper-dropdown">
                <i class="material-icons" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="true">keyboard_arrow_down</i>
                <ul class="dropdown-menu pull-right">
                    <li>
                        <a href="#">
                            <i class="material-icons">settings</i>Settings</a>
                    </li>
                    <li role="separator" class="divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form1').submit();">
                            <i class="material-icons">input</i>Sign Out
                        </a>

                        <form id="logout-form1" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>

                    </li>
                </ul>
            </div>
        </div>
    </div>
    <!-- #User Info -->
    <!-- Menu -->
    <div class="menu" >
        <ul class="list">
            <li class="header">MAIN NAVIGATION</li>
            @role('user')
            <li class="{{Request::is('dashboard') ? 'active' : ''}} ">
                <a href="{{route('dashboard')}}">
                    <i class="material-icons">home</i>
                    <span>Home</span>
                </a>
                 @if(auth()->check() && auth()->user()->hasRole('user') || auth()->user()->hasRole('power-user'))
                <ul>
                    <li style="list-style: none">
                        <a data-toggle="modal" data-target="#showTimeCard" data-user="{{Auth::user()->id}}">
                            <i class="material-icons">grading</i>
                            <span>Time-Card</span>
                        </a>
                    </li>
                </ul>
                @endif
            </li>
             @if(auth()->check() && auth()->user()->hasRole('admin'))
            <li class="{{Request::is('designation') ? 'active' : ''}}">
                <a href="{{route('designation.index')}}">
                    <i class="material-icons">star_half</i>
                    <span>Add Designation</span>
                </a>
            </li>
            @endif
            
            
            @if(auth()->check() && auth()->user()->hasRole('super-admin'))
            <li class="{{Request::is('users') ? 'active' : ''}}">
                <a href="{{route('users.index')}}">
                    <i class="material-icons">person_add</i>
                    <span>New User</span>
                </a>
            </li>
            <li class="{{Request::is('company') ? 'active' : ''}}">
                <a href="{{route('company.index')}}">
                    <i class="material-icons">business</i>
                    <span>Add Company</span>
                </a>
            </li>
             @if(auth()->check() && auth()->user()->hasRole('super-admin') || auth()->user()->hasRole('admin'))
            <li class="{{Request::is('designation') ? 'active' : ''}}">
                <a href="{{route('designation.index')}}">
                    <i class="material-icons">star_half</i>
                    <span>Add Designation</span>
                </a>
            </li>
            @endif
            @endif

            @role('user')
            @if(auth()->check() && auth()->user()->hasRole('admin') || auth()->user()->hasRole('power-user') ||
            auth()->user()->hasRole('super-admin'))
            {{--
                    <li class="header">INFORMATION</li>
                    <li class="{{Request::is('all_patient') ? 'active' : ''}}">
            <a href="{{route('all_patient')}}">
                <i class="material-icons">supervised_user_circle</i>
                <span>Total Patients</span>
            </a>
            </li>

            <li class="{{Request::is('followup_patient') ? 'active' : ''}}">
                <a href="{{route('followup_patient')}}">
                    <i class="material-icons">person_search</i>
                    <span>Followup Patient</span>
                </a>
            </li>

            <li class="{{Request::is('new_patient') ? 'active' : ''}}">
                <a href="{{route('new_patient')}}">
                    <i class="material-icons">person_add</i>
                    <span>New Appointment</span>
                </a>
            </li>

            <li class="{{Request::is('emergency') ? 'active' : ''}}">
                <a href="{{route('emergency')}}">
                    <i class="material-icons">airline_seat_individual_suite</i>
                    <span>Emergency</span>
                </a>
            </li>
            --}}
            @endif

            <li class="header">System</li>
            <li class="{{Request::is('settings') ? 'active' : ''}}">
                <a href="{{route('settings')}}">
                    <i class="material-icons">settings</i>
                    <span>Settings</span>
                </a>
            </li>
            {{-- @if(auth()->check() && auth()->user()->hasRole('power-user'))
                    <li class="{{Request::is('project') ? 'active' : ''}}">
            <a href="{{route('projects.index')}}">
                <i class="material-icons">date_range</i>
                <span>Create Project</span>
            </a>
            </li>
            @endif --}}
            {{-- @if(auth()->check() && auth()->user()->hasRole('super-admin'))
                    <li class="{{Request::is('project') ? 'active' : ''}}">
            <a href="{{route('projects.index')}}">
                <i class="material-icons">date_range</i>
                <span>Create Project</span>
            </a>
            </li>
            @endif --}}
            {{-- @if(auth()->check() && auth()->user()->hasRole('admin'))
                    <li class="{{Request::is('slot') ? 'active' : ''}}">
            <a href="{{route('slot.index')}}">
                <i class="material-icons">date_range</i>
                <span>Create Time Schedule</span>
            </a>
            </li>
            @endif --}}
            {{-- @if(auth()->check() && auth()->user()->hasRole('super-admin'))
                    <li class="{{Request::is('slot') ? 'active' : ''}}">
            <a href="{{route('slot.index')}}">
                <i class="material-icons">date_range</i>
                <span>Create Time Schedule</span>
            </a>
            </li>
            @endif --}}
            <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                    <i class="material-icons">input</i>
                    <span>Sign Out</span>
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>
            @endrole
        </ul>
    </div>
    <!-- #Menu -->
    <!-- Footer -->
    <div class="legal">
        <div class="copyright">
            &copy; 2020 <a href="https://dma-bd.com">Datasoft Manufacturing & Assembly Inc. Ltd.</a>.
            <p>TA-227.Beta Version</p>
        </div>
    </div>
</aside>
{{-- <script src="https://code.jquery.com/jquery-3.5.1.min.js"
    integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script> --}}
    <script src="{{ asset('public/assets/backend/js/jquery-3.5.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        if (sessionStorage.getItem("roomId") != "") {
            $(".btn-group").css("display", "none");
        } else {
            $(".btn-group").css("display", "inline-block");
        }
    });
   
</script>

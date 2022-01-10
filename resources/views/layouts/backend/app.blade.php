<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- /logout --}}

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'Virtual Office') }}</title>
    <!-- Favicon-->
    {{-- <link rel="icon" href="favicon.ico" type="image/x-icon"> --}}

    <!-- Google Fonts -->
    {{-- <link href="https://fonts.googleapis.com/css?family=Roboto:400,700&subset=latin,cyrillic-ext" rel="stylesheet"
        type="text/css"> --}}
    <link href="{{ asset('public/assets/frontend/css/fonts-googleapis-family-Roboto.css') }}" rel="stylesheet"
        type="text/css">
    {{-- <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" type="text/css"> --}}
    <link href="{{ asset('public/assets/frontend/css/fonts-googleapis-Material-Icons.css') }}" rel="stylesheet"
        type="text/css">
    <!-- Bootstrap Core Css -->
    <link href="{{ asset('public/assets/backend/plugins/bootstrap/css/bootstrap.css') }}" rel="stylesheet">

    <!-- Waves Effect Css -->
    <link href="{{ asset('public/assets/backend/plugins/node-waves/waves.css') }}" rel="stylesheet" />

    <!-- Animation Css -->
    <link href="{{ asset('public/assets/backend/plugins/animate-css/animate.css') }}" rel="stylesheet" />

    <!-- Morris Chart Css-->
    <link href="{{ asset('public/assets/backend/plugins/morrisjs/morris.css') }}" rel="stylesheet" />

    <!-- Custom Css -->
    <link href="{{ asset('public/assets/backend/css/style.css') }}" rel="stylesheet">

    <!-- AdminBSB Themes. You can choose a theme from css/themes instead of get all themes -->
    <link href="{{ asset('public/assets/backend/css/themes/all-themes.css') }}" rel="stylesheet" />

    <!--WebRTC css  -->
    <link href="{{asset('public/css/custom.css')}}" rel="stylesheet">

    <!-- templates custom css -->
    {{-- <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css"> --}}
    <link href="{{asset('public/css/toastr.min.css')}}" rel="stylesheet">
    @stack('css')
</head>

<body class="theme-blue">
    <!-- Page Loader -->
    <div class="page-loader-wrapper text-center">
        <div class="loader">
            <div class="preloader">
                <div class="spinner-layer pl-red">
                    <div class="circle-clipper left">
                        <div class="circle"></div>
                    </div>
                    <div class="circle-clipper right">
                        <div class="circle"></div>
                    </div>
                </div>
            </div>
            <p>Please wait...</p>
        </div>
    </div>
    <!-- #END# Page Loader -->
    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>
    <!-- #END# Overlay For Sidebars -->
    <!-- Search Bar -->
    <div class="search-bar">
        <div class="search-icon">
            <i class="material-icons">search</i>
        </div>
        <input type="text" placeholder="START TYPING...">
        <div class="close-search">
            <i class="material-icons">close</i>
        </div>
    </div>
    <!-- #END# Search Bar -->
    <!-- Top Bar -->
    @include('layouts.backend.partial.topbar')
    <!-- #Top Bar -->
    <section>
        <!-- Left Sidebar -->
        @include('layouts.backend.partial.sidebar')
        <!-- #END# Left Sidebar -->
    </section>

    <section class="content">
        @yield('content')
    </section>
    <!-- Jquery Core Js -->
    <script src="{{ asset('public/assets/backend/plugins/jquery/jquery.min.js ')}}"></script>

    <!-- Bootstrap Core Js -->
    <script src="{{ asset('public/assets/backend/plugins/bootstrap/js/bootstrap.js ')}}"></script>

    <!-- Select Plugin Js -->
    <!-- <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script> -->
    <!-- <script src="{{ asset('public/assets/backend/plugins/bootstrap-select/js/bootstrap-select.js ')}}"></script> -->

    <!-- Slimscroll Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/jquery-slimscroll/jquery.slimscroll.js ')}}"></script>

    <!-- Waves Effect Plugin Js -->
    <script src="{{ asset('public/assets/backend/plugins/node-waves/waves.js ')}}"></script>

    <!-- Custom Js -->
    <script src="{{ asset('public/assets/backend/js/admin.js') }}"></script>
    
    <!-- Demo Js -->
    <script src="{{ asset('public/assets/backend/js/demo.js') }}"></script>

    {{-- <script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script> --}}
    <script src="{{ asset('public/assets/backend/js/toastr.min.js') }}"></script>

    <!-- moment js -->
    <script src="{{ asset('public/js/moment.min.js')}}"></script>

    {!! Toastr::message() !!}
    <script>
        @if($errors->any())
        @foreach($errors->all() as $error)
        toastr.error('{{ $error }}', 'Error', {
            CloseButton: true,
            ProgressBar: true,
            positionClass: "toast-top-center"
        });
        @endforeach
        @endif
        $(document).ready(function () {
            const timeout = 900000; // 900000 ms = 15 minutes
            var idleTimer = null;
            $('*').bind('mousemove click mouseup mousedown keydown keypress keyup submit change'+ 
            'mouseenter scroll resize dblclick', function () {
            clearTimeout(idleTimer);

            idleTimer = setTimeout(function () {
            document.getElementById('logout-form').submit();
            var url = window.location.origin+'/login';
            //"http://localhost/Virtual_Office/teleassist-office/login";
            
            // document.location.href = url;
            localStorage.clear();
            // window.location.href = url;
            // {{ route('login') }}
            }, timeout);
        });
        $("body").trigger("mousemove");
        });
    </script>
    @stack('js')
</body>

</html>

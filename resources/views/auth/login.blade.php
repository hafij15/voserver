@extends('layouts.frontend.app')

@section('title', 'Login')
@push('css')
{{-- <link rel="icon" type="image/png" href="{{ asset('public/assets/frontend/login/images/icons/favicon.ico' )}}" /> --}}
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/vendor/bootstrap/css/bootstrap.min.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/fonts/font-awesome-4.7.0/css/font-awesome.min.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/fonts/iconic/css/material-design-iconic-font.min.css' )}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/frontend/login/vendor/animate/animate.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/vendor/css-hamburgers/hamburgers.min.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/vendor/animsition/css/animsition.min.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/vendor/select2/select2.min.css' )}}">
<link rel="stylesheet" type="text/css"
    href="{{ asset('public/assets/frontend/login/vendor/daterangepicker/daterangepicker.css' )}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/frontend/login/css/util.css' )}}">
<link rel="stylesheet" type="text/css" href="{{ asset('public/assets/frontend/login/css/main.css' )}}">
<style>
    img {
        display: block;
        margin-left: auto;
        margin-right: auto;
        height: 100px;
        width: 170px;
    }

</style>
@endpush
@section('content')

<div class="limiter">
    <div class="container-login100"
        style="background-image: url('{{asset('public/assets/frontend/login/images/bg-01.jpg')}}');">
        <div class="wrap-login100 p-l-55 p-r-55 p-t-65 p-b-54">
            <form class="login100-form validate-form" method="POST" action="{{ route('login') }}"
                aria-label="{{ __('Login') }}">
                @csrf
                <div class="logo">
                    <img src="{{asset('public/DMA.gif')}}" class="center">
                </div>
                <span class="login100-form-title p-b-49">
                    Virtual Office
                </span>
                <div class="wrap-input100 validate-input m-b-23" data-validate="Username is reauired">
                    <span class="label-input100">{{ __('E-Mail Address') }}</span>
                    <input id="email" type="email" class="input100{{ $errors->has('email') ? ' is-invalid' : '' }}"
                        name="email" placeholder="Type your email address" value="{{ old('email') }}"
                        oninvalid="this.setCustomValidity('Enter Valid Email address')"
                        oninput="this.setCustomValidity('')" required autofocus>
                    <span class="focus-input100" data-symbol="&#9993;"></span>
                </div>
                <div class="wrap-input100 validate-input" data-validate="Password is required">
                    <span class="label-input100">{{ __('Password') }}</span>
                    <input id="password" type="password"
                        class="input100{{ $errors->has('password') ? ' is-invalid' : '' }}"
                        placeholder="Type your password" name="password" required>
                    <span class="focus-input100" data-symbol="&#xf190;"></span>
                    <!-- @if ($errors->has('password'))
                                    <span class="focus-input100">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif -->
                </div>
                <div class="text-right p-t-8 p-b-31">
                    <a href="{{ route('password.request') }}">
                        {{ __('Forgot Your Password?') }}
                    </a>
                </div>
                <div class="container-login100-form-btn">
                    <div class="wrap-login100-form-btn">
                        <div class="login100-form-bgbtn"></div>
                        <button class="login100-form-btn" type="submit">
                            {{ __('Login') }}
                        </button>
                    </div>
                </div>
                <div class="txt1 text-center p-t-54 p-b-20">
                    <span>
                        Not Registered? <a style="color: #38c91e" href="{{route('register.index')}}">Create an
                            account</a>
                    </span>
                </div>
                <div class="txt1 text-center p-t-54 p-b-20">
                    <span>Or sign up using</span>
                </div>
                <div class="flex-c-m">
                    <a href="#" class="login100-social-item bg1">
                        <i class="fa fa-facebook"></i>
                    </a>
                    <a href="#" class="login100-social-item bg2">
                        <i class="fa fa-twitter"></i>
                    </a>
                    <a href="#" class="login100-social-item bg3">
                        <i class="fa fa-google"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="dropDownSelect1"></div>
@endsection
@push('js')
<script src="{{ asset('public/assets/frontend/login/vendor/jquery/jquery-3.2.1.min.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/animsition/js/animsition.min.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/bootstrap/js/popper.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/bootstrap/js/bootstrap.min.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/select2/select2.min.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/daterangepicker/moment.min.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/daterangepicker/daterangepicker.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/vendor/countdowntime/countdowntime.js' )}}"></script>
<script src="{{ asset('public/assets/frontend/login/js/main.js')}}"></script>
<script>
 $(document).ready(function () {
    localStorage.clear();
 });

</script>
@endpush

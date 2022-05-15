<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tutor Raya </title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('/assets/plugins/fontawesome-free/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('/assets/dist/css/adminlte.min.css') }}">
</head>

<body class="hold-transition login-page">

    <div class="login-box">
        @if (Session::has('message'))
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>{{ session('message') }}</strong> Try again
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif
        <div class="login-logo">
            <img src="{{URL::asset('assets/images/logo.png')}}" />
        </div>

        <div class="card">
            <div class="card-body login-card-body">
                <p class="login-box-msg">Sign in to start your session</p>

                <div class="social-auth-links text-center mb-3">
                    <p>- -</p>
                    <a href="{{ route('auth.web', 'facebook') }}" class="btn btn-block btn-primary">
                        <i class="fab fa-facebook mr-2"></i> Sign in using Facebook (under development)
                    </a>
                    <a href="{{ route('auth.web', 'google') }}" class="btn btn-block btn-danger">
                        <i class="fab fa-google mr-2"></i> Sign in using Google
                    </a>
                </div>

                {{-- <p class="mb-1">
                    <a href="forgot-password.html">I forgot my password</a>
                </p> --}}
                {{-- <p class="mb-0">
                    <a href="#" class="text-center">Register a new membership</a>
                </p> --}}
            </div>

        </div>
    </div>

    <!-- jQuery -->
    <script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
    <!-- Bootstrap 4 -->
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- AdminLTE App -->
    <script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>
</body>

</html>

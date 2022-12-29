<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr">

<head>
    <!-- Meta data -->
    <meta charset="UTF-8">
    <meta name='viewport' content='width=device-width, initial-scale=1.0, user-scalable=0'>
    <meta content="BWA (Badan Wakaf Al-Qur'an)" name="description">
    <meta content="Andhana Web & Software Developer" name="author">
    <meta name="keywords" content="BWA">
    <!-- Title -->
    <title>BWA - Login</title>
    <!--Favicon -->
    <link rel="icon" href="{{ asset('assets/images/brand/favicon.ico')}}" type="image/x-icon" />
    <!--Bootstrap css -->
    <!-- Style css -->
    <link href="{{ asset('assets/css/style.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/dark.css')}}" rel="stylesheet" />
    <link href="{{ asset('assets/css/skin-modes.css')}}" rel="stylesheet" />
    <!-- Animate css -->
    <link href="{{ asset('assets/css/animated.css')}}" rel="stylesheet" />
    <!---Icons css-->
    <link href="{{ asset('assets/css/icons.css')}}" rel="stylesheet" />
    <!-- Color Skin css -->
    <link id="theme" href="{{ asset('assets/colors/color1.css')}}" rel="stylesheet" type="text/css" />
</head>

<body class="register-2">
    <div class="page">
        <div class="page-content">
            <div class="container">
                <div class="row">
                    <div class="col mx-auto">
                        <div class="row justify-content-center">
                            <div class="col-md-4">
                                <div class="text-center mb-5">
                                    <img src="{{ asset('assets/images/brand/logo1.png')}}" class="header-brand-img desktop-lgo" alt="bwa logo">
                                </div>
                                <div class="card">
                                    <div class="card-body">
                                        <div class="text-center mb-3">
                                            <h1 class="mb-2">Log In</h1>
                                        </div>
                                        <form class="mt-5" method="POST" id="frm" action="{{ url('aman/validasi') }}" role="form" onsubmit="return submited()">
                                            @csrf
                                            <div class="input-group mb-4{{ $errors->has('username') ? ' has-error' : '' }}">
                                                <div class="input-group-text">
                                                    <i class="fe fe-user"></i>
                                                </div>
                                                <input type="text" id="username" type="text" name="username" class="form-control" autocomplete="off" placeholder="Username" required tabindex="0">
                                                @if ($errors->has('username'))
                                                <span class="form-bar">
                                                    <strong>{{ $errors->first('username') }}</strong>
                                                </span>
                                                @endif
                                            </div>
                                            <div class="input-group mb-4{{ $errors->has('password') ? ' has-error' : '' }}">
                                                <div class="input-group" id="Password-toggle1">
                                                    <a href="" class="input-group-text">
                                                        <i class="fe fe-eye" aria-hidden="true"></i>
                                                    </a>
                                                    <input id="password" name="password" class="form-control" type="password" autocomplete="off" placeholder="Password" required tabindex="1">
                                                    @if ($errors->has('password'))
                                                    <span class="form-bar">
                                                        <strong>{{ $errors->first('password') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($errors->any())
                                            {{-- <h4>{{$errors->first()}}</h4> --}}
                                            <div class="alert alert-light-danger" role="alert">{{$errors->first()}}</div>
                                            @endif

                                            {{-- <div class="alert alert-warning" role="alert"><button type="button" class="btn-close mr-negative-16" data-bs-dismiss="alert" aria-hidden="true">×</button>Warning alert—At vero eos et accusamus praesentium!</div> --}}

                                            <div class="form-group text-center mb-3">
                                                <button type="submit" id="btnSubmit" class="btn btn-success btn-lg w-100 br-7" data-loading-text="Sedang Proses..." tabindex="2">Login</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Jquery js-->
    <script src="{{ asset('assets/js/jquery.min.js')}}"></script>
    <!-- Bootstrap5 js-->
    <script src="{{ asset('assets/plugins/bootstrap/popper.min.js')}}"></script>
    <script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
    <!--Othercharts js-->
    <script src="{{ asset('assets/plugins/othercharts/jquery.sparkline.min.js')}}"></script>
    <!-- Circle-progress js-->
    <script src="{{ asset('assets/js/circle-progress.min.js')}}"></script>
    <!-- Jquery-rating js-->
    <script src="{{ asset('assets/plugins/rating/jquery.rating-stars.js')}}"></script>
    <!-- Show Password -->
    <script src="{{ asset('assets/js/bootstrap-show-password.min.js')}}"></script>
    <!-- Custom js-->
    <script src="{{ asset('assets/js/custom.js')}}"></script>
    <script type="text/javascript">
        var msg = "{{Session::get('alert ')}}";
        var exist = "{{Session::has('alert')}}";
        if (exist) {
            Swal.fire({
                type: 'warning',
                title: 'Oops...',
                text: msg
            })
        }
        $(document).ready(function() {
            $('#username').focus();
        });

        function submited() {
            $('#btnSubmit').removeClass('btn-primary');
            $('#btnSubmit').addClass('btn-warning');
            $('#btnSubmit').html("<i class='fa fa-spinner fa-spin'></i> Sedang proses ...");
            return true;
        }
    </script>
</body>

</html>
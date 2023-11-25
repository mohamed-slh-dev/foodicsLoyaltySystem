<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Remmsh - Admin Panel</title>
    <meta name="Remmsh" content="Remmsh Admin panel" />
    <meta name="description" content="Remmsh Admin panel">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('assets/images/favicon.png')}}">

    <!-- CSS
	============================================ -->

   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/vendor/bootstrap.min.css')}}">

   <!-- Icon Font CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/vendor/material-design-iconic-font.min.css')}}">
   <link rel="stylesheet" href="{{asset('assets/css/vendor/font-awesome.min.css')}}">
   <link rel="stylesheet" href="{{asset('assets/css/vendor/themify-icons.css')}}">
   <link rel="stylesheet" href="{{asset('assets/css/vendor/cryptocurrency-icons.css')}}">

   <!-- Plugins CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/plugins/plugins.css')}}">

   <!-- Helper CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/helper.css')}}">

   <!-- Main Style CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">

   <!-- Custom My-Style CSS -->
   <link rel="stylesheet" href="{{asset('assets/css/my-style.css')}}">

   <!-- Custom Style CSS Only For Demo Purpose -->
   <link id="cus-style" rel="stylesheet" href="{{asset('assets/css/style-primary.css')}}">

</head>

<body>

    <div class="main-wrapper">

        <!-- Content Body Start -->
        <div class="content-body m-0 p-0">

            <div class="login-register-wrap">
                <div class="row text-white" style="background-color: #a7063c">

                    <div class="d-flex align-self-center justify-content-center order-2 order-lg-1 col-lg-5 col-12" >
                        <div class="login-register-form-wrap">

                            @if ($message = Session::get('warning'))

                            <div class="alert alert-dark bg-white text-danger" role="alert">
                                <strong>{{$message}}</strong> 
                                <button class="close text-danger" data-dismiss="alert"><i class="zmdi zmdi-close"></i></button>
                            </div>
                        
                            @endif
                            
                            <div class="text-center">

                                <img src="{{asset('assets/images/logo/logo.png')}}" width="100" height="100" alt="Remmsh" class="mb-3 ml-5">

                                <h4 class="text-white">Remmsh Admin Panel</h4>

                            </div>
                           
                            <div class="content">
                                <h1 class="text-white">Sign in</h1>
                                <p>Enter Username and Password to LOGIN</p>
                            </div>

                            <div class="login-register-form">
                                <form action=" {{route('admin.checkLogin')}} " method="POST">
                                    @csrf
                                    <div class="row">
                                        <div class="col-12 mb-20">
                                            <input class="form-control" name="username" type="text" placeholder="Username">
                                        </div>

                                        <div class="col-12 mb-20">
                                            <input class="form-control" name="password" type="password" placeholder="Password">
                                        </div>

                                      
                                        <div class="col-12 mt-10"><button class="button button-dark button-outline text-white">sign in</button></div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="login-register-bg order-1 order-lg-2 col-lg-7 col-12">
                        <div class="content">
                            <h1>Sign in</h1>
                            <p>Enter Email and Password to LOGIN</p>
                        </div>
                    </div>

                </div>
            </div>

        </div><!-- Content Body End -->

    </div>

    <!-- JS
============================================ -->

      <!-- Global Vendor, plugins & Activation JS -->
      <script src="{{asset('assets/js/vendor/modernizr-3.6.0.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/jquery-3.3.1.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/popper.min.js')}}"></script>
      <script src="{{asset('assets/js/vendor/bootstrap.min.js')}}"></script>
      <!--Plugins JS-->
      <script src="{{asset('assets/js/plugins/perfect-scrollbar.min.js')}}"></script>
      <script src="{{asset('assets/js/plugins/tippy4.min.js.js')}}"></script>
      <!--Main JS-->
      <script src="{{asset('assets/js/main.js')}}"></script>
      <script src="{{asset('assets/js/plugins/dropify/dropify.min.js')}}"></script>
      <script src="{{asset('assets/js/plugins/dropify/dropify.active.js')}}"></script>
  

</body>

</html>
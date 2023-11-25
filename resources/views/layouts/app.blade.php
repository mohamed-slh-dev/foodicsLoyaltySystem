
 @if (empty(session('user_id')))     
 <script>
     window.location.href = '/'; //login route           
 </script>
@endif

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


        <!-- Header Section Start -->
        <div class="header-section">
            <div class="container-fluid">
                <div class="row justify-content-between align-items-center">

                    <!-- Header Logo (Header Left) Start -->
                    <div class="header-logo col-auto" style="background-color: #a7063c">
                        <a href="index.html">
                            <img src="{{asset('assets/images/logo/logo.png')}}" alt="Remmsh">
                            <img src="{{asset('assets/images/logo/logo.png')}}" class="logo-light" alt="Remmsh">
                        </a>
                    </div><!-- Header Logo (Header Left) End -->

                    <!-- Header Right Start -->
                    <div class="header-right flex-grow-1 col-auto">
                        <div class="row justify-content-between align-items-center">

                            <!-- Side Header Toggle & Search Start -->
                            <div class="col-auto">
                                <div class="row align-items-center">

                                    <!--Side Header Toggle-->
                                    <div class="col-auto"><button class="side-header-toggle"><i class="zmdi zmdi-menu"></i></button></div>

                                    <!--Header Search-->
                                    <div class="col-auto">

                                        <div class="header-search">

                                            <button class="header-search-open d-block d-xl-none"><i class="zmdi zmdi-search"></i></button>

                                            <div class="header-search-form">
                                                <form action="#">
                                                    <input type="text" placeholder="Search Here">
                                                    <button><i class="zmdi zmdi-search"></i></button>
                                                </form>
                                                <button class="header-search-close d-block d-xl-none"><i class="zmdi zmdi-close"></i></button>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div><!-- Side Header Toggle & Search End -->

                            <!-- Header Notifications Area Start -->
                            <div class="col-auto">

                                <ul class="header-notification-area">

                                    <!--Language-->
                                    <li class="adomx-dropdown position-relative col-auto">
                                        <a class="toggle" href="#"><img class="lang-flag" src="{{asset('assets/images/flags/flag-1.jpg')}}" alt=""><i class="zmdi zmdi-caret-down drop-arrow"></i></a>

                                        <!-- Dropdown -->
                                        <ul class="adomx-dropdown-menu dropdown-menu-language">
                                            <li><a href="#"><img src="{{asset('assets/images/flags/flag-1.jpg')}}" alt=""> English</a></li>
                        
                                        </ul>

                                    </li>

                                    <!--Notification-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#"><i class="zmdi zmdi-notifications"></i><span class=""></span></a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-notifications">
                                            <div class="head">
                                                <h5 class="title">You have 0 new notification.</h5>
                                            </div>
                                            <div class="body custom-scroll">
                                                <ul>
                                                    {{-- <li>
                                                        <a href="#">
                                                            <i class="zmdi zmdi-notifications-none"></i>
                                                            <p>There are many variations of pages available.</p>
                                                            <span>11.00 am   Today</span>
                                                        </a>
                                                        <button class="delete"><i class="zmdi zmdi-close-circle-o"></i></button>
                                                    </li>      
                                                   --}}
                                                </ul>
                                            </div>
                                            <div class="footer">
                                                <a href="#" class="view-all">view all</a>
                                            </div>
                                        </div>

                                    </li>

                                    <!--User-->
                                    <li class="adomx-dropdown col-auto">
                                        <a class="toggle" href="#">
                                            <span class="user">
                                        <span class="avatar">
                                            <img src="{{asset('assets/images/users/'.session()->get('user_img'))}}" alt="">
                                            <span class="status"></span>
                                            </span>
                                            <span class="name">{{session()->get('name')}}</span>
                                            </span>
                                        </a>

                                        <!-- Dropdown -->
                                        <div class="adomx-dropdown-menu dropdown-menu-user">
                                            <div class="head">
                                                <h5 class="name"><a href="#">{{session()->get('name')}}</a></h5>
                                                <a class="mail" href="#">{{session()->get('username')}}</a>
                                            </div>
                                            <div class="body">
                                                <ul>
                                                    <li><a href=" {{route('admin.profile')}} "><i class="zmdi zmdi-account"></i>Profile</a></li>
                                                   
                                                   
                                                </ul>
                                                <ul>
                                                    <li><a href=" {{route('admin.logout')}} "><i class="zmdi zmdi-lock-open"></i>Sing out</a></li>
                                                </ul>
                                               
                                            </div>
                                        </div>

                                    </li>

                                </ul>

                            </div><!-- Header Notifications Area End -->

                        </div>
                    </div><!-- Header Right End -->

                </div>
            </div>
        </div><!-- Header Section End -->
        <!-- Side Header Start -->
        <div class="side-header show">
            <button class="side-header-close"><i class="zmdi zmdi-close"></i></button>
            <!-- Side Header Inner Start -->
            <div class="side-header-inner custom-scroll">

                <nav class="side-header-menu" id="side-header-menu">
                    <ul>
                       
                        <li><a href=" {{route('admin.dashboard')}} "><i class="fa fa-home"></i> <span>Dashboard</span></a></li>

                        <li><a href=" {{route('admin.restaurants')}}"><i class="fa fa-university"></i> <span>Restaurants</span></a></li>

                        <li><a href=" {{route('admin.orders')}}"><i class="fa fa-bars"></i> <span>Orders</span></a></li>
                        
                        <li><a href=" {{route('admin.restaurantsMessagesRequests')}}"><i class="fa fa-university"></i> <span>Messages Requests</span></a></li>

                        <li><a href=" {{route('admin.tags')}}"><i class="fa fa-tags"></i> <span>Automated Tags</span></a></li>

                        <li><a href=" {{route('admin.codes')}}"><i class="zmdi zmdi-confirmation-number"></i> <span>Discount Codes</span></a></li>


                        <li><a href=" {{route('admin.messages')}}"><i class="fa fa-envelope"></i> <span>Messages</span></a></li>

                      

                      
                        <li><a href=" {{route('admin.messagesRecords')}}"><i class="zmdi zmdi-mail-send"></i> <span>Messages Records</span></a></li>

                        <li><a href=" {{route('admin.users')}}"><i class="fa fa-users"></i> <span>Users</span></a></li>

            
                        <li class="has-sub-menu"><a href="#"><i class="fa fa-book"></i> <span>Documentaion</span></a>
                            <ul class="side-header-sub-menu">
                                <li><a href="{{route('automated-tags-doc-en')}}"><span>How Automated Tags Works</span></a></li>
                                <li><a href="{{route('orders-structure-doc-en')}}"><span>Orders Structure</span></a></li>
                               
                            </ul>
                        </li>
                    </ul>
                </nav>

            </div><!-- Side Header Inner End -->
        </div><!-- Side Header End -->

        <!-- Content Body Start -->
        <div class="content-body">

          
            <!-- Page Headings Start -->
            <div class="row justify-content-between align-items-center mb-10">

                <!-- Page Heading Start -->
                <div class="col-12 col-lg-auto mb-20">
                    <div class="page-heading">
                        <h3 class="title">Pages <span>/ @yield('title')</span></h3>
                    </div>
                </div><!-- Page Heading End -->

            </div><!-- Page Headings End -->

            <div class="mb-4">
                @include('alerts.alerts')
            </div>
            
            @yield('content')

        </div><!-- Content Body End -->

        <!-- Footer Section Start -->
        <div class="footer-section col-12">
            <div class="container-fluid">

                <div class="footer-copyright text-center">
                    <p class="text-body-light">2023 &copy; <a href="https://remmsh.com">Remmsh</a></p>
                </div>

            </div>
        </div><!-- Footer Section End -->

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

       <!-- Plugins & Activation JS For Only This Page -->
       <script src="{{asset('assets/js/plugins/filepond/filepond.min.js')}}"></script>
       <script src="{{asset('assets/js/plugins/filepond/filepond-plugin-image-exif-orientation.min.js')}}"></script>
       <script src="{{asset('assets/js/plugins/filepond/filepond-plugin-image-preview.min.js')}}"></script>
       <script src="{{asset('assets/js/plugins/filepond/filepond.active.js')}}"></script>
       <script src="{{asset('assets/js/plugins/dropify/dropify.min.js')}}"></script>
       <script src="{{asset('assets/js/plugins/dropify/dropify.active.js')}}"></script>
</body>

</html>
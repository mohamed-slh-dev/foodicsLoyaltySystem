<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Remmsh - Orders Structure</title>
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

   <style>
       hr{
        border-top: 3px solid #fd96b9;
       }
   </style>
</head>

<body>

    <div class="main-wrapper">

        <!-- Content Body Start -->
        <div class="content-body m-0 p-0">

            <div class="row">

                <div class="col-12 text-center" style="background-color: #a7063c">

                    <img width="200" height="200" src="{{asset('assets/images/logo/logo.png')}}" alt="">

                </div>

               

                <div class="col-12 text-center mt-3">
                    <h2>Orders Structure</h2>
                </div>

                <div class="col-12 p-5 ">

                    <h2>#Guest</h2>
                    <h6 class="text-bold">

                       The guest data is:
                       <br>
                       <ul>
                           <li>ID (unique) | Auto generated</li>
                           <li>Restaurant ID (required) | restaurant ID forign key</li>
                           <li>Name (required)</li>
                           <li>Phone Number (required) | in format (9665xxxxxxxx)</li>
                           <li>Customer ID (optional)</li>
                           <li>Email (optional)</li>
                           <li>Gender (optional)</li>
                           <li>Birthdate (optional)</li>

                       </ul>

                          <hr>

                          <h2>#Orders</h2>
                          <h6 class="text-bold">
      
                             The orders is related to guest and restaurant and the order data is:
                             <br>
                             <ul>
                                 <li>ID (unique) | Auto generated</li>
                                 <li>Guest ID (required) | guest ID forign key</li>
                                 <li>Restaurant ID (required) | restaurant ID forign key</li>
                                 <li>Restaurant Referance ID (required) | unique reference id from POS</li>
                                 <li>Total Amount (required) | order total amount from POS</li>
                                 
                                 <li>Timestamp (optional) | date time from POS</li>
                                 <li>Order ID (optional) | order id from POS</li>
                                 <li>Event (optional) | event type from POS</li>
                                 <li>Branch ID (optional) | order branch ID from POS</li>
                                 <li>Branch Name (optional) | order branch Name from POS</li>
      
                             </ul>

                             <hr>

                             <h2>#Order Details</h2>
                             <h6 class="text-bold">
         
                                The order details is related to order and the order deatils data is:
                                <br>
                                <ul>
                                    <li>ID (unique) | Auto generated</li>
                                    <li>Order ID (required) | order ID forign key</li>

                                    <li>Type (optional) | combo or product</li>
                                    <li>Combo ID (optional) | combo id from POS</li>
                                    <li>Combo SKU (optional) | combo SKU from POS</li>
                                    <li>Combo Name (optional) | combo Name from POS</li>
                                    
                                    <li>Product ID (optional) | product id from POS</li>
                                    <li>Product SKU (optional) | product SKU from POS</li>
                                    <li>Product Name (optional) | product Name from POS</li>
                                    
                                    <li>Category Reference (optional) | product category reference from POS</li>
                                    <li>Category Name (optional) | product category name from POS</li>

                                    <li>Price (optional) | price from POS</li>
                                    <li>Quantity (optional) | quantity from POS</li>
                                  
                                </ul>
   

                    </h6>

                    <hr>

                  

                </div>

                <div class="col-12 text-left my-3 ml-3">
                    <a target="_blank" href="https://restaurant-dashboard.remmsh.com">
                        <p style="font-size: 20px"> << Back To Dashboard </p>
                    </a>
                </div>

            </div>

        </div><!-- Content Body End -->

         <!-- Footer Section Start -->
         <div class="footer-section">
            <div class="container-fluid">

                <div class="footer-copyright text-center">
                    <p class="text-body-light">2023 &copy; <a href="https://remmsh.com">All rights recevied to Remmsh</a></p>
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
  

</body>

</html>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Remmsh - How Automated Tags Works</title>
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
                    <h2>How Automated Tags Works?</h2>
                </div>

                <div class="col-12 p-5 ">

                    <h2>#Localization</h2>
                    <h6 class="text-bold">
                        <br><span style="font-size: 18px">#Select Branch </span><br>

                        It means this automated tag will only getting all guest values from this branch only.

                        <br><br><span style="font-size: 16px">For Example </span><br>

                          Total visits will be counting from orders that created from this branch only. 

                          <hr>

                        <br><br><span style="font-size: 18px">#Group </span><br>
                        It means this automated tag will be getting guest values from all orders in all branches

                        <br><br><span style="font-size: 16px">For Example: </span><br>

                        Total visits will be counting from orders that created from ALL branches. 
                        

                    </h6>

                    <hr>

                    <h2>#Automated Tag Type</h2>
                    <h6 class="text-bold">
                        <br><span style="font-size: 18px">#Total Visits </span><br>
                        <span style="font-size: 17">Required values: times</span><br><br>

                        The times value in this type means the number of visits for the guest, and if recurring is checked then it means EVERY (times value)
                        <br><br><span style="font-size: 16px">For Example: #1 </span><br>

                          If we created new automated tag with type (total visits) and times (3) and recurring is unchecked. <br>
                          In this case it means this automated tag will be assinged to any guest in his thired visit ONLY.

                        <br><br><span style="font-size: 16px">For Example: #2 </span><br>
                        If we created new automated tag with type (total visits) and times (3) and recurring is checked. <br>

                        In this case it means this automated tag will be assinged to any guest EVERY 3 visits.
                        The tag will be assinged to this guest multible times and if this automated tag is linked with automated message then he will be getting message in his third visit and in his sixth visit and in his nineth visit etc...

                          <hr>

                        <br><br><span style="font-size: 18px">#Total Orders </span><br>
                        <span style="font-size: 17">Required values: range from - range to</span><br><br>

                        It's related to number of orders from guest order.

                        <br><br><span style="font-size: 16px">For Example: </span><br>

                        If guest order was (1) burger and (2) pepsi and (1) fries combo. The total orders will be (4). <br>
                        And then the system wil check if 4 is in range from and range to.
                        <hr>

                        
                        <br><br><span style="font-size: 18px">#Total Spend </span><br>
                        <span style="font-size: 17">Required values: range from - range to</span><br><br>

                        The total spend for order will be calculated from the total amount of guest's order,

                        And then the system wil check if total spend is in range from and range to.
                        <hr>


                        <br><br><span style="font-size: 18px">#Total Average Spend </span><br>
                        <span style="font-size: 17">Required values: range from - range to</span><br><br>

                        The total average spend calcualted from the summation of total amount for ALL guets's ordres and then will be divided by the number of guets's number of orders

                        And then the system wil check if total average spend is in range from and range to.
                        <hr>


                        <br><br><span style="font-size: 18px">#Last Visit</span><br>
                        <span style="font-size: 17">Required values: range from</span><br><br>

                        The number of days from last visit for the guest

                        <br>
                        Notes:
                        <br>
                        <ul>
                            <li>Automated tag will be assined dynamically and will be displayed in guest profile</li>

                            <li>Assinging this type of automated tag with on going automated messages will have no effect (because it's dynamically assinged)</li>
                        </ul>

                        <hr>

                        <br><br><span style="font-size: 18px">#Order Product/Combo </span><br>
                        <span style="font-size: 17">Required values: times</span><br><br>

                        It has two ways to work and it's mainly depends on reference. If reference is empty then this tag will be apply for any products the guest order it (times value) times and will be added to his favortie product / combo list. <br>

                        Otherwise if the reference fill with value then this tag will only be applied if the guest ordered this product (times value) times. <br>

                        If the is recurring is checked then it will every (times value) instead of times <br>

                        The guest favorite product / combo list will be diplayed on guest profile. <br>

                      <br>

                        Note: 
                        <ul>
                            <li>The history of ordering this product will be counting. </li>
                        </ul> 

                        <br><br><span style="font-size: 16px">For Example: #1</span><br>

                        If the tag type is (order product) and times (3). <br>

                        The guest ordered at his first visit (1) x-coffe, and then at the second visit he ordered (2) x-coffe. Then the automated tag will be applied.
                        <br>

                        <br><br><span style="font-size: 16px">For Example: #2</span><br>

                        If the tag type is (order product) and times (3) and is recurring. <br>

                        The guest ordered at his first visit (1) x-coffe, and then at the second visit he ordered (2) x-coffe. Then the automated tag will be applied. And in his third visit he ordered 3 x-coffe then the automated tag will be applied again, and if this automated tag has automated message then the message will be sent again.
                        <br>

                        <br>

                        Note:
                        <ul>
                            <li>Reference value will be getting from foodics dashboard go to: <br>
                                Dashboard->Menu->products or combos-> SKU
                            </li>
                        </ul>


                        <hr>

                    </h6>

                    <h2>#Based On Tag</h2>

                    <h6 class="text-bold">

                        If you checked based-on-tag on createing a new automated tag and select the tag. In this case this new automated tag will NOT be applied unless the based-on-tag tag is already applied to the guest.

                        <hr>

                        <h2>#Based On Rank</h2>

                        <h6 class="text-bold">
    
                            If you checked based-on-rank on createing a new automated tag and select the rank. In this case this new automated tag will NOT be applied unless the rank is already earned by the guest.
    
                            <hr>
    
                        <br><br><span id="automated_tag_ordering" style="font-size: 18px">#The Order Of Checking And Assinging Automated Tags: </span><br><br>

                        <ol>
                            <li>Check Ranks</li>
                            <li>Total Visits</li>
                            <li>Total Oders</li>
                            <li>Total Spend</li>
                            <li>Total Average Spend</li>
                            <li>Order Product</li>
                            <li>Order Combo</li>

                        </ol>

                        <br><br>
                        This order is important to check before you choose based-on-tag option because the new automated tag may not applied if both tags applied at the same time.

                        <br><br>
                        If the new automated tag is based-on-tag and the both tags meet the applying conditionat the same time, the applying proccess will in <a href="#automated_tag_ordering">
                            #order of checking and assinging automated tags</a>. And in this case if the main automated tag is based-on-tag and the main automated tag is checked first then this automated tag will not be applying even though the based-on-tag will be applied.

                    </h6>

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
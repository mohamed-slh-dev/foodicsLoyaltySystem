@extends('layouts.app')

@section('title','Dashboard')

@section('content')
    
<div class="row">

    <!-- Top Report Start -->
    <div class=" col-md-6 col-12 mb-30">
       <div class="top-report">

           <!-- Head -->
           <div class="head">
               <h4>Total Restaurants</h4>
               <a href="#" class="view"><i class="fa fa-university"></i></a>
           </div>

           <!-- Content -->
           <div class="content">
               <h2>{{$rests->count()}}</h2>
           </div>

           <!-- Footer -->
           <div class="footer">
               <div class="progess">
                   <div class="progess-bar" style="width: 100%;"></div>
               </div>
           </div>

       </div>
   </div><!-- Top Report End -->
     

    <!-- Top Report Start -->
    <div class=" col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Total Orders</h4>
                <a href="#" class="view"><i class="fa fa-bars"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$orders->count()}}</h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->
     
    
     <!-- Top Report Start -->
     <div class=" col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Total Revenue</h4>
                <a href="#" class="view"><i class="fa fa-usd"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$orders->sum('amount')}} <sup>SAR</sup></h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->
      

    <!-- Top Report Start -->
    <div class=" col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Total Automated Tags</h4>
                <a href="#" class="view"><i class="fa fa-tags"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$tags->count()}}</h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->

    <!-- Top Report Start -->
    <div class="col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Total Discounts</h4>
                <a href="#" class="view"><i class="zmdi zmdi-confirmation-number"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$discounts->count()}}</h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->

    <!-- Top Report Start -->
    <div class="col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Total Generated Codes</h4>
                <a href="#" class="view"><i class="zmdi zmdi-confirmation-number"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$codes->count()}}</h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->


     <!-- Top Report Start -->
     <div class="col-md-6 col-12 mb-30">
        <div class="top-report">
 
            <!-- Head -->
            <div class="head">
                <h4>Sent Messages</h4>
                <a href="#" class="view"><i class="fa fa-envelope"></i></a>
            </div>
 
            <!-- Content -->
            <div class="content">
                <h2>{{$messages->count()}}</h2>
            </div>
 
            <!-- Footer -->
            <div class="footer">
                <div class="progess">
                    <div class="progess-bar" style="width: 100%;"></div>
                </div>
            </div>
 
        </div>
    </div><!-- Top Report End -->
 

 </div>{{--End of row div --}}

@endsection
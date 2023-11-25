@extends('layouts.app')

@section('title','Reset Restaurants Data')

@section('content')
    

<div class="row">

    <div class="col-12">

        <h3>Reset Restaurant Data</h3>
        <hr>
    </div>

    <form action="{{route('admin.deleteRestaurantData')}}" class="col-12 row" method="POST">

        @csrf

        <div class="col-md-12 col-lg-4">
            <div class="m-4">

                <label for="">Select Restaurant</label>
                <select class="form-control" name="id" id="">

                    @foreach ($restaurants as $rest)
                        <option value="{{$rest->id}}">{{$rest->name_eng}}</option>
                    @endforeach

                </select>

            </div>
            
        </div>


        <div class="col-md-12 col-lg-4">

            <div class="m-4">
                <label for="">Select Data To Delete</label>

                <select class="form-control" name="data" id="">

                <option value="automated">Automated Tag/ Messages/ Discount Codes/ Ranks </option>
                <option value="orders">Orders</option>
                <option value="guests">Guests</option>


                </select>
            </div>
        </div>

        
        <div class="col-md-12 col-lg-4 text-center">
            
            <div class="m-4">
                <label for="">You Sure You Want To Delete?</label>

                <button type="submit" class="btn btn-lg btn-danger">DELETE</button>
            </div>
        </div>



    </form>

  
</div>



@endsection
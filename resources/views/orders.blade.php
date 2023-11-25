@extends('layouts.app')

@section('title','Restaurants Orders')

@section('content')
    

<div class="row">

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Orders ({{$orders->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name Arabic</th>
                                <th> Name English</th>
                                <th>Number Of Customers</th>

                                <th>Number Of Orders</th>
                                <th>Number Of Orders With Codes</th>
                                <th>Revenue From Codes</th>
                            

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($restaurants as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->name_ar}}</td>
                                <td>{{$rest->name_eng}}</td>
                                <td>{{$rest->guests->count()}}</td>

                                <td>{{$rest->orders->count()}}</td>

                                <td>{{$rest->orders->where('discount_code_id' ,'!=' , null)->count()}}</td>

                                <td>{{$rest->orders->sum('amount')}}</td>
                               
                              
                            </tr>

                            @endforeach
                         
                          
                        </tbody>
                    </table>
                </div>

            @if($restaurants instanceof \Illuminate\Pagination\LengthAwarePaginator )

                <div class="m-4">
                    
                    {{$restaurants->links()}}
        
                </div>

            @endif
                
            </div>
        </div>



@endsection
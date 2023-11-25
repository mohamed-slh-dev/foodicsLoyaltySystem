@extends('layouts.app')

@section('title','Restaurants Discounts Codes')

@section('content')
    

<div class="row">

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Generated Codes ({{$codes->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name Arabic</th>
                                <th> Name English</th>
                                <th>Restaurant Discounts</th>
                                <th>Number Of Generated Codes</th>
                                <th>Number Of Used Codes</th>
                            

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($restaurants as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->name_ar}}</td>
                                <td>{{$rest->name_eng}}</td>
                                <td>{{$rest->discounts->count()}}</td>

                                <td>

                                    @php
                                       
                                       $codes = 0;
                                       foreach ($rest->discounts as $value) {
                                        $codes +=  $value->codes->count();
                                       }

                                       echo $codes;
                                    @endphp
                                   
                                </td>

                                <td>

                                    @php
                                       
                                       $codes = 0;
                                       foreach ($rest->discounts as $value) {
                                        $codes +=  $value->codes->where('used_times' , 1)->count();
                                       }

                                       echo $codes;
                                    @endphp
                                   
                                </td>

                               
                              
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
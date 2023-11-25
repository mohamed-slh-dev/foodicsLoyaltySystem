@extends('layouts.app')

@section('title','Restaurants Messages')

@section('content')
    

<div class="row">

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Sent Messages ({{$messages->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name Arabic</th>
                                <th> Name English</th>
                                <th>Number Of Sent Messages</th>
                                <th>Number Of Remaining Messages</th>
                            

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($restaurants as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->name_ar}}</td>
                                <td>{{$rest->name_eng}}</td>

                                <td>{{$rest->messages->count()}}</td>
                                <td>{{$rest->number_of_messages}}</td>

                               
                              
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
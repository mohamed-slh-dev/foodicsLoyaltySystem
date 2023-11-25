@extends('layouts.app')

@section('title','Messages Records')

@section('content')
    

<div class="row">

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Messages Records ({{$messages->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Restaurant</th>
                                <th>Status</th>
                                <th>Error Code</th>
                                <th>Message ID</th>
                                <th>Message Status</th>
                                <th>Recipient</th>
                                <th>Time</th>
                            

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($messages as $msg)
                                
                            <tr>
                                <th> {{$msg->id}} </th>
                                <td>{{$msg->restaurant->name_eng}}</td>
                                <td>{{$msg->success}}</td>
                                <td>{{$msg->error_code}}</td>
                                <td>{{$msg->message_id}}</td>
                                <td>{{$msg->message_status}}</td>
                                <td>{{$msg->recipient}}</td>
                                <td> {{ date("H:i:s d-m-Y ", strtotime($msg->created_at." +2 hours")) }}</td>
                               

                            </tr>

                            @endforeach
                         
                          
                        </tbody>
                    </table>
                </div>

                @if($messages instanceof \Illuminate\Pagination\LengthAwarePaginator )

                <div class="m-4">
                    
                    {{$messages->links()}}
                

                </div>

                @endif
            </div>
        </div>



@endsection
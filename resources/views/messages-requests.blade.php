@extends('layouts.app')

@section('title','Restsurants Messages Requests')

@section('content')
    

<div class="row">
  

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Messages Requests ({{$requests->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name English</th>
                                <th> Name Arabic</th>
                                <th>Messages Requests</th>

                                <th> Status</th>
                        
                                <th>Update Status</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($requests as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->restaurant->name_eng}}</td>
                                <td>{{$rest->restaurant->name_ar}}</td>
                                <td>{{$rest->number_of_messages}}</td>
                                <td>{{$rest->status}}</td>

                      

                                <td>

                                    <button class="btn btn-primary" data-toggle="modal" data-target="#updatestatus-{{$rest->id}}">
                                       
                                            <i class="zmdi zmdi-edit"></i>
                                       
                                    </button>
                                  
                                </td>

                               
                            </tr>

                            @endforeach
                         
                          
                        </tbody>
                    </table>
                </div>

            @if($requests instanceof \Illuminate\Pagination\LengthAwarePaginator )

                <div class="m-4">
                    
                    {{$requests->links()}}
        
                </div>

            @endif

        </div>



@foreach ($requests as $rest)
    
<div class="modal fade" id="updatestatus-{{$rest->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Status For - {{$rest->restaurant->name_eng}} Request</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.updateRequestStatus')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$rest->id}}">
                @csrf
                <div class="row">
                    <div class="col-sm-6 mb-20">
                        <label for="status">Status</label>
                        <input type="text" id="status" class="form-control" name="status">
                    </div>

                </div>
             
            </div>
            <div class="modal-footer">
                <button type="submit" class="button button-primary">Save </button>
                <button class="button button-danger" data-dismiss="modal">Cancel</button>
            </div>
        </form>
        </div>
    </div>
</div>

@endforeach


@endsection
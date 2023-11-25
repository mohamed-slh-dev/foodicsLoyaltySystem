@extends('layouts.app')

@section('title','Users')

@section('content')
    

<div class="row">

    <div class="col-12">

        <button class="button button-primary" data-toggle="modal" data-target="#newuser">Add User </button>
    </div>

  

    <div class="col-12">
        <hr>
    </div>

    
    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Users ({{$users->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name</th>
                                <th> Username</th>
                                <th> Email</th>
                                <th> Phone Number</th>
                                <th>Reset Password</th>
                                <th>Disabel</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($users as $user)
                                
                            <tr>
                                <th> {{$user->id}} </th>
                                <td>{{$user->name}}</td>
                                <td>{{$user->username}}</td>
                                <td>{{$user->email}}</td>
                                <td> {{$user->phone}} </td>

                                <td>

                                    <button class="btn btn-primary" data-toggle="modal" data-target="#resetpass-{{$user->id}}">
                                       
                                            <i class="zmdi zmdi-edit"></i>
                                       
                                    </button>
                                  
                                </td>

                                <td>
                                    <a class="delete button button-box button-s button-danger" href=" {{route('admin.deleteUser',$user->id)}} "><i class="zmdi zmdi-delete"></i></a>
                                </td>
                            </tr>

                            @endforeach
                         
                          
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


<div class="modal fade" id="newuser">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New User</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.createUser')}}" method="POST">
            <div class="modal-body">

                @csrf
                <div class="row">
                    <div class="col-sm-4 mb-20">
                        <label for="nameeng"> Name</label>
                        <input type="text" id="nameeng" class="form-control" name="name">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="phone">Phone</label>
                        <input type="text" id="phone" class="form-control" name="phone">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="email">Email</label>
                        <input type="text" id="email" class="form-control" name="email">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="username">Username</label>
                        <input type="text" id="username" class="form-control" name="username">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="password">Password</label>
                        <input type="password" id="password" class="form-control" name="password">
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


@foreach ($users as $user)
    
<div class="modal fade" id="resetpass-{{$user->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rest Password for - {{$user->name}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.resetUserPassword')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$user->id}}">

                @csrf
                <div class="row">
                    <div class="col-sm-4 mb-20">
                        <label for="restpass">Reset Password</label>
                        <input type="password" id="restpass" class="form-control" name="password">
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
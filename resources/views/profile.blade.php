@extends('layouts.app')

@section('title','Profile')

@section('content')

<div class="card">


    <form  class=" row p-5" action=" {{route('admin.updateProfile')}} " method="POST" enctype="multipart/form-data">
        @csrf
        <div class="col-12 mb-4">
            <h4>Profile Info.</h4>
        </div>
    <div class="col-sm-4 mb-20">
        <label for="nameeng"> Name</label>
        <input type="text" id="nameeng" class="form-control" value="{{$user->name}}" name="name">
    </div>

    <div class="col-sm-4 mb-20">
        <label for="phone">Phone</label>
        <input type="text" id="phone" class="form-control" value="{{$user->phone}}" name="phone">
    </div>

    <div class="col-sm-4 mb-20">
        <label for="email">Email</label>
        <input type="text" id="email" class="form-control" value="{{$user->email}}" name="email">
    </div>

    <div class="col-sm-4 mb-20">
        <label for="username">Username</label>
        <input type="text" id="username" class="form-control" value="{{$user->username}}" name="username">
    </div>

    <div class="col-12 mb-20">
        <h6 class="mb-15">Profile Image</h6>
        <input class="dropify" type="file" name="img">
    </div>

    <div class="col-12 text-center">
        <button type="submit" class="btn btn-success">Update</button>
    </div>

    </form>


    <hr>

    <form class="row p-5" action=" {{route('admin.updateProfilePassword')}} " method="POST">
        @csrf
        <div class="col-12 mb-4">
            <h4>Update Password</h4>
        </div>
        <div class="col-sm-4 mb-20">
            <label for="password">Password</label>
            <input type="password" id="password" class="form-control" name="password">
        </div>

        <div class="col-12 text-center">
            <button type="submit" class="btn btn-success">Update Password</button>
        </div>

    </form>
    
</div>

@endsection
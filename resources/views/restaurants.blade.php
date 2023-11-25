@extends('layouts.app')

@section('title','Restsurants')

@section('content')
    

<div class="row">

    <div class="col-12">

        <button class="button button-primary" data-toggle="modal" data-target="#newrest">Add Restaurant </button>
    </div>

  

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Restaurants ({{$restaurants->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name English</th>
                                <th> Name Arabic</th>
                                <th>Email</th>
                                <th>Username</th>

                                <th> Number Of Messages</th>

                                <th> Restaurant Type</th>

                                <th> Returntion</th>
                                <th> Online Ordering Pickup</th>
                                <th> Online Ordering Delivery</th>

                                <th> Manager Name</th>
                                <th> Manager Phone</th>
                                <th>Sender Name</th>
                                <th>Reset Password</th>
                                <th>Add Messages</th>
                                <th>Change Sender Name</th>
                                <th>Update Modules</th>

                                <th>Contected With Foodics</th>

                                <th>Status</th>
                                <th>Active/Disable</th>

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($restaurants as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->name_eng}}</td>
                                <td>{{$rest->name_ar}}</td>
                                <td>{{$rest->email}}</td>
                                <td>{{$rest->restaurantUsers->first()->name}}</td>

                                <td>{{$rest->number_of_messages}}</td>

                                <td>{{$rest->type}}</td>

                                <td>
                                    @if ($rest->returntion == 0 )
                                        
                                        <span class="text-success text-bold">Enabled</span> 

                                    @else

                                        <span class="text-danger text-bold">Disabled</span> 
                                        
                                    @endif
                                
                                </td>

                                  <td>
                                    @if ($rest->online_ordering_pickup == 0 )
                                        
                                        <span class="text-success text-bold">Enabled</span> 

                                    @else

                                        <span class="text-danger text-bold">Disabled</span> 
                                        
                                    @endif
                                
                                </td>

                                <td>
                                    @if ($rest->online_ordering_delivery == 0 )
                                        
                                        <span class="text-success text-bold">Enabled</span> 

                                    @else

                                        <span class="text-danger text-bold">Disabled</span> 
                                        
                                    @endif
                                
                                </td>

                                <td>{{$rest->manager_name}}</td>
                                <td> {{$rest->manager_phone}} </td>
                                <td>{{$rest->sender_name}}</td>

                                <td>

                                    <button class="btn btn-primary" data-toggle="modal" data-target="#resetpass-{{$rest->id}}">
                                       
                                            <i class="zmdi zmdi-edit"></i>
                                       
                                    </button>
                                  
                                </td>

                                <td>

                                    <button class="btn btn-success" data-toggle="modal" data-target="#addmesgs-{{$rest->id}}">
                                       
                                            <i class="fa fa-envelope"></i>
                                       
                                    </button>
                                  
                                </td>

                                <td>

                                    <button class="btn btn-success" data-toggle="modal" data-target="#changesender-{{$rest->id}}">
                                       
                                            <i class="fa fa-paper-plane-o"></i>
                                       
                                    </button>
                                  
                                </td>

                               

                                <td>

                                    <button class="btn btn-success" data-toggle="modal" data-target="#updatemodules-{{$rest->id}}">
                                       
                                            <i class="fa fa-th-large"></i>
                                       
                                    </button>
                                  
                                </td>

                                <td>
                                    @if ($rest->access_token == null)
                                        <span class="text-bold text-danger">Not Connected</span>
                                    @else
                                        <span class="text-bold text-success">Connected</span>
                                        
                                    @endif
                                </td>

                                @if ($rest->is_deleted == 0 )
                                <td class="text-bold text-success"> Active </td>

                                <td>
                                    <a class="btn btn-danger" href=" {{route('admin.deleteRestaurant',$rest->id)}} ">
                                        <div>
                                            
                                                <i class="fa fa-lock mx-2"></i> 
                                 
                                                <i class="fa fa-unlock mx-2"></i>
                                          
                                        </div>
                                      
                                    </a>
                                </td>
                                   
                               @else
                                <td class="text-bold text-danger"> Disabled </td>
                                   
                                
                                <td>
                                    <a class="btn btn-success" href=" {{route('admin.deleteRestaurant',$rest->id)}} ">
                                        <div>
                                            
                                                <i class="fa fa-lock mx-2"></i> 
                                 
                                                <i class="fa fa-unlock mx-2"></i>
                                          
                                        </div>
                                      
                                    </a>
                                </td>
                               @endif
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



<div class="modal fade" id="newrest">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Restaurant</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.createRestaurant')}}" method="POST">
            <div class="modal-body">

                @csrf
                <div class="row">
                    <div class="col-sm-4 mb-20">
                        <label for="nameeng"> Name English</label>
                        <input type="text" id="nameeng" class="form-control" name="name_eng">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="namear">Name Arabic</label>
                        <input type="text" id="namear" class="form-control" name="name_ar">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="managername">Manager Name</label>
                        <input type="text" id="managername" class="form-control" name="manager_name">
                    </div>


                    <div class="col-sm-4 mb-20">
                        <label for="phone">Manager Phone</label>
                        <input type="text" id="phone" class="form-control" name="manager_phone">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="email">Manager Email</label>
                        <input type="text" id="email" class="form-control" name="manager_email">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="email">Restaurant Email</label>
                        <input type="text" id="email" class="form-control" name="email">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="password"> Password</label>
                        <input type="password" id="password" class="form-control" name="password">
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="username">Access Username</label>
                        <input type="text" id="username" class="form-control" name="username">
                    </div>

                  

                    <div class="col-sm-4 mb-20">
                        <label for="branch">Has Branches</label>
                        <select id="branch" class="form-control" name="branch">
                            <option value="Yes"> YES </option>
                            <option value="No">NO </option>
                        </select>
                    </div>

                      <div class="col-sm-4 mb-20">
                        <label for="type">Type</label>
                        <select id="type" class="form-control" name="type">
                            <option value="Fine dining"> Fine dining </option>
                            <option value="Casual dining"> Casual dining </option>
                            <option value="Quick services">Quick services</option>

                        </select>
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="pickup">Online Ordering Pickup</label>
                        <select id="pickup" class="form-control" name="pickup">

                            <option selected value="1">Disable </option>
                            <option value="0">Enable</option>

                        </select>
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label for="delivery">Online Ordering Delivery</label>
                        <select id="delivery" class="form-control" name="delivery">

                            <option selected value="1">Disable </option>
                            <option value="0">Enable</option>

                        </select>
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


@foreach ($restaurants as $rest)
    
<div class="modal fade" id="resetpass-{{$rest->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Rest Password for - {{$rest->name_eng}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.resetRestaurantPassword')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$rest->id}}">
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



@foreach ($restaurants as $rest)
    
<div class="modal fade" id="addmesgs-{{$rest->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Messages For - {{$rest->name_eng}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.restaurantAddQuota')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$rest->id}}">
                @csrf
                <div class="row">
                    <div class="col-sm-4 mb-20">
                        <label for="restpass">Number Of Messages</label>
                        <input type="number" min="0" id="restpass" class="form-control" name="quota">
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


@foreach ($restaurants as $rest)
    
<div class="modal fade" id="changesender-{{$rest->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Sender Name For - {{$rest->name_eng}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.restaurantSenderName')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$rest->id}}">
                @csrf
                <div class="row">
                    <div class="col-sm-4 mb-20">
                        <label for="sender">New Sender Name</label>
                        <input type="text" min="0" id="sender" class="form-control" name="sender">
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

@foreach ($restaurants as $rest)
    
<div class="modal fade" id="updatemodules-{{$rest->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Module For - {{$rest->name_eng}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.restaurantUpdateModules')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$rest->id}}">
                @csrf
                <div class="row">
                   

                    <div class="col-sm-4 mb-20">
                        <label >Returntion</label>
                        <select  class="form-control" name="returntion">

                            <option selected  value="{{$rest->returntion}}">
                                @if ($rest->returntion == 0)
                                    Enable
                                @else
                                     Disbale
                                @endif    
                            </option>

                            <option value="1">Disable </option>
                            <option value="0">Enable</option>

                        </select>
                    </div>

                    
                    <div class="col-sm-4 mb-20">
                        <label for="type">Online Ordering Pickup</label>
                        <select id="type" class="form-control" name="pickup">
                            <option selected  value="{{$rest->online_ordering_pickup}}">
                            @if ($rest->online_ordering_pickup == 0)

                                Enable

                            @else

                                 Disbale

                            @endif    
                            </option>

                            <option  value="1">Disable </option>
                            <option value="0">Enable</option>

                        </select>
                    </div>

                    <div class="col-sm-4 mb-20">
                        <label >Online Ordering Delivery</label>
                        <select  class="form-control" name="delivery">

                            <option selected  value="{{$rest->online_ordering_delivery}}">
                                @if ($rest->online_ordering_delivery == 0)

                                    Enable

                                @else

                                     Disbale

                                @endif    
                            </option>

                            <option value="1">Disable </option>
                            <option value="0">Enable</option>

                        </select>
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
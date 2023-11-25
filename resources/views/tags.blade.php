@extends('layouts.app')

@section('title','Automated Tags')

@section('content')
    

<div class="row">

    <div class="col-12">
        <hr>
    </div>

    <div class="col-12 col-lg-12 col-xxl-12 d-flex">
        <div class="card flex-fill">
            <div class="card-header">
    
                <h5 class="card-title mb-0">All Automated Tags ({{$tags->count()}})</h5>

            </div>
            <div style="overflow-x: auto">
                    <table class="table table-hover my-0">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th> Name Arabic</th>
                                <th> Name English</th>
                                <th>Number Of Automated Tags</th>
                            

                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($restaurants as $rest)
                                
                            <tr>
                                <th> {{$rest->id}} </th>
                                <td>{{$rest->name_ar}}</td>
                                <td>{{$rest->name_eng}}</td>

                                <td>{{$rest->autoTags->count()}}</td>

                               
                              
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

{{-- 

<div class="modal fade" id="newtag">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Tag</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.createTag')}}" method="POST">
            <div class="modal-body">

                @csrf
                <div class="row">
                    <div class="col-sm-6 mb-20">
                        <label for="nameeng"> Name</label>
                        <input type="text" id="nameeng" class="form-control" name="name">
                    </div>

                    <div class="col-sm-6 mb-20">
                        <label for="phone">Type</label>
                        <select class="form-control" name="type" id="">
                            <option value="Occasions">Occasions</option>
                            <option value="Restrictions">Restrictions</option>
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
</div> --}}

{{-- 
@foreach ($tags as $tag)
    
<div class="modal fade" id="edittag-{{$tag->id}}">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit for - {{$tag->name}}</h5>
                <button class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span></button>
            </div>
         <form action="{{route('admin.updateTag')}}" method="POST">
            <div class="modal-body">

                <input type="hidden" name="id" value="{{$tag->id}}">

                @csrf
                <div class="row">
                    <div class="col-sm-6 mb-20">
                        <label for="name"> Name</label>
                        <input type="text" id="name" class="form-control" value="{{$tag->name}}" name="name">
                    </div>

                    <div class="col-sm-6 mb-20">
                        <label for="phone">Type</label>
                        <select class="form-control" name="type" id="">
                            <option selected value="{{$tag->type}}">{{$tag->type}}</option>

                            <option value="Occasions">Occasions</option>
                            <option value="Restrictions">Restrictions</option>
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

@endforeach --}}


@endsection
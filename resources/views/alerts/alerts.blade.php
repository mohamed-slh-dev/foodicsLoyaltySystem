

    @if ($message = Session::get('success'))

    <div class=" alert rounded bg-success text-white text-center mt-3 py-3">

        <button type="button" style="background: transparent;border: none;color: white;" class="close" data-dismiss="alert">×</button>
        
        <i class="fa fa-check"></i>{{ $message }}

    </div>

    {{Session::forget('success')}}
    @endif

    @if (Session::get('assigned'))

    <div class=" alert rounded bg-success text-white text-center mt-3 py-3">

        <button type="button" style="background: transparent;border: none;color: white;" class="close" data-dismiss="alert">×</button>
        
        <i class="icon fas fa-check"></i>تم تعيين السائق بنجاح

    </div>

    {{Session::forget('assigned')}}
    @endif


    @if (Session::get('deleted'))

  
    <div class="alert rounded bg-danger text-center mt-3 py-3 text-white border" role="alert">
        <button type="button" style="background: transparent;border: none;color: white;" class="close" data-dismiss="alert">×</button>

      تم مسح العنصر بنجاح

        {{Session::forget('deleted')}}
    </div>

   

    @endif


    @if ($message = Session::get('warning'))

    <div class="alert alert-warning alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <i class="icon fas fa-exclamation-triangle"></i> {{ $message }}

    </div>

    @endif


    @if ($message = Session::get('info'))

    <div class="alert alert-info alert-block">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <i class="icon fas fa-info"></i> {{ $message }}

    </div>

    @endif

    @if ($errors->any())

    <div class="alert alert-danger">

        <button type="button" class="close" data-dismiss="alert">×</button>

        <i class="icon fas fa-ban"></i> Please check the form below for errors
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

    </div>

    @endif


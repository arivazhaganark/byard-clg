@extends('layouts.app')
@section('content')
<div style="margin-bottom: 170px;"></div>
<div class="container">   
    <div class="row"> 
        <div class="col-md-6 col-md-offset-3" style="">
            <div class="panel panel-default">
            <div class="panel-heading" align="center" style="background: #ffffff;">
             <?php 

               $chkPath=base_path() . "/public/uploads/thumbnail/".$filePathImage;
               if ( file_exists($chkPath)) {
                ?>
                 <img src="{{URL::asset('uploads/thumbnail')}}/{{$filePathImage}}" alt="Logo">
                <?php
               } 
             ?>
                <p><font size="4px" color='#000000'><b>User Login</b></font></p> 
                </div>

                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('user/authenticate') }}">
                        {{ csrf_field() }}

                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label for="email" class="col-md-4 control-label">Username</label>

                            <div class="col-md-6">
                                <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required autofocus>
                                @if (session('status'))
                                <span style="color: crimson; font-weight: bold; font-style: italic; font-size: 12px;">
                                    {{ session('status') }}
                                </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label for="password" class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control" name="password" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-8 col-md-offset-8">
                                <button type="submit" class="btn btn-black">
                                    Login
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
.btn-red{background:#ca151c ;
    color:#fff;
    border:none;
    box-shadow:none;
    padding:5px 10px;
    border-radius:0px;
}

.btn-red:focus{
    outline:none;
    color:#fff;
}

.btn-red:hover{
    outline:none;
    color:#fff;
}

.btn-black{background:#000;
    color:#fff;
    border:none;
    box-shadow:none;
    padding:5px 10px;
    border-radius:0px;
}

.btn-black:focus{
    outline:none;
    color:#fff;
}

.btn-black:hover{
    outline:none;
    color:#fff;
}
</style>
@endsection

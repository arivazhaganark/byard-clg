@extends('backend.layouts.app')
@section('htmlheader_title')
Login
@endsection
@section('content')
<div class="container">
    <div style="margin-bottom:200px;"></div> 
    <div class="row">
        <div class="login-logo"></div>
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-default"> 
            <div class="panel-heading" align="center" >                      
                 {{--<div class="panel-heading" align="center" ><img src="{{ asset('images/bricky-admin-logo.png') }}" alt="Logo"></div> --}}
                 <?php  $chkPath=base_path() . "/public/uploads/thumbnail/" . $filePathImage; 
                    if ( file_exists($chkPath)) {
                        ?>
                       <img src="{{URL::asset('uploads/thumbnail')}}/{{$filePathImage}}" alt="Logo">
                       <?php
                    } ?> 
                    <p><font size="4px" color='#000000'><b>Admin Login</b></font></p> 
                </div>
                <div class="panel-body">
                    <form class="form-horizontal" role="form" method="POST" action="{{ url('/admin/authenticate') }}">
                        {{ csrf_field() }}                  
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Email</label>

                            <div class="col-md-6">
                                <input type="text" class="form-control" name="email" value="{{ old('email') }}">
                                @if ($errors->has('email'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                                  @if (session('status'))
                                <div style="font-size: 11px; color: crimson; font-weight: bold;">
                                    {{ session('status') }}
                                </div>
                                @endif
                               <!-- @if (count($errors)) 
                                   <div>
                                        @foreach($errors->all() as $error) 
                                            <p>{{ $error }}</p>
                                        @endforeach 
                                    </div>
                                @endif  -->
                            </div>
                        </div>

                        <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">Password</label>

                            <div class="col-md-6">
                                <input type="password" class="form-control" name="password">

                                @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div>

                        <!-- <div class="form-group{{ $errors->has('license') ? ' has-error' : '' }}">
                            <label class="col-md-4 control-label">License</label>

                            <div class="col-md-6">
                            <select name='license' id='license' >
                             <option value=""> Select </option>
                             <option value="1">Voice</option>
                             <option value="2">Voice screen </option>
                             <option value="3">Voice video</option>
                             <option value="4">Voice video screen</option>
                            </select>
                               

                                @if ($errors->has('license'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('license') }}</strong>
                                </span>
                                @endif
                            </div>
                        </div> -->

                        <!--<div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="remember"> Remember Me
                                    </label>
                                </div>
                            </div>
                        </div>-->
                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-black">
                                    <i class="fa fa-btn fa-sign-in"></i> Login
                                </button> &nbsp; <button type="button" class="btn btn-red" onclick="window.location='{{ url('admin') }}'">
                                    <i class="fa fa-btn fa-times"></i> Cancel
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
@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create User Profile
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="password_form" id="password_form" action="{{ url('admin/change_password/store') }}" enctype="multipart/form-data" onsubmit="return false;">

                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Change password</h3>
                                <!--<div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/userCreation') }}"><i class="fa fa-arrow-left"></i> Back</a> </div> -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Old password<span class="text-red">*</span></label>
                                  <input name="old_password" id="old_password" type="password"  class="form-control" placeholder="Old password" value="" autocomplete="off" />
                                </div>

                                
                                <div class="form-group">
                                    <label id="username">New password<span class="text-red">*</span></label>
                                    <input name="new_password" id="new_password" type="password"  class="form-control" placeholder="New password" value="" autocomplete="off" />
                                </div>

 <div class="form-group">
                                    <label id="username">Confirm password<span class="text-red">*</span></label>
                                    <input name="confirm_password" id="confirm_password" type="password"  class="form-control" placeholder="Confirm password" value="" autocomplete="off" />
                                </div>
                                 
                                
 

                                                
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_user" id="btn_Student" type="submit">Submit</button>
                                </div>
                                
                            </div>
                            <!-- /.box-body --> 
                            <!-- /.box --> 
                        </div>
                    </div>                 
                </div>
            </form>

        </div>
    </div>


    
</div>
<script src="{{ asset('js/admin_pagejs/user-change-password-validation.js?v=1.00') }}"></script>
@endsection


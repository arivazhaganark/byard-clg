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
            <form role="form" method="post" name="userFrm" id="userFrm" action="{{ url('admin/userCreation/update') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value='{{$id}}'> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add User</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/userCreation') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">User Name<span class="text-red">*</span></label>
                                  <input name="username" id="username" type="text"  class="form-control" placeholder="User name" value="<?php echo isset($getDetails[0]->name)?$getDetails[0]->name:''; ?>" autocomplete="off" />
                                </div>

                                
                                <div class="form-group">
                                    <label id="username">User E-mail<span class="text-red">*</span></label>
                                    <input name="useremail" id="useremail" type="text"  class="form-control" placeholder="Email-id" value="<?php echo isset($getDetails[0]->email)?$getDetails[0]->email:''; ?>" autocomplete="off" />
                                </div>
                             <!--    <div class="form-group">
                                   <input id="voice" <?php  //if($getDetails[0]->voice==1) { //echo 'checked=true'; } ?> class="chkall"  name="licence[]" value="1" type="checkbox"><label id="username">Voice</label>
                                   <input id="voicescreen" <?php  //if($getDetails[0]->voice_screen==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="2" type="checkbox"><label id="username">Voice/Screen</label>
                                    <input id="voicevid" <?php  //if($getDetails[0]->voice_video==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="3" type="checkbox"><label id="username">Voice/Video</label>
                                    <input id="voicevidscr" <?php  //if($getDetails[0]->voice_video_screen==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="4" type="checkbox"> <label id="username">Voice/Video/Screen</label>
                                </div> -->
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
<script src="{{ asset('js/admin_pagejs/userCreation-validation.js?v=1.67') }}"></script>
@endsection
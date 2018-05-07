@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Staff
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="staffFrm" id="staffFrm" action="{{ url('admin/schstaff/update') }}" >
                {{ csrf_field() }}
                <?php
                    $scl_name="";
                    
                    $id="";; 
                  foreach ($sclStaff as $key => $value) {
                    $sCode=$value->staff_code;
                    $sName=$value->staff_name;
                    $id=$value->scl_stf_id;
                    $voice=$value->v_permission;
                    $voice_screen=$value->v_s_permission;
                    $voice_video=$value->v_vid_permission;
                    $voice_video_screen=$value->v_vid_s_permission;
                  }


                ?>
                <input type="hidden" name="hidid" id="hidid" value="{{$id}}"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Staff</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstaff') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Staff Code<span class="text-red">*</span></label>
                                    <input name="scode" id="scode" type="text" value='{{$sCode}}' class="form-control" placeholder="Staff Code "  autocomplete="off" />
                                </div>

                                <div class="form-group">
                                    <label id="username">Staff Name<span class="text-red">*</span></label>
                                    <input name="sname" id="sname" type="text" value='{{$sName}}' class="form-control" placeholder="Staff Name"  autocomplete="off" />
                                </div>

                                 <div class="form-group">
                                   <input id="voice" <?php  if($voice==1) { echo 'checked=true'; } ?> class="chkall"  name="licence[]" value="1" type="checkbox"><label id="username">Voice</label>
                                   <input id="voicescreen" <?php  if($voice_screen==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="2" type="checkbox"><label id="username">Voice/Screen</label>
                                    <input id="voicevid" <?php  if($voice_video==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="3" type="checkbox"><label id="username">Voice/Video</label>
                                    <input id="voicevidscr" <?php  if($voice_video_screen==1) { echo 'checked=true'; } ?> class="chkall" name="licence[]" value="4" type="checkbox"> <label id="username">Voice/Video/Screen</label>
                                </div>
                                

                                  
                                            
                                <div class="clearfix "></div>
                                 
                                <div class="clearfix "></div>
                                <div class="box-footer">
                                <button class="btn btn-primary" name="btn_staff" id="btn_staff" type="submit">Submit</button>
                                </div>
                                </form>
                            </div>
                             
                        </div>
                    </div>                 
                </div>
            </form>

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/schoolStaff-validation.js?v=1.99') }}"></script>
@endsection

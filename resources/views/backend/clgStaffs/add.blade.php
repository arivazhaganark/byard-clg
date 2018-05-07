@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Staff Profile
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="staffFrm" id="staffFrm" action="{{ url('admin/clgstaff/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Staff</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstaff') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Staff Code<span class="text-red">*</span></label>
                                    <input name="scode" id="scode" type="text"  class="form-control" placeholder="Staff Code" value="{!! old('scode') !!}" autocomplete="off" />
                                </div>
                                 
                                <div class="form-group">
                                    <label id="username">Staff Name<span class="text-red">*</span></label>
                                    <input name="sname" id="sname" type="text"  class="form-control" placeholder="Staff Name" value="{!! old('sname') !!}" autocomplete="off" />
                                </div>

                                 <div class="form-group">
                                   <input id="voice" class="chkall" name="licence[]" value="1" type="checkbox"><label id="username">Voice</label>
                                  
                                    <input id="voicescreen" class="chkall" name="licence[]" value="2" type="checkbox"><label id="username">Voice/Screen</label>
                                    
                                    <input id="voicevid" class="chkall" name="licence[]" value="3" type="checkbox"><label id="username">Voice/Video</label>
                                   
                                    <input id="voicevidscr" class="chkall" name="licence[]" value="4" type="checkbox"> <label id="username">Voice/Video/Screen</label>
                                </div>
                                
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_staff" id="btn_staff" type="submit">Submit</button>
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

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        <form role="form" method="post" name="staffFrmBulk" id="staffFrmBulk" action="{{ url('admin/clgstaff/bulkstore') }}" enctype="multipart/form-data">
        {{ csrf_field() }} 
         <div class="tab-content"> 
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Staff Bulk Upload</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" download="staffprofileUpload_xls.xls" href="{{ asset('/uploads/bulk-xls-file/staffprofileUpload_xls.xls') }}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download bulk uplaod xls file format </a></div>
                               <!--  <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstaff') }}"><i class="fa fa-arrow-left"></i> Back</a> </div> -->
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Upload XL File<span class="text-red">*</span></label>
                                    <input type="file" name="import_file" />
                                </div>                          
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_staff" id="btn_staff" type="submit">Submit</button>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             </form>
        </div>
    </div>


</div>
<script src="{{ asset('js/admin_pagejs/schoolStaff-validation.js?v=1.13') }}"></script>
@endsection

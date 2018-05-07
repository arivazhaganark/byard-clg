@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Staff subject mapping
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="subjectFrm" id="subjectFrm" action="{{ url('admin/clgstaffsubmapp/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Staff-subject mapping</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstaffsubmapp') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;">
                                <!-- text input -->
                                 <div class="form-group">
                                  <label id="username">Staff Name<span class="text-red">*</span></label>
                                     <select id="selectstaffCls" name="selectstaffCls" > 
                                     <option value="">--Select Staff--</option>
                                     @foreach ($staffAll as $sId)
                                       <option   value='{{$sId->cl_stf_id}}'>{{$sId->staff_name}} ( {{$sId->staff_code}} ) </option>
                                     @endforeach
                                     </select>
                                </div>

                                <div class="form-group">
                                  <label id="username">Course Name<span class="text-red">*</span></label>
                                     <select id="selectCour" name="selectCour" > 
                                     <option value="">--Select Course--</option>
                                     @foreach($getCourse as $Val)
                                       <option value="{{$Val->course_id}}">{{strtoupper($Val->grad_name)}}-{{strtoupper($Val->depart_name)}}-{{strtoupper($Val->course_name)}}</option>
                                     @endforeach
                                   
                                     </select>
                                </div>

                                <div class="form-group">
                                  <label id="username">Subject Name<span class="text-red">*</span></label>
                                  <div class="multiselect" id='sectName'  >
                                   </div>
                                   <label id="sectName-error" class="errornew" for="selectsectName"> </label>
                                   <input type="hidden" value="" name="hidcls" id="hidcls" >
                                </div>  

                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_map_subject" id="btn_map_subject" type="submit">Submit</button>
                                </div>
                                </form>
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
<script src="{{ asset('js/admin_pagejs/collegeStfSubMap-validation.js?v=1.00') }}"></script>
<!-- <script src="{{ asset('js/admin_pagejs/multiselect.js?v=1.00') }}"></script> -->


<style type="text/css">
    .errorLicence,.error1{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

  <style type="text/css">
  .multiselect {
    width:15em;
    height:10em;
    border:solid 1px #c0c0c0;
    overflow:auto;
}
 
.multiselect label {
    display:block;
    font-size: 10px;
}
 
.multiselect-on {
    color:#ffffff;
    background-color:#000099;
}
.errornew
{
    color: #cc0000;
    font-size: 11px;
}
</style>


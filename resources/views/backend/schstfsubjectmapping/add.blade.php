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
            <form role="form"  method="post" name="subjectFrm" id="subjectFrm" action="{{ url('admin/schstaffsubmapp/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Staff-subject mapping</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstaffsubmapp') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;">
                                <!-- text input -->
                                 <div class="form-group">
                                  <label id="username">Staff Name<span class="text-red">*</span></label>
                                     <select id="selectstaffCls" name="selectstaffCls" > 
                                     <option value="">--Select Staff--</option>
                                     @foreach ($staffAll as $sId)
                                       <option   value='{{$sId->scl_stf_id}}'>{{$sId->staff_name}} ( {{$sId->staff_code}} ) </option>
                                     @endforeach
                                     </select>
                                </div>

                                <div class="form-group">
                                  <label id="username">Class Name<span class="text-red">*</span></label>
                                     <select id="selectClass" name="selectClass" > 
                                     <option value="">--Select Class--</option>
                                     </select>
                                </div>

                                <!-- <div class="form-group">
                                  <label id="username">Section Name<span class="text-red">*</span></label>
                                     <select id="selectSection" name="selectSection" > 
                                     <option value="">--Select Section--</option>
                                     </select>
                                </div> -->

                                <div class="form-group">
                                  <label id="username">Section Name<span class="text-red">*</span></label>
                                  <div class="multiselect" id='sectName'  >
                                   </div>
                                   <label id="sectName-error" class="errornew" for="selectsectName"> </label>
                                   <input type="hidden" value="" name="hidcls" id="hidcls" >
                                </div>  

                                <!-- <div class="form-group">
                                  <label id="username">Subject<span class="text-red">*</span></label>
                                  <div class="multiselect" id='subName'>
                                  
                                   </div>
                                   <label id="subName-error" class="errornew" for="selectsubName">Select subject</label>
                                   <input type="hidden" value="" name="hidsub" id="hidsub" >
                                </div>  -->


                                

                                
                                 
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
<script src="{{ asset('js/admin_pagejs/schoolStfSubMap-validation.js?v=1.01') }}"></script>
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
<!--
    
    span.multiselect-native-select {
    position: relative
}
span.multiselect-native-select select {
    border: 0!important;
    clip: rect(0 0 0 0)!important;
    height: 1px!important;
    margin: -1px -1px -1px -3px!important;
    overflow: hidden!important;
    padding: 0!important;
    position: absolute!important;
    width: 1px!important;
    left: 50%;
    top: 0% !important;
}
.multiselect-native-select .open  ul#selectMulCls{top:0%!important;}
.multiselect-container {
    position: absolute;
    list-style-type: none;
    margin: 0;
    padding: 0
}
.multiselect-container .input-group {
    margin: 5px
}
.multiselect-container>li {
    padding: 0
}
.multiselect-container>li>a.multiselect-all label {
    font-weight: 700
}
.multiselect-container>li.multiselect-group label {
    margin: 0;
    padding: 3px 20px 3px 20px;
    height: 100%;
    font-weight: 700
}
.multiselect-container>li.multiselect-group-clickable label {
    cursor: pointer
}
.multiselect-container>li>a {
    padding: 0
}
.multiselect-container>li>a>label {
    margin: 0;
    height: 100%;
    cursor: pointer;
    font-weight: 400;
    padding: 3px 0 3px 30px
}
.multiselect-container>li>a>label.radio, .multiselect-container>li>a>label.checkbox {
    margin: 0
}
.multiselect-container>li>a>label>input[type=checkbox] {
    margin-bottom: 5px
}
.btn-group>.btn-group:nth-child(2)>.multiselect.btn {
    border-top-left-radius: 4px;
    border-bottom-left-radius: 4px
}
.form-inline .multiselect-container label.checkbox, .form-inline .multiselect-container label.radio {
    padding: 3px 20px 3px 40px
}
.form-inline .multiselect-container li a label.checkbox input[type=checkbox], .form-inline .multiselect-container li a label.radio input[type=radio] {
    margin-left: -20px;
    margin-right: 0
}
</style> -->

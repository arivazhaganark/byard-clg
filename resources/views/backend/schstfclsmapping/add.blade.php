@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Staff class mapping
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="subjectFrm" id="subjectFrm" action="{{ url('admin/schstfclsmap/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add staff-class mapping</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstfclsmap') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
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
                                 <div style="float: left;width: 100%;">
                                <div class="form-group">
                                    <label id="username" style="float: left;width: 35%;">Class and Section<span  class="text-red">*</span></label>
                                    
                                        <select  id="selectMulCls" name='selectMulCls[]' class="multiselect-ui form-control" multiple="multiple">

                                          @foreach ($classId as $cId)
                                          <optgroup label="{{$cId->sch_class}}">
                                    
                                          <?php 
                                           $getSection=DB::table('at_school_class_section_mapping')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_section_mapping.sec_id')->where(['at_school_class_section_mapping.sch_cls_id'=>$cId->sch_cls_id])->orderBy('at_school_section_master.sec_id','ASC')->get();
                                           foreach ($getSection as $key => $value) {
                                            ?>
                                           <option value='{{$value->sec_id}}#@#{{$cId->sch_cls_id}}'>{{$value->section_name}}  </option>   
                                            <?php
                                               
                                           }

                                          ?>

                                          
                                           </optgroup>
                                       
                                        @endforeach  
                                        </select>
                                          <div style="float: left;width: auto;"> 
                                      <label class="error1" style='display:none'>Select class </label>
                                </div>
                                 </div>
                               </div>
                                 

                                
                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_subject" id="btn_add_subject" type="submit">Submit</button>
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
<script src="{{ asset('js/admin_pagejs/schoolStfClsMap-validation.js?v=1.00') }}"></script>
<script src="{{ asset('js/admin_pagejs/multiselect.js?v=1.00') }}"></script>


<style type="text/css">
    .errorLicence,.error1{color: #cc0000;
    font-size: 11px;}
span.multiselect-native-select {float: left;width:  auto;margin: 0 10px; }
</style>
@endsection

<style type="text/css">
    
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
</style>

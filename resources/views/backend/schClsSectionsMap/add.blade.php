@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create class section mapping
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="sectionFrm" id="sectionFrm" action="{{ url('admin/schsectionmap/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Section</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schsectionmap') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Class Name<span class="text-red">*</span></label>
                                    <select id="selectCls" name="selectCls" > 
                                     <option value="">--Select Class--</option>
                                     @foreach ($classId as $cId)
                                       <option value='{{$cId->sch_cls_id}}'>{{$cId->sch_class}}  </option>
                                     @endforeach
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Class Name<span class="text-red">*</span></label>
                                    <select id="selectSec" name="selectSec" > 
                                     <option value="">--Select Section--</option>
                                     @foreach ($sectionId as $sId)
                                       <option value='{{$sId->sec_id}}'>{{$sId->section_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>

                                 <!-- <div class="form-group">
                                    <label id="username">Section Name<span class="text-red">*</span></label>
                                    <input name="sname" id="sname" type="text"  class="form-control" placeholder="Section Name" value="{!! old('sname') !!}" autocomplete="off" />
                                </div> -->
                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_section" id="btn_add_section" type="submit">Submit</button>
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
<script src="{{ asset('js/admin_pagejs/schoolClsSectionMap-validation.js?v=1.00') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Edit subject 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="subjectFrm" id="subjectFrm" action="{{ url('admin/schsubject/update') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value="{{$sub_id}}"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit subject</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schsubject') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Class Name<span class="text-red">*</span></label>
                                    <select id="selectCls" name="selectCls" > 
                                     <option value="">--Select Key--</option>
                                     @foreach ($classId as $cId)
                                     <?php 
                                     $selectVal="";
                                     if($cId->sch_cls_id==$getSub[0]->sch_cls_id)
                                     {
                                       $selectVal="selected=true";
                                     }


                                     ?>
                                       <option {{$selectVal}} value='{{$cId->sch_cls_id}}'>{{$cId->sch_class}}  </option>
                                     @endforeach
                                     </select>
                                </div>

                                 <div class="form-group">
                                    <label id="username">Subject Name<span class="text-red">*</span></label>
                                    <input name="subname" id="subname" type="text"  class="form-control" placeholder="Subject Name" value="{{$getSub[0]->sub_name}}" autocomplete="off" />
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
<script src="{{ asset('js/admin_pagejs/schoolSubject-validation.js?v=1.00') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

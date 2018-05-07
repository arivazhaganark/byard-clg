@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Edit Course
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="POST" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgcourse/update') }}" >
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value="{{$course_id}}" > 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Course</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgcourse') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                          
                            <div class="box-body">
                             <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectGrd" name="selectGrd" class="form-control"> 
                                     <option value="">--Select Key--</option>
                                     @foreach ($gradId as $cId)
                                     <?php 
                                     $checkVal="";
                                      if($cId->gr_id==$courseAll[0]->gr_id)
                                      {
                                           $checkVal="selected=true";
                                      }

                                     ?>
                                       <option {{$checkVal}} value='{{$cId->gr_id}}'>{{$cId->grad_name}}    </option>
                                     @endforeach
                                     </select>
                                </div>

                           

                                 <div class="form-group">
                                    <label id="username">Department Type <span class="text-red">*</span></label>
                                    <select id="selectDept" name="selectDept" class="form-control"> 
                                    <option value=''>--Select Department --  </option>
                                    @foreach ($getDepart as $dId)

                                     <?php 
                                     $checkDivVal="";
                                      if($dId->dep_id==$courseAll[0]->dep_id)
                                      {
                                           $checkDivVal="selected=true";
                                      }
                                     ?>
                                       <option {{$checkDivVal}} value='{{$dId->dep_id}}'>{{$dId->depart_name}}  </option>
                                     @endforeach
                                      
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Course Year <span class="text-red">*</span></label>
                                    <select id="selectYear" name="selectYear" class="form-control"> 
                                    <option value="">--Select Year--</option>
                                     @foreach ($yearCourse as $yId)
                                      <?php 
                                     $checkYrVal="";
                                      if($yId->year_id==$courseAll[0]->year_id)
                                      {
                                           $checkYrVal="selected=true";
                                      }
                                     ?>
                                       <option {{$checkYrVal}} value='{{$yId->year_id}}'>{{$yId->cour_year}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label id="username">Course Name<span class="text-red">*</span></label>
                                    <input name="cname" id="cname" type="text"  class="form-control" placeholder="Course Name" value="{{$courseAll[0]->course_name}}" autocomplete="off" />
                                </div>                                      
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_dep" id="btn_add_dep" type="submit">Submit</button>
                                </div>
                                </form>
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/clgCourse-validation.js?v=1.01') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
    .editCls{
    color: #1CA8DD;
    font-size: 20px; 
    /*font-weight: bold;*/
    cursor: pointer;
    }
</style>
@endsection

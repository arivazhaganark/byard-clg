@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Edit Student 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgstudent/update') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value="{{$stuRowId}}"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit student</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstudent') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                     <select id="selectGrd" name="selectGrd" > 
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
                          <!--        <div class="form-group">
                                    <label id="username">Division Type <span class="text-red">*</span></label>
                                    <select id="selectDiv" name="selectDiv" > 
                                     <option value="">--Select Division--</option>
                                     @foreach ($getDivision as $dId)

                                     <?php 
                                     $checkDivVal="";
                                      if($dId->division_id==$courseAll[0]->division_id)
                                      {
                                           $checkDivVal="selected=true";
                                      }

                                     ?>
                                       <option {{$checkDivVal}} value='{{$dId->division_id}}'>{{$dId->division_name}}  </option>
                                     @endforeach
                                     </select>
                                </div> -->

                                <div class="form-group">
                                    <label id="username">Department Type <span class="text-red">*</span></label>
                                    <select id="selectDept" name="selectDept" > 
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
                                    <label id="username">Course<span class="text-red">*</span></label>
                                    <select id="selectCourse" name="selectCourse" > 
                                    <option value="">--Select Course--</option>
                                    @foreach ($getCourse as $cId)
                                     <?php 
                                     $checkCourVal="";
                                      if($cId->course_id==$courseAll[0]->course_id)
                                      {
                                           $checkCourVal="selected=true";
                                      }
                                     ?>
                                       <option {{$checkCourVal}} value='{{$cId->course_id}}'>{{$cId->course_name}}  </option>
                                     @endforeach
                                    </select>
                                </div>
                               
                               <div class="form-group">
                                    <label id="username">Roll name<span class="text-red">*</span></label>
                                    <input name="rollno" id="rollno" type="text"  class="form-control" placeholder="Roll no" value="{{$roll_no}}" autocomplete="off" />
                                </div>
                                 
                                <div class="form-group">
                                    <label id="username">Student Name<span class="text-red">*</span></label>
                                    <input name="sname" id="sname" type="text"  class="form-control" placeholder="Student name" value="{{$sName}}" autocomplete="off" />
                                </div>

                                <?php 

                                     $curr_month=date('m');
                                     $curr_year=date('y');
                                    if($curr_month >= 6 )
                                    { 

                                        $year=$curr_year.(date('y')+1);
                                    }
                                    else
                                    {
                                        $year=(date('y')-1).$curr_year;

                                    }
                                ?>

                                                  
                               
                               

                                <!--  <div class="form-group">
                                    <label id="username">Course Name<span class="text-red">*</span></label>
                                    <input name="cname" id="cname" type="text"  class="form-control" placeholder="Course Name" value="{!! old('cname') !!}" autocomplete="off" />
                                </div>   -->
                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_dep" id="btn_add_dep" type="submit">Submit</button>
                                </div>
                                </form>
                            </div>
                            <!-- /.box-body --> 
                            <!-- /.box --> 
                        </div>
                    </div>                 
                </div>
             

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/clgStudent-validation.js?v=1.00') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

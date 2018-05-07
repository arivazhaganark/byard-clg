@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Student Profile
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
             <form role="form"  method="post" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgstudent/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 

                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Student</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstudent') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectGrd" name="selectGrd" > 
                                     <option value="">--Select Type--</option>
                                     @foreach ($gradId as $cId)
                                       <option value='{{$cId->gr_id}}'>{{$cId->grad_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                 <!-- <div class="form-group">
                                    <label id="username">Division Type <span class="text-red">*</span></label>
                                    <select id="selectDiv" name="selectDiv" > 
                                     <option value="">--Select Division--</option>
                                     @foreach ($getDivision as $dId)
                                       <option value='{{$dId->division_id}}'>{{$dId->division_name}}  </option>
                                     @endforeach
                                     </select>
                                </div> -->

                                <div class="form-group">
                                    <label id="username">Department Type <span class="text-red">*</span></label>
                                    <select id="selectDept" name="selectDept" > 
                                    <option value=''>--Select Department --  </option>
                                      
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Course<span class="text-red">*</span></label>
                                    <select id="selectCourse" name="selectCourse" > 
                                    <option value="">--Select Course--</option>
                                    </select>
                                </div>
                              
                                <div class="form-group">
                                    <label id="username">Roll name<span class="text-red">*</span></label>
                                    <input name="rollno" id="rollno" type="text"  class="form-control" placeholder="Roll no" value="{!! old('rollno') !!}" autocomplete="off" />
                                </div>
                                 
                                <div class="form-group">
                                    <label id="username">Student Name<span class="text-red">*</span></label>
                                    <input name="sname" id="sname" type="text"  class="form-control" placeholder="Student name" value="{!! old('sname') !!}" autocomplete="off" />
                                </div>

                                

                                <div class="form-group">
                                    <label id="username">Academic year<span class="text-red">*</span></label>
                                    <input name="ayear" readonly="true" id="ayear" type="text"  class="form-control" placeholder="Academic year" value="{{$year}}" autocomplete="off" />
                                </div>                    
                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_dep" id="btn_add_dep" type="submit">Submit</button>
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
        <form role="form" method="post" name="stuFrmBulk" id="stuFrmBulk" action="{{ url('admin/clgstudent/bulkstore') }}" enctype="multipart/form-data">
         <div class="tab-content"> {{ csrf_field() }} 
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add student bulk upload</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" download="studentCollegeprofileUpload.xls" href="{{ asset('/uploads/bulk-xls-file/studentCollegeprofileUpload.xls') }}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download bulk uplaod xls file format </a></div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                  <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectBulkGrd" name="selectBulkGrd" > 
                                     <option value="">--Select Type--</option>
                                     @foreach ($gradId as $cId)
                                       <option value='{{$cId->gr_id}}'>{{$cId->grad_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                 <!-- <div class="form-group">
                                    <label id="username">Division Type <span class="text-red">*</span></label>
                                    <select id="selectBulkDiv" name="selectBulkDiv" > 
                                     <option value="">--Select Division--</option>
                                     @foreach ($getDivision as $dId)
                                       <option value='{{$dId->division_id}}'>{{$dId->division_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>
 -->
                                <div class="form-group">
                                    <label id="username">Department Type <span class="text-red">*</span></label>
                                    <select id="selectBulkDept" name="selectBulkDept" > 
                                    <option value=''>--Select Department --  </option>
                                      
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Course<span class="text-red">*</span></label>
                                    <select id="selectBulkCourse" name="selectBulkCourse" > 
                                    <option value="">--Select Course--</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Academic year<span class="text-red">*</span></label>
                                    <input name="ayearBulk" readonly="true" id="ayearBulk" type="text"  class="form-control" placeholder="Academic year" value="{{$year}}" autocomplete="off" />
                                </div> 
                                <div class="form-group">
                                    <label id="username">Upload XLS File<span class="text-red">*</span></label>
                                    <input type="file" name="import_file" />
                                </div>
                                 

                                 <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_bulk" id="btn_add_bulk" type="submit">Bulk Upload</button>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             </form>
        </div>
    </div>



</div>
<script src="{{ asset('js/admin_pagejs/clgStudent-validation.js?v=1.00') }}"></script>
@endsection

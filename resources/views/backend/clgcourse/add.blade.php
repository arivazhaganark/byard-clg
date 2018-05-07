@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Course 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgcourse/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Course</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgcourse') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectGrd" name="selectGrd" class="form-control"> 
                                     <option value="">--Select Type--</option>
                                     @foreach ($gradId as $cId)
                                       <option value='{{$cId->gr_id}}'>{{$cId->grad_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                

                                <div class="form-group">
                                    <label id="username">Department Type <span class="text-red">*</span></label>
                                    <select id="selectDept" name="selectDept" class="form-control"> 
                                    <option value=''>--Select Department --  </option>
                                      
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Course Year <span class="text-red">*</span></label>
                                    <select id="selectYear" name="selectYear" class="form-control"> 
                                    <option value="">--Select Year--</option>
                                     @foreach ($yearCourse as $yId)
                                       <option value='{{$yId->year_id}}'>{{$yId->cour_year}}  </option>
                                     @endforeach
                                      
                                     </select>
                                </div>
                               

                                 <div class="form-group">
                                    <label id="username">Course Name<span class="text-red">*</span></label>
                                    <input name="cname" id="cname" type="text"  class="form-control" placeholder="Course Name" value="{!! old('cname') !!}" autocomplete="off" />
                                </div>  
                                 
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
                </div><br/>
             
                {{-- <div class="row">
                <div class="col-md-12">
                <form role="form" method="post" name="sectionFrmBulk" id="sectionFrmBulk" action="{{ url('admin/clgcourse/bulkstore') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                 <div class="tab-content"> 
                            <div id="en" class="tab-pane fade in active">
                                <div class="box box-primary">
                                    <div class="box-header">
                                        <h3 class="box-title text-navy">Add Course Bulk Upload</h3>
                                        <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgdepart') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
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
                </div> --}}

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/clgCourse-validation.js?v=1.01') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

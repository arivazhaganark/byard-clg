@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Subject
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
             <form role="form"  method="post" name="subjectFrm" id="subjectFrm" action="{{ url('admin/clgsubject/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 

                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Subject</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgsubject') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                                            
                                <div class="form-group">
                                    <label id="username">Course<span class="text-red">*</span></label>
                                    <select id="selectCourse" name="selectCourse" > 
                                    <option value="">--Select Course--</option>
                                    @foreach($getCourse as $key)
                                    <option value="{{$key->course_id}}">{{$key->grad_name}}-{{$key->depart_name}}-{{$key->course_name}}</option>
                                    @endforeach
                                    </select>
                                </div>
                              
                                 <div class="form-group">
                                    <label id="username">Semester<span class="text-red">*</span></label>
                                    <select id="selectSem" name="selectSem" > 
                                    <option value="">--Select Semester--</option>
                                    </select>
                                </div>
                              

                                <div class="form-group">
                                    <label id="username">Subject name<span class="text-red">*</span></label>
                                    <input name="subName" id="subName" type="text"  class="form-control" placeholder="Subject name" value="{!! old('subName') !!}" autocomplete="off" />
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
        <form role="form" method="post" name="stuFrmBulk" id="stuFrmBulk" action="{{ url('admin/clgsubject/bulkstore') }}" enctype="multipart/form-data">
         <div class="tab-content"> {{ csrf_field() }} 
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header"> 
                                <h3 class="box-title text-navy">Add Subject bulk upload</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" download="bulkSubjectUplaod.xls" href="{{ asset('/uploads/bulk-xls-file/bulkSubjectUplaod.xls') }}"><i class="fa fa-download" aria-hidden="true"></i>&nbsp;Download bulk uplaod xls file format </a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
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
<script src="{{ asset('js/admin_pagejs/clgSubject-validation.js?v=1.00') }}"></script>
@endsection

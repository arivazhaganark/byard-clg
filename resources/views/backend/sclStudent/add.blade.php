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
            <form role="form" method="post" name="studentFrm" id="studentFrm" action="{{ url('admin/schstudent/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Student</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstudent') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Class Name<span class="text-red">*</span></label>
                                    <select id="selectCls" name="selectCls" > 
                                     <option value="">--Select class--</option>

                                     @foreach ($classId as $cId)

                                      <!-- @if (Request::old('selectCls') == $cId->sch_cls_id )
                                        <option value='{{$cId->sch_cls_id}}' selected >{{$cId->sch_class}}  </option>
                                        @else -->
                                         <option value='{{$cId->sch_cls_id}}'>{{$cId->sch_class}}  </option>
                                       <!--  @endif -->
                                       
                                       @endforeach


                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Class section<span class="text-red">*</span></label>
                                    <select id="selectClsSec" name="selectClsSec" > 
                                     <option value="">--Select section--</option>
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

                                 <div class="form-group">
                                    <label id="username">Academic year<span class="text-red">*</span></label>
                                    <input name="ayear" readonly="true" id="ayear" type="text"  class="form-control" placeholder="Academic year" value="{{$year}}" autocomplete="off" />
                                </div>                    
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_Student" id="btn_Student" type="submit">Submit</button>
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
        <form role="form" method="post" name="stuFrmBulk" id="stuFrmBulk" action="{{ url('admin/schstudent/bulkstore') }}" enctype="multipart/form-data">
         <div class="tab-content"> {{ csrf_field() }} 
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add student bulk upload</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstudent') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Class Name<span class="text-red">*</span></label>
                                    <select id="selectbulkCls" name="selectbulkCls" > 
                                     <option value="">--Select class--</option>
                                     @foreach ($classId as $cId)
                                     <option value='{{$cId->sch_cls_id}}'>{{$cId->sch_class}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                <div class="form-group">
                                    <label id="username">Class section<span class="text-red">*</span></label>
                                    <select id="selectbulkClsSec" name="selectbulkClsSec" > 
                                     <option value="">--Select section--</option>
                                     </select>
                                </div>

                                <div class="form-group">
                                    <label id="username">Academic year<span class="text-red">*</span></label>
                                    <input name="ayearbulk" readonly="true" id="ayearbulk" type="text"  class="form-control" placeholder="Academic year" value="{{$year}}" autocomplete="off" />
                                </div>  

                                <div class="form-group">
                                    <label id="username">Upload XLS File<span class="text-red">*</span></label>
                                    <input type="file" name="import_file" />
                                </div>
                                 
                                
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_bulk_Student" id="btn_bulk_Student" type="submit">Submit</button>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             </form>
        </div>
    </div>



</div>
<script src="{{ asset('js/admin_pagejs/schoolStudent-validation.js?v=1.67') }}"></script>
@endsection

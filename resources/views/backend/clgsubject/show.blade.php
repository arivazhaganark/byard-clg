@extends('backend.layouts.app_inner')

@section('htmlheader_title')
Subject View 
@endsection


@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">Subject View</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgsubject/') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                
                                 <dl class="dl-horizontal">
                                    <dt>Graduation  :</dt>
                                   <dd>{{$gruadName}}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Department :</dt>
                                   <dd>{{strtoupper($depName)}}</dd> 
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Course Name  :</dt>
                                    <dd>{{strtoupper($courseName)}}</dd>
                                </dl>

                                <?php 
                                  foreach ($clgCourseSubAll as $key => $value) 
                                  {
                                    ?>
                                    <dl class="dl-horizontal">
                                    <dt>Semester-{{$value->semester_id }}  :</dt>
                                    <dd>{{strtoupper($value->subNames) }}</dd>
                                    </dl> 
                                    <?php
                               
                                   }
                                    
                                     ?>
                            </form>
                        </div>                       
                    </div>
                    <!-- /.box-body --> 
                    <!-- /.box --> 
                </div>
            </div>
        </div>
    </div>
    @endsection

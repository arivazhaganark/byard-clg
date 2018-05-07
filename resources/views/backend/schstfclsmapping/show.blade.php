@extends('backend.layouts.app_inner')

@section('htmlheader_title')
Staff class-section view 
@endsection


@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">View mapping Details</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schstfclsmap/') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                 
                                
                                <dl class="dl-horizontal">
                                    <dt>Name  :</dt>
                                    <dd>{{ $schStaff[0]->staff_name }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Customer Id  :</dt>
                                    <dd>{{ $schStaff[0]->staff_code }}</dd>
                                </dl> 

                                <?php

                                foreach ($classId as $key => $value) {

                                    ?>

                                    <dl class="dl-horizontal">
                                    <dt>Class  :</dt>
                                    <dd>{{ $value->sch_class }}( {{$value->sectionVal}} )</dd>
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

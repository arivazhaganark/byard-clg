@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Staff subject taken view 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">View subject Details</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgstaffsubmapp/') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                <dl class="dl-horizontal">
                                    <dt>Name  :</dt>
                                    <dd>{{$stfName}} {{$stfCode}} </dd>
                                </dl> 
                              @foreach($chkStafSubMap as $value)
                              <dl class="dl-horizontal">
                                    <dt>{{$value->course_name}}   </dt>
                                    <dt>Semester- {{$value->semester_id}}  </dt>
                                    <dd> {{$value->subject_name}} </dd>
                                </dl> 
                              @endforeach
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

@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Class
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="clsFrm" id="clsFrm" action="{{ url('admin/schclass/update') }}" >
                {{ csrf_field() }}
                <?php
                    $scl_name="";
                    
                    $id="";; 
                  foreach ($sclClass as $key => $value) {

                    $cls_name=$value->sch_class;
                    $id=$value->sch_cls_id;
                      
                  }


                ?>
                <input type="hidden" name="hidid" id="hidid" value="{{$id}}"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Class</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schclass') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">School Name<span class="text-red">*</span></label>
                                    <input name="cname" id="cname" type="text" value='{{$cls_name}}' class="form-control" placeholder="Class Name"  autocomplete="off" />
                                </div>

                                  
                                            
                                <div class="clearfix "></div>
                                 
                                <div class="clearfix "></div>
                                <div class="box-footer">
                                <button class="btn btn-primary" name="btn_add_class" id="btn_add_sclool" type="submit">Submit</button>
                                </div>
                                </form>
                            </div>
                             
                        </div>
                    </div>                 
                </div>
            </form>

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/school-validation.js?v=1.99') }}"></script>
@endsection

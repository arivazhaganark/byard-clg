@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Used Package 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="frm_new" id="frm_new" action="" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Use package</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/schusedpack') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->
                              <input type="hidden" id="hiddcode" type="hiddcode"  class="form-control" placeholder="Staff Code" 
                              value='{{auth()->guard("admin")->user()->email }}' autocomplete="off" /> 
                                <div class="form-group">
                                    <label id="username">Customer key<span class="text-red">*</span></label>
                                    <input name="ckey" id="ckey" type="text"  class="form-control" placeholder="Customer key" value="" autocomplete="off" />
                                </div>
                                 
                                <div class="form-group">
                                    <label id="username">Package key<span class="text-red">*</span></label>
                                    <input name="pakkey" id="pakkey" type="text"  class="form-control" placeholder="Package key" value="" autocomplete="off" />

                                    <span id="err"></span>
                                </div>
                                
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_staff" id="btn_staff" type="submit">Submit</button>
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


   



</div>
<script src="{{ asset('js/admin_pagejs/usedCdn-validation.js?v=1.43') }}"></script>
@endsection

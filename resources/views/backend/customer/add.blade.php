@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create customer
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="cusFrm" id="cusFrm" action="{{ url('admin/customer/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Customer</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/customer') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Name(industrial) <span class="text-red">*</span></label>
                                    <input name="iname" id="iname" type="text"  class="form-control" placeholder="Name" value="{!! old('iname') !!}" autocomplete="off" />
                                </div>
                                 <div class="form-group">
                                    <label id="email">Email-id <span class="text-red">*</span></label>
                                    <input name="emailid" id="emailid" type="text" class="form-control" placeholder="Email-id" value="{!! old('emailid') !!}" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label id="email">Customer-id <span class="text-red">*</span></label>
                                    <input name="cid" id="cid" value='{{$randNo}}' readonly="true" type="text" class="form-control" placeholder="Customer Id" value="" autocomplete="off" />
                                </div>
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_customer" id="btn_add_customer" type="submit">Submit</button>
                                </div>
                                </form>
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
<script src="{{ asset('js/admin_pagejs/customer-validation.js?v=1.10') }}"></script>
@endsection

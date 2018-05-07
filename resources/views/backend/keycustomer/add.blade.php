@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create customer key
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="post" name="cusKeyFrm" id="cusKeyFrm" action="{{ url('admin/keycustomer/store') }}">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value=""> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Customer Key</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/keycustomer') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Name(industrial) <span class="text-red">*</span></label>
                                    <select id="selectKey" name="selectKey" > 
                                     <option value="">--Select Key--</option>
                                     @foreach ($customerId as $cId)
                                       <option value='{{$cId->cus_id}}'>{{$cId->c_name}} ({{$cId->c_id}}) </option>
                                     @endforeach
                                     </select>
                                </div>

                                 <div class="form-group">
                                    <label class="col-md-12" id="Licencea">Licence Type <span class="text-red">*</span></label> 

                                    <input type='hidden' name='hiddCkBox' id='hiddCkBox' value="" > 
                                     @foreach ($LicenseInterface as $lId)
                                     <div class="col-sm-3">
                                            <div class="col-sm-12">
                                                <input type="checkbox" name="Licence" value="{{$lId->lin_id}}"> {{$lId->interface_name}} 
                                            </div>
                                            <div class="col-sm-12">
                                                 <input  name="type_{{$lId->lin_id}}" id="type_{{$lId->lin_id}}" type="text" value='' class="form-control" placeholder="{{$lId->interface_name}} Max "  autocomplete="off" />
                                                 <label style="display: none" id="type_{{$lId->lin_id}}-error" class="errorLicence" for="type_{{$lId->lin_id}}">Licence use count is required in numbers</label> 
                                            </div>
                                     </div> 
                                     @endforeach
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
<script src="{{ asset('js/admin_pagejs/customerKey-validation.js?v=1.72') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>
@endsection

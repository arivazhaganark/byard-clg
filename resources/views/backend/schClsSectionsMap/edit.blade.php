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
            <form role="form"  method="POST" name="cusKeyEditFrm" id="cusKeyEditFrm" >
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value="{{$cus_id}}" > 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Customer Key</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/keycustomer') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Name(industrial)  </label>
                                     {{$KeyViewCustomerAll[0]->c_name}} ({{$KeyViewCustomerAll[0]->c_id}})
                                </div>

                                 <div class="form-group">
                                    <label class="col-md-12" id="Licencea">Licence use count<span class="text-red">*</span></label> 

                                    <input type='hidden' name='hiddCkBox' id='hiddCkBox' value="" > 
                                   
 
                                     <div class="col-sm-3">
                                            <div class="col-sm-12">
                                               @if($KeyViewCustomerAll[0]->Voice !="")
                                                Voice({{ $KeyViewCustomerAll[0]->Used_Voice}}) 
                                               @else
                                               Voice(0)
                                               @endif
                                               
                                            
                                            </div>
                                            <div class="col-sm-12">
                                           <div style="width: 50%; padding:0px 5px; float: left;">
                                                 <input  name="type_1" id="type_1" type="text" value='{{$KeyViewCustomerAll[0]->Voice}}' class="form-control" placeholder=""  autocomplete="off" />
                                                 
                                            </div>

                                            <div style="width: 50%; padding:0px 5px; float: left;">
                                              <a class='editCls btn  btn-default btn-xs text-purple' lang='1'>EDIT</a> 
                                            </div>
                                            <label style="display: none" id="type_1-error" class="errorLicence" for="type_1">Licence use count is required in numbers</label> 
                                            </div>
                                     </div>

                                       <div class="col-sm-3">
                                            <div class="col-sm-12">
                                              @if($KeyViewCustomerAll[0]->Voice_S !="")
                                             Voice/Screen({{$KeyViewCustomerAll[0]->Used_Voice_S}})
                                              @else
                                              Voice/Screen(0)
                                              @endif
                                                
                                            </div>
                                            <div class="col-sm-6">
                                                 <input  name="type_2" id="type_2" type="text" value='{{$KeyViewCustomerAll[0]->Voice_S}}' class="form-control" placeholder=""  autocomplete="off" />
                                                
                                            </div>

                                            <div class="col-sm-6">
                                              <a class='editCls btn  btn-default btn-xs text-purple' lang='2'>EDIT</a> 
                                            </div>
                                             <label style="display: none" id="type_2-error" class="errorLicence" for="type_2">Licence use count is required in numbers</label> 
                                     </div>


                                       <div class="col-sm-3">
                                            <div class="col-sm-12">
                                             @if($KeyViewCustomerAll[0]->Voice_Video !="")
                                              Voice/Video({{$KeyViewCustomerAll[0]->Used_Voice_Video}})
                                              @else
                                                Voice/Video(0)
                                              @endif
                                            </div>
                                            

                                            <div class="col-sm-6">
                                                 <input  name="type_3" id="type_3" type="text" value='{{$KeyViewCustomerAll[0]->Voice_Video}}' class="form-control" placeholder=""  autocomplete="off" />
                                                  
                                            </div>

                                            <div class="col-sm-6"   >
                                             <a class='editCls btn btn-default btn-xs text-purple' lang='3'>EDIT</a> 
                                            </div>
                                            <label style="display: none" id="type_3-error" class="errorLicence" for="type_3">Licence use count is required in numbers</label>
                                     </div>


                                       <div class="col-sm-3">
                                            <div class="col-sm-12">
                                             @if($KeyViewCustomerAll[0]->Voice_Video_Screen !="")
                                                <!-- <input type="checkbox" disabled="disabled"  checked="true" name="Licence" value="4"> -->Voice/Video/Screen({{$KeyViewCustomerAll[0]->Used_Voice_Video_Screen}}) 
                                               <!--  <a class='editCls' lang='4'>Edit</a>  -->
                                              @else
                                                <!-- <input type="checkbox" name="Licence" value="4"> -->Voice/Video/Screen(0) 
                                              <!--   <a class='editCls' lang='4'   >Edit</a> -->
                                              @endif
                                                 
                                            </div>
                                            <div class="col-sm-6">
                                                 <input  name="type_4" id="type_4" type="text" value="{{$KeyViewCustomerAll[0]->Voice_Video_Screen}}" class="form-control" placeholder=""  autocomplete="off" />
                                                 
                                            </div>

                                            <div class="col-sm-6">
                                              <a class='editCls btn  btn-default btn-xs text-purple' lang='4'>EDIT</a> 
                                            </div>
                                            <label style="display: none" id="type_4-error" class="errorLicence" for="type_4">Licence use count is required in numbers</label> 

                                     </div> 

                                      
                                </div>
                                 
                               <div class="clearfix "></div>
                               <!--  <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_customer" id="btn_add_customer" type="submit">Submit</button>
                                </div> -->
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
<script src="{{ asset('js/admin_pagejs/customerKey-validation.js?v=1.99') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
    .editCls{
    color: #1CA8DD;
    font-size: 20px; 
    /*font-weight: bold;*/
    cursor: pointer;
    }
</style>
@endsection

@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Edit Department
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form"  method="POST" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgdepart/update') }}" >
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid" value="{{$dep_id}}" > 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Department</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgdepart') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                          
                            <div class="box-body">
                             <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectGrd" name="selectGrd" class="form-control"> 
                                     <option value="">--Select Type--</option>
                                     @foreach ($gradId as $cId)
                                     <?php 
                                     $checkVal="";
                                      if($cId->gr_id==$departAll[0]->gr_id)
                                      {
                                           $checkVal="selected=true";
                                      }

                                     ?>
                                       <option {{$checkVal}} value='{{$cId->gr_id}}'>{{$cId->grad_name}}    </option>
                                     @endforeach
                                     </select>
                                </div>

                                 

                                 <div class="form-group">
                                    <label id="username">Department Name<span class="text-red">*</span></label>
                                    <input name="dname" id="dname" type="text"  class="form-control"placeholder="Section Name" value="{{$departAll[0]->depart_name}}" autocomplete="off" />
                                </div>
                                 
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_dep" id="btn_add_dep" type="submit">Submit</button>
                                </div>
                                
                                 

                                  
                                 
                                
                                </form>
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             

        </div>
    </div>
</div>
<script src="{{ asset('js/admin_pagejs/clgDepart-validation.js?v=1.539') }}"></script>
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

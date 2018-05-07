@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Create Department
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="sectionFrm" id="sectionFrm" action="{{ url('admin/clgdepart/store') }}" enctype="multipart/form-data">
                {{ csrf_field() }} 
                <input type="hidden" name="hidid" id="hidid"> 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Department</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgdepart') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Graduate Type <span class="text-red">*</span></label>
                                    <select id="selectGrd" name="selectGrd" class="form-control"> 
                                     <option value="">--Select Type--</option>
                                     @foreach ($gradId as $cId)
                                       <option value='{{$cId->gr_id}}'>{{$cId->grad_name}}  </option>
                                     @endforeach
                                     </select>
                                </div>
                                 <!-- <div class="form-group">
                                    <label id="username">Division Type <span class="text-red">*</span></label>
                                    <select id="selectDiv" name="selectDiv" > 
                                     <option value="">--Select Division--</option>
                                     @foreach ($getDivision as $dId)
                                       <option value='{{$dId->division_id}}'>{{$dId->division_name}}  </option>
                                     @endforeach
                                     </select>
                                </div> -->                               

                                <div class="form-group">
                                    <label id="username">Department Name<span class="text-red">*</span></label>
                                    <input name="dname" id="dname" type="text"  class="form-control" placeholder="Department Name" value="{!! old('dname') !!}" autocomplete="off" />
                                </div>
                                 
                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_dep" id="btn_add_dep" type="submit">Submit</button>
                                </div>
                                
                            </div>
                            <!-- /.box-body --> 
                            <!-- /.box --> 
                        </div>
                    </div>                 
                </div>
            </form>



        </div>
    </div><br/>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
        <form role="form" method="post" name="sectionFrmBulk" id="sectionFrmBulk" action="{{ url('admin/clgdepart/bulkstore') }}" enctype="multipart/form-data">
        {{ csrf_field() }} 
         <div class="tab-content"> 
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Department Bulk Upload</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/clgdepart') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body col-md-6" style="float: none;" >
                                <!-- text input -->

                                <div class="form-group">
                                    <label id="username">Upload XL File<span class="text-red">*</span></label>
                                    <input type="file" name="import_file" />
                                </div>                          
                               
                               <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_staff" id="btn_staff" type="submit">Submit</button>
                                </div>
                                
                            </div>
                            
                        </div>
                    </div>                 
                </div>
             </form>
        </div>
    </div>

</div>

<script src="{{ asset('js/admin_pagejs/clgDepart-validation.js?v=2.0') }}"></script>
<style type="text/css">
    .errorLicence{color: #cc0000;
    font-size: 11px;}
</style>

@endsection

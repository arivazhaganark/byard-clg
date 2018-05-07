@extends('layouts.adminapp_inner')

@section('htmlheader_title')
CMS
@endsection
@section('content')
<section class="content">
    <div class="container-fluid">
        <div class="block-header">
          
        </div>
     
    <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">

            <form class="form-horizontal" role="form" name="edit_menu" id="edit_menu" method="POST" enctype="multipart/form-data" action="{{ asset('admin/menu/update') }}">
              
                <input type="hidden" name="id" value="{{ $menu->id }}" />
                {{ csrf_field() }}
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit CMS</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn bg-teal  btn-xs waves-effect" style="color:green;" href="{{ url('/admin/menu') }}" > Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="padding:25px;">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Name <span class="text-red">*</span></label>
                                    <input name="name" id="name" type="text" class="form-control" placeholder="Name" value="{{ $menu->name }}" autocomplete="off" />
                                </div>
                               

                                


                                      
                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_updatemenu" id="btn_updatemenu" type="submit">Submit</button>
                                </div>
                            </div>
                            <!-- /.box-body --> 
                            <!-- /.box --> 
                        </div>
                    </div>                 
                </div>
                </option>
                </select>

            </form>

        </div>
    </div>
</div>
<link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
<script src="{{ asset('js/pages/ui/notifications.js') }}"></script>
 <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/pages/forms/admin/menu-validation.js') }}"></script>
@endsection

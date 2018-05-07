
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
            <form role="form" method="post" name="add_category" id="add_category" action="" onsubmit="return false;">
                {{ csrf_field() }}
                <input type="hidden" name="hid_update_id" id="hid_update_id" value="" />
                  <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Category</h3>
                                <div class="pull-right" > <a  class="btn bg-teal  btn-xs waves-effect" style="text-align:center;" href="{{ url('/admin/category') }}"> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="padding:25px;">
                            <div class="form-group form-float">
                                <!-- text input -->
                                <div class="form-line">
                                    <label id="username">Name <span class="text-red">*</span></label>
                                    <input name="category_name" id="category_name" type="text" class="form-control" placeholder="Category Name" value="" autocomplete="off" />
                                </div>
                                </div>

                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_category" id="btn_add_category" type="submit">Submit</button>
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
</div>
</div>
</section>

<link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
<script src="{{ asset('js/pages/ui/notifications.js') }}"></script>
 <script src="{{ asset('plugins/bootstrap-select/js/bootstrap-select.js') }}"></script>
<script src="{{ asset('js/pages/forms/admin/category-validation.js?v=2.5') }}"></script>
@endsection

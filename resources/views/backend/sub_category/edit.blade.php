
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
            <form role="form" method="post" name="edit_sub_category" id="edit_sub_category" action="" onsubmit="return false;">
                {{ csrf_field() }}
                <input type="hidden" name="hid_update_id" id="hid_update_id" value="{{$sub_category->id}}" />
                <input type="hidden" name="hid_category_id" id="hid_category_id" value="{{$category->id}}" />
                  <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Edit Sub Category For "{{$category->name}}"</h3>
                                <div class="pull-right" > <a  class="btn bg-teal  btn-xs waves-effect" style="text-align:center;" href="{{ url('/admin/sub_category/'.$category->id) }}"> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="padding:25px;">
                            <div class="form-group form-float">
                                <!-- text input -->
                                <div class="form-line">
                                    <label id="username">Name <span class="text-red">*</span></label>
                                    <input name="category_name" id="category_name" type="text" class="form-control" placeholder="Sub Category Name" value="{{$sub_category->name}}" autocomplete="off" />
                                </div>
                                </div>

                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_update_sub_category" id="btn_update_sub_category" type="submit">Update</button>
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
<script src="{{ asset('js/pages/forms/admin/sub-category-validation.js?v=2.6') }}"></script>
@endsection


@extends('layouts.adminapp_inner')
@section('htmlheader_title')
Banner
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
            <form role="form" method="post" name="edit_banner" id="edit_banner" action="{{ url('/admin/banner/update') }}">
                {{ csrf_field() }}
                <input type="hidden" name="hid_update_id" id="hid_update_id" value="{{$banner->id}}" />
                <input type="hidden" name="hid_folder_name" id="hid_folder_name" value="{{$banner->id}}" />
                  <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add Banner</h3>
                                <div class="pull-right" > <a  class="btn bg-teal  btn-xs waves-effect" style="text-align:center;" href="{{ url('/admin/banner') }}"> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body" style="padding:25px;">
                            <div class="form-group form-float">
                                <!-- text input -->
                                <div class="form-line">
                                    <label id="username">Name <span class="text-red">*</span></label>
                                    <input name="name" id="name" type="text" class="form-control" placeholder="Name" value="{{$banner->name}}" autocomplete="off" />
                                </div>
                                </div>

                                <div class="form-group form-float">
                                    <!-- text input -->
                                    <div class="form-line">
                                      <span class="btn btn-primary" onclick="onChatUploadClick()">Upload</span>
                                      <span id="attach_area" style="display:none;"><img src="{{ URL("images/loading.gif") }}" /></span>
                                      <output id="file_upload_result" /><img src="{{ url('uploads/banner_attachment/'.$banner->id.'/'.$banner->image) }}" height="100" width="100" /></output>
                                      <input type="hidden" name="hid_file_name" id="hid_file_name" value="{{ $banner->image}}"  />
                                    </div>
                                    </div>

                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="update_submit_btn" id="update_submit_btn" type="submit">Update</button>
                                </div>
                            </div>
                            <!-- /.box-body -->
                            <!-- /.box -->
                        </div>
                    </div>
                </div>
            </form>

            <form id="event_upload" method="post" action="{{ asset('admin/banner/banner_attachment') }}" enctype="multipart/form-data"  >
                {{ csrf_field() }}
                <input type="hidden" name="hid_folder_name" id="hid_folder_name" value="{{$banner->id}}" />
                <input style="width:1px;height:1px;" id="uploadfile-file-chat" name="upl" type="file">
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
<script src="{{ asset('js/pages/forms/admin/banner-upload.js?v=2.7') }}"></script>
<script src="{{ asset('js/pages/forms/admin/banner-validation.js?v=2.7') }}"></script>
@endsection
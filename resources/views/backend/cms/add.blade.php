@extends('backend.layouts.app_inner')
@section('htmlheader_title')
CMS
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <form role="form" method="post" name="add_cms" id="add_cms" action="{{ url('admin/cms/store') }}">
                {{ csrf_field() }} 
                <div class="tab-content">
                    <div id="en" class="tab-pane fade in active">
                        <div class="box box-primary">
                            <div class="box-header">
                                <h3 class="box-title text-navy">Add CMS</h3>
                                <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/cms') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                            </div>
                            <!-- /.box-header -->
                            <div class="box-body">
                                <!-- text input -->
                                <div class="form-group">
                                    <label id="username">Title <span class="text-red">*</span></label>
                                    <input name="title" id="title" type="text" class="form-control" placeholder="Title" value="" autocomplete="off" />
                                </div>
                                <div class="form-group">
                                    <label id="username">Menu <span class="text-red">*</span></label>
                                    <select name="menu_id" id="menu_id" class="form-control">
                                        <option value="">Please Select</option>
                                        @foreach ($cms_menu_list as $menu)
                                           <option value="{{$menu->id}}">{{$menu->name}}</div> 
                                        @endforeach  
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputFile">Type</label>
                                    <div class="radio">
                                        <label><input name="page_type" id="content" class="" value="content" onclick="div_display_fn('content');" checked="checked"  type="radio">Content
                                        </label>
                                        &nbsp;&nbsp;
                                        <label><input name="page_type" id="link" class="" value="link" onclick="div_display_fn('link');" type="radio">Link
                                        </label>
                                    </div>
                                </div> 
                                <div id="linkdiv" style="display:none;">
                                    <label>Link <span style="font-size:9px;">(http:// must)</span> <span class="text-red">*</span></label>
                                    <div class="clearfix"></div>
                                    <div>
                                        <div class="col-md-8">
                                            <input value="" maxlength="400" class="form-control" id="page_link" name="page_link" type="text" />
                                            &nbsp; </div>
                                        <div class="col-md-4">
                                            <select class="form-control slt-box col-md-6" name="page_linktype" id="page_linktype">
                                                <option value="_self">Self</option>
                                                <option value="_blank">Blank</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="clearfix"></div>
                                </div>
                                <div id="contentdiv">
                                    <div class="form-group">
                                        <label>Content <span class="text-red"></span></label> 
                                        <div><textarea id="editor1" name="editor1" rows="10" cols="80"></textarea></div>
                                    </div>
                                    <div class="box-header">
                                        <h3 class="box-title text-navy">Seo Settings</h3>
                                    </div>  <div class="form-group">
                                        <label>Title</label>
                                        <input type="text" value="" maxlength="200" placeholder="Title" class="form-control" id="seo_title" name="seo_title">
                                        <span style="font-size:12px;" class="text-light-blue">Most search engines use a maximum of 60 chars for the title.</span> </div>
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea class="form-control" rows="4" placeholder="Description" id="seo_description" name="seo_description"></textarea>
                                        <span style="font-size:12px;" class="text-light-blue">Most search engines use a maximum of 160 chars for the description.</span> </div>
                                    <div class="form-group">
                                        <label>Keywords <span style="font-size:12px;" class="text-light-blue">(comma separated)</span></label>
                                        <textarea class="form-control" rows="5" placeholder="Keywords" id="seo_keywords" name="seo_keywords"></textarea>
                                        <span style="font-size:12px;" class="text-light-blue"> Most search engines use a maximum of 200 chars for the Keywords.</span> </div>
                                </div>
                                <div class="form-group">
                                    <label>Position <span class="text-red">*</span></label> 
                                    <input type="text" style="width: 110px;" class="form-control" name="position" id="position" autocomplete="off" placeholder="Position"  autocomplete="off"/>
                                </div>           
                                <div class="clearfix "></div>
                                <div class="box-footer">
                                    <button class="btn btn-primary" name="btn_add_cms" id="btn_add_cms" type="submit">Submit</button>
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
<script src="{{ asset('js/admin_pagejs/cms-validation.js?v=1.1') }}"></script>
<script src="{{ asset('js/tinymce/js/tinymce/tinymce.min.js') }}"></script>
<script>
    tinymce.init({
        selector: '#editor1',
        height: 200,
        theme: 'modern',
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true,
        plugins: [
            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
            'searchreplace wordcount visualblocks visualchars code fullscreen',
            'insertdatetime media nonbreaking save table contextmenu directionality',
            'emoticons template paste textcolor colorpicker textpattern imagetools codesample toc'
        ],
        toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
        toolbar2: 'print preview media | forecolor backcolor emoticons | codesample',
        image_advtab: true,
        templates: [
            {title: 'Test template 1', content: 'Test 1'},
            {title: 'Test template 2', content: 'Test 2'}
        ],
        content_css: [
            '//fonts.googleapis.com/css?family=Lato:300,300i,400,400i',
            '//www.tinymce.com/css/codepen.min.css'
        ],
        file_browser_callback: RoxyFileBrowser
    });

    function RoxyFileBrowser(field_name, url, type, win) {
        var roxyFileman = app_url + '/js/tinymce/fileman/index.html';
        if (roxyFileman.indexOf("?") < 0) {
            roxyFileman += "?type=" + type;
        } else {
            roxyFileman += "&type=" + type;
        }
        roxyFileman += '&input=' + field_name + '&value=' + win.document.getElementById(field_name).value;
        if (tinyMCE.activeEditor.settings.language) {
            roxyFileman += '&langCode=' + tinyMCE.activeEditor.settings.language;
        }
        tinyMCE.activeEditor.windowManager.open({
            file: roxyFileman,
            title: 'Roxy Fileman',
            width: 850,
            height: 650,
            resizable: "yes",
            plugins: "media",
            inline: "yes",
            close_previous: "no"
        }, {window: win, input: field_name});
        return false;
    }
</script>
@endsection

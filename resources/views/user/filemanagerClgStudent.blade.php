@extends('layouts.app')
@section('htmlheader_title')
Student File Manager
@endsection
@section('content')
<div class="container spark-screen">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif  
    <div class="row">
   @if(Session::has('message'))
   <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
   @endif  
   <div class="col-md-12">
      <div class="tab-content">
         <div id="en" class="tab-pane fade in active">
            <div class="box box-primary">
               <div  class="box-header breadcrum-fm">
                     <!--<h3 class="box-title text-navy">File Manager</h3>-->
                     <span id="brdcrum" style="">
                        <a href="{{url('/user/filemanagerclgstudent')}}" class="root">Home</a> &nbsp;&raquo;&nbsp;
                     </span>
                  </div>
               <!-- /.box-header -->
               <div class="box-body">
                  <div id="assigngrp-err" class="error" align="center" style="padding:10px; font-size: 11px; font-weight: bold; display: none;"></div>
                  <!--beard crums -->
                  <section class="breadcrum pt-10px pb-10px">
                     <div class="bg-lit">
                        <div id='headMenu' class="pull-right" style="display:block">
                            <form name="frm_search" id="frm_search" method="post" style="float: left;">     
                              {{ csrf_field() }}     
                              <input type="hidden" name="pwd" id="pwd">                 
                              <input type="hidden" name="file_id" id="file_id">
                                                      
                              <input type="text" name="search_txt" id="search_txt" placeholder="Enter search text"  autocomplete="off" style="height: 30px">
                              <input type="button" lang='' name="search" id="search" value="Search" class="btn btn-primary btn-sm p-0-10 font-14">      
                           </form>
                           &nbsp;&nbsp;  
                           <!-- <button type="button"  class="btn btn-warning btn-sm p-0-10 font-14 assign-per" id='btnRefresh' lang=''>Refresh</button> -->
                          <!--  <button type="button" id='upBtn' lang='' class="btn btn-success btn-sm p-0-10 font-14" style="background: darkcyan; border: none;" data-toggle="modal" data-target="#upFile">Upload file <i class="fa fa-upload" aria-hidden="true"></i></button> -->
                            <button type="button" lang='' id='crbtnAdvSearch' class="btn btn-info btn-sm p-0-10 font-14" data-toggle="modal" data-target="#myModal">Advance Search <i class="fa fa-folder-open" aria-hidden="true"></i></button>   
                            
                           <form name="frm_action" id="frm_action" method="post" style="float: right;">
                              {{ csrf_field() }}     
                              <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                              <select name="act" id="act" style="background: #1ca8dd; color: white; display: none;">
                                 <option value="C">Client</option>
                                 <option value="P">Partner</option>
                                 <option value="E">Employee</option>
                              </select>
                              <button style="height: 23px;padding-top: 2px; display: none;" onclick="return check_confirm_filemanager('Are you sure want to do the action?');" id="btn_action" value="Action" type="button" class="btn btn-primary btn-sm" name="btn_action">Submit</button>
                           </form>
                           <button type="button" class="btn btn-danger btn-sm p-0-10 font-14 back" style="float: right; display: none;">Back</button>
                           <br/>
                        </div>
                     </div>
                  </section>
                  <br/> 
                  <div>&nbsp;</div>
                  <div>&nbsp;</div>
                  <div class="col-xs-12 col-md-6 col-lg-12 scroll-y">
                     <div class="">
                        <div class="row">
                           <div class="col-md-12">
                              <div class="ibox float-e-margins">
                                 <div class="ibox-content">
                                    <form name="frmfilemanager" id="frmfilemanager" onsubmit="return false;">
                                      <input type="hidden" name="pwd" id="pwd">                 
                                      <input type="hidden" name="file_id" id="file_id">
                                      <input type="hidden" name="search_txt" id="search_txt">
                                      <div class="file-manager">
                                         <div id="ajax-fm"></div>
                                      </div>
                                    </form>
                                    <br/><br/>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <!-- fodlers end-->
               </div>
            </div>
         </div>
      </div>
   </div>
</div>



<!-- Modal -->
<form method="post" name="folder_create" id="folder_create" onsubmit="return false;">
   {{ csrf_field() }} 
   <!-- <input type="hidden" name="pwd" id="pwd">
   <input type="hidden" name="file_id" id="file_id">
   <input type="hidden" name="current_url" id="current_url" value="<?php //echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>"> -->
   <div id="myModal" class="modal fade" role="dialog">
      <div class="modal-dialog">
         <!-- Modal content-->
         <div class="modal-content">
            <div class="modal-header">
               <button type="button" class="close" data-dismiss="modal">&times;</button>
               <h4 class="modal-title">Search folder</h4>
            </div>
            <div class="modal-body">
               <label>Semester Name</label>
               <select id='semSelect' name='semSelect'>
                <option value="">--Select semester--</option>
               <?php
                for($semI=1;$semI<=$totYear;$semI++)
                { 
                  ?>
                     <option value="<?php echo $semI; ?>"><?php echo 'Semester-'.$semI; ?></option>
                  <?php
                }
               ?>
               </select>
               
               <div class="error" id="folerr1" style="font-size: 12px; font-weight: bold;"></div>
            </div>
             <div class="modal-body">
               <label>Subject Name</label>
               <select id='subSelect' name='subSelect'>
              <option value="">--Select subject--</option>
                
               </select>
               
               <div class="error" id="folerr2" style="font-size: 12px; font-weight: bold;"></div>
            </div>

            <div class="modal-body">
               <label>Staff Name</label>
               <select id='staffSelect' name='staffSelect'>
                <option value="">--Select staff--</option>
               </select>
               
               <div class="error" id="folerr3" style="font-size: 12px; font-weight: bold;"></div>
            </div>


            <div class="modal-footer">
               <button type="button" class="btn btn-default btn-folder-create">Search</button>
            </div>
         </div>
      </div>
   </div>
</form>

 <div id="upFile" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Upload Files</h4>
         </div>
         <div class="modal-body">
            <form action="upload.php" enctype="multipart/form-data" class="dropzone" id="image-upload">
               {{ csrf_field() }} 
               <input type="hidden" name="pwd" id="pwd">
               <input type="hidden" value="sdffsf" name="file_id" id="file_id">
                <input type="hidden" value="" name="path_ids" id="path_ids">

               <input type="hidden" name="current_url" id="current_url" value="<?php echo 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'] ?>">
               <div>
                  <h3 align="center">Upload Multiple File By Click On Box</h3>
               </div>
            </form>
         </div>
         <div class="modal-footer">
         </div>
      </div>
   </div>
</div>

              </div>                 
          </div>
            
        </div>
    </div>
</div>

<div id="mypic" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Images</h4>
         </div>
         <div class="modal-body">            
            <img src="" id="imglang" class="img-responsive m-auto"/>
         </div>
         <div class="modal-footer">
         </div>
      </div>
   </div>
</div>


<div id="myvid" class="modal fade" role="dialog">
   <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Video</h4>
         </div>
         <div class="modal-body video">  

            <link href="//vjs.zencdn.net/4.12/video-js.css" rel="stylesheet">

            <video id="example_video_1" class="video-js vjs-default-skin vjs-big-play-centered"
                  controls preload="auto" width="560" height="315">
                <source src="" type="video/mp4" />
            </video>

            <script src="//vjs.zencdn.net/4.12/video.js"></script>
            <script>
                videojs(document.getElementById('example_video_1'), {}, function() {
                    // This is functionally the same as the previous example.
                });
            </script>

         </div>
         <div class="modal-footer">
         </div>
      </div>
   </div>
</div>  


<input type="hidden" name="hid_file_selected_id" id="hid_file_selected_id" />
<input type="hidden" name="hid_file_selected_name" id="hid_file_selected_name" />
  <input type="hidden" name="searchVals" id="searchVals" >


<link href="{{ asset('css/dropzone.min.css') }}" rel="stylesheet">
<script src="{{ asset('js/dropzone.min.js') }}"></script>

<link href="{{ asset('css/contextmenu.css') }}" rel="stylesheet">
<script src="{{ asset('js/contextmenu.js') }}"></script> 

<script src="{{ asset('js/admin_pagejs/filemanager-clg-student-validation.js?v=1.14') }}"></script>
<!-- <script src="{{ asset('js/admin_pagejs/multiselect.js?v=1.00') }}"></script> -->

<style type="text/css">
    .errorLicence,.error1{color: #cc0000;
    font-size: 11px;}
  /* #search_txt{ 
    width: 55%; 
    padding: 12px 20px;
    /*margin: 8px 0; 
    box-sizing: border-box;
  }*/
</style>

<style type="text/css">
  .multiselect {
    width:15em;
    height:10em;
    border:solid 1px #c0c0c0;
    overflow:auto;
}
 
.multiselect label {
    display:block;
    font-size: 10px;
}
 
.multiselect-on {
    color:#ffffff;
    background-color:#000099;
}
.errornew
{
    color: #cc0000;
    font-size: 11px;
}
.root{

  cursor: pointer;
}
.scroll-y {
    max-height: 350px;
    overflow-y: auto;
}
/*.context-menu-one-unpub,.context-menu-one span
{
      display: block;
}*/
.aliSearchCls 
{
   display: block;
}
.mb-3p {

  /*margin-bottom: 3%;
  float: left;
  width:20%;
  height: 100px;
  overflow: hidden;*/
   margin-bottom: 3%;
  float: left;
      width: 24%;
    height: 210px;
  overflow: hidden;
}
.des_content
{
  height: 100px;
    /*overflow-y: scroll;*/
    display: block;
    max-height: 220px;
        color: #949494;
}
.custom_span_fa
{
      font-size: 120px !important;
    margin-bottom: 7px;
}
 
</style>

@endsection

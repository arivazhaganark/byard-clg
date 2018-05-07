@extends('backend.layouts.app_inner')
@section('htmlheader_title')
User Permission Add 
@endsection
@section('content')
<?php    $active_count=1; $inactive_count=1; ?>
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif 
    <div class="row">
       <!--  <div class="col-md-8 col-md-offset-2"> <a class="btn btn-app" href="{{ url('admin/userClgFile/add') }}" title="Add"> <i class="fa fa-plus"></i> Add </a>

        <a class="btn btn-app bg-green" href="{{ url('admin/userClgFile') }}" title="Active"> <i class="fa fa-check-circle"></i>
                Active ({{ $active_count }}) </a>
            <a class="btn btn-app bg-red" href="{{ url('admin/userClgFile/?token=inactive') }}" title="Inactive"> <i class="fa fa-ban"></i>
                Inactive ({{ $inactive_count }}) </a>
             </div> -->
        <div class="col-xs-12">
            <div class="box <?php
            if (isset($_REQUEST['token']) && $_REQUEST['token'] != '') {
                echo 'box-danger';
            } else {
                echo 'box-success';
            }
            ?>">
                <div class="box-header">
                    <h3 class="box-title">
                        <p class="">
                        </p>
                    </h3>
                </div>
                @if(count($UserAll) > 0)
                <div class="box-footer clearfix"> 
                    <form role="form" method="post" name="userPerFrm" id="userPerFrm" action="{{ url('admin/userClgFile/store') }}" enctype="multipart/form-data">
                        {{ csrf_field() }}

                        <div class="form-group">
                                    <label id="username">User Name<span class="text-red">*</span></label>
                                         <select id='sUser' name='sUser' >
                                         <option value="">--Select user--</option>
                                            @foreach ($UserAll as $user)
                                            <option value="{{$user->id}}">{{$user->name}}</option>
                                            @endforeach
                                         </select>
                                         <span id="errMsg"></span>
                                </div>
                        
                          
                    
                </div>
                @endif
                <div class="box-body">
                    <table  id="userPermission" class="table table-bordered table-striped">
                             
                       
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box --> 
        </div>
    </div></form>
</div>
<script src="{{ asset('js/admin_pagejs/userFilePermission-validation.js?v=1.01') }}"></script>
@endsection

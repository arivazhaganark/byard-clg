@extends('backend.layouts.app_inner')
@section('htmlheader_title')
User Profile 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif 
    <div class="row">
        <div class="col-md-8 col-md-offset-2"> <a class="btn btn-app" href="{{ url('admin/userSclFile/add') }}" title="Add"> <i class="fa fa-plus"></i> Add/Edit </a>

        <a class="btn btn-app bg-green" href="{{ url('admin/userSclFile') }}" title="Active"> <i class="fa fa-check-circle"></i>
                Active ({{ $active_count }}) </a>
            <a class="btn btn-app bg-red" href="{{ url('admin/userSclFile/?token=inactive') }}" title="Inactive"> <i class="fa fa-ban"></i>
                Inactive ({{ $inactive_count }}) </a>
             </div>
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
                        <p class="<?php
                        if (isset($_REQUEST['token']) && $_REQUEST['token'] != '') {
                            echo 'text-red';
                        } else {
                            echo 'text-green';
                        }
                        ?>">
                               <?php
                               if (isset($_REQUEST['token']) && $_REQUEST['token'] != '') {
                                   echo 'User profile ';
                               } else {
                                   ?>User profile <?php } ?>
                        </p>
                    </h3>
                </div>
                @if(count($UserAll) > 0)
                <div class="box-footer clearfix"> 
                    <form name="frm_action" id="frm_action" method="post" action="{{ url('/admin/userSclFile/actionupdate') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                          <div style="text-align:right;padding-top:18px;" class="col-sm-12">Action :
                            <select id="action" name="action" style="width:100px; margin-right:5px;"> 
                                <?php if (isset($_REQUEST['token']) && $_REQUEST['token'] == 'inactive') { ?>
                                    <option value="Active">Active</option> 
                                    
                                <?php } else { ?>
                                    <option value="Inactive">Inactive</option> 
                                <?php } ?>
                            </select>
                            <button onclick="return check_confirm('Are you sure want to do the action?');" id="btn_action" value="Action" type="button" 
                                    class="btn btn-primary btn-sm " name="btn_action"><i class="fa fa-bolt"></i>Action</button>
                            <button onclick="if (markAll())
                                        return false;" type="button" class="btn btn-primary btn-sm">Select all</button>
                            <button onclick="if (unmarkAll())
                                        return false;" type="button" class="btn btn-primary btn-sm">Deselect All</button>
                        </div>  
                    </form>
                </div>
                @endif
                <div class="box-body">
                    <table @if(count($UserAll)) id="example1" @else id="" @endif class="table table-bordered table-striped">
                            <thead>
                            <tr class="bg-gray color-palette">
                                <th class="head_text">#</th>
                                <th class="head_text">Name</th>
                                <th class="head_text">Email</th>
                      <!--           <th class="head_text">Edit</th> -->
                                <th class="head_text">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @if ( count($UserAll) > 0 )
                            @foreach( $UserAll as $UserAll_data )
                            <tr>          
                                <td>{{ $i }}</td>                        
                                <td>{{ $UserAll_data->name }} </td> 
                                <td>{{ $UserAll_data->email }} </td> 

                                <!-- <td><a class="btn btn-default" href="<?php //echo url('admin/userSclFile/edit/'.$UserAll_data->id); ?>" title="Edit"><i class="fa fa-edit"></i></a></td> -->
                                <td><input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="{{ $UserAll_data->id }}"></td>
                            </tr>
                            <?php $i++; ?>   
                            @endforeach
                            @else
                            <tr>          
                                <td colspan="6">No User found </td>
                            </tr>   
                            @endif                    
                        </tbody>
                    </table>
                </div><!-- /.box-body -->
            </div><!-- /.box --> 
        </div>
    </div>
</div>
@endsection 
@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Staff Profile 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif 
    <div class="row">
        <div class="col-md-8 col-md-offset-2"> <a class="btn btn-app" href="{{ url('admin/clgstaff/add') }}" title="Add"> <i class="fa fa-plus"></i> Add </a>
            <a class="btn btn-app bg-green" href="{{ url('admin/clgstaff') }}" title="Active"> <i class="fa fa-check-circle"></i>
                Active ({{ $active_count }}) </a>
            <a class="btn btn-app bg-red" href="{{ url('admin/clgstaff/?token=inactive') }}" title="Inactive"> <i class="fa fa-ban"></i>
                Inactive ({{ $inactive_count }}) </a></div>
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
                                   echo 'Inactive College Staff';
                               } else {
                                   ?>Active College Staff<?php } ?>
                        </p>
                    </h3>
                </div>
                @if($StaffAll->count() > 0)
                <div class="box-footer clearfix"> 
                    <form name="frm_action" id="frm_action" method="post" action="{{ url('/admin/clgstaff/actionupdate') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                        <div style="text-align:right;padding-top:18px;" class="col-sm-12">Action :
                            <select id="action" name="action" style="width:100px; margin-right:5px;"> 
                                <?php if (isset($_REQUEST['token']) && $_REQUEST['token'] == 'inactive') { ?>
                                    <option value="Active">Active</option> 
                                    <!-- <option value="Delete">Delete</option> -->
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
                    <table @if($StaffAll->count()>0) id="example1" @else id="" @endif class="table table-bordered table-striped">
                            <thead>
                            <tr class="bg-gray color-palette">
                                <th class="head_text">#</th>
                                 <th class="head_text">Staff Code</th>
                                <th class="head_text">Staff Name</th>
                                <th class="head_text">Action</th>
                                <th class="head_text">Select</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @if ( $StaffAll->count() > 0 )
                            @foreach( $StaffAll as $StaffAll_data )
                            <tr>          
                                <td>{{ $i }}</td>                        
                                <td>{{ $StaffAll_data->staff_code }} </td> 
                                <td>{{ $StaffAll_data->staff_name }} </td>      
                                <td><a class="btn btn-default" href="<?php echo url('admin/clgstaff/edit/'.$StaffAll_data->cl_stf_id); ?>" title="Edit"><i class="fa fa-edit"></i></a></td>
                                <td><input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="{{ $StaffAll_data->cl_stf_id }}"></td>
                            </tr>
                            <?php $i++; ?>   
                            @endforeach
                            @else
                            <tr>          
                                <td colspan="5">No staff found </td>
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
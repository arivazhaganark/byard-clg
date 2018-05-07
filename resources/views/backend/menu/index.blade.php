@extends('layouts.adminapp_inner')
@section('htmlheader_title')
Menu
@endsection
@section('content')
 <section class="content">


            <!-- Basic Examples -->
            <div class="row clearfix">
            <ul class="header-dropdown m-r--5">
                                <div class="col-md-8 col-md-offset-2">

                                <button type="button" class="btn btn-default waves-effect"><a href="{{ url('admin/menu/add') }}">
                                    <i class="material-icons" >add</i></a>
                                </button>


                                    <a class="btn btn-app bg-green" href="{{ url('admin/menu') }}" title="Active"> <i class="material-icons">verified_user</i></i>
                                        Active ({{ $active_count }}) </a>
                                    <a class="btn btn-app bg-red" href="{{ url('admin/menu/?token=inactive') }}" title="Inactive"> <i class="material-icons">cancel</i>
                                        Inactive ({{ $inactive_count }}) </a>
                                </div>
                            </ul>
                            </br>
                            </div>

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <p>
                      
                    </p>
                              <div class="card">
                        <div class="header">
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
                                            echo 'Inactive Menu';
                                        } else {
                                            ?>Active Menu<?php } ?>
                                </p>
                            </h3>
                        </div>

                            @if($menu->count() > 0)

                    <div class="box-footer clearfix">
                        <form name="frm_action" id="frm_action" method="post" action="{{ url('/admin/menu/actionupdate') }}">
                            {{ csrf_field()}}
                            <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                        <div style="text-align:right;padding-top:18px;" class="col-sm-12">Action :
                            <select id="action" name="action" style="width:100px; margin-right:5px;">
                                <?php if (isset($_REQUEST['token']) && $_REQUEST['token'] == 'inactive') { ?>
                                    <option value="Active">Active</option>
                                    <option value="Delete">Delete</option>
                                <?php } else { ?>
                                    <option value="Inactive">Inactive</option>
                                    <?php } ?>
                            </select>
                            <!-- <button onclick="return check_confirm('Are you sure want to do the action?');" id="btn_action" value="Action" type="button"
                                    class="btn btn-primary btn-sm " name="btn_action" ><i class="fa fa-bolt"></i>Action</button> -->
                                    <button onclick="return check_confirm('Are you sure want to do the action?');" id="btn_action" value="Action" type="button" class="btn btn-primary btn-sm">Action</button>
                            <button onclick="if (markAll())
                                        return false;" type="button" class="btn btn-primary btn-sm">Select all</button>
                            <button onclick="if (unmarkAll())
                                        return false;" type="button" class="btn btn-primary btn-sm">Deselect All</button>
                        </div>
                    </form>
                </div>
                </div>
                </br>

                <div class="body">

                        <table @if($menu->count()>0) id="example1" @else id="" @endif class="table table-bordered table-striped table-hover js-basic-example dataTable">
                        <thead>
                        <tr class="bg-gray color-palette">
                            <th >#</th>
                            <th >Name</th>
                            <th >Action</th>
                            <th >Select</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>

                        @foreach( $menu as $cms_data )
                        <tr>
                            <td>{{ $i }}</td>
                            <td>{{ $cms_data->name }} </td>
                            <td>
                                <a class="btn bg-cyan  btn-xs waves-effect"
                                 href="{{url('admin/menu/edit', $cms_data->id) }}" title="Edit"><i class=" material-icons" style="color:green">edit</i></a>
                                    &nbsp;&nbsp;

                               <a class="btn bg-teal  btn-xs waves-effect"
                                 href="{{url('admin/menu/show', $cms_data->id) }}" title="Show"><i class=" material-icons" style="color:green">visibility</i></a>

                            </td>
                            <td><div class="demo-switch">
                                <input type="checkbox" name="chkall[]" id="chkall" class="chkall"  value="{{ $cms_data->id }}">
                                <label for="md_checkbox"></label></div>
                            </td>
                        </tr>
                        <?php $i++; ?>
                        @endforeach
                        @else
                        <tr>
                            <td colspan="6">No menu found </td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
<!-- #END# Basic Examples -->
</div>
</section>
<link href="{{ asset('plugins/animate-css/animate.css') }}" rel="stylesheet" />
<script src="{{ asset('plugins/jquery-validation/jquery.validate.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-notify/bootstrap-notify.js') }}"></script>
<script src="{{ asset('js/pages/ui/notifications.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
<script type="text/javascript">
  $('tbody').sortable();
</script>
@endsection

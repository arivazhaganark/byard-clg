@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Subject Creation  
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif 
    <div class="row">
          <div class="col-md-8 col-md-offset-2"> 
          @if($uPermission[0]->file_view==1 || $uPermission[0]->file_delete==1 || $uPermission[0]->file_edit==1  ) 
              <a class="btn btn-app bg-green" href="{{ url('admin/clgsubject/viewalledit/') }}/{{$subject_id}}" title="Active"> <i class="fa fa-check-circle"></i>
                Active ({{ $active_count }}) </a>
            <a class="btn btn-app bg-red" 
            href="{{ url('admin/clgsubject/viewalledit/') }}/{{$subject_id}}/{{'inactive'}}" title="Inactive"> <i class="fa fa-ban"></i>
                Inactive ({{ $inactive_count }}) </a> 
                @endif 

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
                        if (isset($str) && $str != '') {
                            echo 'text-red';
                        } else {
                            echo 'text-green';
                        }
                        ?>">
                               <?php
                               if (isset($str) && $str != '') {
                                   echo 'Inactive Subject';
                               } else {
                                   ?>Active Subject<?php } ?>
                        </p>
                    </h3>
                </div> 

                @if(count($clgCourseSubAll)  > 0)
                <div class="box-footer clearfix"> 
                    <form name="frm_action" id="frm_action" method="post" action="{{ url('/admin/clgsubject/actionupdate') }}/{{$subject_id}}">
                        {{ csrf_field() }}
                        <input type="hidden" name="c_ids" id="c_ids" value="{{$subject_id}}" />
                        <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                        @if($uPermission[0]->file_delete==1)
                          <div style="text-align:right;padding-top:18px;" class="col-sm-12">Action :
                            <select id="action" name="action" style="width:100px; margin-right:5px;"> 
                                <?php if (isset($str) && $str == 'inactive') { ?>
                                   
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
                        @endif
                    </form>
                </div>
                @endif
                <div class="box-body">
                    <table @if(count($clgCourseSubAll)>0) id="example1" @else id="" @endif class="table table-bordered table-striped">
                            <thead>
                            <tr class="bg-gray color-palette">
                                <th class="head_text">#</th>
                                <th class="head_text">Course name</th>
                                <th class="head_text">Department</th> 
                                <th class="head_text">Graduation</th>
                                <th class="head_text">Semester</th> 
                                <th class="head_text">Subject name</th>  
                                <th class="head_text">Action</th>
                                <th class="head_text">Select</th>
                                 
                            </tr>
                        </thead>
                        <tbody> 
                            <?php $i = 1; ?>
                            @if ( count($clgCourseSubAll) > 0 )
                            @foreach( $clgCourseSubAll as $clgclgCourseSubAll_data )
                            <tr>          
                            <td>{{ $i }}</td>
                            <td>{{ $clgclgCourseSubAll_data->course_name }} </td> 
                            <td>{{ $clgclgCourseSubAll_data->depart_name }} </td>                        
                            <td>{{ $clgclgCourseSubAll_data->grad_name }} </td> 
                            <td>{{ $clgclgCourseSubAll_data->semester_id }} </td> 
                          
                            <td>{{ $clgclgCourseSubAll_data->subject_name }} </td> 
                             
                            <td>
                            @if($uPermission[0]->file_edit==1)
                            <a class="btn btn-default" href="<?php  echo url('admin/clgsubject/subedit/'.$clgclgCourseSubAll_data->sub_id); ?>" title="Edit"><i class="fa fa-edit"></i></a>  
                            @endif 
                            </td> 
                            <td>
                            @if($uPermission[0]->file_delete==1)
                            <input name="chkall[]" id="chkall" class="chkall" type="checkbox" value="{{ $clgclgCourseSubAll_data->sub_id }}">
                            @else
                            ---
                            @endif



                            </td> 
                            </tr>
                            <?php $i++; ?>   
                            @endforeach
                            @else
                            <tr>          
                                <td colspan="8">No subject found </td>
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
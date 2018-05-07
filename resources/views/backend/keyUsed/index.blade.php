@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Used Package 
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif 
    <div class="row">
        <div class="col-md-8 col-md-offset-2"> <a class="btn btn-app" href="{{ url('admin/schusedpack/add') }}" title="Add"> <i class="fa fa-plus"></i> Add </a>
            <a class="btn btn-app bg-green" href="{{ url('admin/schusedpack') }}" title="Active"> <i class="fa fa-check-circle"></i>
                Used Count ({{ $active_count }}) </a>
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
                        <p class="text-green">Used Package</p>
                    </h3>
                </div>
                @if($StaffAll->count() > 0)
                <div class="box-footer clearfix"> 
                    <form name="frm_action" id="frm_action" method="post" action="{{ url('/admin/schusedpack/actionupdate') }}">
                        {{ csrf_field() }}
                        <input type="hidden" name="hid_selected_ids" id="hid_selected_ids" value="" />
                        
                    </form>
                </div>
                @endif
                <div class="box-body">
                    <table @if($StaffAll->count()>0) id="example1" @else id="" @endif class="table table-bordered table-striped">
                            <thead>
                            <tr class="bg-gray color-palette">
                                <th class="head_text">#</th>
                                 <th class="head_text">Package</th>
                                <th class="head_text">Package Type</th>
                                <th class="head_text">Customer Key</th>
                                <th class="head_text">Date/Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; ?>
                            @if ( $StaffAll->count() > 0 )
                            @foreach( $StaffAll as $StaffAll_data )


                            <tr>          
                                <td>{{ $i }}</td>                        
                                <td><?php echo substr($StaffAll_data->package_key, 0, 4)."-".substr($StaffAll_data->package_key, 4, 4)."-".substr($StaffAll_data->package_key,8,4).'-'.substr($StaffAll_data->package_key,12,4);  ?>  </td> 
                                 <td>{{ $StaffAll_data->interface_name }}     </td> 
                                <td>{{ $StaffAll_data->c_id }} </td> 
                                
                                   <td>{{ $StaffAll_data->ceate_time }} </td>          
                                 
                            </tr>
                            <?php $i++; ?>   
                            @endforeach
                            @else
                            <tr>          
                                <td colspan="4">No staff found </td>
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
@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Home
@endsection
@section('content') 

<div class="row">
          <!-- <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-gear-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Users</span>
              <span class="info-box-number">{{$userCnt}}</span>
            </div>
           
          </div>
         
        </div>   -->
        <!-- /.col -->
           <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
              <span class="info-box-icon bg-red"><i class="fa fa-microphone"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Voice</span>
              <span class="info-box-number">{{$voiceUsedCnt}}</span>
            </div>
             
          </div>
          
        </div>  

        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

           <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="fa fa-desktop"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Voice+Screen</span>
              <span class="info-box-number">{{$voiceScrnUsedCnt}}</span>
            </div>
             
          </div>
          
        </div>   
        <!-- /.col -->
         <div class="col-md-3 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="fa fa-video-camera"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Voice+Video</span>
              <span class="info-box-number">{{$voiceVidUsedCnt}}</span>
            </div>
            
          </div>
          
        </div>  
        <!-- /.col -->
        
      </div>
      @if(auth()->guard('admin')->user()->id==1)
 <div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Staff</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
          
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <th>Staff Name</th>                
                    <th>Staff Code</th>          
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  @if(count($user)>0)
                    <?php $i=1; ?>
                    @foreach($user as $u)
                    <tr>
                      <td> {{ $u->staff_name }} </td>
                      <td> {{ $u->staff_code }} </td>
                     <td>@if($u->active==1) Active @else InActive @endif</td>
                    </tr><?php $i++; ?>
                    @endforeach
                  @else
                  <tr>
                    <td colspan="4">No user found</td>
                  </td>
                  @endif
                  </tbody>
                </table>
              </div>
              
            </div>
           
            <div class="box-footer clearfix">
              <a href="{{ url('admin/schstaff') }}" class="btn btn-sm btn-default btn-flat pull-right">View All Customer</a>
            </div>
            
          </div>
    </div>  
    @endif
   
</div>

@endsection

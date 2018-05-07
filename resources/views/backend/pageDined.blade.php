@extends('backend.layouts.app_inner')
@section('htmlheader_title')
File permission denied
@endsection
@section('content') 
@if(auth()->guard('admin')->user()->id !=1)
 <div class="row">
    <div class="col-md-12">
        <div class="box box-info">
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin">
                  <thead>
                  <tr>
                    <td align="center"><img src="{{asset('assets/images/cancel.png')}}"/><h3>File permission denied..</h3></td>                
                  </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
    </div>  
    @endif
</div>
@endsection

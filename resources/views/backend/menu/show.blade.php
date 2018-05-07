@extends('layouts.adminapp_inner')

@section('htmlheader_title')
CMS
@endsection


@section('content')

<section class="content">
    <div class="container-fluid">
        <div class="block-header">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 
         <div class="row clearfix">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="card">
                    <div class="body">

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">View Menu Details</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn bg-teal  btn-xs waves-effect" style="color:green;" href="{{ url('/admin/menu/') }}"> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                <dl class="dl-horizontal">
                                    <dt>Name :</dt>
                                    <dd>{{ $menu->name }}</dd>
                                </dl>
                               
                            </form>
                        </div>                       
                    </div>
                    <!-- /.box-body --> 
                    <!-- /.box --> 
                </div>
            </div>
        </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
    </div>
   
    
    @endsection

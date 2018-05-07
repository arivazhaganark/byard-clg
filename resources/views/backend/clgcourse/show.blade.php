@extends('backend.layouts.app_inner')

@section('htmlheader_title')
Customer Key 
@endsection


@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">View Key Details</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/keycustomer/') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                 
                                
                                <dl class="dl-horizontal">
                                    <dt>Name  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->c_name }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Customer Id  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->c_id }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Email  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->email_id }}</dd>
                                </dl>
                                
                                <dl class="dl-horizontal">
                                    <dt>Voice  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->Voice }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Voice+Screen  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->Voice_S }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Voice+Video  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->Voice_Video }}</dd>
                                </dl> 
                                 <dl class="dl-horizontal">
                                    <dt>Voice+Video+Screen  :</dt>
                                    <dd>{{ $KeyViewCustomerAll[0]->Voice_Video_Screen }}</dd>
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
    @endsection

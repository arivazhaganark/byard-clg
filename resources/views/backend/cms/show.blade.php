@extends('backend.layouts.app_inner')

@section('htmlheader_title')
CMS
@endsection


@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1"> 

            <div class="box box-primary">
                <div class="box-header">
                    <h3 class="box-title text-navy">View CMS Details</h3>
                    <div class="pull-right"> <a style="margin-right:4px;" class="btn  btn-default btn-xs text-purple" href="{{ url('/admin/cms/') }}"><i class="fa fa-arrow-left"></i> Back</a> </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="tab-content">
                        <div id="en" class="tab-pane fade in active">
                            <form role="form" class="form-horizontal">
                                <dl class="dl-horizontal">
                                    <dt>Title :</dt>
                                    <dd>{{ $cms->title }}</dd>
                                </dl>
                                <dl class="dl-horizontal">
                                    <dt>Slug  :</dt>
                                    <dd>{{ $cms->slug }}</dd>
                                </dl> 
                                <dl class="dl-horizontal">
                                    <dt>Type  :</dt>
                                    <dd>{{ $cms->page_type }}</dd>
                                </dl>
                                @if($cms->page_type =='content')
                                <dl class="dl-horizontal">
                                    <dt>Content  :</dt>
                                    <dd>{!! $cms->content !!}</dd>
                                </dl>
                                @else                                
                                <dl class="dl-horizontal">
                                    <dt>Link  :</dt>
                                    <dd>{{ $cms->page_link }} ( {{ $cms->page_linktype }})</dd>
                                </dl>
                                @endif
                                <dl class="dl-horizontal">
                                    <dt>Position  :</dt>
                                    <dd>{{ $cms->position }} </dd>
                                </dl>  
                                <dl class="dl-horizontal">
                                    <dt>Status  :</dt>
                                    <dd>{{ $cms->status == 'Y' ? 'Active' : 'InActive' }}</dd>
                                </dl>
                                <dl class="dl-horizontal">
                                    <dt>Created At  :</dt>
                                    <dd>{{ $cms->created_at }}</dd>
                                </dl> 
                                @if($cms->page_type =='content')
                                <div class="box-header">
                                    <h3 class="box-title text-navy">Seo Settings</h3>
                                </div>
                                <dl class="dl-horizontal">
                                    <dt>Title  :</dt>
                                    <dd>{{ $cms->seo_title }} </dd>
                                </dl> 
                                <dl class="dl-horizontal">
                                    <dt>Description  :</dt>
                                    <dd>{{ $cms->seo_description }} </dd>
                                </dl> 
                                <dl class="dl-horizontal">
                                    <dt>Keywords  :</dt>
                                    <dd>{{ $cms->seo_keywords }} </dd>
                                </dl> 
                                @endif
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

@extends('backend.layouts.app_inner')
@section('htmlheader_title')
Admin Settings
@endsection
@section('content')
<div class="container spark-screen" style="width:100%;">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <!-- @if(Session::has('message'))
            <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
            @endif -->
            <div class="box box-primary">
                <div class="box-body" style="padding: 20px;">
                    <?php 
                     $customerId="";
                     $institName="";
                     $license_email_id="";
                     $Voice="";
                     $VoiceScreen="";
                     $VoiceVideo="";
                     $VoiceVideoScreen="";
                     $VoicePak="";
                     $VoiceScreenPak="";
                     $VoiceVideoPak="";
                     $VoiceVideoScreenPak="";

                     
                    ?>
                    @foreach($CdnCusKeyMap as $kMap)
                    <?php

                    if($kMap->lin_id==1 )
                    {

                         $Voice=$kMap->package_max_cnt;
                         $VoicePak=substr($kMap->package_key, 0, 4)."-".substr($kMap->package_key, 4, 4)."-".substr($kMap->package_key,8,4).'-'.substr($kMap->package_key,12,4); 
                    
                    }
                    elseif($kMap->lin_id==2)
                    {
                         $VoiceScreen=$kMap->package_max_cnt;
                         $VoiceScreenPak=substr($kMap->package_key, 0, 4)."-".substr($kMap->package_key, 4, 4)."-".substr($kMap->package_key,8,4).'-'.substr($kMap->package_key,12,4); 
                     
                    }
                    elseif($kMap->lin_id==3)
                    {
                        $VoiceVideo=$kMap->package_max_cnt;
                        $VoiceVideoPak=substr($kMap->package_key, 0, 4)."-".substr($kMap->package_key, 4, 4)."-".substr($kMap->package_key,8,4).'-'.substr($kMap->package_key,12,4); 
           
                    }
                    elseif($kMap->lin_id==4)
                    {

                         $VoiceVideoScreen=$kMap->package_max_cnt;
                         $VoiceVideoScreenPak=substr($kMap->package_key, 0, 4)."-".substr($kMap->package_key, 4, 4)."-".substr($kMap->package_key,8,4).'-'.substr($kMap->package_key,12,4); 

                    }


                    ?>
                    @endforeach 

                    @foreach($setting as $s)
                    <?php 

                    $customerId=$s->c_id;
                    $institName=$s->institution_name;
                    $license_email_id=$s->license_email_id;
                    $proTitle=$s->institution_title ;
                    $img_path =$s->img_path ;
                    ?>
                    <form role="form" method="post" name="frm_new" id="frm_new" action="" enctype="multipart/form-data">
                        {{ csrf_field() }}
                        <div class='col-md-2'>
                       
                            <div class="form-group">
                            <label class='col-md-12'>Type</label>
                            @if($s->ed_id>0 AND  $s->cus_id !="" )
                                <select id="selectId" name="selectId" > 
                                @foreach ($eduType as $cId)
                                <?php 
                                $chked='';
                                if($s->ed_id==$cId->ed_id)
                                {
                                $chked='selected=selected';
                                ?>
                                <option {{$chked}} value='{{$cId->ed_id}}'>{{$cId->ed_type}} </option>
                                <?php
                                }
                                ?>
                            @endforeach
                            </select>
                            @else
                                <select id="selectId" name="selectId" > 
                                <option value="">--Select Key--</option>
                                @foreach ($eduType as $cId)
                                <?php 
                                $chked='';
                                if($s->ed_id==$cId->ed_id)
                                {
                                $chked='selected=selected';
                                }

                                ?>
                                <option {{$chked}} value='{{$cId->ed_id}}'>{{$cId->ed_type}} </option>
                                @endforeach
                                </select>
                            @endif
                            </div>

                       
                        </div>

                       <div class='col-md-4'>
                           <div class="form-group">
                                <label>Customer key</label>
                                <input name="c_key" id="c_key" type="text" class="form-control" placeholder="Customer key" maxlength="200" value="{{$s->c_id}}"  autocomplete="off"  />
                            </div>  
                        </div>

                        <div class='col-md-4'>
                        </div>

                        <div class='col-md-12'>
                        <button class="btn btn-primary" name="btn_sub" id="btn_sub" type="submit">Synchronize</button><br>
                        <span id='err' style='color:red;' > </span>
                         </div>

 
                    </form>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-10 col-md-offset-1">
             
            <div class="box box-primary">

               <div class="box-body" style="padding: 20px;">
                  <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                 Customer key :
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$customerId}}</label>
                            </div>  
                        </div>
                  </div>

                   <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                 Institution_name :
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$institName}}</label>
                            </div>  
                        </div>
                  </div>

                  <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                               License Email-id:
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$license_email_id}}</label>
                            </div>  
                        </div>
                  </div>

                  <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                               Voice:
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$VoicePak}}  <?php if($Voice>0){ echo "($Voice)"; }  ?> </label>
                            </div>  
                        </div>
                  </div>

                  <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                               Voice+Screen:
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$VoiceScreenPak}}  <?php if($VoiceScreen>0){ echo "($VoiceScreen)"; }  ?></label>
                            </div>  
                        </div>
                  </div>

                  <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                               Voice+Video:
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$VoiceVideoPak}} <?php if($VoiceVideo>0){ echo "($VoiceVideo)"; }  ?>   </label>
                            </div>  
                        </div>
                  </div>

                   <div class="row">
                   <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                               Voice+Video+Screen:
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <label>{{$VoiceVideoScreenPak}}  {{$VoiceVideoScreen}} </label>
                            </div>  
                        </div>
                  </div>

                   <form id='frmTitle' name="frmTitle" enctype="multipart/form-data" role="form"  action='{{url("admin/setting/addTitle")}}' method="POST"  >
                    {{ csrf_field() }}
                    @if ($errors->has('Title'))
                    <p class="alert {{ Session::get('alert-class', 'alert-warning') }}">{{ $errors->first('Title') }}</p>
                    @endif
                   <div class="row">
                   <div class='col-md-4 col-xs-4'>
                           <div class="form-group">
                                 Title  
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-4'>
                           <div class="form-group">
                             <input type="text" name='Title' id='Title' value='{{ $proTitle}}' >    
                        
                            </div>  
                        </div>
                        <div class='col-md-4 col-xs-4'>
                           <div class="form-group">
                                <button class="btn btn-primary" name="btn_tit" id="btn_tit" type="submit">Add/update Title</button>
                            </div>  
                        </div>
                  </div>

                   </form>


                  <form id='frmImage' name="frmImage" enctype="multipart/form-data" role="form"  action='{{url("admin/setting/Upload_Banner")}}' method="POST"  >
                    {{ csrf_field() }}
                      @if ($errors->has('image'))
                      <p class="alert {{ Session::get('alert-class', 'alert-warning') }}">{{ $errors->first('image') }}</p>
                      @endif
                  <div class="row">
                     <div class='col-md-4 col-xs-4'>
                             <div class="form-group">
                                   Image upload  
                              </div>  
                      </div>
                        <div class='col-md-4 col-xs-4'>
                           <div class="form-group">
                                 <input type="file" name="image" class="aaaaform-control" id="kv_image" value="" /> 
                            </div>  
                        </div>
                         <div class='col-md-4 col-xs-4'>
                           <div class="form-group">
                           <?php  $chkPath=base_path() . "/public/uploads/thumbnail/" . $img_path; 
                             if ( file_exists($chkPath)) {
                              ?>
                              <img width='80px' height='40px' title="no img" src="{{URL::asset('uploads/thumbnail')}}/{{$img_path}}"> 
                              <?php

                             }
                             else
                             {
                              echo "No image";
                             }
                             ?> 
                               
                            </div>  
                        </div>
                        
                  </div>

                   <div class="row">
                   <div class='col-md-4 col-xs-6'>
                            
                        </div>
                        <div class='col-md-4 col-xs-6'>
                           <div class="form-group">
                                <button class="btn btn-primary" name="btn_sub" id="btn_sub" type="submit">Upload image</button><br>
                        <span id='imgerr' style='color:red;' > </span>
                            </div>  
                        </div>
                        
                  </div>  
                   
                  </form>




                 <!--  <div class='col-md-4'>
                    <div class="form-group">
                    Customer key: 
                    </div>  
                    </div>

                    <div class='col-md-4'>
                    <div class="form-group">
                   <label>{{$customerId}}</label>
                    </div>  
                    </div> -->

                    <!-- <div class='col-md-12'>
                    <div class="form-group">
                    Customer key: <label>{{$customerId}}</label>
                    </div>  
                    </div>

                    <div class='col-md-12'>
                    <div class="form-group">
                    Institution_name: <label>{{$institName}}</label>
                    </div>  
                    </div>

                    <div class='col-md-12'>
                    <div class="form-group">
                    License Email-id: <label>{{$license_email_id}}</label>
                    </div>  
                    </div>

                    <div class='col-md-12'>
                    <div class="form-group">
                    License Email-id: <label>{{$license_email_id}}</label>
                    </div>  
                    </div>

                    <div class='col-md-12'>
                    <div class="form-group">
                    License Email-id: <label>{{$license_email_id}}</label>
                    </div>  
                    </div>

                    <div class='col-md-12'>
                    <div class="form-group">
                    License Email-id: <label>{{$license_email_id}}</label>
                    </div>  
                    </div>
                    <div class='col-md-12'>
                    <div class="form-group">
                    License Email-id: <label>{{$license_email_id}}</label>
                    </div>  -->
                    </div> 




                </div>  
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('js/admin_pagejs/settingCdn-validation.js?v=1.85') }}"></script>
@endsection

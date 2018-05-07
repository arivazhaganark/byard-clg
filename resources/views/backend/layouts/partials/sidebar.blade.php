<?php 
//if(auth()->guard('admin')->user()->id==1)
//{
    $getSettingCnt=DB::table('settings')->where('id',1)->get()->count();
    if($getSettingCnt==1)
    {
        $getSetting=DB::table('settings')->where('id',1)->get(); 
        $instiType=$getSetting[0]->ed_id;
        $cus_id=$getSetting[0]->ed_id;
        $institution_name=$getSetting[0]->institution_name;
        $c_id=$getSetting[0]->c_id;
   }

//}
?>
<aside class="main-sidebar"> 
    <div class="image" style="text-align:center; padding:5px;">

    </div>
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <ul class="sidebar-menu" id="sildermenu_id">

         @if(auth()->guard('admin')->user()->id==1)
           <li class="header">MAIN NAVIGATION</li>
                    <li @if(Request::segment('2')=='home') class="active" @endif>
                        <a href="{{ url('admin/home') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Home</span>
                        </a>
                    </li>
                      <li @if(Request::segment('2')=='setting') class="active" @endif>
                        <a href="{{ url('admin/setting') }}">
                            <i class="fa fa-cog"></i>
                            <span>Setting</span>
                        </a>
                    </li>  

                    <li @if(Request::segment('2')=='userCreation') class="active" @endif>
                        <a href="{{ url('admin/userCreation') }}">
                            <i class="fa fa-user-plus" aria-hidden="true"></i>
                            <span>User creation</span>
                        </a>
                    </li>

                    @if($instiType==2)

                     <li @if(Request::segment('2')=='userClgFile') class="active" @endif>
                        <a href="{{ url('admin/userClgFile') }}">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>User File Permission</span>
                        </a>
                    </li>
                    @elseif($instiType==1)
                    <li @if(Request::segment('2')=='userSclFile') class="active" @endif>
                        <a href="{{ url('admin/userSclFile') }}">
                            <i class="fa fa-file" aria-hidden="true"></i>
                            <span>User File Permission</span>
                        </a>
                    </li>
                    @endif

                   
  
                     <!--For scholl menu -->
                    @if($instiType==1 AND $cus_id>0 AND $c_id !="")
                

                    <li @if(Request::segment('2')=='schclass') class="active" @endif>
                        <a href="{{ url('admin/schclass') }}">
                            <i class="fa fa-graduation-cap"></i>
                            <span>Create Class</span>
                        </a>
                    </li>

                     <li @if(Request::segment('2')=='schsectionmap') class="active" @endif>
                        <a href="{{ url('admin/schsectionmap') }}">
                            <i class="fa fa-graduation-cap"></i>
                            <span>Section Mapping</span>
                        </a>
                    </li>
                    <li @if(Request::segment('2')=='schsubject') class="active" @endif>
                        <a href="{{ url('admin/schsubject') }}">
                            <i class="fa fa fa-book"></i>
                            <span>Create Subject</span>
                        </a>
                    </li>
                     <li @if(Request::segment('2')=='schstfclsmap') class="active" @endif>
                        <a href="{{ url('admin/schstfclsmap') }}">
                            <i class="fa fa fa-map"></i>
                            <span>Class-Staff Mapping</span>
                        </a>
                    </li>
                    <li @if(Request::segment('2')=='schstudent') class="active" @endif>
                        <a href="{{ url('admin/schstudent') }}">
                            <i class="fa fa-child"></i>
                            <span>Create Student</span>
                        </a>
                    </li>
                    <li @if(Request::segment('2')=='schstaff') class="active" @endif>
                        <a href="{{ url('admin/schstaff') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Staff</span>
                        </a>
                    </li>

                    <li @if(Request::segment('2')=='schstaffsubmapp') class="active" @endif>
                        <a href="{{ url('admin/schstaffsubmapp') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Staff-Subject Mapping </span>
                        </a>
                    </li>

                      

                    <!--For collage menu -->
                    @elseif($instiType==2 AND $cus_id>0 AND $c_id !="")  


                     <li @if(Request::segment('2')=='clgstaff') class="active" @endif>
                        <a href="{{ url('admin/clgstaff') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Staff</span>
                        </a>
                    </li>

                    <li @if(Request::segment('2')=='clgdepart') class="active" @endif>
                        <a href="{{ url('admin/clgdepart') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Department</span>
                        </a>
                    </li>

                     <li @if(Request::segment('2')=='clgcourse') class="active" @endif>
                        <a href="{{ url('admin/clgcourse') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Course</span>
                        </a>
                    </li>

                    <li @if(Request::segment('2')=='clgsubject') class="active" @endif>
                        <a href="{{ url('admin/clgsubject') }}">
                            <i class="fa fa fa-book"></i>
                            <span>Create Subject</span>
                        </a>
                    </li>

                    <li @if(Request::segment('2')=='clgstaffsubmapp') class="active" @endif>
                        <a href="{{ url('admin/clgstaffsubmapp') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Staff-Subject Mapping </span>
                        </a>
                    </li>

                    <li @if(Request::segment('2')=='clgstudent') class="active" @endif>
                        <a href="{{ url('admin/clgstudent') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Student</span>
                        </a>
                    </li>
                    @endif

                    @elseif(auth()->guard('admin')->user()->id !=1 )
                       
                      @if(auth()->guard('admin')->user()->usertype=='WS')
 
                       <li @if(Request::segment('2')=='home') class="active" @endif>
                        <a href="{{ url('admin/home') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Home</span>
                        </a>
                    </li>
                    <?php

                     $userIdScl=auth()->guard('admin')->user()->id;
                     $getSclMenu=DB::table('at_school_file_permission_master')->leftJoin('admins','at_school_file_permission_master.id','=','admins.id')->leftJoin('at_school_file_permission_mapping','at_school_file_permission_mapping.s_id','=','at_school_file_permission_master.s_id')->leftJoin('at_school_menu_master','at_school_menu_master.m_s_id','=','at_school_file_permission_mapping.m_s_id')->where(['at_school_file_permission_master.id'=>"$userIdScl",
                         "admins.active"=>1,"at_school_file_permission_master.active"=>1,"at_school_menu_master.active"=>1])->orderBy('at_school_menu_master.order_priority')->get();

                     foreach ($getSclMenu as $key => $value) {

                        if($value->file_add==1 || $value->file_edit==1 || $value->file_view==1 || $value->file_delete==1)
                        {
                        ?>
                            <li <?php if(Request::segment('2')==$value->menu_controller) { ?> class="active" <?php } ?> >
                            <a href="<?php echo url('admin').'/'.$value->menu_controller;  ?>">
                            <i class="fa {{$value->font_awesome}}" ></i>
                            <span>{{$value->menu_name}}</span>
                            </a>
                            </li>

                        <?php 
                        
                        }
                        }   
 

                    ?>


                     <li @if(Request::segment('2')=='home') class="active" @endif>
                        <a href="{{ url('admin/change_password') }}">
                           <i class="fa fa-key" aria-hidden="true"></i>
                            <span>Change password</span>
                        </a>
                    </li>

                     @elseif(auth()->guard('admin')->user()->usertype=='WC')

                     <li @if(Request::segment('2')=='home') class="active" @endif>
                        <a href="{{ url('admin/home') }}">
                            <i class="fa fa-dashboard"></i>
                            <span>Home</span>
                        </a>
                    </li>

                     <?php


                     $userId=auth()->guard('admin')->user()->id;
                     $getMenu=DB::table('at_college_file_permission_master')->leftJoin('admins','at_college_file_permission_master.id','=','admins.id')->leftJoin('at_college_file_permission_mapping','at_college_file_permission_mapping.p_id','=','at_college_file_permission_master.p_id')->leftJoin('at_college_menu_master','at_college_menu_master.m_c_id','=','at_college_file_permission_mapping.m_c_id')->where(['at_college_file_permission_master.id'=>"$userId",
                         "admins.active"=>1,"at_college_file_permission_master.active"=>1,"at_college_menu_master.active"=>1])->orderBy('at_college_menu_master.order_priority')->get();

 
              
                     foreach ($getMenu as $key => $value) {

                        if($value->file_add==1 || $value->file_edit==1 || $value->file_view==1 || $value->file_delete==1)
                        {
                        ?>
                            <li <?php if(Request::segment('2')==$value->menu_controller) { ?> class="active" <?php } ?> >
                            <a href="<?php echo url('admin').'/'.$value->menu_controller;  ?>">
                            <i class="fa {{$value->font_awesome}}" ></i>
                            <span>{{$value->menu_name}}</span>
                            </a>
                            </li>

                        <!--      <li @if(Request::segment('2')=='clgstaff') class="active" @endif>
                        <a href="{{ url('admin/clgstaff') }}">
                            <i class="fa fa fa-user"></i>
                            <span>Create Staff</span>
                        </a>
                    </li> -->
                       <?php
                       }
                        
                     }
                     ?>
                     <li @if(Request::segment('2')=='change_password') class="active" @endif>
                        <a href="{{ url('admin/change_password') }}">
                           <i class="fa fa-key" aria-hidden="true"></i>
                            <span>Change password</span>
                        </a>
                    </li>

                     @endif
              
                     @endif
                      
                    <li>
                        <a href="{{ url('admin/logout') }}">
                            <i class="fa fa-sign-out"></i>
                            <span>Logout</span>
                        </a>
                    </li>


        </ul>
        <div style="height:70px;">&nbsp;</div>
    </section>
    <!-- /.sidebar -->
</aside>

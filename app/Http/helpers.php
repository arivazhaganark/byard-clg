<?php
use App\Model\backend\Clgmenumaster;
use App\Model\backend\Clgfilepermissionmaster;
use App\Model\backend\Clgfilepermissionmapp;
use App\Model\backend\Sclmenumaster;
use App\Model\backend\Sclfilepermissionmaster;
use App\Model\backend\Sclfilepermissionmapp;
 

function getUserPermission($contrlVal=null,$eduType=null)
{

	$returnMenu[]=(object) array('file_add'=>"0",'file_edit'=>"0",'file_delete'=>"0","file_view"=>"0");
	 

    if($eduType=='college')
    {
    	 $userId=auth()->guard('admin')->user()->id;
    	 if($userId==1)
    	 {
    	 	  $returnAdminMenu[]=(object) array('file_add'=>"1",'file_edit'=>"1",'file_delete'=>"1","file_view"=>"1");
    	 	  return $returnAdminMenu;
    	 	  exit;

    	 	
    	 }

		$chkMenuMasterCnt=Clgmenumaster::where(['menu_controller'=>$contrlVal,'active'=>1])->count();
		if($chkMenuMasterCnt>0)
		{
			$chkMenuMaster=Clgmenumaster::where(['menu_controller'=>$contrlVal,'active'=>1])->get();
		 $m_c_id=isset($chkMenuMaster[0]->m_c_id)?$chkMenuMaster[0]->m_c_id:0;
		

		 $getMenuCnt=DB::table('at_college_file_permission_master')->leftJoin('admins','at_college_file_permission_master.id','=','admins.id')->leftJoin('at_college_file_permission_mapping','at_college_file_permission_mapping.p_id','=','at_college_file_permission_master.p_id')->leftJoin('at_college_menu_master','at_college_menu_master.m_c_id','=','at_college_file_permission_mapping.m_c_id')->where(['at_college_file_permission_master.id'=>"$userId",
                         "admins.active"=>1,"at_college_file_permission_master.active"=>1,"at_college_menu_master.active"=>1,'at_college_file_permission_mapping.m_c_id'=>$m_c_id])->count();

			 if($getMenuCnt>0)
			 {

			 	$getMenu=DB::table('at_college_file_permission_master')->leftJoin('admins','at_college_file_permission_master.id','=','admins.id')->leftJoin('at_college_file_permission_mapping','at_college_file_permission_mapping.p_id','=','at_college_file_permission_master.p_id')->leftJoin('at_college_menu_master','at_college_menu_master.m_c_id','=','at_college_file_permission_mapping.m_c_id')->where(['at_college_file_permission_master.id'=>"$userId",
                         "admins.active"=>1,"at_college_file_permission_master.active"=>1,"at_college_menu_master.active"=>1,'at_college_file_permission_mapping.m_c_id'=>$m_c_id])->get(array('file_add','file_edit','file_delete','file_view'));

			 	return $getMenu;
			 	exit;




  

			 }
			 else
			 {
               return $returnMenu;
               exit;
			 }
	 

		}
		else
		{
          
          return $returnMenu;
          exit;

		}


    }
    elseif($eduType=='school')
    {


    	 $userId=auth()->guard('admin')->user()->id;
    	 if($userId==1)
    	 {
    	 	  $returnAdminMenu[]=(object) array('file_add'=>"1",'file_edit'=>"1",'file_delete'=>"1","file_view"=>"1");
    	 	  return $returnAdminMenu;
    	 	  exit;

    	 	
    	 }

		$chkMenuMasterCnt=Sclmenumaster::where(['menu_controller'=>$contrlVal,'active'=>1])->count();
		if($chkMenuMasterCnt>0)
		{
			$chkMenuMaster=Sclmenumaster::where(['menu_controller'=>$contrlVal,'active'=>1])->get();
		 $m_s_id=isset($chkMenuMaster[0]->m_s_id)?$chkMenuMaster[0]->m_s_id:0;
		

		 $getMenuCnt=DB::table('at_school_file_permission_master')->leftJoin('admins','at_school_file_permission_master.id','=','admins.id')->leftJoin('at_school_file_permission_mapping','at_school_file_permission_mapping.s_id','=','at_school_file_permission_master.s_id')->leftJoin('at_school_menu_master','at_school_menu_master.m_s_id','=','at_school_file_permission_mapping.m_s_id')->where(['at_school_file_permission_master.id'=>"$userId",
                         "admins.active"=>1,"at_school_file_permission_master.active"=>1,"at_school_menu_master.active"=>1,'at_school_file_permission_mapping.m_s_id'=>$m_s_id])->count();

			 if($getMenuCnt>0)
			 {

			 	$getMenu=DB::table('at_school_file_permission_master')->leftJoin('admins','at_school_file_permission_master.id','=','admins.id')->leftJoin('at_school_file_permission_mapping','at_school_file_permission_mapping.s_id','=','at_school_file_permission_master.s_id')->leftJoin('at_school_menu_master','at_school_menu_master.m_s_id','=','at_school_file_permission_mapping.m_s_id')->where(['at_school_file_permission_master.id'=>"$userId",
                         "admins.active"=>1,"at_school_file_permission_master.active"=>1,"at_school_menu_master.active"=>1,'at_school_file_permission_mapping.m_s_id'=>$m_s_id])->get(array('file_add','file_edit','file_delete','file_view'));

			 	return $getMenu;
			 	exit;




  

			 }
			 else
			 {
               return $returnMenu;
               exit;
			 }
	 

		}
		else
		{
          
          return $returnMenu;
          exit;

		}

    }
	

}


?>
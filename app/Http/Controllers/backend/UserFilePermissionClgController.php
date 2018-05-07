<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\Admin;
use App\Model\backend\Setting;
use App\Model\backend\Clgmenumaster;
use App\Model\backend\Clgfilepermissionmaster;
use App\Model\backend\Clgfilepermissionmapp;
use Carbon\Carbon;
use Redirect;
use Session;
use DB;
use Auth;


class UserFilePermissionClgController extends Controller
{
     
    public function index(){

            if(auth()->guard('admin')->user()->id !=1)
            {
            return view('backend.pageDined');
            }

          $input = Input::all(); 
          $settingCnt=Setting::select('*')->where('id', 1)->count();
          if($settingCnt>0)
          {
                $setting=Setting::select('*')->where('id', 1)->get();
                $usersType="WC";
  
                if($setting[0]->ed_id==1 || $setting[0]->ed_id==2 )//for school/college
                {
                    if($setting[0]->ed_id==1)
                    {
                        $usersType="WS"; //for school 
                    }

                    if (isset($input['token']) && $input['token'] == 'inactive') {
                    $UserAll = Admin::where(['active'=>0,"usertype"=>"$usersType"])->whereNotIn('id',[1])->get();

                    } else {
                    $UserAll = Admin::where(['active'=>1,"usertype"=>"$usersType"])->whereNotIn('id',[1])->get();

                    }
                    $active_count = Admin::where(['active'=>1,"usertype"=>"$usersType"])->whereNotIn('id',[1])->count();
                    $inactive_count = Admin::where(['active'=>0,"usertype"=>"$usersType"])->whereNotIn('id',[1])->count();
                    return view('backend.clgFilePermission.index', compact('UserAll', 'active_count', 'inactive_count'));


                }
                else
                {
                     Session::flash('message', 'Please fill the setting form');
                     Session::flash('alert-class', 'alert-warning');
                }


          }
          else
          {

            Session::flash('message', 'Please fill the setting form');
            Session::flash('alert-class', 'alert-warning');


          }
        
        
   
    }

    public function create() {
    if(auth()->guard('admin')->user()->id !=1)
    {
    return view('backend.pageDined');
    }

     $UserAll = Admin::where(['active'=>1,"usertype"=>"WC"])->whereNotIn('id',[1])->get();
     return view('backend.clgFilePermission.add',compact('UserAll'));
    }
    public function userPermissionAjax(Request $request)
    {
           $input=$request->all() ;
           $resultArr['status']=0;
           $resultArr['errMsg']="";
           $resultArr['output']="";
           $sUserId=$input['sUser'];
           if($sUserId !=1)
           {
             $getValidUserCnt=Admin::where(['id'=>$sUserId,"active"=>"1"])->count();
             if($getValidUserCnt>0)
             {

                $getMenuMaster=Clgmenumaster::where(['active'=>1])->get(array('m_c_id', 'menu_name', 'active',DB::raw(" 0 as userAdd"),DB::raw(" 0 as userEdit"),DB::raw(" 0 as userDelete"),DB::raw(" 0 as userView"))) ;

                foreach ($getMenuMaster as $key => $value) {

                    $m_c_id=$value->m_c_id;
                    $getFileMasterCnt=Clgfilepermissionmaster::where(['id'=>"$sUserId"])->count();
                    if($getFileMasterCnt>0)
                    {
                        $getFileMaster=Clgfilepermissionmaster::where(['id'=>"$sUserId"])->get();
                        $p_id=isset($getFileMaster[0]->p_id)?$getFileMaster[0]->p_id:0; 

                        $getMapCnt=Clgfilepermissionmapp::where(['p_id'=>$p_id,'m_c_id'=>$m_c_id])->count();
                        if($getMapCnt>0)
                        {
                             $getMap=Clgfilepermissionmapp::where(['p_id'=>$p_id,'m_c_id'=>$m_c_id])->get();

                             
                           $value->file_add=isset($getMap[0]->file_add)?$getMap[0]->file_add:0;
                           $value->file_edit=isset($getMap[0]->file_edit)?$getMap[0]->file_edit:0;
                           $value->file_delete=isset($getMap[0]->file_delete)?$getMap[0]->file_delete:0;
                           $value->file_view=isset($getMap[0]->file_view)?$getMap[0]->file_view:0;

                        }
                    }



                    
                }
                 $resultArr['status']=1;

                 $resultArr['output']=$getMenuMaster;
            
             }
             else
             {
                $resultArr['errMsg']="Invalid user";
             }
           }
           else
           {
              $resultArr['errMsg']="Invalid user";
           }

           echo json_encode($resultArr);
           
    }
    public function userPermissionCurdAjax(Request $request)
    {

        $input=$request->all();
        $adChk=$input['ad'];
        $edChk=$input['ed'];
        $delChk=$input['del'];
        $viewChk=$input['view'];
        $rowMenuId=$input['rowId'];
        $userId=$input['sUser'];

        $chkUserCnt=Admin::where(['id'=>$userId,"active"=>"1"])->count();
        if($chkUserCnt>0)
        {
            $getFileMasterCnt=Clgfilepermissionmaster::where(['id'=>"$userId"])->count();
            if($getFileMasterCnt>0)
            {
               $getFileMaster=Clgfilepermissionmaster::where(['id'=>"$userId"])->get();
               $lstInsertId=isset($getFileMaster[0]->p_id)?$getFileMaster[0]->p_id:0;
            }
            else
            {

                $inserMaster=Clgfilepermissionmaster::create(['id'=>$userId]);
                $lstInsertId=$inserMaster->p_id ;

            }

             if($lstInsertId>0)
             {
                $getMapCnt=Clgfilepermissionmapp::where(['p_id'=>$lstInsertId,'m_c_id'=>$rowMenuId])->count();
                if($getMapCnt>0)
                {
               
                  $getMapUpdate=Clgfilepermissionmapp::where(['p_id'=>$lstInsertId,'m_c_id'=>$rowMenuId])->update(['file_add'=>$adChk,'file_edit'=>$edChk,'file_delete'=>$delChk,'file_view'=>$viewChk]);

                }
                else
                {
                    $getMapUpdate=Clgfilepermissionmapp::insert(['file_add'=>$adChk,'file_edit'=>$edChk,'file_delete'=>$delChk,'p_id'=>$lstInsertId,'m_c_id'=>$rowMenuId,'file_view'=>$viewChk]);

                }

                echo "Updated successfully";
                 exit;

             }
             else
             {
                echo "Invalid request";
                exit;
             }



        }
        else
        {
             echo "Invalid user";
             exit;
        }
 
    }
    

      public function store(Request $request) {

        if(auth()->guard('admin')->user()->id !=1)
        {
        return view('backend.pageDined');
        }
   
     	$inputs=$request->all();
        $userName=trim($inputs['username']);
        $userEmail=trim($inputs['useremail']); 
        $settingCnt=Setting::select('*')->where('id', 1)->count();
        if($settingCnt>0)
        {
            $setting=Setting::select('*')->where('id', 1)->get();
  
            if($setting[0]->ed_id==1)//for school
            {

                $checkAdminCnt=Admin::where('email',$userEmail)->count();

                if($checkAdminCnt==0)
                {

                    $randomPass=str_random(8);
                    $usertype='WS';
                    $created=Carbon::now();

                    $insertUser=Admin::insert(['email'=>"$userEmail",'name'=>"$userName",
                                               'password'=>bcrypt($randomPass),
                                               'usertype'=>$usertype,
                                               'created_at'=>$created,
                                               'updated_at'=>$created]);
                    Session::flash('message', 'Admin user added successfully');
                    Session::flash('alert-class', 'alert-success');
                    return redirect('admin/userCreation/add');


                }
                else
                {
                    Session::flash('message', 'E-mail id already exists');
                    Session::flash('alert-class', 'alert-warning');
                    return redirect('admin/userCreation/add')->withInput($request->all());
                }


            }
            elseif($setting[0]->ed_id==2) //for college
            {


                $checkAdminCnt=Admin::where('email',$userEmail)->count();

                if($checkAdminCnt==0)
                {

                    $randomPass=str_random(8);
                    $usertype='WC';
                    $created=Carbon::now();
                    $insertUser=Admin::insert(['email'=>"$userEmail",'name'=>"$userName",
                                               'password'=>bcrypt($randomPass),
                                               'usertype'=>$usertype,
                                               'created_at'=>$created,
                                               'updated_at'=>$created]);
                    Session::flash('message', 'Admin user added successfully');
                    Session::flash('alert-class', 'alert-success');
                    return redirect('admin/userCreation/add');


                }
                else
                {
                    Session::flash('message', 'E-mail id already exists');
                    Session::flash('alert-class', 'alert-warning');
                    return redirect('admin/userCreation/add')->withInput($request->all());
                }

            }
            else
            {
                Session::flash('message', 'Please fill the setting form first');
                Session::flash('alert-class', 'alert-warning');
                return redirect('admin/userCreation/add')->withInput($request->all());
            }
             
        }
        else
        {
           Session::flash('message', 'Please fill the setting form first');
           Session::flash('alert-class', 'alert-warning');
           return redirect('admin/userCreation/add')->withInput($request->all());
        }
}

     



    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "User has been successfully inactivated.";
            $redirect_value = "admin/userCreation";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "User has been successfully activated.";
            $redirect_value = "admin/userCreation/?token=inactive";
        }
        else
        {

            $msg_value="Invalid request";
        }
            
        
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                Admin::select('*')->where('id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}

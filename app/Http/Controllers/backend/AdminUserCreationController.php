<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchClassModel;
use App\Model\backend\Admin;
use App\Model\backend\Setting;
use Carbon\Carbon;
use Redirect;
use Session;
use DB;
use Auth;


class AdminUserCreationController extends Controller
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
  return view('backend.adminUserCreation.index', compact('UserAll', 'active_count', 'inactive_count'));


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
       
        return view('backend.adminUserCreation.add');
    }
    

      public function store(Request $request) {
        if(auth()->guard('admin')->user()->id !=1)
        {
        return view('backend.pageDined');
        }
       	$inputs=$request->all();

        $voice=0;
        $voicescreen=0;
        $voicevid=0;
        $voicevidscr=0;

        // if(isset($inputs['licence']))
        // {
        //   $licenceVal=$inputs['licence'];
        //   for($i=0;$i<count($licenceVal); $i++)
        //   {
        //       if($licenceVal[$i]==1)
        //            $voice=1;
        
        //       if($licenceVal[$i]==2)
        //           $voicescreen=1;
    
        //       if($licenceVal[$i]==3)
        //         $voicevid=1;
    
        //       if($licenceVal[$i]==4)
        //          $voicevidscr=1;
        //   }

        // }
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
                                               //'voice'=>$voice,
                                               //'voice_screen'=>$voicescreen,
                                               //'voice_video'=>$voicevid,
                                              // 'voice_video_screen'=>$voicevidscr,
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
                                              // 'voice'=>$voice,
                                              // 'voice_screen'=>$voicescreen,
                                              // 'voice_video'=>$voicevid,
                                              // 'voice_video_screen'=>$voicevidscr,
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

     

    public function edit($id){  
    	$getDetailsCnt = Admin::where(['id' => "$id"])->count();
        if($getDetailsCnt>0)
        {
            $getDetails = Admin::where(['id' => "$id"])->get(); 
            return view('backend.adminUserCreation.edit', compact('getDetails','id'));
        }
        else
        {
           Session::flash('message', 'Invalid id');
           Session::flash('alert-class', 'alert-warning');
           return redirect('admin/userCreation');
        }
        
    }

     public function update(Request $request) {
         $inputs=$request->all();
         $id = $inputs['hidid'];
         $userName=trim($inputs['username']);
         $userEmail=trim($inputs['useremail']);
         $getDetailsCnt = Admin::where(['id' => "$id"])->count();
         $voice=0;
         $voicescreen=0;
         $voicevid=0;
         $voicevidscr=0;

          // if(isset($inputs['licence']))
          // {
          // $licenceVal=$inputs['licence'];
          //   for($i=0;$i<count($licenceVal); $i++)
          //   {
          //     if($licenceVal[$i]==1)
          //     $voice=1;

          //     if($licenceVal[$i]==2)
          //     $voicescreen=1;

          //     if($licenceVal[$i]==3)
          //     $voicevid=1;

          //     if($licenceVal[$i]==4)
          //     $voicevidscr=1;

          //     }
          // }

         if($getDetailsCnt>0)
         {

              $getExitsCnt=Admin::where(['email'=>$userEmail])->whereNotIn('id', [$id])->get()->count();
              if($getExitsCnt==0)
              {
                $getUpdate=Admin::where(['id'=>$id])->update(['email'=>$userEmail,'name'=>$userName]);
                Session::flash('message', 'User updated successfully');
                  Session::flash('alert-class', 'alert-success');
                  return Redirect::to('admin/userCreation/edit/'.$id);
              }
              else
              {
                  Session::flash('message', 'Email-id already exists');
                  Session::flash('alert-class', 'alert-warning');
                  return Redirect::to('admin/userCreation/edit/'.$id);
              } 

         }
         else
         {
            Session::flash('message', 'Invalid id');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/userCreation');
          // Session::flash('message', 'School name is already exists');
          // Session::flash('alert-class', 'alert-warning');
          // return Redirect::to('admin/userCreation/edit/'.$id);
         }


     }

//     public function update(Request $request) {
//         $id = $request->get('hidid');
//         $cName=$request->get('cname');
        
//         $GetClassUnique = SchClassModel:: where(['sch_class' => "$cName"])->whereNotIn('sch_cls_id', [$id])->get()->count();
//         if($GetClassUnique==0)
//         {
//             $updateCus=SchClassModel::where('sch_cls_id', $id)
//                       ->update(["sch_class"=> "$cName"]);
//             Session::flash('message', 'Class Name updated successfully');
//             Session::flash('alert-class', 'alert-success');
//             return redirect('admin/schclass/') ;          
//         }
//         else
//         {

//           Session::flash('message', 'School name is already exists');
//             Session::flash('alert-class', 'alert-warning');
//             return Redirect::to('admin/schclass/edit/'.$id);
//         }

        
//     }

    public function actionupdate(Request $request) {
        if(auth()->guard('admin')->user()->id !=1)
       {
          return view('backend.pageDined');
       }

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

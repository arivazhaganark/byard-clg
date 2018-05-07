<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\ClgStaffModel;
use App\Model\backend\User;
use Redirect;
use Session;
use DB;
use Excel;


class ClgStaffController extends Controller
{
    public function index()
    {
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $StaffAll = ClgStaffModel::where('active', 0)->get();
        }
        else {
            $StaffAll = ClgStaffModel::where('active', 1)->get();
        }

        $active_count = ClgStaffModel::where('active', 1)->count();
        $inactive_count = ClgStaffModel::where('active', 0)->count();
        return view('backend.clgStaffs.index', compact('StaffAll', 'active_count', 'inactive_count'));
    }

    public function create()
    {
        return view('backend.clgStaffs.add');
    }

    public function store(Request $request)
    {
        $inputs = $request->all();
        $sName = $inputs['sname'];
        $sCode = $inputs['scode'];
        $voice=0;
        $voicescreen=0;
        $voicevid=0;
        $voicevidscr=0;
        if(isset($inputs['licence']))
        {
          $licenceVal=$inputs['licence'];
          for($i=0;$i<count($licenceVal); $i++)
          {
              if($licenceVal[$i]==1)
                   $voice=1;
        
              if($licenceVal[$i]==2)
                  $voicescreen=1;
    
              if($licenceVal[$i]==3)
                $voicevid=1;
    
              if($licenceVal[$i]==4)
                 $voicevidscr=1;
          }

        }

 
        $GetCodeUnique = ClgStaffModel::where(['staff_code' => "$sCode"])->get()->count();
        $GetAdminLoginUniqe = User::where(['email' => $sCode])->get()->count();
        if ($GetCodeUnique > 0 && $GetAdminLoginUniqe > 0) {
            Session::flash('message', 'Staff code already exits');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/clgstaff/add')->withInput($request->all());
        }
        else {
    
            $dataInsert = ClgStaffModel::insert(['staff_name' => $sName, 'staff_code' => $sCode,'v_permission'=>$voice,'v_s_permission'=>$voicescreen,'v_vid_permission'=>$voicevid,'v_vid_s_permission'=>$voicevidscr]);
            $adminData = User::create(['name' => "$sName", 'email' => "$sCode", 'password' => bcrypt($sCode) , 'usertype' => 'CSF']); //CSF::College staff
            Session::flash('message', 'Staff has been added successfully');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/clgstaff/add');
        }
    }

    public function bulkstore(Request $request)
    {
        if (Input::hasFile('import_file')) {
            $insetCnt = 0;
            $updateCnt = 0;
            $toralCnt = 0;
            $voice=0;
            $voicescreen=0;
            $voicevid=0;
            $voicevidscr=0;
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path,
            function ($reader)
            {
            })->get();
            if (!empty($data) && $data->count()) {
                foreach($data as $key => $value) {
                    if (isset($value->scode) && isset($value->name)) {
                        
                        $voice=0;
                        $voicescreen=0;
                        $voicevid=0;
                        $voicevidscr=0; 

                        if ($value->scode != "" && $value->name != "") {
                            $stfCode = $value->scode;
                            $stfName = $value->name;
                            $voice=$value->voice;
                            $voicescreen=$value->voice_screen;
                            $voicevid=$value->voice_video;
                            $voicevidscr=$value->voice_video_screen;

                            $getCodeCnt = ClgStaffModel::where(['staff_code' => "$stfCode"])->get()->count();
                            $GetAdminLoginUniqe = User::where(['email' => $stfCode])->get()->count();
                            if ($getCodeCnt == 0 && $GetAdminLoginUniqe == 0) {
                                $saveData = ClgStaffModel::insert(['staff_code' => $stfCode, 'staff_name' => $stfName,'v_permission'=>$voice,'v_s_permission'=>$voicescreen,'v_vid_permission'=>$voicevid,'v_vid_s_permission'=>$voicevidscr]);
                                $adminData = User::create(['name' => "$stfName", 'email' => "$stfCode", 'password' => bcrypt($stfCode) , 'usertype' => 'CSF']); //CSF::College staff
                                $insetCnt++;
                            }
                            else {
                                $updateCus = ClgStaffModel::where('staff_code', $stfCode)->update(["staff_name" => "$stfName",'v_permission'=>$voice,'v_s_permission'=>$voicescreen,'v_vid_permission'=>$voicevid,'v_vid_s_permission'=>$voicevidscr]);
                                $adminData = User::where('email', '=', $stfCode)->update(['name' => $stfName]); 
                                //CSF::College staff
                                $updateCnt++;
                            }

                            $toralCnt++;
                        }
                    }
                    else {
                        Session::flash('message', 'Invalid header!');
                        Session::flash('alert-class', 'alert-warning');
                        return Redirect::to('admin/clgstaff/add');
                        exit;
                    }
                }

                if ($toralCnt > 0) {
                    Session::flash('message', "Staff added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                    Session::flash('alert-class', 'alert-success');
                    return Redirect::to('admin/clgstaff/add');
                    exit;
                }

                //  $insert[] = ['title' => $value->title, 'description' => $value->description];

            }
            else {
                Session::flash('message', 'File content is empty!');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/clgstaff/add');
            }

            // echo "<pre>";print_r($data);

        }
        else {
            Session::flash('message', 'Please upload xls file ');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/clgstaff/add');
        }
    }

    public function edit($id)
    {
        $clStaff = ClgStaffModel::where(['cl_stf_id' => "$id"])->get();

        return view('backend.clgStaffs.edit', compact('clStaff'));
    }

    public function update(Request $request)
    {
        $id = $request->get('hidid');
        $sCode = $request->get('scode');
        $sName = $request->get('sname');
        $voice=0;
        $voicescreen=0;
        $voicevid=0;
        $voicevidscr=0;
       
          $licenceVal=$request->get('licence');
          for($i=0;$i<count($licenceVal); $i++)
          {
              if($licenceVal[$i]==1)
                   $voice=1;
        
              if($licenceVal[$i]==2)
                  $voicescreen=1;
    
              if($licenceVal[$i]==3)
                $voicevid=1;
    
              if($licenceVal[$i]==4)
                 $voicevidscr=1;
          }
       
        $GetStaffUnique = ClgStaffModel::where(['staff_code' => "$sCode"])->whereNotIn('cl_stf_id', [$id])->get()->count();
        if ($GetStaffUnique == 0) {


            $updateCus = ClgStaffModel::where('cl_stf_id', $id)->update(["staff_name" => "$sName", "staff_code" => "$sCode",'v_permission'=>$voice,'v_s_permission'=>$voicescreen,'v_vid_permission'=>$voicevid,'v_vid_s_permission'=>$voicevidscr]);
            Session::flash('message', 'Staff details updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/clgstaff/');
        }
        else {
            Session::flash('message', 'Staff code is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/clgstaff/edit/' . $id);
        }
    }

    public function actionupdate(Request $request)
    {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            $action_value = "0";
            $msg_value = "Staff has been successfully inactivated.";
            $redirect_value = "admin/clgstaff";
        }
        else
        if ($action == 'Active') {
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/clgstaff/?token=inactive";
        }
        else {
            $msg_value = "Invalid request";
        }

        foreach($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                ClgStaffModel::select('*')->where('cl_stf_id', $update_id)->update($data);
            }
        }

        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
}  

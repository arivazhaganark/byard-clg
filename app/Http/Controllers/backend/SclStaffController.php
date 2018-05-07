<?php

namespace App\Http\Controllers\backend;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchStaffModel;
use App\Model\backend\User;
use Redirect;
use Session;
use DB;
use Excel;


class SclStaffController extends Controller
{
     
    public function index(){

        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }
        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $StaffAll = SchStaffModel::where('active', 0)->get();
        
        } else {
            $StaffAll = SchStaffModel::where('active', 1)->get();

        }
     	
        $active_count = SchStaffModel::where('active', 1)->count();
        $inactive_count = SchStaffModel::where('active', 0)->count();
       return view('backend.sclStaffs.index', compact('StaffAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         
        return view('backend.sclStaffs.add');
    }
    

      public function store(Request $request) {
        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
   
     	$inputs=$request->all();
        $sName=$inputs['sname']; 
        $sCode=$inputs['scode']; 
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

        $GetCodeUnique = SchStaffModel:: where(['staff_code' => "$sCode"])->get()->count();
        $GetAdminLoginUniqe=User::where(['email'=>$sCode])->get()->count();

        if($GetCodeUnique>0 && $GetAdminLoginUniqe>0)
         {
            Session::flash('message', 'Staff code already exits');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schstaff/add')->withInput($request->all());
         }
         else
         {
            $data = SchStaffModel::create([
            'staff_name' => $sName,
            'staff_code'=>$sCode,
            'v_permission'=>$voice,
            'v_s_permission'=>$voicescreen,
            'v_vid_permission'=>$voicevid,
            'v_vid_s_permission'=>$voicevidscr
        ]); 

        $adminData=User::create(['name'=>"$sName",'email'=>"$sCode",'password'=>bcrypt($sCode),'usertype'=>'SSF']);
    

       Session::flash('message', 'Staff has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/schstaff/add');
    }
}

public function bulkstore(Request $request) {
            $uPermission=getUserPermission('schstaff','school');
            if($uPermission[0]->file_add==0) /**Check the file permission**/
            { return view('backend.pageDined');}
            if(Input::hasFile('import_file')){

                $insetCnt=0;
                $updateCnt=0;
                $toralCnt=0;

                 $path = Input::file('import_file')->getRealPath();
                 $data = Excel::load($path, function($reader) { 
                 })->get();
                 if(!empty($data) && $data->count()){

                    foreach ($data as $key => $value) {

                        if(isset($value->scode) && isset($value->name))
                        {
                            if($value->scode !="" && $value->name !="" )
                            {

                                $stfCode=$value->scode;
                                $stfName=$value->name;
                                $getCodeCnt = SchStaffModel:: where(['staff_code' => "$stfCode"])->get()->count();
                                $GetAdminLoginUniqe=User::where(['email'=>$stfCode])->get()->count();
                                if($getCodeCnt==0 && $GetAdminLoginUniqe==0)
                                {

                                    $saveData = SchStaffModel::create([
                                                    'staff_code' =>$stfCode,
                                                    'staff_name'=>$stfName
                                                ]);

                                    $adminData=User::create(['name'=>"$stfName",'email'=>"$stfCode",'password'=>bcrypt($stfCode),'usertype'=>'SSF']);
    
                                    $insetCnt++;


                                }
                                else
                                {

                                    $updateCus=SchStaffModel::where('staff_code', $stfCode)
                                    ->update(["staff_name"=> "$stfName"]);

                                      $adminUpdateData=User::where('email', $stfCode)->update(['name'=>"$stfName"]);
                                    $updateCnt++;


                                }

                                $toralCnt++;





                            }


                        }
                        else
                        {

                            Session::flash('message', 'Invalid header!');
                            Session::flash('alert-class', 'alert-warning');
                            return Redirect::to('admin/schstaff/add');
                            exit;



                        }

                        
                       
                    }


                    if($toralCnt>0) 
                        {

                            

                            Session::flash('message', "Staff added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                            Session::flash('alert-class', 'alert-success');
                            return Redirect::to('admin/schstaff/add');
                            exit;
                        }
                
                 }
                 else
                 {

                    Session::flash('message', 'File content is empty!');
                    Session::flash('alert-class', 'alert-warning');
                    return Redirect::to('admin/schstaff/add');


                 }


            } 
            else
            {


                Session::flash('message', 'Please upload xl,xls file ');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/schstaff/add');



            }   
}

     

    public function edit($id){ 
        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');} 
    	$sclStaff = SchStaffModel::where(['scl_stf_id' => "$id"])->get();
        return view('backend.sclStaffs.edit', compact('sclStaff'));
    }

    public function update(Request $request) {
        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $id = $request->get('hidid');
        $sCode=$request->get('scode');
        $sName=$request->get('sname');
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
        
        $GetStaffUnique = SchStaffModel:: where(['staff_code' => "$sCode"])->whereNotIn('scl_stf_id', [$id])->get()->count();
        if($GetStaffUnique==0)
        {
            $updateCus=SchStaffModel::where('scl_stf_id', $id)
                      ->update(["staff_name"=> "$sName","staff_code"=>"$sCode",'v_permission'=>$voice,'v_s_permission'=>$voicescreen,'v_vid_permission'=>$voicevid,'v_vid_s_permission'=>$voicevidscr]);
            Session::flash('message', 'Staff details updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schstaff/') ;          
        }
        else
        {

          Session::flash('message', 'Staff code is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstaff/edit/'.$id);
        }

        
    }

    public function actionupdate(Request $request) {
        $uPermission=getUserPermission('schstaff','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Staff has been successfully inactivated.";
            $redirect_value = "admin/schstaff";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/schstaff/?token=inactive";
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
                SchStaffModel::select('*')->where('scl_stf_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}

<?php

namespace App\Http\Controllers\backend;
 


use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\schusedpackModel;
use App\Model\backend\CdnkeyUsed;
use App\Model\backend\Admin;
use Redirect;
use Session;
use DB;
use Excel;


class UsedPackController extends Controller
{
      


    public function index(){ 
        $input = Input::all(); 
        $staffCode=auth()->guard('admin')->user()->email; 
        $StaffAll = CdnkeyUsed::leftJoin('atnetwork_license_interface','atnetwork_license_interface.lin_id','=','cdn_customer_key_used.lin_id')->where(['staff_code'=>$staffCode])->orderBy('cdn_customer_key_used.cku_id','DESC')->get();
        $active_count = CdnkeyUsed::where(['staff_code'=>$staffCode])->count();
       
       return view('backend.keyUsed.index', compact('StaffAll', 'active_count'));
    }

    public function create() {
         
        return view('backend.keyUsed.add');
    }
    

      public function store(Request $request) {
   
     	$inputs=$request->all();
        $sName=$inputs['sname']; 
        $sCode=$inputs['scode']; 
        $GetCodeUnique = schusedpackModel:: where(['staff_code' => "$sCode"])->get()->count();
        $GetAdminLoginUniqe=Admin::where(['email'=>$sCode])->get()->count();

        if($GetCodeUnique>0 && $GetAdminLoginUniqe>0)
         {
            Session::flash('message', 'Staff code already exits');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schusedpack/add')->withInput($request->all());
         }
         else
         {
            $data = schusedpackModel::create([
            'staff_name' => $sName,
            'staff_code'=>$sCode
        ]); 

        $adminData=Admin::create(['name'=>"$sName",'email'=>"$sCode",'password'=>bcrypt($sCode),'usertype'=>'SSF']);
    

       Session::flash('message', 'Staff has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/schusedpack/add');
    }
}

public function bulkstore(Request $request) {
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
                                $getCodeCnt = schusedpackModel:: where(['staff_code' => "$stfCode"])->get()->count();
                                if($getCodeCnt==0)
                                {

                                    $saveData = schusedpackModel::create([
                                                    'staff_code' =>$stfCode,
                                                    'staff_name'=>$stfName
                                                ]);
                                    $insetCnt++;


                                }
                                else
                                {

                                    $updateCus=schusedpackModel::where('staff_code', $stfCode)
                                    ->update(["staff_name"=> "$stfName"]);
                                    $updateCnt++;


                                }

                                $toralCnt++;





                            }


                        }
                        else
                        {

                            Session::flash('message', 'Invalid header!');
                            Session::flash('alert-class', 'alert-warning');
                            return Redirect::to('admin/schusedpack/add');
                            exit;



                        }

                        
                       
                    }


                    if($toralCnt>0) 
                        {

                            

                            Session::flash('message', "Staff added successfully Total Row($toralCnt) Insert Row ($insetCnt) Update Row ($updateCnt)");
                            Session::flash('alert-class', 'alert-success');
                            return Redirect::to('admin/schusedpack/add');
                            exit;
                        }
                



                 //  $insert[] = ['title' => $value->title, 'description' => $value->description];

                 }
                 else
                 {

                    Session::flash('message', 'File content is empty!');
                    Session::flash('alert-class', 'alert-warning');
                    return Redirect::to('admin/schusedpack/add');


                 }


//echo "<pre>";print_r($data);


 
            } 
            else
            {


                Session::flash('message', 'Please upload xl,xls file ');
                Session::flash('alert-class', 'alert-warning');
                return Redirect::to('admin/schusedpack/add');



            }   
}

     

    public function edit($id){  
    	$sclStaff = schusedpackModel::where(['scl_stf_id' => "$id"])->get();
        return view('backend.keyUsed.edit', compact('sclStaff'));
    }

    public function update(Request $request) {
        $id = $request->get('hidid');
        $sCode=$request->get('scode');
        $sName=$request->get('sname');
        
        $GetStaffUnique = schusedpackModel:: where(['staff_code' => "$sCode"])->whereNotIn('scl_stf_id', [$id])->get()->count();
        if($GetStaffUnique==0)
        {
            $updateCus=schusedpackModel::where('scl_stf_id', $id)
                      ->update(["staff_name"=> "$sName","staff_code"=>"$sCode"]);
            Session::flash('message', 'Class Name updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schusedpack/') ;          
        }
        else
        {

          Session::flash('message', 'Staff code is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schusedpack/edit/'.$id);
        }

        
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Staff has been successfully inactivated.";
            $redirect_value = "admin/schusedpack";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/schusedpack/?token=inactive";
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
                schusedpackModel::select('*')->where('scl_stf_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}

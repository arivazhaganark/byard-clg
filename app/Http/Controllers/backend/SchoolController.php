<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\CustomerModel;
use App\Model\backend\SchoolModel;
use Redirect;
use Session;
use DB;


class SchoolController extends Controller
{
     
    public function index(){
        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $SchoolAll = SchoolModel::where('active', 0)->get();
        
        } else {
            $SchoolAll = SchoolModel::where('active', 1)->get();

        }
     	
        $active_count = SchoolModel::where('active', 1)->count();
        $inactive_count = SchoolModel::where('active', 0)->count();
       return view('backend.school.index', compact('SchoolAll', 'active_count', 'inactive_count'));
    }

    public function create() {
         
        return view('backend.school.add');
    }
    

      public function store(Request $request) {
   
     	$inputs=$request->all();
        $sName=$inputs['sname'];
        $GetNameUnique = SchoolModel:: where(['scl_name' => "$sName"])->get()->count();
        if($GetNameUnique>0)
         {
            Session::flash('message', 'School name already exits');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/school/add')->withInput($request->all());
         }
         else
         {
            $data = SchoolModel::create([
            'scl_name' => $sName 
        ]);     

       Session::flash('message', 'School has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/school/add');
    }
}

     

    public function edit($cus_id){  
    	$school = SchoolModel::where(['scl_id' => "$cus_id"])->get();
        return view('backend.school.edit', compact('school'));
    }

    public function update(Request $request) {
        $id = $request->get('hidid');
        $sName=$request->get('sname');
        
        $GetSchoolUnique = SchoolModel:: where(['scl_name' => "$sName"])->whereNotIn('scl_id', [$id])->get()->count();
        if($GetSchoolUnique==0)
        {
            $updateCus=SchoolModel::where('scl_id', $id)
                      ->update(["scl_name"=> "$sName"]);
            Session::flash('message', 'School Name updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/school') ;          
        }
        else
        {

          Session::flash('message', 'School name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/school/edit/'.$id);
        }

        
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "School has been successfully inactivated.";
            $redirect_value = "admin/school";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "School has been successfully activated.";
            $redirect_value = "admin/school/?token=inactive";
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
                SchoolModel::select('*')->where('scl_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}

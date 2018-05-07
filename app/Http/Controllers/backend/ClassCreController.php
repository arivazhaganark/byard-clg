<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchClassModel;
use Redirect;
use Session;
use DB;


class ClassCreController extends Controller
{
     
    public function index(){

        $uPermission=getUserPermission('schclass','school');

        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }


        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $ClassAll = SchClassModel::where('active', 0)->get();
        
        } else {
            $ClassAll = SchClassModel::where('active', 1)->get();

        }
     	
        $active_count = SchClassModel::where('active', 1)->count();
        $inactive_count = SchClassModel::where('active', 0)->count();
       return view('backend.classCre.index', compact('ClassAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {

        $uPermission=getUserPermission('schclass','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         
        return view('backend.classCre.add');
    }
    

      public function store(Request $request) {
         $uPermission=getUserPermission('schclass','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         
   
     	$inputs=$request->all();
        $cName=$inputs['cname']; 
        $GetNameUnique = SchClassModel:: where(['sch_class' => "$cName"])->get()->count();

        if($GetNameUnique>0)
         {
            Session::flash('message', 'Class name already exits');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schclass/add')->withInput($request->all());
         }
         else
         {
            $data = SchClassModel::create([
            'sch_class' => $cName
        ]);     

       Session::flash('message', 'Class has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/schclass/add');
    }
}

     

    public function edit($id){  
         $uPermission=getUserPermission('schclass','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         
    	$sclClass = SchClassModel::where(['sch_cls_id' => "$id"])->get();
        return view('backend.classCre.edit', compact('sclClass'));
    }

    public function update(Request $request) {
         $uPermission=getUserPermission('schclass','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         
        $id = $request->get('hidid');
        $cName=$request->get('cname');
        
        $GetClassUnique = SchClassModel:: where(['sch_class' => "$cName"])->whereNotIn('sch_cls_id', [$id])->get()->count();
        if($GetClassUnique==0)
        {
            $updateCus=SchClassModel::where('sch_cls_id', $id)
                      ->update(["sch_class"=> "$cName"]);
            Session::flash('message', 'Class Name updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schclass/') ;          
        }
        else
        {

          Session::flash('message', 'School name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schclass/edit/'.$id);
        }

        
    }

    public function actionupdate(Request $request) {
         $uPermission=getUserPermission('schclass','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
         

        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Class has been successfully inactivated.";
            $redirect_value = "admin/schclass";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Class has been successfully activated.";
            $redirect_value = "admin/schclass/?token=inactive";
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
                SchClassModel::select('*')->where('sch_cls_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

     

   
}

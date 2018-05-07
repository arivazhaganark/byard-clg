<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\CmsMenuModel;
use Redirect;
use Session;

class MenuController extends Controller
{
    //
    public function index(){
    	$input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $menu = CmsMenuModel::where('status', 'N')->get();
        
        } else {
            $menu = CmsMenuModel::where('status', 'Y')->get();

        }
        $active_count = CmsMenuModel::where('status', 'Y')->count();
        $inactive_count = CmsMenuModel::where('status', 'N')->count();
        return view('backend.menu.index', compact('menu', 'active_count', 'inactive_count'));

    }

    public function create() {
        $menu = CmsMenuModel::all();
        // $master_languages = MasterLanguageModel::select('*')->where('status', 1)->orderBy('name', 'ASC')->get();
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        
        return view('backend.menu.add', compact('menu', 'cms_menu_list'));
    }
    public function store(){
    	$inputs=Input::all();
    	$insert_arr['name']=$inputs['tname'];
    	$insert_arr['status']='Y';
    	$data = CmsMenuModel::create($insert_arr);
        $id = $data->id;
      
       
        return Redirect::to('admin/menu/add');

    }

      public function show($id) {
        $menu = CmsMenuModel::find($id);
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        return view('backend.menu.show', compact('menu','cms_menu_list'));
    }
    public function edit($id){
    	$menu = CmsMenuModel::find($id);
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        return view('backend.menu.edit', compact('menu', 'cms_menu_list'));
    }
        public function update(Request $request) {
        $id = $request->get('id');
        $inputs = $request->all();
        $insert_arr['name']=$inputs['name'];
    	$insert_arr['status']='Y';
    	 $updated_row_cnt = CmsMenuModel::select('*')->where('id', $id)->update($insert_arr);
        
        return Redirect::to('admin/menu/edit/' . $id);
    }
     public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            $column_name = "status";
            $action_value = "N";
            $msg_value = "CMS(s) has been successfully inactivated.";
            $redirect_value = "admin/menu";
            
        } else if ($action == 'Active') {
            $column_name = "status";
            $action_value = "Y";
            $msg_value = "CMS(s) has been successfully activated.";
            $redirect_value = "admin/menu/?token=inactive";
            
        } else if ($action == 'Delete') {
            $msg_value = "CMS(s) has been successfully deleted.";
            $redirect_value = "admin/menu/?token=inactive";
            
        }
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                CmsMenuModel::select('*')->where('id', $update_id)->update($data);
                
            } else {
                $cms_data = CmsMenuModel::find($update_id);
                $cms_data->delete();
            }
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

    public function check_name(Request $request)
    {
        $inputs = $request->all();
       
        $name = $inputs['name'];
        $check_name = CmsMenuModel::where('name',$name)->first();
        if($check_name != ''){
            $get_name = $check_name->name;
            if($get_name != $name){
                return "true";
            }
            else{
                return "false";
            }
        }
        else{
            return "true";
        }
    }



}

<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use App\Model\backend\Cms_Model;
use App\Model\backend\CmsMenuModel;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Redirect;
use DB;

class CmsController extends Controller
{
    public function index() {
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $cms = Cms_Model::where('status', 'N')->orderBy('position', 'ASC')->get();
        
        } else {
            $cms = Cms_Model::where('status', 'Y')->orderBy('position', 'ASC')->get();

        }
        $active_count = Cms_Model::where('status', 'Y')->count();
        $inactive_count = Cms_Model::where('status', 'N')->count();
        return view('backend.cms.index', compact('cms', 'active_count', 'inactive_count'));
    }

    public function create() {
        $cms = Cms_Model::all();
        // $master_languages = MasterLanguageModel::select('*')->where('status', 1)->orderBy('name', 'ASC')->get();
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        //  $itemID = Input::get('id');
        // $itemIndex = Input::get('name');
 
        // foreach($cms_menu_list as $value){
        //     return DB::table('cms_menu')->where('id','=',$itemID)->update(array('name'=> $itemIndex));
        // }
        return view('backend.cms.add', compact('cms', 'cms_menu_list'));
    }

    public function store(Request $request) {
        $inputs = Input::all();
        $slug_value = str_slug($inputs['title'], "-");
        $insert_arr['title'] = $inputs['title'];
        $insert_arr['language_id'] = ''; 
        $insert_arr['menu_id'] = $inputs['menu_id'];
        $insert_arr['slug'] = $slug_value;
        $insert_arr['status'] = "Y";
        $insert_arr['page_type'] = '';
        $insert_arr['content'] = $inputs['editor1'];
        $insert_arr['seo_title'] = $inputs['seo_title'];
        $insert_arr['seo_description'] = $inputs['seo_description'];
        $insert_arr['seo_keywords'] = $inputs['seo_keywords'];
  
        $insert_arr['page_link'] = '';
        $insert_arr['page_linktype'] = '';
         $insert_arr['page_type'] = $inputs['page_type'];
        if ($inputs['page_type'] == 'content') {
            $insert_arr['content'] = $inputs['editor1'];
            $insert_arr['seo_title'] = $inputs['seo_title'];
            $insert_arr['seo_description'] = $inputs['seo_description'];
            $insert_arr['seo_keywords'] = $inputs['seo_keywords'];
            $insert_arr['page_link'] = "";
            $insert_arr['page_linktype'] = "";
        } else {
            $insert_arr['page_link'] = $inputs['page_link'];
            $insert_arr['page_linktype'] = $inputs['page_linktype'];
            $insert_arr['content'] = "";
            $insert_arr['seo_title'] = "";
            $insert_arr['seo_description'] = "";
            $insert_arr['seo_keywords'] = "";
        }
        $insert_arr['position'] = $inputs['position'];
        $data = Cms_Model::create($insert_arr);
        $id = $data->id;      
        
        Session::flash('message', 'CMS has been added successfully');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/cms/add');
    }

    public function show($id) {
        $cms = Cms_Model::find($id);
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        return view('backend.cms.show', compact('cms','cms_menu_list'));
    }

    public function edit($id) {
        $cms = Cms_Model::find($id);
        $cms_menu_list = CmsMenuModel::select('*')->orderBy('id', 'ASC')->get();
        return view('backend.cms.edit', compact('cms', 'cms_menu_list'));
    }

    public function update(Request $request) {
        $id = $request->get('id');
        $inputs = $request->all();
        $slug_value = str_slug($inputs['title'], "-");
        $insert_arr['title'] = $inputs['title'];
        
        $insert_arr['menu_id'] = $inputs['menu_id'];
        $insert_arr['slug'] = $slug_value;
        $insert_arr['status'] = "Y";
        $insert_arr['page_type'] = '';
        $insert_arr['content'] = $inputs['editor1'];
        $insert_arr['seo_title'] = $inputs['seo_title'];
        $insert_arr['seo_description'] = $inputs['seo_description'];
        $insert_arr['seo_keywords'] = $inputs['seo_keywords'];
  
        $insert_arr['page_link'] = '';
        $insert_arr['page_linktype'] = '';
        $insert_arr['page_type'] = $inputs['page_type'];
        if ($inputs['page_type'] == 'content') {
            $insert_arr['content'] = $inputs['editor1'];
            $insert_arr['seo_title'] = $inputs['seo_title'];
            $insert_arr['seo_description'] = $inputs['seo_description'];
            $insert_arr['seo_keywords'] = $inputs['seo_keywords'];
            $insert_arr['page_link'] = "";
            $insert_arr['page_linktype'] = "";
        } else {
            $insert_arr['page_link'] = $inputs['page_link'];
            $insert_arr['page_linktype'] = $inputs['page_linktype'];
            $insert_arr['content'] = "";
            $insert_arr['seo_title'] = "";
            $insert_arr['seo_description'] = "";
            $insert_arr['seo_keywords'] = "";
        }
        $insert_arr['position'] = $inputs['position'];
        $updated_row_cnt = Cms_Model::select('*')->where('id', $id)->update($insert_arr);
        
        Session::flash('message', 'CMS has been updated successfully');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/cms/edit/' . $id);
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            $column_name = "status";
            $action_value = "N";
            $msg_value = "CMS(s) has been successfully inactivated.";
            $redirect_value = "admin/cms";
            
        } else if ($action == 'Active') {
            $column_name = "status";
            $action_value = "Y";
            $msg_value = "CMS(s) has been successfully activated.";
            $redirect_value = "admin/cms/?token=inactive";
            
        } else if ($action == 'Delete') {
            $msg_value = "CMS(s) has been successfully deleted.";
            $redirect_value = "admin/cms/?token=inactive";
            
        }
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                Cms_Model::select('*')->where('id', $update_id)->update($data);
                
            } else {
                $cms_data = Cms_Model::find($update_id);
                $cms_data->delete();
            }
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

}

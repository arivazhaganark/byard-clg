<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\CategoryModel;
use Redirect;
use Session;

class SubCategoryController extends Controller
{
    public function index($id)
    {
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $sub_category = CategoryModel::where('status', 'N')->where('type', $id)->get();

        } else {
            $sub_category = CategoryModel::where('status', 'Y')->where('type', $id)->get();
        }
        $active_count   = CategoryModel::where('status', 'Y')->where('type', $id)->count();
        $inactive_count = CategoryModel::where('status', 'N')->where('type', $id)->count();
        $category = CategoryModel::where('id', $id)->first();
        return view('backend.sub_category.index', compact('sub_category', 'active_count', 'inactive_count', 'category'));

    }

    public function create($id)
    {
        $category = CategoryModel::where('id', $id)->first();
        return view('backend.sub_category.add', compact('category'));
    }
    public function store($id)
    {
        $inputs               = Input::all();
        $insert_arr['name']   = $inputs['category_name'];
        $insert_arr['status'] = 'Y';
        $insert_arr['type'] = $id;
        $data = CategoryModel::create($insert_arr);
        return "success";
    }
    public function edit($id, $sub_id)
    {
        $category = CategoryModel::find($id);
        $sub_category = CategoryModel::where('type', $id)->first();
        return view('backend.sub_category.edit', compact('category', 'sub_category'));
    }
    public function update(Request $request)
    {
        //$id = $request->get('hid_update_id');
        $inputs             = $request->all();
        $id                 = $inputs['hid_update_id'];
        $category_id        = $inputs['hid_category_id'];
        $update_arr['name'] = $inputs['category_name'];
        $updated_row_cnt    = CategoryModel::select('*')->where('id', $id)->where('type', $category_id)->update($update_arr);
        return "success";
    }
    public function actionupdate(Request $request)
    {
        $inputs            = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $category_id = $inputs['hid_category_id'];
        $action            = $inputs['action'];
        if ($action == 'Inactive') {
            $column_name    = "status";
            $action_value   = "N";
            $msg_value      = "Categories has been successfully inactivated.";
            $redirect_value = "admin/sub_category/".$category_id;

        } else if ($action == 'Active') {
            $column_name    = "status";
            $action_value   = "Y";
            $msg_value      = "Categories has been successfully activated.";
            $redirect_value = "admin/sub_category/".$category_id."?token=inactive";

        } else if ($action == 'Delete') {
            $msg_value      = "Categories has been successfully deleted.";
            $redirect_value = "admin/sub_category/".$category_id."?token=inactive";

        }
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                CategoryModel::select('*')->where('id', $update_id)->where('type', $category_id)->update($data);

            } else {
                $cms_data = CategoryModel::find($update_id);
                $cms_data->delete();
            }
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

    public function exist_check(Request $request)
    {
        $inputs    = $request->all();
        $name      = $inputs['category_name'];
        $update_id = $inputs['id'];
        $category_id = $inputs['category_id'];
        if (!empty($update_id)) {
            $check_count = CategoryModel::where('name', $name)->where('id', '!=', $update_id)->where('type', $category_id)->count();
        } else {
            $check_count = CategoryModel::where('name', $name)->where('type', $category_id)->count();
        }
        if ($check_count == 0) {
            return "true";
        } else {
            return "false";
        }
    }
}

<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\frontend\User;
use Redirect;
use Session;

class UserController extends Controller
{
    public function index() {
        $input = Input::all();
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $user = User::where('status', 'N')->get();

        } else {
            $user = User::where('status', 'Y')->get();

        }
        $active_count = User::where('status', 'Y')->count();
        $inactive_count = User::where('status', 'N')->count();
        return view('backend.user.index', compact('user', 'active_count', 'inactive_count'));
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();

        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $action = $inputs['action'];

        if ($action == 'Inactive') {
            $column_name = "status";
            $action_value = "N";
        } else if ($action == 'Active') {
            $column_name = "status";
            $action_value = "Y";
        }

        $action_taken_username_list = "";
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                User::select('*')->where('id', $update_id)->update($data);
            } else {
                $user = User::find($update_id);
                $user->delete();
            }
        }
        if ($action == 'Inactive') {
            $msg_value = "User has been deactivated.";
            $redirect_value = "admin/user";
        } else if ($action == 'Active') {
            $msg_value = "User has been activated successfully.";
            $redirect_value = "admin/user/?token=inactive";
        } else if ($action == 'Delete') {
            $msg_value = "User has been deleted";
            $redirect_value = "admin/user/?token=inactive";
        }

        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

}

<?php

namespace App\Http\Controllers\backend;

use App\Model\backend\ChangePassword;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Auth;
use Hash;

class ChangePassController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

     public function index($guard = 'admin') { 
        $admin_user_details = "";
        if (Auth::guard($guard)->check()) {
            $admin_user_details = Auth::guard($guard)->user(); 
            $current_password = $admin_user_details->password;
        } 
         return view('backend.user_change_password', compact('admin_user_details'));
    }

    public function store(Request $request, $guard ='admin') {
        $inputs = $request->all();
        $old_password = Hash::make($inputs['new_password']);

        $data = array(
            "password" => $old_password
        );
        if (Auth::guard($guard)->check()) {
            $admin_user_details = Auth::guard($guard)->user(); 
            $current_user_id = $admin_user_details->id;
        }
        $update_row_cnt = ChangePassword::select('*')->where('id', $current_user_id)->update($data);

        Session::flash('message', 'Password changed successfully');
        Session::flash('alert-class', 'alert-success');
    

        return redirect('admin/change_password');
    }

    public function OldPasswordCheck($guard = 'admin') {
        $input = Input::all();
        $old_password = Hash::make($input['old_password']); 
        if (Auth::guard($guard)->check()) {
            $admin_user_details = Auth::guard($guard)->user();
            $current_password = $admin_user_details->password;
            $current_user_id = $admin_user_details->id;
            if (password_verify($input['old_password'], $current_password)) {
                return "true";
            } else {
                return "false";
            }
        } else {
            return "false";
        }
    }
    
}

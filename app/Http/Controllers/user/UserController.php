<?php

namespace App\Http\Controllers\user;

//use App\User;
//use App\Model\backend\CustomerModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use App\Model\frontend\Feedback;
use App\Model\backend\Setting;
use Auth;
use Hash;
use DB;
use Redirect;


class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
     
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function home()
    {    //echo  auth()->guard('admin')->user()->usertype;
        return view('user.home',compact('logoImgPath'));
    }    

    public function change_password(Request $request, $locale = '', $guard = 'user') {
        app()->setLocale($locale);
        $inputs = $request->all();
        $old_password = Hash::make($inputs['new_password']);

        $data = array("password" => $old_password);
        if (Auth::guard($guard)->check()) {
            $user_details = Auth::guard($guard)->user();
            $current_user_id = $user_details->id;
        }
        DB::table('users')->where('id', $current_user_id)->update($data);
        echo 'success';
    }

    public function OldPasswordCheck($guard = 'user') {
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

    public function NewPasswordCheck() {
        $input = Input::all();
        $new_password = $input['new_password'];
        $old_password = $input['old_password'];
        if ($new_password == $old_password) {
            return "false";
        } else {
            return "true";
        }
    }

    public function help()
    { 
       $settingCnt=Setting::select('*')->where('id', 1)->count();
        $logoImgPath='';
          if($settingCnt>0)
          {
            $setting=Setting::select('*')->where('id', 1)->get(); 
            $logoImgPath=isset($setting[0]->img_path)?$setting[0]->img_path:'' ;
          }  
  
        return view('user.help',compact('logoImgPath'));
    }  

    public function feedback() 
    {
        $input = Input::all();
        $user = Feedback::create([
            'message' => $input['message'],
            'user_id' => Auth::guard('admin')->user()->id,
            'status' => 'A'
        ]);

        return Redirect::to($input['current_url']);
    }
    
}

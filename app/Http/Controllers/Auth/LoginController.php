<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Model\backend\User;
use App\Model\backend\Setting;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest', ['except' => 'logout']);
    }

    public function userLoginForm() {
 
        if(auth()->guard('user')->check()) {
            return redirect()->to('user/home');
        } else {

          $settingCnt=Setting::select('*')->where('id', 1)->count();
          $filePathImage='';
          if($settingCnt>0)
          {
            $setting=Setting::select('*')->where('id', 1)->get(); 
            $filePathImage=isset($setting[0]->img_path)?$setting[0]->img_path:'' ;
          }
            return view('user.auth.login',compact('filePathImage'));
        }
    }   

    // User login check
    public function userAuthenticate(Request $request)
    {        
        $username = $request->input('username');
        $password = $request->input('password');     //, 'usertype' => 'SSF'    
        if (auth()->guard('user')->attempt(['email' => $username, 'password' => $password,'usertype' => 'SSF'])) //School staff
        {   
            return redirect()->intended('user/home');
        }
        elseif(auth()->guard('user')->attempt(['email' => $username, 'password' => $password,'usertype' => 'SS']))
        {
         return redirect()->intended('user/home');
        }
        elseif(auth()->guard('user')->attempt(['email' => $username, 'password' => $password,'usertype' => 'CSF']))
        {
          return redirect()->intended('user/home');
        }
        elseif(auth()->guard('user')->attempt(['email' => $username, 'password' => $password,'usertype' => 'CS']))
        {
            return redirect()->intended('user/home');
        }
        else
        {
            return redirect()->intended('user/login')->with('status', 'Invalid Login Credentials !');
        }
    }   

    public function userLogout() 
    {
        auth()->guard('user')->logout();
        return redirect()->intended('user/login');
    } 
}

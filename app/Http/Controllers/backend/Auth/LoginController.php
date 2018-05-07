<?php

namespace App\Http\Controllers\backend\Auth;
use App\Model\backend\Admin;
use App\Model\backend\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    
    public function getLoginForm()
    { 

         if(auth()->guard('admin')->check()) {
            return redirect()->to('admin/home');
        } else {


          $settingCnt=Setting::select('*')->where('id', 1)->count();
          $filePathImage='';
          if($settingCnt>0)
          {
            $setting=Setting::select('*')->where('id', 1)->get(); 
            $filePathImage=isset($setting[0]->img_path)?$setting[0]->img_path:'' ;
          }

        return view('backend/auth/login',compact('filePathImage'));
      }
    }
    
    public function authenticate(Request $request)
    {   
      $validateVal=$this->validate($request,['email' => 'required','password' => 'required']);
 
        $email = $request->input('email');
        $password = $request->input('password');  
        // $license = $request->input('license');
        // $ChkfieldVal='';
        // if($license==1) 
        //      $ChkfieldVal="voice";
        // elseif($license==2)
        //      $ChkfieldVal="voice_screen";
        // elseif($license==3)
        //      $ChkfieldVal="voice_video";
        // elseif($license==4)
        //     $ChkfieldVal="voice_video_screen";

        if (auth()->guard('admin')->attempt(['email' => $email, 'password' => $password,'usertype'=>'W','active'=>1 ])) 
        { 
             return redirect()->intended('admin/home');
        }
        elseif(auth()->guard('admin')->attempt(['email' => $email, 'password' => $password,'usertype'=>'WS','active'=>1]))
        {
            return redirect()->intended('admin/home');
        }
        elseif(auth()->guard('admin')->attempt(['email' => $email, 'password' => $password,'usertype'=>'WC','active'=>1 ]))
        {
            return redirect()->intended('admin/home');
        }
        else
        {
          return redirect()->intended('admin/login')->with('status', 'Invalid Login !');
        }
    }

   
    
    
    public function getLogout() 
    {
        auth()->guard('admin')->logout();
        return redirect()->intended('admin/login');
    }
    
}

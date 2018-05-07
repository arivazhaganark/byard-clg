<?php

namespace App\Http\Controllers\backend;

use App\Model\backend\User;
use App\Model\backend\Setting;
use App\Model\backend\EducationTypeModel;
use App\Model\backend\CdnCustomerKeyMapModel;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;

use Intervention\Image\Facades\Image as Image;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;



class SettingController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
     protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|max:255',
            'from_email' => 'required|email|max:255|unique:email',
            'support_email' => 'required|email|max:255|unique:email'
        ]);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       if(auth()->guard('admin')->user()->id !=1)
       {
          return view('backend.pageDined');
       }

        $eduType=EducationTypeModel::select('*')->where('active', 1)->get(); 
         
        $setting=Setting::select('*')->where('id', 1)->get();
        if( (isset($setting[0])) AND ($setting[0]->ed_id==0 OR $setting[0]->ed_id=="" ) )
        {
            $CdnCusKeyMapDel=CdnCustomerKeyMapModel::select('*')->delete();
            $settingUpdate=Setting::select('*')->update(['c_id'=>'','cus_id'=>'','c_name'=>'','institution_name'=>'','license_email_id'=>'']);
             
             
        }
        $CdnCusKeyMap=CdnCustomerKeyMapModel::select('*')->get();
        return view('backend.setting', compact('setting','eduType','CdnCusKeyMap'));
    }
 public function addTitle(Request $request)
 {
   $inputs=$request->all();
   $rules = array( 'Title'=>'required');
   $validator = Validator::make(Input::all(), $rules); 
  if ($validator->fails())
  {
  return Redirect::to('admin/setting')->withErrors($validator);
  }
  else{ 

    $data = array(
         'institution_title'=>$inputs['Title']);
          $update_cnt = Setting::select('*')->where('id', 1)->update($data);
    return Redirect::to('admin/setting');


  }


 }
   public function Upload_Banner(Request $request)
   {
          $inputs=$request->all();
          $rules = array('image' => 'required'  );  
          $file = array('image' => Input::file('image'));
          $validator = Validator::make(Input::all(), $rules);

          if ($validator->fails())
          {
          return Redirect::to('admin/setting')->withErrors($validator);
          }
          else{
          $image = $request->file('image');
          $destinationPath = public_path('uploads/thumbnail');
          $input['imagename'] = mt_rand(999,999999)."_".time().".".$image->getClientOriginalExtension();
          $image->move($destinationPath, $input['imagename']);
          $data = array(
          'img_path' => $input['imagename']);
          $update_cnt = Setting::select('*')->where('id', 1)->update($data);
          if($update_cnt)
          {
         
          Session::flash('message', 'Subject updated successfully');
          Session::flash('alert-class', 'alert-success');
          return Redirect::to('admin/setting');
        

          }

          }
   }

    public function store(Request $request) 
    {
        if(auth()->guard('admin')->user()->id !=1)
        {
        return view('backend.pageDined');
        }
        $inputs = $request->all();
        $data = array(
            'from_email' => $inputs['from_email'],
            'from_email_display_name' => $inputs['from_email_display_name'],
            'support_email' => $inputs['support_email'],
            'facebook_link' => $inputs['facebook_link'],
            'twitter_link' => $inputs['twitter_link'],
            'youtube_link' => $inputs['youtube_link'],
            'ed_id'=>$inputs['selectId'],
            'header_script' => htmlentities($inputs['header_script']),
            'footer_script' => htmlentities($inputs['footer_script'])
        );
        $update_cnt = Setting::select('*')->where('id', 1)->update($data);
        Session::flash('message', 'Admin Settings has been updated successfully.');
        Session::flash('alert-class', 'alert-success');
        return redirect('admin/setting');
    }
    
}

<?php

namespace App\Http\Controllers\backend;

//use App\User;
//use App\Model\backend\CustomerModel;
use App\Model\backend\SchStaffModel;
use App\Model\backend\ClgStaffModel;
use App\Model\backend\EducationTypeModel;
use App\Model\backend\CdnCustomerKeyMapModel;
use App\Model\backend\CdnkeyUsed;
use App\Model\backend\Setting;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;


class AdminController extends Controller
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
    public function index()
    {
        return view('home');
    }
    
    public function home()
    {        
         
         if(1) //for admin user
         {
   
            $getCdnMapCnt=Setting::get()->count();
            if($getCdnMapCnt>0)
            {
              
               $getCdnMap=Setting::get();

               if($getCdnMap[0]->ed_id==1)  //school 
               {

                    $user = SchStaffModel::get();
                    $userCnt = SchStaffModel::get()->count();
                    $voiceUsedCnt = CdnkeyUsed::where(['lin_id'=>1])->count();
                    $voiceScrnUsedCnt = CdnkeyUsed::where(['lin_id'=>2])->count();
                    $voiceVidUsedCnt = CdnkeyUsed::where(['lin_id'=>3])->count();
                    $voiceVidScrnUsedCnt = CdnkeyUsed::where(['lin_id'=>4])->count();
                    return view('backend.home', compact('user','userCnt','voiceUsedCnt','voiceScrnUsedCnt','voiceVidUsedCnt','voiceVidScrnUsedCnt'));    

               }
               elseif($getCdnMap[0]->ed_id==2) //college
               { 

                 $user = ClgStaffModel::get();
                 $userCnt = ClgStaffModel::get()->count();
                 $voiceUsedCnt = CdnkeyUsed::where(['lin_id'=>1])->count();
                 $voiceScrnUsedCnt = CdnkeyUsed::where(['lin_id'=>2])->count();
                 $voiceVidUsedCnt = CdnkeyUsed::where(['lin_id'=>3])->count();
                 $voiceVidScrnUsedCnt = CdnkeyUsed::where(['lin_id'=>4])->count();
                 return view('backend.home', compact('user','userCnt','voiceUsedCnt','voiceScrnUsedCnt','voiceVidUsedCnt','voiceVidScrnUsedCnt'));        

               


               }
               else
               {

                  return redirect('/admin/setting')->send();
                  
               }
           
            }
            else
            {

                 return redirect('/admin/setting')->send();
              
            }



         }
         else{


            // $getCdnMapCnt=Setting::get()->count();

            // if($getCdnMapCnt>0)
            // {

            //    $getCdnMap=Setting::get();

            //    if($getCdnMap[0]->ed_id==1) //School  
            //    {

            //     if(auth()->guard('admin')->user()->usertype=="SSF") //school staff
            //     {

            //     }
            //     elseif(auth()->guard('admin')->user()->usertype=="SS")
            //     {

            //     }

            //     $staffCode=auth()->guard('admin')->user()->email; 
            //     $StaffAll = CdnkeyUsed::leftJoin('atnetwork_license_interface','atnetwork_license_interface.lin_id','=','cdn_customer_key_used.lin_id')->where(['staff_code'=>$staffCode])->orderBy('cdn_customer_key_used.cku_id','DESC')->get();
            //     $active_count = CdnkeyUsed::where(['staff_code'=>$staffCode])->count();
            //     return view('backend.keyUsed.index', compact('StaffAll', 'active_count'));

            //    }
            //    elseif($getCdnMap[0]->ed_id==2) //College
            //    {

            //     if(auth()->guard('admin')->user()->usertype=="CSF") //school staff
            //     {

            //         $staffCode=auth()->guard('admin')->user()->email; 
            //         $StaffAll = CdnkeyUsed::leftJoin('atnetwork_license_interface','atnetwork_license_interface.lin_id','=','cdn_customer_key_used.lin_id')->where(['staff_code'=>$staffCode])->orderBy('cdn_customer_key_used.cku_id','DESC')->get();
            //         $active_count = CdnkeyUsed::where(['staff_code'=>$staffCode])->count();

            //         return view('backend.keyUsed.index', compact('StaffAll', 'active_count'));

            //     }
            //     elseif(auth()->guard('admin')->user()->usertype=="CS")
            //     {

            //       $staffCode=auth()->guard('admin')->user()->email; 
            //         $StaffAll = CdnkeyUsed::leftJoin('atnetwork_license_interface','atnetwork_license_interface.lin_id','=','cdn_customer_key_used.lin_id')->where(['staff_code'=>$staffCode])->orderBy('cdn_customer_key_used.cku_id','DESC')->get();
            //         $active_count = CdnkeyUsed::where(['staff_code'=>$staffCode])->count();

            //         return view('backend.keyUsed.index', compact('StaffAll', 'active_count'));


            //     }



                    

            //    }


            // }

         }         
            
    }
    
}

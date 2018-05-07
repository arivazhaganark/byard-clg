<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\CdnCustomerKeyMapModel;
use App\Model\backend\Setting;
use App\Model\backend\User;
use App\Model\backend\SchStaffModel;
use App\Model\backend\ClgStaffModel;
use App\Model\backend\CdnkeyUsed;
use App\Model\backend\SclStfSubMasterModel;
use App\Model\backend\SclStfSubMapModel;
use App\Model\backend\SchFileManagerModel;
use App\Model\backend\SclstaffClassMappModel;
use App\Model\backend\SclstaffClassMasterModel;
use App\Model\backend\ClgStfSubMasterModel;
use App\Model\backend\ClgStfSubMapModel;
use App\Model\backend\ClgSubjectModel;
use App\Model\backend\ClgCourseModel;
use App\Model\backend\ClgFileManagerModel;
use App\Model\backend\ClgAcademicYearModel;
use Redirect;
use Session;
use DB;
use Auth;


class APIController extends Controller
{

  public function dataSynchrons(Request $request)  
  {
    $inputs = file_get_contents("php://input");
    $data = json_decode($inputs);
    $status=0;
    $result="";
    $error='';
    $CustomerName="" ;
    $customerEmailId="" ;
    $cusIds="";
       if(isset($data->operation) && isset($data->user->cusId) && isset($data->user->institeType) )
       {

          if($data->operation=="Synchrons" && $data->user->cusId !="" && $data->user->institeType>0 )
          {

            $getCusId=$data->user->cusId;
            $result=$data->user->cusId;
             
            $getCustomerDetailsCnt = DB::connection('mysql2')->table("atnetwork_customer_master")->where(['c_id'=>$getCusId])->get()->count();
            if($getCustomerDetailsCnt==1)
            {

              $getCustomerDetailsId=DB::connection('mysql2')->table("atnetwork_customer_master")->where(['c_id'=>$getCusId])->get();
              $CustomerName=$getCustomerDetailsId[0]->c_name ;
              $customerEmailId=$getCustomerDetailsId[0]->email_id ;
              $result=$getCustomerDetailsId;
              if($getCustomerDetailsId[0]->cus_id>0)
              {

                $cusIds=$getCustomerDetailsId[0]->cus_id;
                $chkKey=DB::connection('mysql2')->table("atnetwork_customer_key_master")->where('cus_id',$cusIds)->get()->count();

                if($chkKey>0)
                {


                  $getcusMapKey=DB::connection('mysql2')->table("atnetwork_customer_key_master")->where('cus_id',$cusIds)->get(array('cus_key_id')) ;
              
                 $cusKeyIds=$getcusMapKey[0]->cus_key_id;
                 $CusKeyMapCnt=DB::connection('mysql2')->table("atnetwork_customer_key_mapping")->where('cus_key_id',$cusKeyIds)->get()->count();

                 if($CusKeyMapCnt>0)
                 {

                  $CusKeyMap=DB::connection('mysql2')->table("atnetwork_customer_key_mapping")->where('cus_key_id',$cusKeyIds)->get();

                   foreach ($CusKeyMap as $key => $value) 
                   {

                          $package_key=$value->package_key;
                          $cus_key_id=$value->cus_key_id;
                          $lin_id=$value->lin_id; 
                          $package_max_cnt=$value->package_max_cnt;
                          $active=$value->active;
                          $updateTime=$value->update_time;

                        $getcdnCusKeyMapcnt = CdnCustomerKeyMapModel::where('package_key',$package_key)->get()->count();
                        if($getcdnCusKeyMapcnt==0)
                        {

                          
                           $insertKeyMapData=CdnCustomerKeyMapModel::insert(['c_id'=>$getCusId, 'lin_id'=>$lin_id, 'package_max_cnt'=>$package_max_cnt,'active'=>$active,'package_key'=>$package_key]);

                        }
                        else
                        {

                             $updateKeyMapData=CdnCustomerKeyMapModel::where('package_key',$package_key)->update(['package_max_cnt'=>$package_max_cnt,'active'=>$active]);


                        }

                               
                    }

                    $data = array(
                    'ed_id' => $data->user->institeType,
                    'cus_id' => $cusIds,
                    'c_name' => $CustomerName,
                    'c_id' => $data->user->cusId,
                    'license_email_id' => $customerEmailId,
                    'institution_name'=>$CustomerName
                    );
                    $update_cnt = Setting::select('*')->where('id', 1)->update($data);
    
                    $status=1;
                    $result="success";

                 }
                 else
                 {

                    $result="Invalid customer key mapping";
                    $error=6;


                 }

                }
                else
                {
                  $result="Invalid customer key";
                  $error=5;

                }
               

              }
              else
              {

                $result="Invalid customer key";
                $error=4;


              }



            }
            else
            {

              $result="Invalid customer key";
              $error=3;


            }

          

          }
          else
          {
            $result="Invalid data";
            $error=2;
          }
        }
        else
        {
          $result="Invalid data";
          $error=1;
        }

      return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
  }

  public function dataStudent(Request $request)
  {


    $inputs = file_get_contents("php://input");
    $data = json_decode($inputs);
    $status=0;
    $result="";
    $error='';
    $outputArr=array();

    if(isset($data->operation) && isset($data->user->rollno) && isset($data->user->password) )
       {

         if($data->operation=="viewStudent" && $data->user->rollno !="" && $data->user->password !="" )
          {
               $rollNo=$data->user->rollno;
               $pass=$data->user->password;
               if(Auth::guard('user')->attempt(['email' => $rollNo,'password' => $pass])) {

                    $Voice=url('/uploads/Voice/Vstatus.mp3');
                    $VoiceScreen=url('/uploads/VoiceScreen/Introduction.mp4');
                    $VoiceVideo=url('/uploads/VoiceVideo/Project_Intro.mp4');
                    $VoiceVideoScreen="";
                    $result="Success";
                    $outputArr=array("Voice"=>"$Voice","VoiceScreen"=>"$VoiceScreen","VoiceVideo"=>"$VoiceVideo","VoiceVideoScreen"=>"") ;  
                    $status=1;
               }
             
          
            else {  

            	$Voice=url('/uploads/Voice/Vstatus.mp3');
                    $VoiceScreen=url('/uploads/VoiceScreen/Introduction.mp4');
                    $VoiceVideo=url('/uploads/VoiceVideo/Project_Intro.mp4');
                    $VoiceVideoScreen="";
                    $result="Success";
                    $outputArr=array("Voice"=>"$Voice","VoiceScreen"=>"$VoiceScreen","VoiceVideo"=>"$VoiceVideo","VoiceVideoScreen"=>"") ;  
                    $status=1;

               // $result= "Invalid data or credentials";
               //    $error=3;

                
            } 


          }
          else
          {

             $result="Invalid data";
             $error=2;
          }

      }
      else
      {

         $result="Invalid data";
         $error=1;

      }


 
    return response()->json(['status'=>$status,'result'=>$result,'outputArr'=>$outputArr,'error'=>$error]);




  }

    public function packUsedSynchron(Request $request)  
   {

       $inputs = file_get_contents("php://input");
       $data = json_decode($inputs);
       $status=0;
       $result="";
       $error='';

    
  if(isset($data->operation) && isset($data->user->cusId) && isset($data->user->pakageId) && isset($data->user->staffcode))
  {

      if($data->operation=="UsedSynchrons" && $data->user->cusId !="" && $data->user->pakageId !="" && $data->user->staffcode !="" )
      {
            /** Check valid staff code **/
            $staffCode=$data->user->staffcode;
            $cusId=$data->user->cusId;
            $pakageId=$data->user->pakageId;

            $GetStaffCnt=Admin::where(['email'=>$staffCode])->get()->count();

            if($GetStaffCnt>0)
            {

               $GetStaff=Admin::where(['email'=>$staffCode])->get();
               $userType=$GetStaff[0]->usertype;
               $getActiveUser=0;
               if($userType=='SSF') //School staff
               {

                $getActiveUser=SchStaffModel::where(['staff_code'=>$staffCode,'active'=>1])->count();

               }
               elseif($userType=='CSF') //collge staff
               {

                 $getActiveUser=ClgStaffModel::where(['staff_code'=>$staffCode,'active'=>1])->count();;  
                
               }
               else
               {

                 $getActiveUser=0;  

               }

               if($getActiveUser>0)
               {

                  /** chk package already used or not **/
                  $chkPakAlreadyUsed=CdnkeyUsed::where(['staff_code'=>$staffCode,'package_key'=>$pakageId])->get()->count();

                   if($chkPakAlreadyUsed==0){
                  /** chk package valide and used count exceeded **/                

                        $getResValExced=$this->getResValExcedFunction($cusId,$pakageId,$staffCode,$userType);

                        if($getResValExced==0)
                        {

                          $result="Invalide Package";
                          $error=5;

                        }
                        elseif($getResValExced==1)
                        {
                            $result="Package maximum used level reached";
                            $error=6;

                        }
                        elseif($getResValExced==2)
                        {

                            $result="success";
                            $status=1; 

                        }

                    }
                    else
                    {

                        $result="Package already used this staff";
                        $error=7;

                    }
 
                }
               else
               {

                     $result="User not in active mode ";
                     $error=4;

               }


 
              


            } 
            else{

              $result="Invalid user";
              $error=3;


            }
 


      }
      else
      {

        $result="Invalid data";
        $error=2;


      }


  }
  else
  {
    
    $result="Invalid data";
    $error=1;

  }

return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);

   }

   public function getResValExcedFunction($cusIdval,$pakageIdval,$staffCodeVal,$userTypeVal)
   {


    $getPakValidCnt=CdnCustomerKeyMapModel::where(['package_key'=>$pakageIdval,'c_id'=>$cusIdval])->get()->count();

    if($getPakValidCnt>0)
    {

      $getUserCnt=CdnkeyUsed::where(['c_id'=>$cusIdval ,'package_key'=>$pakageIdval])->get()->count();
      $getPakValidMax=CdnCustomerKeyMapModel::where(['package_key'=>$pakageIdval,'c_id'=>$cusIdval])->get() ;

     if($getPakValidMax[0]->package_max_cnt>$getUserCnt )
     {

         $staffId="";
         $getPakData=CdnCustomerKeyMapModel::where(['package_key'=>$pakageIdval,'c_id'=>$cusIdval])->get();
         $lin_id=$getPakData[0]->lin_id;

         if($userTypeVal=='SSF') //School staff
         {

            $getStaffData=SchStaffModel::where(['staff_code'=>$staffCodeVal])->get(); 
            $staffId=$getStaffData[0]->scl_stf_id; 

         }
         elseif($userTypeVal=='CSF') //Collage staff
         {
            $getStaffData=ClgStaffModel::where(['staff_code'=>$staffCodeVal])->get(); 
            $staffId=$getStaffData[0]->cl_stf_id; 
            
         }
         else
         {
          return 0;
         }
         
          
        $dataInsert = array(
             "c_id"=>"$cusIdval", 
             "staff_id"=>"$staffId", 
             "lin_id"=>"$lin_id", 
             "package_key"=>"$pakageIdval",
             "staff_code"=>"$staffCodeVal"  
            );

        $useInsertData=CdnkeyUsed::insert($dataInsert);

          return 2;             
     }
     else
     {

        return 1;
     }
 
    }
    else
    {
     
      return 0;


    }

  
   }

 public function listlicenseloginuser(Request $request)
 {

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $error=0;
  $userType="";
  $listofusers=array();
  if(isset($data->operation) && isset($data->user->staffmode))
  {
     
    if($data->operation=='listlicenseuser')
    {
      if($data->user->staffmode=='college')
      {
        $userType='CSF';
        $listofusers=$this->licenseLoggedUsersList($userType); 
        $status=1;

      }
      elseif($data->user->staffmode=='school')
      {
        $userType='SSF';
        $listofusers=$this->licenseLoggedUsersList($userType); 
        $status=1;
      }
      else
      {
        $result="Invalid staffmode";
        $error=3;
      }
    }
    else
    {
      $result="Invalid request";
      $error=2;
    }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }
  if($status==1)
  {
    if(count($listofusers)>0)
    {
       return response()->json(['status'=>$status,'result'=>"success",'output'=>$listofusers]);
    }
    else
    {
       return response()->json(['status'=>$status,'result'=>"No users are logged in",'error'=>$error]);
    }
  }
  else
  {
      return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
  }
 }
 public function staffLogin(Request $request)
 {
 //{"operation": "staffLogin","user": {"staffcode": "CT200","password": "CT200","staffmode":"college"}}

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $voice=0;
  $voice_screen=0;
  $voice_video=0;
  $voice_video_screen=0;
  $error=0;
  if(isset($data->operation) && isset($data->user->staffcode) && isset($data->user->password) && isset($data->user->staffmode) )
  {

    if($data->operation=="staffLogin" && $data->user->staffcode !="" && $data->user->password !="" && $data->user->staffmode !=""  )
      {

        $sCode=$data->user->staffcode;
        $sPass=$data->user->password;
        $staffMode=$data->user->staffmode;
        $getUserCredential=$this->UserCredential($sCode,$sPass,$staffMode);
        if($getUserCredential['status']==1)
        {
          $status=1; 
          $result='success';
          $output= $getUserCredential['userId'];
          $staffMode=$getUserCredential['userType'];
          $voice=$getUserCredential['voice'];  
          $voice_screen=$getUserCredential['voice_screen'];
          $voice_video=$getUserCredential['voice_video'];
          $voice_video_screen=$getUserCredential['voice_video_screen'];
        }
        else
        {
          $result=$getUserCredential['errorMsg'];
        }

      }
      else
      {
        $result="Invalid request";
        $error=2;
      }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }

  if($status==1)
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'userId'=>$output,'staffMode'=>$staffMode,'voice'=>$voice,'voice_screen'=>$voice_screen,'voice_video'=>$voice_video,'voice_video_screen'=>$voice_video_screen]);
    }
    else
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
    }


 }
 public function licenselogin(Request $request)
 {
   //{"operation": "licenseLogin","user": {"staffcode":"CT300","staffmode":"college","license":"voice"}}    
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $error=0;
  $output=array();
  if(isset($data->operation) && isset($data->user->staffcode) && isset($data->user->license) && isset($data->user->staffmode) )
  {

    if($data->operation=="licenseLogin" && $data->user->staffcode !="" && $data->user->license !="" && $data->user->staffmode !=""  )
      {

        $staffcode=$data->user->staffcode;
        $license=$data->user->license;
        $staffMode=$data->user->staffmode;

        $licenseField='';

        if($license=='voice'){
        $licenseField='voice';}
        elseif($license=='voice_screen'){
        $licenseField='voice_screen'; }
        elseif($license=='voice_video'){
        $licenseField='voice_video';}
        elseif($license=='voice_video_screen'){
        $licenseField='voice_video_screen';}
        else{
        $licenseField='';
        }

        if($licenseField !="")
        {
          $chkLicenseLoginCnt=$this->LicenseLoginCnt($license,$staffMode,$staffcode);
        if($chkLicenseLoginCnt['status']==1)
        { 

          $updateUserLicense=User::where('email',"$staffcode")->update([$licenseField=>1]);
          $status=1;
          $result="success";

        }
        elseif($chkLicenseLoginCnt['status']==2)
        {
          $status=1;
          $error=1;
          $result=$chkLicenseLoginCnt['msg'];

        }
        else
        {
           $result=$chkLicenseLoginCnt['msg'];
        }

        }
        else
        {
          $result="Invalid license";
          $error=3;

        }

      }
      else
      {

        $result="Invalid request";
        $error=2;

      }
  }
  else
  {
     $result="Invalid request";
     $error=1;

  }
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
 }
 public function staffLogout(Request $request)
 {

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error=0;
  if(isset($data->operation) && isset($data->user->staffcode) && isset($data->user->staffmode))
  {

    if($data->operation=="staffLogout" && $data->user->staffcode !="" && $data->user->staffmode !="" )
      {

        $sCode=$data->user->staffcode;
        $staffMode=$data->user->staffmode;
        //$license=$data->user->license;
        $licenseField='';

        // if($license=='voice'){
        //          $licenseField='voice';}
        //         elseif($license=='voice_screen'){
        //           $licenseField='voice_screen'; }
        //           elseif($license=='voice_video'){
        //             $licenseField='voice_video';}
        //             elseif($license=='voice_video_screen'){
        //               $licenseField='voice_video_screen';}
        //              else{
        //                $licenseField='';
        //              }
             if($staffMode=='school')
             {
                 $chkUserIdCnt=SchStaffModel::where('staff_code',$sCode)->where('active',1)->count();
                 if($chkUserIdCnt>0)
                 {

                  if($licenseField ==""){
                    $updateUserLicense=User::where('email',"$sCode")->update(['voice'=>0,'voice_screen'=>0,'voice_video'=>0,'voice_video_screen'=>0]);
                    $status=1;
                    $result='success';
                  }
                  else
                  {
                     $result="Invalid license";
                  }

                 }
                 else
                 {
                     $result="Invalid staff";
                 }
             }
             elseif($staffMode=='college')
             {
                 $chkUserIdCnt=ClgStaffModel::where('staff_code',$sCode)->where('active',1)->count();
                 if($chkUserIdCnt>0)
                 {
                    if($licenseField ==""){
                    $updateUserLicense=User::where('email',$sCode)->update(['voice'=>0,'voice_screen'=>0,'voice_video'=>0,'voice_video_screen'=>0]);
                    $status=1;
                    $result='success';
                  }
                  else
                  {
                     $result="Invalid license";
                  }
                 }
                 else
                 {
                  $result="Invalid staff";
                 }
             }
             else
             {
               $result="Invalid staff mode";
             }         
      }
      else
      {

        $result="Invalid request";
        $error=2;

      }

  }
  else
  {
    $result="Invalid request";
    $error=1;
  }

  if($status==1)
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
    }
    else
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);
    }



 }
 public function staffClass(Request $request) //for school class staff
 {
  //{"operation": "staffClass","user": {"staffId": "91","staffmode":"school"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error=0;
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) )
  {

    if($data->operation=="staffClass" && $data->user->staffId >0 && $data->user->staffmode =="school" )
      {
         $staffId=$data->user->staffId;
         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffClass');
        if($chkStfValid['status']==1)
        {
          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          

           $schStaffClassCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'active'=>1])->groupby('sch_cls_id')->get()->count() ;  
            if($schStaffClassCnt>0)
           { 


             $schStaffClass=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'active'=>1])->groupby('sch_cls_id')->get()  ; 
             $ChkCnt=0;

              foreach ($schStaffClass as $key => $value) {

                $fileManagerClassCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"0","sch_cls_id"=>$value->sch_cls_id,"active"=>1])->count(); 
                if($fileManagerClassCnt>0)
                {
                  $fileManagerClass=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"0","sch_cls_id"=>$value->sch_cls_id])->get();
                    $filePath=isset($fileManagerClass[0]->file_path)?$fileManagerClass[0]->file_path:'';
                    $fileName=isset($fileManagerClass[0]->file_name)?$fileManagerClass[0]->file_name:'';
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

                     if(file_exists($chkPath)) {
                      $ChkCnt++;
                      $output[]=array('file_id'=>$fileManagerClass[0]->scl_stf_file_id,
                                       'path_id'=>$urlPath.'/'.$filePath . $fileName,
                                       'class_id'=>$fileManagerClass[0]->sch_cls_id,
                                       'file_type'=>'folder',
                                       'file_name'=>$fileName

                                       );
    
                    }
                }

              }

              if($ChkCnt>0)
               {
                $status=1;
               }
               else
               {
                 $result='No class found';
                 $error=5;
               }
          
          }
          else
          {
            $result='No class found';
            $error=4;

          } 

        }
        else
        {
          $result=$chkStfValid['error'];
          $error=3;
        }
      }
      else
      {
        $result="Invalid request";
        $error=2;
      }

  }
  else
  {
    $result="Invalid request";
    $error=1;
  }
 
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);

 }

 public function staffSection(Request $request) //for school class staff
 {
  //{"operation": "staffSection","user": {"staffId": "87","class_id":"1","file_id"=>"1","staffmode":"school"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error=0;
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->file_id) && isset($data->user->class_id)  )
  {

    if($data->operation=="staffSection" && $data->user->staffId >0 && $data->user->staffmode =="school" && $data->user->file_id>0 && $data->user->class_id>0 )
      {
         $staffId=$data->user->staffId;
         $file_id=$data->user->file_id;
         $class_id=$data->user->class_id;

         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
        if($chkStfValid['status']==1)
        {


          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          

          $schStaffClassCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'active'=>1])->groupby('sec_id')->get()->count() ; 
          
            if($schStaffClassCnt>0)
           { 


             $schStaffClass=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'active'=>1])->groupby('sec_id')->get()  ; 
             $ChkCnt=0;

              foreach ($schStaffClass as $key => $value) {

                $fileManagerClassCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"$file_id","sch_cls_id"=>$class_id,"sec_id"=>$value->sec_id,"active"=>1,'file_type'=>'folder'])->count(); 
                if($fileManagerClassCnt>0)
                {
                  $fileManagerClass=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"$file_id","sch_cls_id"=>$class_id,"sec_id"=>$value->sec_id,"active"=>1,'file_type'=>'folder'])->get();
                    $filePath=isset($fileManagerClass[0]->file_path)?$fileManagerClass[0]->file_path:'';
                    $fileName=isset($fileManagerClass[0]->file_name)?$fileManagerClass[0]->file_name:'';
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

                   if(file_exists($chkPath)) {
                    $ChkCnt++;
                    $output[]=array('file_id'=>$fileManagerClass[0]->scl_stf_file_id,
                                     'path_id'=>$urlPath.'/'.$filePath . $fileName,
                                     'class_id'=>$fileManagerClass[0]->sch_cls_id, 
                                     'section_id'=>$fileManagerClass[0]->sec_id,
                                     'file_type'=>'folder',
                                     'file_name'=>$fileName);
  
                  }
                }

              }

              if($ChkCnt>0)
               {
                $status=1;
               }
               else
               {
                 $result='No section found';
                 $error=5;
               }
          
          }
          else
          {
            $result='No section found';
            $error=4;

          }
        }
        else
        {
          $result=$chkStfValid['error'];
          $error=3;
        }
      }
      else
      {
        $result="Invalid request";
        $error=2;
      }

  }
  else
  {
    $result="Invalid request";
    $error=1;
  }
 
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);

 }

 public function staffSclSubject(Request $request)
 {


  //{"operation": "staffSubject","user": {"staffId": "87","class_id":"1","file_id"=>"1","staffmode":"school"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error=0;
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->file_id) && isset($data->user->class_id) && isset($data->user->section_id)  )
  {

    if($data->operation=="staffSubject" && $data->user->staffId >0 && $data->user->staffmode =="school" && $data->user->file_id>0 && $data->user->class_id>0 && $data->user->section_id>0 )
      {
         $staffId=$data->user->staffId;
         $file_id=$data->user->file_id;
         $class_id=$data->user->class_id;
         $section_id=$data->user->section_id;

         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
        if($chkStfValid['status']==1)
        {
          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'active'=>1])->get()->count() ; 
   
            if($schStaffSubCnt>0)
             {
 
                  $schStaffSub=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'active'=>1])->get();
                $ChkCnt=0;
                foreach ($schStaffSub as $key => $value) 
                {
                     $subject_id=$value->sub_id;

                     $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();
                    if($fileManagerCnt>0)
                    {

                       $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->get();

                      $filePath=isset($fileManagerSubject[0]->file_path)?$fileManagerSubject[0]->file_path:'';
                     $fileName=isset($fileManagerSubject[0]->file_name)?$fileManagerSubject[0]->file_name:'';
                      $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;
                      if(file_exists($chkPath)) 
                      {
                          $ChkCnt++;
                          $output[]=array('file_id'=>$fileManagerSubject[0]->scl_stf_file_id,
                                'path_id'=>$urlPath.'/'.$filePath . $fileName,
                                'class_id'=>$fileManagerSubject[0]->sch_cls_id, 
                                'section_id'=>$fileManagerSubject[0]->sec_id,
                                'subject_id'=>$fileManagerSubject[0]->sub_id,
                                'file_type'=>'folder',
                                'file_name'=>$fileName);

                      }

                    } 
                    else
                    {

                    }

               }

              if($ChkCnt>0)
              {
                $status=1;
              }
              else
              {
                $result='No section found';
                $error=5;
              }
           }
           else
           {
             $result='No subject found';
             $error=4;

           }
                 
          }
          else
          {

             $result=$chkStfValid['error'];
             $error=3;
          }
 
           

  }
  else
  {
    $result="Invalid request";
    $error=2;
  }

}
else
{
    $result="Invalid request";
    $error=1;

}
 
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);



 }
 public function staffSclSubjectFile(Request $request)
 {

  //{"operation": "staffSubjectFile","user": {"staffId": "87","class_id":"1","subject_id":"",file_id"=>"1","staffmode":"school"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error=0;
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->file_id) && isset($data->user->class_id) && isset($data->user->section_id)  && isset($data->user->subject_id) )
  {

    if($data->operation=="staffSubjectFile" && $data->user->staffId >0 && $data->user->staffmode =="school" && $data->user->file_id>0 && $data->user->class_id>0 && $data->user->section_id>0 && $data->user->subject_id >0 )
      {
         $staffId=$data->user->staffId;
         $file_id=$data->user->file_id;
         $class_id=$data->user->class_id;
         $section_id=$data->user->section_id;
         $subject_id=$data->user->subject_id;

         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
        if($chkStfValid['status']==1)
        {
          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
           $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'sub_id'=>$subject_id,'active'=>1])->get()->count() ; 
    
            if($schStaffSubCnt>0)
             {
 
                   
                $ChkCnt=0;
                $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();
                      
                    if($fileManagerCnt>0)
                    {

                       $fileManagerSubjectCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1])->get()->count();

                       if($fileManagerSubjectCnt>0)
                       {

                         $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1])->get() ;

                      foreach ($fileManagerSubject as $key => $value) {


                        $filePath=isset($value->file_path)?$value->file_path:'';
                        $fileName=isset($value->file_name)?$value->file_name:'';
                         $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;
 
                            if(file_exists($chkPath)) 
                           {
                                 $ChkCnt++;
                                $output[]=array('file_id'=>$value->scl_stf_file_id,
                                'path_id'=>$urlPath.'/'.$filePath . $fileName,
                                'class_id'=>$value->sch_cls_id, 
                                'section_id'=>$value->sec_id,
                                'subject_id'=>$value->sub_id,
                                'file_name'=>$value->file_name,
                                'file_type'=>$value->file_type);

                           }


                            
                         }
 
                        if($ChkCnt>0)
                        {
                        $status=1;
                        }
                        else
                        {
                        $result='No files/folder found';
                        $error=7;
                        }


                       }
                       else
                       {

                         $result='No files/folder found';
                         $error=6;

                       }

                    } 
                    else
                    {

                       $result='No files/folder found';
                       $error=5;

                    }

              

              
           }
           else
           {
             $result='No subject found';
             $error=4;

           }
                 
          }
          else
          {

             $result=$chkStfValid['error'];
             $error=3;
          }
 
           

  }
  else
  {
    $result="Invalid request";
    $error=2;
  }

}
else
{
    $result="Invalid request";
    $error=1;

}
 
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);


 }


 public function schoolStaffValid($stfId=null,$apiMode=null)
 {
  $resultArr['status']=0;
  $resultArr['error']='';
  $getStaffDetailsCnt = SchStaffModel::where(['scl_stf_id' => $stfId, 'active' => 1])->count();
  if($getStaffDetailsCnt>0)
  {
    $getStaffDetails= SchStaffModel::where(['scl_stf_id' => $stfId, 'active' => 1])->get();
    $resultArr['staffCode']=isset($getStaffDetails[0]->staff_code)?$getStaffDetails[0]->staff_code:"";
    $resultArr['staffName']=isset($getStaffDetails[0]->staff_name)?$getStaffDetails[0]->staff_name:"";
    $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $stfId,'active'=>1])->get()->count();
    if ($chkSubMasterCnt == 0) {
       $resultArr['error']='Yet not mapped subject or inactive';
    }
    else
    {
      $chkSubMaster= SclStfSubMasterModel::where(['scl_stf_id' => $stfId,'active'=>1])->get();
      $resultArr['scl_stf_sub_id']=isset($chkSubMaster[0]->scl_stf_sub_id)?$chkSubMaster[0]->scl_stf_sub_id:'0';
      $resultArr['status']=1; //SclStfSubMasterModel
    } //$sclStfSubMasterId = $chkActiveCnt[0]->scl_stf_sub_id;
  }
  else
  {
    $resultArr['error']='Invalid staff id';
  }

  return $resultArr;

 }

 public function  staffCourse(Request $request) //for college staff
 {

  //{"operation": "staffCourse","user": {"staffId": "222","staffmode":"college"}}
  //{"operation": "staffClass","user": {"staffId": "91","staffmode":"school"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error='';
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) )
  {

    if($data->operation=="staffCourse" && $data->user->staffId >0 && $data->user->staffmode !="" )
      {

        $staffId=$data->user->staffId;
        $staffMode=$data->user->staffmode;


        $getUserCredentialId=$this->UserCredentialUserId($staffId,$staffMode);
        if($getUserCredentialId>0)
            {
               if($staffMode=='college')
               {
                  $getSubjectMapped=$this->checkSubjectMapped($staffId,$staffMode);
                  if($getSubjectMapped['status']==1 && $getSubjectMapped['error']=='success')
                  {
                    $status=1;
                    $result='success';
                    $output=$getSubjectMapped['result'];
                  }
                  else
                  {
                    $result=$getSubjectMapped['error'];
                  } 

                 }
                 elseif($staffMode=='school') /** school course start **/ 
                 {



         $staffId=$data->user->staffId;
         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffClass');
        if($chkStfValid['status']==1)
        {
          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          

           $schStaffClassCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'active'=>1])->groupby('sch_cls_id')->get()->count() ;  
            if($schStaffClassCnt>0)
           { 


             $schStaffClass=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'active'=>1])->groupby('sch_cls_id')->get()  ; 
             $ChkCnt=0;

              foreach ($schStaffClass as $key => $value) {

                $fileManagerClassCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"0","sch_cls_id"=>$value->sch_cls_id,"active"=>1])->count(); 
                if($fileManagerClassCnt>0)
                {
                  $fileManagerClass=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"0","sch_cls_id"=>$value->sch_cls_id])->get();
                  //echo "<pre>"; print_r($fileManagerClass); exit;
                    $filePath=isset($fileManagerClass[0]->file_path)?$fileManagerClass[0]->file_path:'';
                    $fileName=isset($fileManagerClass[0]->file_name)?$fileManagerClass[0]->file_name:'';
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

                     if(file_exists($chkPath)) {
                      $ChkCnt++;
                      $output[]=array('file_id'=>$fileManagerClass[0]->scl_stf_file_id,
                                       'file_path'=>$urlPath.'/'.$filePath ,
                                       'course_id'=>$fileManagerClass[0]->sch_cls_id,//class_id
                                       'file_type'=>'folder',
                                       'course_name'=>$fileName

                                       );
    
                    }
                }

              }

              if($ChkCnt>0)
               {
                $status=1;
                $result="success";
               }
               else
               {
                 $result='No class found';
                 $error=5;
               }
          
          }
          else
          {
            $result='No class found';
            $error=4;

          } 

        }
        else
        {
          $result=$chkStfValid['error'];
          $error=3;
        }

                      

                 }/** school course end **/
                 else
                 {
 
                  $result='Invalid staff mode';
                  $error=13;

                 }  
                 
            }
            else
            {
               $result='Invalid credential';
                $error=3;
               
            }

      }
      else
      {

        $result="Invalid request";
        $error=2;
      }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }

  if($status==1)
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);
    }
    else
    {
       return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>array()]);
    }


 }

 public function staffSemester(Request $request)
 {
  //{"operation": "staffSemester","user": {"staffId": "91","staffmode":"college","course_id":"11","file_id":111}}

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error='';
  $urlPath=url('uploads/file_manager/')  ;
  
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->course_id) && isset($data->user->file_id) )
  {

      if($data->operation =='staffSemester' && $data->user->staffId >0 && $data->user->staffmode !="" && $data->user->course_id >0  && $data->user->file_id >0)
      {

        $course_id=$data->user->course_id;
        $GetUserId=$data->user->staffId; 
        $fileId=$data->user->file_id;
        if($GetUserId>0)
        {
            $getUserCredentialId=$this->UserCredentialUserId($data->user->staffId,$data->user->staffmode);

          if($getUserCredentialId>0)
          { 
    
         if($data->user->staffmode=='college') /** college start **/
         {  
          $chkStfMapIdcnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get()->count();

          $getFileMnCnt=ClgFileManagerModel::where(['clg_stf_file_id'=>$fileId,'course_id'=>$course_id])->get()->count();  

          if($chkStfMapIdcnt>0 && $getFileMnCnt>0)
          {

             $chkStfMapId=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get();
             $clg_stf_sub_id=$chkStfMapId[0]->clg_stf_sub_id;
         
             $chkSubMapCnt=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'active'=>1])->groupBy(['semester_id'])->get()->count();
             if($chkSubMapCnt>0)
             {

              $chkSubMap=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'active'=>1])->groupBy(['semester_id'])->get(array('course_id','semester_id',DB::raw(' "" as file_id'),DB::raw(' "folder" as file_type'),DB::raw(' "" as file_path'))) ;
              
              foreach ($chkSubMap as $key => $value) {

                $semID=$value->semester_id;
                $getSemesterFm=ClgFileManagerModel::where(['semester_id'=>$semID,'parent_id'=>$fileId,'file_type'=>'folder'])->get();
                $getParentId=isset($getSemesterFm[0]->clg_stf_file_id)?$getSemesterFm[0]->clg_stf_file_id:0;
                $chkSubMap[$key]->file_id=$getParentId;
                $fPath=isset($getSemesterFm[0]->file_path)?$getSemesterFm[0]->file_path:'';
                $fName=isset($getSemesterFm[0]->file_name)?$getSemesterFm[0]->file_name:'';
                $chkSubMap[$key]->file_path=$urlPath.'/'.$fPath.$fName;
    
              }


               $status=1;

               $result='success';
               $output=$chkSubMap;
             }
             else
             {

              $result="Invalid subject mapping";
              $error=5;

             }
          }
          else
          {

            if($chkStfMapIdcnt==0){ 

              $result="Invalid subject mapping";
              $error=4;

             }
             else
             {

              $result="Invalid subject in file manager";
              $error=4;

             }   
             
          }

         }/** college end **/
         elseif($data->user->staffmode=='school')/* school-start*/
         {




         $staffId=$data->user->staffId;
         $file_id=$data->user->file_id;
         $class_id=$data->user->course_id;

         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
        if($chkStfValid['status']==1)
        {


          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          

          $schStaffClassCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'active'=>1])->groupby('sec_id')->get()->count() ; 
          
            if($schStaffClassCnt>0)
           { 


             $schStaffClass=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'active'=>1])->groupby('sec_id')->get()  ; 
             $ChkCnt=0;

              foreach ($schStaffClass as $key => $value) {

                $fileManagerClassCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"$file_id","sch_cls_id"=>$class_id,"sec_id"=>$value->sec_id,"active"=>1,'file_type'=>'folder'])->count(); 
                if($fileManagerClassCnt>0)
                {
                  $fileManagerClass=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"parent_id"=>"$file_id","sch_cls_id"=>$class_id,"sec_id"=>$value->sec_id,"active"=>1,'file_type'=>'folder'])->get();
                    $filePath=isset($fileManagerClass[0]->file_path)?$fileManagerClass[0]->file_path:'';
                    $fileName=isset($fileManagerClass[0]->file_name)?$fileManagerClass[0]->file_name:'';
                   $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

                   if(file_exists($chkPath)) {
                    $ChkCnt++;
                    $output[]=array('file_id'=>$fileManagerClass[0]->scl_stf_file_id,
                                     'file_path'=>$urlPath.'/'.$filePath . $fileName,
                                     'course_id'=>$fileManagerClass[0]->sch_cls_id, //class_id
                                     'semester_id'=>$fileManagerClass[0]->sec_id, //section id
                                     'file_type'=>'folder',
                                     'file_name'=>$fileName);
  
                  }
                }

              }

              if($ChkCnt>0)
               {
                $status=1;
                $result="success";
               }
               else
               {
                 $result='No section found';
                 $error=5;
               }
          
          }
          else
          {
            $result='No section found';
            $error=4;

          }
        }
        else
        {
          $result=$chkStfValid['error'];
          $error=3;
        }


 
         } /*school end */
         else
         {

          $result="Invalid staff mode";
          $error=113;
         }
       }
       else
       {

          $result="Invalid credentials";
          $error=114;


       }
        }
        else
        {
          $result="Invalid subject mapping";
          $error=3;
        }
      }
      else
      {
        $result="Empty request";
        $error=2;
      }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }

  return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);
 }

 public function staffSubject(Request $request)
 {

  //{"operation": "staffSubject","user": {"staffId": "91","staffmode":"college","course_id":"","semester_id":""}}

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error='';
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->course_id) && isset($data->user->semester_id) && isset($data->user->file_id))
  {

      if($data->operation =='staffSubject' && $data->user->staffId >0 && $data->user->staffmode !="" && $data->user->course_id >0 && $data->user->semester_id >0 && $data->user->file_id>0 )
      {

        $getUserCredentialId=$this->UserCredentialUserId($data->user->staffId,$data->user->staffmode);

        $course_id=$data->user->course_id;
        $GetUserId=$data->user->staffId; 
        $semId=$data->user->semester_id;
        $file_id=$data->user->file_id;

        if($GetUserId>0)
        {


           if($getUserCredentialId>0)
        {

          if($data->user->staffmode=='college') /* college start */
          {


            $chkStfMapIdcnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get()->count();

          if($chkStfMapIdcnt>0)
          {

             $chkStfMapId=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get();
             $clg_stf_sub_id=$chkStfMapId[0]->clg_stf_sub_id;
         
             $chkSubMapCnt=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'active'=>1])->get()->count();
             if($chkSubMapCnt>0)
             {

              $chkSubMapSubCnt=ClgStfSubMapModel::leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_mapping.active'=>1])->get()->count() ;

               if($chkSubMapSubCnt>0)
               {

                  $chkSubMapSub=ClgStfSubMapModel::leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_mapping.active'=>1])->get(array('at_college_staff_subject_mapping.course_id','at_college_staff_subject_mapping.sub_id','at_college_staff_subject_mapping.semester_id','at_college_subject_master.subject_name','at_college_staff_subject_mapping.clg_stf_sub_id',DB::raw('"" as file_id '),DB::raw('"folder" as file_type '),DB::raw('"" as file_path '))) ;

                    foreach ($chkSubMapSub as $key => $value) {

                      $sub_id=$value->sub_id;

                      $getSubFm=ClgFileManagerModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'parent_id'=>$file_id,'file_type'=>'folder'])->get();
                      $getParentId=isset($getSubFm[0]->clg_stf_file_id)?$getSubFm[0]->clg_stf_file_id:0;
                      $chkSubMapSub[$key]->file_id=$getParentId;
                      $fPath=isset($getSubFm[0]->file_path)?$getSubFm[0]->file_path:'';
                      $fName=isset($getSubFm[0]->file_name)?$getSubFm[0]->file_name:'';
                      $chkSubMapSub[$key]->file_path=$urlPath.'/'.$fPath.$fName;
                  
                   }

                  $status=1;
                  $result='success';
                  $output=$chkSubMapSub;

               }
               else
               {
                 $result="Invalid subject mapping";
                 $error=6;
               }
            }
             else
             {
              $result="Invalid subject mapping";
              $error=5;
             }
          }
          else
          {
             $result="Invalid subject mapping";
             $error=4;
          }


            

 
          } /* college end */
          elseif($data->user->staffmode=='school') /* school start */
          {



         $staffId=$data->user->staffId;
         $file_id=$data->user->file_id;
         $class_id=$data->user->course_id;
         $section_id=$data->user->semester_id;

         $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
        if($chkStfValid['status']==1)
        {
          
          $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
          $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'active'=>1])->get()->count() ; 
   
            if($schStaffSubCnt>0)
             {
 
                  $schStaffSub=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'active'=>1])->get();
                $ChkCnt=0;
                foreach ($schStaffSub as $key => $value) 
                {
                     $subject_id=$value->sub_id;

                     $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();
                    if($fileManagerCnt>0)
                    {

                       $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->get();

                      $filePath=isset($fileManagerSubject[0]->file_path)?$fileManagerSubject[0]->file_path:'';
                     $fileName=isset($fileManagerSubject[0]->file_name)?$fileManagerSubject[0]->file_name:'';
                      $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;
                      if(file_exists($chkPath)) 
                      {
                          $ChkCnt++;
                          $output[]=array('file_id'=>$fileManagerSubject[0]->scl_stf_file_id,
                                'file_path'=>$urlPath.'/'.$filePath . $fileName,
                                'course_id'=>$fileManagerSubject[0]->sch_cls_id, //class_id
                                'semester_id'=>$fileManagerSubject[0]->sec_id, //section_id
                                'subject_id'=>$fileManagerSubject[0]->sub_id,
                                'file_type'=>'folder',
                                'file_name'=>$fileName);

                      }

                    } 
                    else
                    {

                    }

               }

              if($ChkCnt>0)
              {
                $status=1;
                $result="success";
              }
              else
              {
                $result='No subject found';
                $error=5;
              }
           }
           else
           {
             $result='No subject found';
             $error=4;

           }
                 
          }
          else
          {

             $result=$chkStfValid['error'];
             $error=3;
          }
 
      

             //echo "dddddddddddddd";
             //exit;


          } /* school end */
          else
          {

             $result="Invalid staff mode";
             $error=113;


          }



        }
        else
        {
  

          $result="Invalid credentials";
          $error=114;

        }

          
        }
        else
        {
          $result="Invalid subject mapping";
          $error=3;
        }
      }
      else
      {
        $result="Invalid request";
        $error=2;
      }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);
 }
 public function staffSubjectFiles(Request $request)
 {
   //{"operation": "staffSubjectFiles","user": {"staffId": "91","staffmode":"college","course_id":"","semester_id":"","subject_id":"","file_id":""}}

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output=array();
  $error='';
  $urlPath=url('uploads/file_manager/')  ;
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->course_id) && isset($data->user->semester_id) && isset($data->user->file_id) && isset($data->user->subject_id))
  {

      if($data->operation =='staffSubjectFiles' && $data->user->staffId >0 && $data->user->staffmode !="" && $data->user->course_id >0 && $data->user->semester_id >0 && $data->user->subject_id >0 && $data->user->file_id>0 )
      {

        $course_id=$data->user->course_id;
        $GetUserId=$data->user->staffId; 
        $semId=$data->user->semester_id;
        $file_id=$data->user->file_id;
        $sub_id=$data->user->subject_id;
        if($GetUserId>0)
        {

           $getUserCredentialId=$this->UserCredentialUserId($data->user->staffId,$data->user->staffmode);

           if($getUserCredentialId>0)
           {

            if($data->user->staffmode=='college') /* college start */
            {

              $chkStfMapIdcnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get()->count();

              if($chkStfMapIdcnt>0)
              {

              $chkStfMapId=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get();
              $clg_stf_sub_id=$chkStfMapId[0]->clg_stf_sub_id;

              $chkSubMapCnt=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'active'=>1])->get()->count();
              if($chkSubMapCnt>0)
              {

              $chkSubMapSubCnt=ClgStfSubMapModel::leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_mapping.sub_id'=>$sub_id,'at_college_staff_subject_mapping.active'=>1])->get()->count() ;

              if($chkSubMapSubCnt>0)
              {

              $getSubFmCnt=ClgFileManagerModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'clg_stf_file_id'=>$file_id,'file_type'=>'folder'])->get()->count();

              if($getSubFmCnt>0)
              {

                // $getSubFm=ClgFileManagerModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'parent_id'=>$file_id])->get(array('course_id','semester_id','file_name','file_type',DB::raw(' clg_stf_file_id  as file_id '),DB::raw(' sub_id  as subject_id '),DB::raw("concat('".$urlPath."/', file_path,file_name  ) as file_path")));
                 $resultOutArr=[];

                 $getSubFm=ClgFileManagerModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'parent_id'=>$file_id])->get();

                 foreach ($getSubFm as $key => $value) {

                  $fmRowIds=$value->path_folder_ids;
                  $fileName=$value->file_name;
                   $getFilePath=$this->getFileManagerPathIds($fmRowIds);
 
                  if($getFilePath!='0')
                  {

                    $destinationPathChk = base_path() . '/public/uploads/file_manager/'.$getFilePath.$fileName;
                    $path=$urlPath.'/'.$getFilePath.$fileName;

                     if(file_exists($destinationPathChk)) {

                       $resultOutArr[]=array("file_id"=>$value->clg_stf_file_id,
                                              'course_id'=>$course_id,
                                              'semester_id'=>$semId ,
                                              "subject_id"=>$sub_id,
                                              "file_name"=>"$fileName",
                                              "file_path" =>"$path",
                                              "file_type"=>$value->file_type
                                             );
                     }
                  }
                 }
              if(count($resultOutArr)>0)
              {
                $status=1;
                $result='success';
              }   
              
              $output=$resultOutArr;
              }
              else
              {
              $status=0;
              $result='No files and folder found';
              $error=7;
              }


              }
              else
              {
              $result="Invalid subject mapping";
              $error=6;
              }
              }
              else
              {
              $result="Invalid subject mapping";
              $error=5;
              }
              }
              else
              {
              $result="Invalid subject mapping";
              $error=4;
              }



            }/* college end */
            elseif($data->user->staffmode=='school')/* school start */
            {

              $staffId=$data->user->staffId;
              $file_id=$data->user->file_id;
              $class_id=$data->user->course_id;
              $section_id=$data->user->semester_id;
              $subject_id=$data->user->subject_id;


              $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
              if($chkStfValid['status']==1)
              {

              $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
              $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'sub_id'=>$subject_id,'active'=>1])->get()->count() ; 

              if($schStaffSubCnt>0)
              {


              $ChkCnt=0;
              $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();

              if($fileManagerCnt>0)
              {

              $fileManagerSubjectCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1])->get()->count();

              if($fileManagerSubjectCnt>0)
              {

              $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"parent_id"=>$file_id,"active"=>1])->get() ;

              foreach ($fileManagerSubject as $key => $value) {


              $filePath=isset($value->file_path)?$value->file_path:'';

              $pathIds=$value->path_folder_ids;
              $filePath = $this->getFileManagerPathWithIds($pathIds,$staffId);

              $fileName=isset($value->file_name)?$value->file_name:'';
              $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

              if(file_exists($chkPath)) 
              {
              $ChkCnt++;
              $output[]=array('file_id'=>$value->scl_stf_file_id,
              'file_path'=>$urlPath.'/'.$filePath . $fileName,
              'course_id'=>$value->sch_cls_id, //class_id
              'semester_id'=>$value->sec_id, //section_id
              'subject_id'=>$value->sub_id,
              'file_name'=>$value->file_name,
              'file_type'=>$value->file_type);

              }

              }

              if($ChkCnt>0)
              {
              $status=1;
              $result='success';
              }
              else
              {
              $result='No files/folder found';
              $error=7;
              }


              }
              else
              {

              $result='No files/folder found';
              $error=6;

              }

              } 
              else
              {

              $result='No files/folder found';
              $error=5;

              }




              }
              else
              {
              $result='No subject found';
              $error=4;

              }

              }
              else
              {

              $result=$chkStfValid['error'];
              $error=3;
              }

            } /* school end */
            else
            {

               $result="Invalid staff mode";
               $error=114;


            }



           }
           else
           {
                $result="Invalid credentials";
                $error=113;
           }

        
        }
        else
        {
          $result="Invalid subject mapping";
          $error=3;
        }
      }
      else
      {
        $result="Invalid request";
        $error=2;
      }
  }
  else
  {
    $result="Invalid request";
    $error=1;
  }
 return response()->json(['status'=>$status,'result'=>$result,'error'=>$error,'output'=>$output]);




 }

 public function getFileManagerPathWithIds($fmRowIds,$staffId)
  {
    $expIds = explode(',', $fmRowIds);
    if (count($expIds) > 0) {
      $urlPath = '';
      for ($i = 0; $i < count($expIds); $i++) {
        $fmRowId = $expIds[$i];
        $getUrlPathCnt = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
        if ($getUrlPathCnt > 0) {
          $getUrlPath = SchFileManagerModel::where(['scl_stf_file_id' => $fmRowId])->get();
          $urlPath.= $getUrlPath[0]->file_name . '/';
        }
      }

      $getRootPath = $this->getuserAccessPath($staffId);
      if ($getRootPath != '0') {
        return $getRootPath . '/' . $urlPath;
        exit;
      }
      else {
        return 0;
        exit;
      }
    }
    else {
      return 0;
      exit;
    }
  }
  public function getuserAccessPath($staffId)
  {
    $getStffCode = $staffId;
    $chkStfMapCnt = SchStaffModel::where(['scl_stf_id' => $getStffCode, 'active' => 1])->get()->count();
    if ($chkStfMapCnt == 0) {
      return 0;
      exit;
    }
    else {
      $getStaffDetails = SchStaffModel::where(['scl_stf_id' => $getStffCode, 'active' => 1])->get();
      $scl_stf_id = $getStaffDetails[0]->scl_stf_id;
      $chkSubMasterCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get()->count();
      if ($chkSubMasterCnt == 0) {
        return 0;
        exit;
      }
      else {
        $chkActiveCnt = SclStfSubMasterModel::where(['scl_stf_id' => $scl_stf_id])->get();
        if ($chkActiveCnt[0]->active == 0) {
          return 0;
          exit;
        }

        return $staffNameFolder = $getStaffDetails[0]->staff_code . '_' . $getStaffDetails[0]->staff_name;
      }
    }
  }

 public function getFileManagerPathIds($fmRowIds)
  {
      $expIds = explode(',', $fmRowIds);
      if (count($expIds) > 0) {
      $urlPath = '';
      for ($i = 0; $i < count($expIds); $i++) 
      {
         $fmRowId = $expIds[$i];
         $getUrlPathCnt = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId, 'file_type' => 'folder'])->get()->count();
      if ($getUrlPathCnt > 0) {
        $getUrlPath = ClgFileManagerModel::where(['clg_stf_file_id' => $fmRowId])->get();
 
      if($i==0)
      {
      $urlPath.= $getUrlPath[0]->file_path.$getUrlPath[0]->file_name . '/';
      }
      else
      {
      $urlPath.=$getUrlPath[0]->file_name.'/';
      }
        }

      }
      return $urlPath;
      exit;

      }
      else {
      return 0;
      exit;
      }


  }
 public function staffSubjectPath(Request $request)
 {
  //{"operation": "staffSubject","user": {"staffId": "92","staffmode":"college","course_id":"11","semester_id":"1","subject_id":"3"}}
  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output='';
  $error='';
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->course_id) && isset($data->user->semester_id) && isset($data->user->subject_id) && isset($data->user->file_id) )
  {
       
      if($data->operation =='staffSubjectPath' && $data->user->staffId >0 && $data->user->staffmode !="" && $data->user->course_id >0 && $data->user->semester_id >0 && $data->user->subject_id >0 && $data->user->file_id>0 )
        {


         $getUserCredentialId=$this->UserCredentialUserId($data->user->staffId,$data->user->staffmode);
         if($getUserCredentialId>0)
         {

            if($data->user->staffmode=='college') /* college start */
            {

              $course_id=$data->user->course_id;
              $GetUserId=$data->user->staffId; 
              $semId=$data->user->semester_id;
              $sub_id=$data->user->subject_id;
              $file_id=$data->user->file_id;

          $chkStfMapIdcnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get()->count();
          if($chkStfMapIdcnt>0)
          {

             $chkStfMapId=ClgStfSubMasterModel::where(['cl_stf_id'=>$GetUserId,'active'=>1])->get();
             $clg_stf_sub_id=$chkStfMapId[0]->clg_stf_sub_id;
        
             $chkSubMapCnt=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'active'=>1])->get()->count();


             if($chkSubMapCnt>0)
             {
 

              $chkSubMapSubCnt=ClgStfSubMapModel::leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_mapping.sub_id'=>$sub_id,'at_college_staff_subject_mapping.active'=>1])->get()->count() ;

               if($chkSubMapSubCnt>0)
               {

                  $chkSubMapSub=ClgStfSubMapModel::leftJoin('at_college_subject_master','at_college_subject_master.sub_id','=','at_college_staff_subject_mapping.sub_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.course_id'=>$course_id,'at_college_staff_subject_mapping.semester_id'=>$semId,'at_college_staff_subject_mapping.sub_id'=>$sub_id,'at_college_staff_subject_mapping.active'=>1])->get(array('at_college_staff_subject_mapping.course_id','at_college_staff_subject_mapping.semester_id','at_college_subject_master.subject_name')) ;
                  $subjectName=isset($chkSubMapSub[0]->subject_name)?$chkSubMapSub[0]->subject_name:'';

                  $destinationBasePath = public_path('uploads/file_manager/');
                  $destinationUrlPath = url('uploads/file_manager/');

                  $status=1;
                  $result='success';

                  $getPath=$this->PathFolderFunction($GetUserId,$course_id);
                  if($getPath !='0' && $subjectName !="")
                  {
                    return $output=strtolower($destinationUrlPath.'/'.$getPath.'/semester-'.$semId.'/'.$subjectName.'/');
                    exit;


                  }
                  else
                  {

                    return "Invalid subject url";
                    exit;

                  }
                  

               }
               else
               {
                 return "Invalid subject mapping";
                 exit;
               }
            }
             else
             {
              return "Invalid subject mapping";
              exit;
             }
          }
          else
          {
             return "Invalid subject mapping";
             exit;
             
          }


            }/* college end */
            elseif($data->user->staffmode=='school')/* school start */
            {

              $staffId=$data->user->staffId;
              $file_id=$data->user->file_id;
              $class_id=$data->user->course_id;
              $section_id=$data->user->semester_id;
              $subject_id=$data->user->subject_id;

              $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
              if($chkStfValid['status']==1)
              {


              $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
              $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'sub_id'=>$subject_id,'active'=>1])->get()->count() ; 
              if($schStaffSubCnt>0)
              {

                $ChkCnt=0;
              $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();
                if($fileManagerCnt>0)
                 {

                    $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->get();
                    foreach ($fileManagerSubject as $key => $value) 
                    {
                      /** File path get directly file_path fields bcoz we can't edit subject folder name **/
                      $urlPath = url('uploads/file_manager/');
                      $filePath=isset($value->file_path)?$value->file_path:'';
                      $fileName=isset($value->file_name)?$value->file_name:'';
                      $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;
                        if(file_exists($chkPath)) 
                        {

                          return $urlPath.'/'.$filePath . $fileName.'/';
                          exit;

                        }
                        else
                        {

                          echo "Invalid path";
                          exit;
                        }
                     


                    }

                }
                else
                {

                   return 'No files/folder found';
                   exit;


                }

               

              }
              else
              {
                return 'No subject path found';
                exit;
              }





              }
              else
              {

                return $chkStfValid['error'];
                exit;
              }

            }/* school start */
            else
            {

              return "Invalid staff mode";
              exit;


            }

         }
         else
         {

          return "Invalid credentials";
          exit;
          


         }
 
      
      }
      else
      {
         return "Invalid request";
         exit;
      }
 }
 else
 {
     return "Invalid request";
     exit;
 }

  return 'Invalid path';
 }

 public function UploadFileSave(Request $request)
 {
//{"operation": "SaveUploadFile","user": {"staffId": "91","staffmode":"college","course_id":"11","semester_id":"1","subject_id":"1","file_id":"153","file_name":"aaaaa,bbbbb","file_desc":"daaaaa,dbbbbb","thumb_img":"img1.png,img2.png,img3.png"}}

  $inputs =file_get_contents("php://input");
  $data =json_decode($inputs);
  $status=0;
  $result="";
  $output='';
  $error='';
  if(isset($data->operation) && isset($data->user->staffId) && isset($data->user->staffmode) && isset($data->user->course_id) && isset($data->user->semester_id) && isset($data->user->subject_id) && isset($data->user->file_id) && isset($data->user->file_name))
  {

      if($data->operation =='SaveUploadFile' && $data->user->staffId >0 && $data->user->staffmode !="" && $data->user->course_id >0 && $data->user->semester_id >0 && $data->user->subject_id >0 && $data->user->file_id>0 && $data->user->file_name !='')
      {

         $course_id=$data->user->course_id;
         $GetUserId=$data->user->staffId; 
         $semId=$data->user->semester_id;
         $sub_id=$data->user->subject_id;
         $file_id=$data->user->file_id;
         $getFileArr=explode(',',$data->user->file_name);
         $getFileDescArr=explode(',',$data->user->file_desc);
         $getThumbArr=explode(',',$data->user->thumb_img);
         if(count($getFileArr)>0)
           {

             $getUserCredentialId=$this->UserCredentialUserId($data->user->staffId,$data->user->staffmode);

             
           if($getUserCredentialId>0)
           {


                if($data->user->staffmode=='college') /* college start */
                {

                    $chkFileManagerId=ClgFileManagerModel::where(['clg_stf_file_id'=>$file_id])->get()->count();
                    if($chkFileManagerId>0)
                    {
                    $chkFileManagerId=ClgFileManagerModel::where(['clg_stf_file_id'=>$file_id])->get();
                    $clg_stf_sub_id=$chkFileManagerId[0]->clg_stf_sub_id;
                    $GetAcademicYear=ClgAcademicYearModel::get();
                    $AcademicYear=$GetAcademicYear[0]->academic_year;
                    $path_folder_ids=$file_id;
                    $filePath=$chkFileManagerId[0]->file_path.$chkFileManagerId[0]->file_name.'/';
                    for($iVal=0;$iVal<count($getFileArr);$iVal++)
                    {

                    $fileName=isset($getFileArr[$iVal])?$getFileArr[$iVal]:'';
                    $fDesc=isset($getFileDescArr[$iVal])?$getFileDescArr[$iVal]:'';
                    $imgThumb=isset($getThumbArr[$iVal])?$getThumbArr[$iVal]:'';

                    $UploadFilemanagerExits=ClgFileManagerModel::where(['cl_stf_id'=>$GetUserId,'clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'parent_id'=>$file_id,'file_type'=>'file','file_name'=>$fileName])->get()->count();

                    if($UploadFilemanagerExits==0){
                    $insertUploadFilemanager=ClgFileManagerModel::insert(['cl_stf_id'=>$GetUserId,'clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>$semId,'sub_id'=>$sub_id,'parent_id'=>$file_id,'file_type'=>'file','file_name'=>$fileName,'academic_year'=>$AcademicYear,'path_folder_ids'=>"$path_folder_ids",'file_path'=>$filePath,'description'=>"$fDesc","thumb_img"=>"$imgThumb"]);
                    }

                    }

                    $result="Save successfully";
                    $status=1;
                    }
                    else
                    {
                    $result="File manager is empty";
                    $error=4;
                    }

                }
                elseif($data->user->staffmode=='school')
                {


                  $staffId=$data->user->staffId;
                  $file_id=$data->user->file_id;
                  $class_id=$data->user->course_id;
                  $section_id=$data->user->semester_id;
                  $subject_id=$data->user->subject_id;


              $chkStfValid=$this->schoolStaffValid($data->user->staffId,'staffSection');
             // print_r($chkStfValid);
             // exit;

                if($chkStfValid['status']==1)
                {

                  $scl_stf_sub_id=$chkStfValid['scl_stf_sub_id'];
                  $schStaffSubCnt=SclStfSubMapModel::where(['scl_stf_sub_id'=>$scl_stf_sub_id,'sch_cls_id'=>$class_id,'sec_id'=>$section_id,'sub_id'=>$subject_id,'active'=>1])->get()->count() ;
                  if($schStaffSubCnt>0)
                  {

                    $ChkCnt=0;
                    $fileManagerCnt=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->count();
                    if($fileManagerCnt>0)
                    {

                       $fileManagerSubject=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,"scl_stf_file_id"=>$file_id,"active"=>1,'file_type'=>'folder'])->get();

                      $urlPath = url('uploads/file_manager/');
                      $filePath=isset($fileManagerSubject[0]->file_path)?$fileManagerSubject[0]->file_path:'';
                      $fileName=isset($fileManagerSubject[0]->file_name)?$fileManagerSubject[0]->file_name:'';
                      $insertPath=$filePath.$fileName.'/';
                      $path_folder_ids=$fileManagerSubject[0]->path_folder_ids.','.$file_id;
                     $chkPath = base_path() . "/public/uploads/file_manager/" . $filePath . $fileName;

                      if(file_exists($chkPath)) 
                        {
                          $insertDataCnt=0;

                          for($iVal=0;$iVal<count($getFileArr);$iVal++)
                          {

                           //$fileName=$getFileArr[$iVal];
                           //$fDesc=$getFileDescArr[$iVal];

                           $fileName=isset($getFileArr[$iVal])?$getFileArr[$iVal]:'';
                           $fDesc=isset($getFileDescArr[$iVal])?$getFileDescArr[$iVal]:'';
                           $imgThumb=isset($getThumbArr[$iVal])?$getThumbArr[$iVal]:'';

                          $UploadFilemanagerExits=SchFileManagerModel::where(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,'file_type'=>'file','file_name'=>"$fileName",'parent_id'=>$file_id])->count();
                           if($UploadFilemanagerExits==0)
                           {
                              $insertDataCnt++;
                              $UploadFilemanagerInsert=SchFileManagerModel::insert(["scl_stf_id"=>"$staffId","scl_stf_sub_id"=>$scl_stf_sub_id,"sch_cls_id"=>$class_id,"sec_id"=>$section_id,"sub_id"=>$subject_id,'file_type'=>'file','file_name'=>"$fileName","file_path"=>"$insertPath",'parent_id'=>$file_id,'path_folder_ids'=>"$path_folder_ids",'description'=>"$fDesc","thumb_img"=>"$imgThumb"]);

                           }

                          }
                          if($insertDataCnt>0)
                          {
                            $status=1;
                            $result="Save successfully";
                          }
                          else
                          {
                            $result="File name already Exists";
                            $error=120;
                          }

                        }
                        else
                        {
                          $result='Invalid path';
                          $error=118;
                        }
                     }
                    else
                    {

                      $result='Invalid folder in file manager';
                      $error=117;


                    }


                  }
                  else
                  {

                    $result='No subject path found';
                    $error=116;
                  }
                

                }
                else
                {

                   $result=$chkStfValid['error'];
                   $error=115;

                }




                }
                else
                {
                   $result="Invalid staff mode";
                   $error=114;

                }



           }
           else
           {

            $result="Invalid credentials";
            $error=113;


           }
           

         }
         else
         {

             $result="Upload file name is empty";
             $error=3;


         }
 
          
      }
      else
      {
         $result="Invalid request";
         $error=2;
      }
 }
 else
 {
     $result="Invalid request";
      $error=1;
 }
 
  return response()->json(['status'=>$status,'result'=>$result,'error'=>$error]);

 }
 public function UserCredential($sCode=null,$sPass=null,$staffMode=null)
 {

       $responseArr['status']=0;
       $responseArr['errorMsg']='';
       $responseArr['userId']=0;
       $responseArr['userType']='';
       //$licenseField='';
       $credentials = array(
                          'email' => $sCode,
                          'password' => $sPass,
                          );
       //$chkLicenseLoginCnt=$this->LicenseLoginCnt($license,$staffMode);
       // if($chkLicenseLoginCnt['status']==1)
       // {

           if (Auth::guard('user')->attempt($credentials)){

          $getUserType=auth()->guard('user')->user()->usertype;
          if($getUserType=='SSF') //school staff
          {
            $chkUserIdCnt=SchStaffModel::where('staff_code',$sCode)->where('active',1)->get()->count();
            if($chkUserIdCnt>0)
            {
               // if($license=='voice')
               //   $licenseField='voice';
               //  elseif($license=='voice_screen')
               //    $licenseField='voice_screen'; 
               //    elseif($license=='voice_video')
               //      $licenseField='voice_video';
               //      elseif($license=='voice_video_screen')
               //        $licenseField='voice_video_screen';

               //$updateUserLicense=User::where('staff_code',"$sCode")->update([$licenseField=>1]);

              $chkUserId=SchStaffModel::where('staff_code',$sCode)->where('active',1)->get();
              $responseArr['userId']=isset($chkUserId[0]->scl_stf_id)?$chkUserId[0]->scl_stf_id:'0';
              $responseArr['voice']=isset($chkUserId[0]->v_permission)?$chkUserId[0]->v_permission:'0';
              $responseArr['voice_screen']=isset($chkUserId[0]->v_s_permission)?$chkUserId[0]->v_s_permission:'0';
              $responseArr['voice_video_screen']=isset($chkUserId[0]->v_vid_s_permission)?$chkUserId[0]->v_vid_s_permission:'0';
              $responseArr['voice_video']=isset($chkUserId[0]->v_vid_permission)?$chkUserId[0]->v_vid_permission:'0';
              $responseArr['status']=1;
              $responseArr['userType']='school';

            }
            else
            {
              $responseArr['errorMsg']='Invalid staff profile';
            }

          }
          elseif($getUserType=='CSF')  //college staff
          {

            $chkUserIdCnt=ClgStaffModel::where('staff_code',$sCode)->where('active',1)->get()->count();
            if($chkUserIdCnt>0)
            {

               $responseArr['status']=1;

               // if($license=='voice')
               //   $licenseField='voice';
               //  elseif($license=='voice_screen')
               //    $licenseField='voice_screen'; 
               //    elseif($license=='voice_video')
               //      $licenseField='voice_video';
               //      elseif($license=='voice_video_screen')
               //        $licenseField='voice_video_screen';

               //$updateUserLicense=User::where('email',"$sCode")->update([$licenseField=>1]);
             $chkUserId=ClgStaffModel::where('staff_code',$sCode)->where('active',1)->get();
             $responseArr['userId']=isset($chkUserId[0]->cl_stf_id)?$chkUserId[0]->cl_stf_id:'0';
             $responseArr['voice']=isset($chkUserId[0]->v_permission)?$chkUserId[0]->v_permission:'0';
             $responseArr['voice_screen']=isset($chkUserId[0]->v_s_permission)?$chkUserId[0]->v_s_permission:'0';
             $responseArr['voice_video_screen']=isset($chkUserId[0]->v_vid_s_permission)?$chkUserId[0]->v_vid_s_permission:'0';
             $responseArr['voice_video']=isset($chkUserId[0]->v_vid_permission)?$chkUserId[0]->v_vid_permission:'0';
             $responseArr['userType']='college';
            }
            else
            {
              $responseArr['errorMsg']='Invalid staff profile';
            }
          }
          else
          {
            $responseArr['errorMsg']='Invalid credentials';
          }

         }
         else
         {
            $responseArr['errorMsg']='Invalid credentials';
         }

       // }
       // else
       // {
       //    $responseArr['errorMsg']=$chkLicenseLoginCnt['msg'];
 
       // }
      return $responseArr;
 }

  public function  UserCredentialUserId($staffId=null,$staffMode=null)
  {
      if($staffId>0)
      {
        if($staffMode=='college'){
          $chkUserIdCnt=ClgStaffModel::where('cl_stf_id',$staffId)->where('active',1)->get()->count();
          if($chkUserIdCnt>0)
          {
            return 1;
            exit;
          }
          else
          {
            return 0;
            exit;
          }
        }
        elseif($staffMode=='school')
        {

           $chkUserIdCnt=SchStaffModel::where(['scl_stf_id'=>$staffId,'active'=>1])->count();
           if($chkUserIdCnt>0)
           {
            return 1;
            exit;
           }
           else
           {
            return 0;
            exit;
           }

        }
        else
        {

          return 0;
          exit;

        }

      }
      else
      {
        return 0;
        exit;
      }
  }
 public function UserGetDerails($sCode=null)
 {

  $chkUserIdCnt=ClgStaffModel::where('staff_code',$sCode)->where('active',1)->get()->count();
  if($chkUserIdCnt>0)
  {

    $chkUserId=ClgStaffModel::where('staff_code',$sCode)->get();
    $userId=$chkUserId[0]->cl_stf_id;

    $chkStfMapCnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$userId,'active'=>1])->get()->count();
    if($chkStfMapCnt>0)
    {

      return $userId;
      exit; 
    }
    else
    {

      return 0;
      exit; 

    }

  }
  else
  {
    return 0;
    exit;  
  }
 }
 public function checkSubjectMapped($staffId=null,$staffMode=null)
 {


    $mapResult['status']=0;
    $mapResult['result']='';
    $urlPath=url('uploads/file_manager/')  ;

    if($staffMode=='college')
    {

      $chkUserIdCnt=ClgStaffModel::where('cl_stf_id',$staffId)->where('active',1)->get()->count();
      if($chkUserIdCnt>0)
      {

        //$chkUserId=ClgStaffModel::where('cl_stf_id',$staffId)->get();
        $userId=$staffId;//$chkUserId[0]->cl_stf_id;

        $chkStfMapCnt=ClgStfSubMasterModel::where(['cl_stf_id'=>$userId,'active'=>1])->get()->count();
        if($chkStfMapCnt>0)
        {

           $chkStfMapId=ClgStfSubMasterModel::where(['cl_stf_id'=>$userId,'active'=>1])->get();
           $clg_stf_sub_id=$chkStfMapId[0]->clg_stf_sub_id;
           $chkSubMapCnt=ClgStfSubMapModel::where(['clg_stf_sub_id'=>$clg_stf_sub_id,'active'=>'1'])->get()->count();
           if($chkSubMapCnt>0)
           {

            $getCourseName=ClgStfSubMapModel::leftJoin('at_college_course_master','at_college_course_master.course_id','=','at_college_staff_subject_mapping.course_id')->where(['at_college_staff_subject_mapping.clg_stf_sub_id'=>$clg_stf_sub_id,'at_college_staff_subject_mapping.active'=>'1'])->groupBy('at_college_staff_subject_mapping.course_id')->get(array('at_college_course_master.course_id','at_college_course_master.course_name',DB::raw("  $clg_stf_sub_id  as clg_stf_sub_id"),DB::raw("  $clg_stf_sub_id  as clg_stf_sub_id"),DB::raw("  ''  as file_id"),DB::raw("  'folder'  as file_type"),DB::raw("  ''  as file_path")));

               foreach ($getCourseName as $key => $value) {

               $course_id=$value->course_id;
                $getFileManagerIdCnt=ClgFileManagerModel::where(['cl_stf_id'=>$staffId,'clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>0,'sub_id'=>0,'file_type'=>'folder','parent_id'=>0])->get()->count();
                 if($getFileManagerIdCnt>0)
                 {
                   $getFileManagerId=ClgFileManagerModel::where(['cl_stf_id'=>$staffId,'clg_stf_sub_id'=>$clg_stf_sub_id,'course_id'=>$course_id,'semester_id'=>0,'sub_id'=>0,'file_type'=>'folder','parent_id'=>0])->get();
                  $getCourseName[$key]->file_id=isset($getFileManagerId[0]->clg_stf_file_id)?$getFileManagerId[0]->clg_stf_file_id:0;
                  $fPath=isset($getFileManagerId[0]->file_path)?$getFileManagerId[0]->file_path:'';
                  $getCourseName[$key]->file_path=$urlPath.'/'.$fPath;


                }       

                
               }




              
              $mapResult['status']=1;
              $mapResult['error']='success';
              $mapResult['result']=$getCourseName;

           }
           else
           {

             $mapResult['error']='Staff is not mapping with subject';
           }

        }
        else
        {

          $mapResult['error']='Staff not mapping with subject';
        }

         return  $mapResult;

      }
      else
      {
 
         $mapResult['error']='Invalid user account';
         return  $mapResult;

      }

    }
    elseif($staffMode=='school')
    {

    }


}

 public function checkSubjectMappedold($sCode=null,$staffMode=null)
 {


    $mapResult['status']=0;

   if($staffMode=='school')
   {


      $getStffCode=$sCode;
     
      $chkStfMapCnt=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get()->count() ;
      if($chkStfMapCnt==0)
      {
         $mapResult['error']='Permission denied';
         return $mapResult;
         exit;
      }

      $getStaffDetails=SchStaffModel::where(['staff_code'=>$getStffCode,'active'=>1])->get();  
      $scl_stf_id=$getStaffDetails[0]->scl_stf_id;
      $chkSubMasterCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get()->count();
      if($chkSubMasterCnt==0)
      {
            $mapResult['error']="Staff yet not mapped subject";
            return $mapResult;
            exit;
      }

    $chkActiveCnt=SclStfSubMasterModel::where(['scl_stf_id'=>$scl_stf_id])->get();  
    if($chkActiveCnt[0]->active==0)
    {
      $mapResult['error']="Staff mode is inactive";
      return $mapResult;
      exit;
    }

    $sclStfSubMasterId=$chkActiveCnt[0]->scl_stf_sub_id;
    $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;
    $chkMappingFileTbl=SchFileManagerModel::where(['scl_stf_id'=>$scl_stf_id,'scl_stf_sub_id'=>$sclStfSubMasterId])->get()->count();
    if($chkMappingFileTbl>0)
    {

       $mapResult['status']=1;
       $mapResult['sclStfSubMasterId']=$sclStfSubMasterId;
       $mapResult['scl_stf_id']=$scl_stf_id;
       $mapResult['staffNameFolder']=$staffNameFolder;
       $mapResult['error']="success";
       return $mapResult;
       exit;
       
    }
    else
    {


      $mapResult['error']="Staff not mapping in file manager part";
      return $mapResult;
      exit;
    }
    

   }
   elseif($staffMode=='college')
   {

    $chkSubMapped=ClgStaffModel::where('cl_sft_id',$sCode)->get()->count();

    if($chkSubMapped==11)
    {


    }
    else
    {

      $mapResult['error']="Invalid  staff";
      return $mapResult;
      exit;


    }


    //ClgStfSubMasterModel::

   // $mapResult['error']="college staff";
   // return $mapResult;
    //exit;

   }
   else
   {

     $mapResult['error']="Invalid staff mode";
    return $mapResult;
    exit;


   }


   }


public function PathFolderFunction($StaffId=null,$courId=null,$semId=null)
{
   $getStaffDetailsCnt=ClgStaffModel::where(['cl_stf_id'=>$StaffId,'active'=>1])->get()->count();
   if($getStaffDetailsCnt>0)
   {

       $getStaffDetails=ClgStaffModel::where('cl_stf_id',$StaffId)->get();
       $staffNameFolder=strtolower($getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name);
       $getCourseNameCnt=ClgCourseModel::where(['course_id'=>$courId,'active'=>1])->get()->count();
       if($getCourseNameCnt>0)
       {
        $getCourseName=ClgCourseModel::where(['course_id'=>$courId,'active'=>1])->get() ;
        $courseName=$getCourseName[0]->course_name;

        return  $staffNameFolder.'/'.$courseName;
        exit;
       }
       else
       {
        return 0;
        exit;
       }
   }
   else
   {
    return 0;
    exit;
   }

}
public function licenseLoggedUsersList($userType=null)
{
  $userList=User::where('usertype',$userType)->where(function($query){
                    return $query
                            ->orWhere('voice', '=', '1') 
                            ->orWhere('voice_screen', '=', '1') 
                            ->orWhere('voice_video', '=', '1') 
                            ->orWhere('voice_video_screen', '=', '1');
             })->get(array(DB::raw(" email as staffcode "),'voice','voice_screen','voice_video','voice_video_screen'));
  return $userList;
}
public function LicenseLoginCnt($license=null,$staffMode=null,$staffcode=null)
{
  $licenseArr['status']=0;
  $licenseArr['msg']="";
  $licenseCnt=0;
  $voice=0; 
  $voice_screen=0;
  $voice_video=0;
  $voice_video_screen=0;
  $logedVoiceCnt=0;
  $userType='';

  $licenseField='';

  if($license=='voice'){
  $licenseField='v_permission';}
  elseif($license=='voice_screen'){
  $licenseField='v_s_permission'; }
  elseif($license=='voice_video'){
  $licenseField='v_vid_permission';}
  elseif($license=='voice_video_screen'){
  $licenseField='v_vid_s_permission';}
  
   if($staffMode=='college')
   {
       $userType="CSF";
       $chkLicensePerimissionCnt=ClgStaffModel::where('staff_code',$staffcode)->where($licenseField,1)->count();
 
   }
   elseif($staffMode=='school')
   {
       $userType="SSF";//SchStaffModel
       $chkLicensePerimissionCnt=SchStaffModel::where('staff_code',$staffcode)->where($licenseField,1)->count();
   }
   else
   {
     $licenseArr['msg']="Invalid staff mode";
     return $licenseArr;
     exit;

   }

   if($chkLicensePerimissionCnt==0)
   {
    $licenseArr['msg']="Invalid $license license permission";
    return $licenseArr;
    exit;
   }
   

  $getSettingKeyCnt=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')->count();

  if($getSettingKeyCnt>0)
  {

    $getSettingKey=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')
  ->get();

  //echo "<pre>"; print_r($getSettingKey); exit

  foreach($getSettingKey as $value)
  {

    if($value->lin_id==1) //voice
       $voice=$value->package_max_cnt;

    if($value->lin_id==2) //voice_screen
      $voice_screen=$value->package_max_cnt;
    
    if($value->lin_id==3) //voice_video
       $voice_video=$value->package_max_cnt;
    
    if($value->lin_id==4) //voice_video_screen
       $voice_video_screen=$value->package_max_cnt;
  }

    if($license=='voice')
    {
        if($voice>0)
        {
          $chkUserLoged=User::where('usertype',$userType)->where('email',$staffcode)->first(array('voice'));
          $is_voice=isset($chkUserLoged['voice'])?$chkUserLoged['voice']:'';
          if($is_voice==1)
          {
            $licenseArr['status']=1;
            $licenseArr['msg']='User already logged in voice license';
          }
        else
        {
          $logedVoiceCnt=User::where('usertype',"$userType")->sum('voice');
            if($voice>$logedVoiceCnt )
            {
              $licenseArr['status']=1;
              $licenseArr['msg']='ok';
            }
            else{
              $chkOtherlicenseAvail=$this->licenseAvail($staffcode,'v_permission','clg',$userType);
              $otherLicenselog='';
              if($chkOtherlicenseAvail['status']==1)
              {
              unset($chkOtherlicenseAvail['status']);
              if(count($chkOtherlicenseAvail)>0){
              $otherLicenselog=',other available license '.implode(',', $chkOtherlicenseAvail);
              $licenseArr['status']=2;
            }
              }
              $licenseArr['msg']='Voice license logged user exceeded'.$otherLicenselog;
            }
        }

            
        }
        else
        {
        $licenseArr['msg']="Invalid voice setting";
        }
       
    }
    elseif($license=='voice_screen')
    {
      if($voice_screen>0)
      {

        $chkUserLoged=User::where('usertype',$userType)->where('email',$staffcode)->first(array('voice_screen'));
        $is_voice_screen=isset($chkUserLoged['voice_screen'])?$chkUserLoged['voice_screen']:'';
        if($is_voice_screen==1)
          {
            $licenseArr['status']=1;
            $licenseArr['msg']='User already logged in voice screen license';
          }
          else{

            $logedVoicescrCnt=User::where('usertype',"$userType")->sum('voice_screen');
            if($voice_screen>$logedVoicescrCnt )
            {
              $licenseArr['status']=1;
              $licenseArr['msg']='voice_screen';
            }
            else{

              $chkOtherlicenseAvail=$this->licenseAvail($staffcode,'v_s_permission','clg',$userType);
              $otherLicenselog='';
              if($chkOtherlicenseAvail['status']==1)
              {
              unset($chkOtherlicenseAvail['status']);
              if(count($chkOtherlicenseAvail)>0){
              $otherLicenselog=',other available license '.implode(',', $chkOtherlicenseAvail);
              $licenseArr['status']=2;
            }
              }
              $licenseArr['msg']='Voice screen license logged user exceeded'.$otherLicenselog;
            }
          }
      }
      else
      {
         $licenseArr['msg']="Invalid voice screen setting";
      }

    }
    elseif($license=='voice_video')
    {
      if($voice_video>0)
      {
        //check already loged 
        $chkUserLoged=User::where('usertype',$userType)->where('email',$staffcode)->first(array('voice_video'));
        $is_voice_video=isset($chkUserLoged['voice_video'])?$chkUserLoged['voice_video']:'';
        if($is_voice_video==1)
        {
          $licenseArr['status']=1;
          $licenseArr['msg']='User already logged in Voice-video license';
        }
        else
        {

          $logedVoicevideoCnt=User::where('usertype',"$userType")->sum('voice_video');
          if($voice_video>$logedVoicevideoCnt)
          {
            $licenseArr['status']=1;
            $licenseArr['msg']='voice_video';
          }
          else{
             $chkOtherlicenseAvail=$this->licenseAvail($staffcode,'v_vid_permission','clg',$userType);
             $otherLicenselog='';
             if($chkOtherlicenseAvail['status']==1)
             {
               unset($chkOtherlicenseAvail['status']);
                 if(count($chkOtherlicenseAvail)>0){
                  $otherLicenselog=',other available license '.implode(',', $chkOtherlicenseAvail);
                   $licenseArr['status']=2;
                }
             }
            
            $licenseArr['msg']='Voice-video license logged user exceeded '.$otherLicenselog ;
          }
             
        }
        

      }
      else
      {
         $licenseArr['msg']="Invalid voice video setting";
      }
      
    }
    elseif($license=='voice_video_screen')
    {
      if($voice_video_screen>0)
      {

        $chkUserLoged=User::where('usertype',$userType)->where('email',$staffcode)->first(array('voice_video_screen'));
        $is_voice_video_screen=isset($chkUserLoged['voice_video_screen'])?$chkUserLoged['voice_video_screen']:'';
        if($is_voice_video_screen==1)
        {
            $licenseArr['status']=1;
            $licenseArr['msg']='User already logged in Voice-video-screen license';
        }
        else
        {
            $logedVoicevideoScrCnt=User::where('usertype',"$userType")->sum('voice_video_screen');
            if($voice_video_screen>$logedVoicevideoScrCnt)
            {
            $licenseArr['status']=1;
            $licenseArr['msg']='voice_video_screen';
            }
            else{
              $chkOtherlicenseAvail=$this->licenseAvail($staffcode,'v_vid_s_permission','clg',$userType);
              $otherLicenselog='';
              if($chkOtherlicenseAvail['status']==1)
              {
              unset($chkOtherlicenseAvail['status']);
              if(count($chkOtherlicenseAvail)>0){
              $otherLicenselog=',other available license '.implode(',', $chkOtherlicenseAvail);
              $licenseArr['status']=2;
            }
              }
            $licenseArr['msg']='Voice-video-screen license logged user exceeded'.$otherLicenselog;
            }

        }

       
      }
      else
      {
         $licenseArr['msg']="Invalid voice setting";
      }
    }
    else
    {
       $licenseArr['msg']="Invalid license";
    }
       
 
  }
  else
  {
     $licenseArr['msg']="Invalid setting key";
  }
  return $licenseArr;  
}
public function licenseAvail($staffCode,$exceptField,$mode,$userType)
{
  
 if($mode=='clg')
 {

  $resultArr['status']=0;
  $retArray1=array();

  $getotherLicense=ClgStaffModel::where('staff_code',$staffCode)->first(array('v_permission','v_s_permission','v_vid_s_permission','v_vid_permission'));
   $retArray=array();
 
    if($getotherLicense['v_permission']==1)
    {
      $retArray['v_permission']=$getotherLicense['v_permission'];
    }
     if($getotherLicense['v_s_permission']==1)
    {
      $retArray['v_s_permission']=$getotherLicense['v_s_permission'];
    }
    if($getotherLicense['v_vid_s_permission']==1)
    {
       $retArray['v_vid_s_permission']=$getotherLicense['v_vid_s_permission'];
    }
    if($getotherLicense['v_vid_permission']==1)
    {
      $retArray['v_vid_permission']=$getotherLicense['v_vid_permission'];
    }
   
 if(count($retArray)>0)
 {
    unset($retArray[$exceptField]);
    if(count($retArray)>0)
    {

      foreach($retArray as $k=>$val)
      {
          if($k=='v_permission'){
             $license='voice';
             $getSettingKey=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')->where('lin_id',1)->first(array('package_max_cnt'));

           }
          elseif($k=='v_s_permission'){
              $license='voice_screen';
              $getSettingKey=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')->where('lin_id',2)->first(array('package_max_cnt')); 
          }
          elseif($k=='v_vid_permission'){
              $license='voice_video';
              $getSettingKey=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')->where('lin_id',3)->first(array('package_max_cnt'));
          }
          elseif($k=='v_vid_s_permission'){
            $license='voice_video_screen';
            $getSettingKey=Setting::join('cdn_customer_key_mapping as a','a.c_id','=','settings.c_id')->where('lin_id',4)->first(array('package_max_cnt'));
          }
         $package_max_cnt=isset($getSettingKey['package_max_cnt'])?$getSettingKey['package_max_cnt']:0;
         $logedCnt=User::where('usertype',"$userType")->sum($license);
         if($package_max_cnt>0)
         {
          if($package_max_cnt>$logedCnt)
          {
            $licenseKey=str_replace('_', '-', $license);
            $resultArr[$licenseKey]=$licenseKey;
            $resultArr['status']=1;
          }

         }
          
      }
    }
 }
 }
 return $resultArr;
}
   
}

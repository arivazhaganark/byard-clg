<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use Redirect;
use Session;
use DB;


class SchSectionController extends Controller
{
     
    public function index(){

        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
           $schsectionAll = SchSectionModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_section_master.sch_cls_id')->where('at_school_section_master.active',0)->get();
       
        } else {

            $schsectionAll = SchSectionModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_section_master.sch_cls_id')->where('at_school_section_master.active',1)->get();

             
        }

        $active_count = SchSectionModel::where('active', 1)->count();
        $inactive_count = SchSectionModel::where('active', 0)->count();
       return view('backend.schSections.index', compact('schsectionAll', 'active_count', 'inactive_count'));
    }

    public function create() {
       
    $classId = SchClassModel::select('sch_cls_id','sch_class','active')->where('active',1)->get();
    $sectionId = SchSectionModel::select('sec_id','section_name','active')->where('active',1)->get();
    return view('backend.schSections.add',compact('classId','sectionId'));
    }
    

    public function store(Request $request) {
         $inputs=$request->all();
         $clsId=$inputs['selectCls'];
         $sectionName=$inputs['sname'];

        $chkCnt = SchSectionModel::where(['sch_cls_id'=>$clsId,'section_name'=>$sectionName])->get()->count();

        if($chkCnt==0)
        {
      
        $sectiondata = SchSectionModel::create([
                              'sch_cls_id'=>$clsId,   
                            'section_name' =>$sectionName,
                           ]);

            Session::flash('message', 'Section created successfully .');
            Session::flash('alert-class', 'alert-success');
            return Redirect::to('admin/schsection/add');

        }
        else
        {

            Session::flash('message', 'Section already exists .');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schsection/add')->withInput($request->all());

        }
   
}

     

    public function edit($cus_id){


        $KeyViewCustomerAll = SchSectionModel::leftJoin('atnetwork_customer_master','atnetwork_customer_key_master.cus_id','=','atnetwork_customer_master.cus_id')
                 ->where('atnetwork_customer_key_master.cus_key_id',$cus_id)
                 ->get(array('atnetwork_customer_master.c_name',
                             'atnetwork_customer_master.c_id',
                             'atnetwork_customer_key_master.cus_key_id',
                             'atnetwork_customer_master.c_name',
                             'atnetwork_customer_master.c_id', 
                             'atnetwork_customer_master.email_id',
                              DB::raw('""  as Voice'),
                              DB::raw('""  as Voice_S'),
                              DB::raw('""  as Voice_Video'),
                              DB::raw('""  as Voice_Video_Screen'),
                              DB::raw('""  as Used_Voice'),
                              DB::raw('""  as Used_Voice_S'),
                              DB::raw('""  as Used_Voice_Video'),
                              DB::raw('""  as Used_Voice_Video_Screen'),



                              ));

     $KeyMappVal=CustomerKeyMapModel::select('cus_key_id', 'lin_id', 'package_max_cnt')->where('cus_key_id',$cus_id)->get() ; 
  
    
      foreach ($KeyMappVal as $key => $value) {

        $mapCusId=$value->cus_key_id;
        $mapLicenceId=$value->lin_id;

        $GetKeyUsed = CustomerKeyUsedModel:: where(['cus_id' => "$mapCusId","lin_id"=>"$mapLicenceId"])->get()->count();

     
         if($value->lin_id==1)
         {
            $KeyViewCustomerAll[0]->Voice=$value->package_max_cnt;
            $KeyViewCustomerAll[0]->Used_Voice=$GetKeyUsed;

         }
         elseif($value->lin_id==2)
         {
           $KeyViewCustomerAll[0]->Voice_S=$value->package_max_cnt;
           $KeyViewCustomerAll[0]->Used_Voice_S=$GetKeyUsed;

         }
         elseif($value->lin_id==3)
         {
            $KeyViewCustomerAll[0]->Voice_Video=$value->package_max_cnt;
            $KeyViewCustomerAll[0]->Used_Voice_Video=$GetKeyUsed;
         }
         elseif($value->lin_id==4)
         {
            $KeyViewCustomerAll[0]->Voice_Video_Screen=$value->package_max_cnt;
            $KeyViewCustomerAll[0]->Used_Voice_Video_Screen=$GetKeyUsed;
         }
              
      } 

   	 $LicenseInterface=LicenseInterfaceModel::select('lin_id','interface_name')->where('active', 1)->get();
        return view('backend.schsection.edit', compact('KeyViewCustomerAll','LicenseInterface','cus_id'));



    }

     public function update(Request $request) {


         $id = $request->get('hidid');
         $licenceId=$request->get('licenceVal');
         $textBoxMaxVal=$request->get('type_'.$licenceId);  
         if($id>0 && $licenceId>0 && $textBoxMaxVal>0)
         {

            $getLicenceCnt=SchSectionModel::leftJoin('atnetwork_customer_key_mapping','atnetwork_customer_key_mapping.cus_key_id','=','atnetwork_customer_key_master.cus_key_id')
                ->where('atnetwork_customer_key_master.cus_key_id',$id)
                ->where('atnetwork_customer_key_mapping.lin_id',$licenceId)->get()->count();

             if($getLicenceCnt>0) //already existing need update only 
             {

             
              $getLicenceMax=SchSectionModel::leftJoin('atnetwork_customer_key_mapping','atnetwork_customer_key_mapping.cus_key_id','=','atnetwork_customer_key_master.cus_key_id')
                ->where('atnetwork_customer_key_master.cus_key_id',$id)
                ->where('atnetwork_customer_key_mapping.lin_id',$licenceId)->get(array('atnetwork_customer_key_mapping.package_max_cnt'));

                 $LastMaxCnt=$getLicenceMax[0]->package_max_cnt ;

                if($textBoxMaxVal>=$LastMaxCnt)
                {

                    if($textBoxMaxVal != $LastMaxCnt)
                    {
                       $updateCus=CustomerKeyMapModel::where('cus_key_id',$id)->where('lin_id',$licenceId)
                        ->update(["package_max_cnt"=> "$textBoxMaxVal"]);
                       echo 'Value successfully updated'; //success
                    }
                    else
                    {

                        
                        echo 'Value successfully updated'; //success
                       
                    }
                      
                }
                else
                {
                    $GetKeyUsed = CustomerKeyUsedModel:: where(['cus_id' => "$id","lin_id"=>"$licenceId"])->get()->count();
                    if($GetKeyUsed>0)
                    {

                      $getResVal=$GetKeyUsed-$textBoxMaxVal;

                      if($getResVal>0)
                      { 
                        echo  "Used count is exceeded";

                      }
                      else
                      {

                        $updateCus=CustomerKeyMapModel::where('cus_key_id',$id)->where('lin_id',$licenceId)
                        ->update(["package_max_cnt"=> "$textBoxMaxVal"]);
                         echo 'Value successfully updated'; //success

                         //echo 'no'.$getResVal;
                      }
                        // if($GetKeyUsed>$textBoxMaxVal)
                        // {

                        // }
                       // echo $getResVal;
                        //exit;

                    }
                    else
                    {

                        $updateCus=CustomerKeyMapModel::where('cus_key_id',$id)->where('lin_id',$licenceId)
                        ->update(["package_max_cnt"=> "$textBoxMaxVal"]);
                        echo 'Value successfully updated'; //success

                    }



                }





             }
             else{ //insert licence 


                $RandKey=$this->generateRandomString(16);
                $GetKeyUniqueCnt=CustomerKeyMapModel:: where(['package_key' => "$RandKey"])->get()->count();

                if($GetKeyUniqueCnt==0)
                {

                  
                $Childdata = CustomerKeyMapModel::create([
                'cus_key_id'=>$id,   
                'lin_id' => $licenceId,
                'package_max_cnt' =>$textBoxMaxVal,
                'package_key' =>$RandKey

                ]);

                echo 'Value inserted successfully';
                }
                else
                {

                    echo 'Please try again.'; //invali random key ples try again
                }


 
             }   

                

         }
         else
         {

            echo 'Please try again.';
         }

      

        
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Section has been successfully inactivated.";
            $redirect_value = "admin/schsection";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Section has been successfully activated.";
            $redirect_value = "admin/schsection/?token=inactive";
        }
        else
        {

            $msg_value="Invalid request";
        }
            
        
        foreach ($updated_ids_value as $update_id) {
            if ($action != 'Delete') {
                $data = array(
                    $column_name => $action_value
                );
                SchSectionModel::select('*')->where('sec_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
    public function show($id) {
        $schsectionAll = SchSectionModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_section_master.sch_cls_id')->where('at_school_section_master.sec_id',$id)->get();
   }

     
    public function generateRandomString($length) {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
     }



}

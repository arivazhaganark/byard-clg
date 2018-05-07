<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SclSubjectModel;
use App\Model\backend\SclstaffClassMasterModel;
use App\Model\backend\SchStaffModel;
use App\Model\backend\SclStfSubMasterModel;
use App\Model\backend\SclStfSubMapModel;
use App\Model\backend\SchFileManagerModel;
use App\Model\backend\SchAcademicYearModel;
use Redirect;
use Session;
use DB;
use File;


class SchStfSubjectMapController extends Controller
{
     
    public function index(){

        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }

        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
           $schSubjectAll = SclStfSubMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where('at_school_staff_subject_master.active',0)->get();
      
        } else {

            $schSubjectAll = SclStfSubMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')->where('at_school_staff_subject_master.active',1)->get();
                     
        }

        $active_count = SclStfSubMasterModel::where('active', 1)->count();
        $inactive_count = SclStfSubMasterModel::where('active', 0)->count();
       return view('backend.schstfsubjectmapping.index', compact('schSubjectAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
          $uPermission=getUserPermission('schstaffsubmapp','school');
          if($uPermission[0]->file_add==0) /**Check the file permission**/
          { return view('backend.pageDined');}
          $staffAll=SclstaffClassMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_class_staff_master.scl_stf_id')->where('at_school_class_staff_master.active',1)->get();
        return view('backend.schstfsubjectmapping.add',compact('staffAll'));
    }
    

    public function store(Request $request) {
            $uPermission=getUserPermission('schstaffsubmapp','school');
            if($uPermission[0]->file_add==0) /**Check the file permission**/
            { return view('backend.pageDined');}

            $inputs=$request->all();
            $StaffId=$inputs['selectstaffCls'];
            $ClassId=$inputs['selectClass'];
            $explSecCls=explode(',', $inputs['hidcls']);
            $destinationPath = public_path('uploads/file_manager/');

            $GetAcademicYear=SchAcademicYearModel::get();
            $AcademicYear=$GetAcademicYear[0]->academic_year;
            if($StaffId>0 && $ClassId>0 && count($explSecCls)>0 )
            {

                $chkStfId=SclStfSubMasterModel::where('scl_stf_id',$StaffId)->get()->count();
                if($chkStfId==0)
                {
                  $inserMaster=SclStfSubMasterModel::create(['scl_stf_id'=>$StaffId]);
                  $lstInsertId=$inserMaster->scl_stf_sub_id ;
                }
                else
                {
                  $getlstInsertId=SclStfSubMasterModel::where('scl_stf_id',$StaffId)->get(array('scl_stf_sub_id')) ;
                  $lstInsertId=$getlstInsertId[0]->scl_stf_sub_id ;
                }

                $getStaffDetails=SchStaffModel::where('scl_stf_id',$StaffId)->get();
                $staffNameFolder=$getStaffDetails[0]->staff_code.'_'.$getStaffDetails[0]->staff_name;

                // $classId = SchClassModel::select('sch_cls_id','sch_class','active')->get();
       // $getSub=SclSubjectModel::where('sub_id',$sub_id)->get();

                if($lstInsertId>0)
                {

                    for($i=0;$i<count($explSecCls);$i++)
                    {

                        $splClassSub=explode('@#@', $explSecCls[$i]);
                        $secId=$splClassSub[0];
                        $SubId=$splClassSub[1];
                        $getClassNameFolder = SchClassModel::where('sch_cls_id',$ClassId)->get();
                        $classNameFolder=$getClassNameFolder[0]->sch_class;

                        $getMapExistSub=SclStfSubMapModel::where(['scl_stf_sub_id'=>$lstInsertId,'sch_cls_id'=>$ClassId,'sec_id'=>$secId,'sub_id'=>$SubId])->get()->count();
                        $getSectionFolder=SchSectionModel::where('sec_id',$secId)->get();
                        $SectionFolder=$getSectionFolder[0]->section_name;
                        $getSubjectFolder=SclSubjectModel::where('sub_id',$SubId)->get();
                        $SubjectFolder=$getSubjectFolder[0]->sub_name;
                        $directory=$staffNameFolder.'/'.$classNameFolder.'/'.$SectionFolder.'/'.$SubjectFolder;

                       if (!File::exists( $destinationPath.'/'.$directory)) {  
                            File::makeDirectory($destinationPath . '/' . $directory, 0755, true);
                          }
                        
                        if($getMapExistSub==0)
                        {

                            $insertMapping=SclStfSubMapModel::create(['scl_stf_sub_id'=>$lstInsertId,'sch_cls_id'=>$ClassId,'sec_id'=>$secId,'sub_id'=>$SubId]);

                        }

                    }


          /*File Manager start */

          $getClassFM=SclStfSubMapModel::where(['scl_stf_sub_id'=>$lstInsertId])->distinct()->get(array('sch_cls_id'));
          foreach ($getClassFM as $key => $value) {
          $getClassName = SchClassModel::where(['sch_cls_id'=>$value->sch_cls_id])->get(array('sch_class'));
          $chkClassExits=SchFileManagerModel::where(['sch_cls_id'=>$value->sch_cls_id,
          'scl_stf_id'=>$StaffId])->get()->count();
          if($chkClassExits==0) //Insert Class
          {
             $stfPath=$staffNameFolder.'/';
          $insertClass=SchFileManagerModel::insert(['file_name'=>$getClassName[0]->sch_class,'sch_cls_id'=>$value->sch_cls_id,'file_type'=>'folder','scl_stf_id'=>$StaffId,'academic_year'=>$AcademicYear,'scl_stf_sub_id'=>$lstInsertId,'file_path'=>"$stfPath" ]);
          }
  
         /*File Manager end */
                   



                 }


                  //insert section

            $getClsSecDetails=SclStfSubMapModel::where('scl_stf_sub_id',$lstInsertId)->groupBy(array('sch_cls_id','sec_id'))->get();

            foreach ($getClsSecDetails as $key => $getClsSecDetvalue) {

            $sclClsId=$getClsSecDetvalue->sch_cls_id;
            $sclClsIdSecId=$getClsSecDetvalue->sec_id;
            $getSclSec=SchSectionModel::where('sec_id',$sclClsIdSecId)->get();
            if(isset($getSclSec[0]->section_name))
            {
            $section_name=$getSclSec[0]->section_name;
            $getClassSecFM=SchFileManagerModel::where(['scl_stf_sub_id'=>$lstInsertId,'sch_cls_id'=>$sclClsId,'file_type'=>'folder','parent_id'=>0,'sec_id'=>0,'sub_id'=>0])->get();

            foreach ($getClassSecFM as $key => $getClassSecFMvalue) {

            $sclStfId=$getClassSecFMvalue->scl_stf_file_id;

            $chkClassSecExits=SchFileManagerModel::where(['sch_cls_id'=>$sclClsId,
            'sec_id'=>$sclClsIdSecId,
            'scl_stf_id'=>$StaffId,'scl_stf_sub_id'=>$lstInsertId,'file_type'=>'folder'])->get()->count();

            if($chkClassSecExits==0)
            {
            $standredName=$getClassSecFMvalue->file_name;
            $stfPathSection=$staffNameFolder.'/'.$standredName.'/' ;
            $insertClassSec=SchFileManagerModel::insert(['file_name'=>$section_name,
            'file_type'=>'folder',
            'scl_stf_id'=>$StaffId,
            'academic_year'=>$AcademicYear,
            'scl_stf_sub_id'=>$lstInsertId,
            'sec_id'=>$sclClsIdSecId,
            'parent_id'=>$sclStfId,
            'sch_cls_id'=>$sclClsId,
            'file_path'=>"$stfPathSection",
            'path_folder_ids'=>$sclStfId,

            ]);
            }
            }
            }
            }

        $getClassSubMap=SclStfSubMapModel::where(['scl_stf_sub_id'=>$lstInsertId])->get();

          foreach ($getClassSubMap as $key => $getClassSubMapvalue) {

            $Map_sch_cls_id=$getClassSubMapvalue->sch_cls_id;
            $Map_sec_id=$getClassSubMapvalue->sec_id;
            $Map_sub_id=$getClassSubMapvalue->sub_id;
            $getFMDetails=SchFileManagerModel::where(['scl_stf_sub_id'=>$lstInsertId,
              'sch_cls_id'=>$Map_sch_cls_id,'sec_id'=>$Map_sec_id,'scl_stf_id'=>$StaffId,
              'sub_id'=>0,'file_type'=>'folder'])->whereNotIn('parent_id',[0] )->get();
 
            foreach($getFMDetails as $keyval => $getFMDetailsvalue) {

               $subjectName='';

               $getSubName=SclSubjectModel::where('sub_id',$Map_sub_id)->get();
               if(isset($getSubName[0]->sub_name))
               {
                      $subjectName=$getSubName[0]->sub_name;
               }

               $getFMSubjectCnt=SchFileManagerModel::where(['scl_stf_sub_id'=>$lstInsertId,
              'sch_cls_id'=>$Map_sch_cls_id,'sec_id'=>$Map_sec_id,'scl_stf_id'=>$StaffId,
              'sub_id'=>$Map_sub_id,'file_type'=>'folder'])->get()->count();

               if($getFMSubjectCnt==0){
                  $stfPathSecCls=$getFMDetailsvalue->file_path.$getFMDetailsvalue->file_name.'/' ;
                  $pathIds=$getFMDetailsvalue->path_folder_ids.','.$getFMDetailsvalue->scl_stf_file_id;
               $insertClassSecSub=SchFileManagerModel::insert(['file_name'=>$subjectName,
                  'file_type'=>'folder',
                  'scl_stf_id'=>$StaffId,
                  'academic_year'=>$AcademicYear,
                  'scl_stf_sub_id'=>$lstInsertId,
                  'sec_id'=>$Map_sec_id,
                  'sub_id'=>$Map_sub_id,
                  'parent_id'=>$getFMDetailsvalue->scl_stf_file_id,
                  'sch_cls_id'=>$Map_sch_cls_id,
                  'folder_access'=>1,
                  'file_path'=>"$stfPathSecCls",
                  'path_folder_ids'=>$pathIds
                    ]);

               }

               echo $Map_sch_cls_id.'='.$Map_sec_id.'='.$getFMDetailsvalue->scl_stf_file_id.'subis'.$Map_sub_id.'=='.$subjectName.'<br>';
               
            }

          }
        
                Session::flash('message', 'Staff subject has been saved successfully');
                Session::flash('alert-class', 'alert-success');
                return redirect('admin/schstaffsubmapp/add');
                }
                else
                {
                Session::flash('message', 'Invalid request.');
                Session::flash('alert-class', 'alert-success');
                return redirect('admin/schstaffsubmapp/add')->withInput($request->all());
                }
            }
            else
            {
                Session::flash('message', 'Staff with class section mapped successfully.');
                Session::flash('alert-class', 'alert-success');
                return redirect('admin/schstaffsubmapp/add')->withInput($request->all());
            }

         
}
  public function edit($sub_id){

       // $classId = SchClassModel::select('sch_cls_id','sch_class','active')->get();
       // $getSub=SclSubjectModel::where('sub_id',$sub_id)->get();
       // return view('backend.schstfsubjectmapping.edit', compact('classId','sub_id','getSub'));
    }

     public function update(Request $request) {
        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}

         $inputs=$request->all();
         $id = $request->get('hidid');
         $clsId=$inputs['selectCls'];
         $subname=$inputs['subname'];
         
         $chkCnt = SclSubjectModel::where(['sch_cls_id'=>$clsId,'sub_name'=>$subname])->whereNotIn('sub_id', [$id])->get()->count();
         
         if($chkCnt==0)
         {
            $updateCus=SclSubjectModel::where('sub_id', $id)
                      ->update(['sch_cls_id'=>$clsId,'sub_name'=>$subname]);
            Session::flash('message', 'Subject updated successfully');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schstaffsubmapp/') ;  
         }
         else
         {

            Session::flash('message', 'Subject name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstaffsubmapp/edit/'.$id);
         }

       
    }

    public function actionupdate(Request $request) {
       $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Staff has been successfully inactivated.";
            $redirect_value = "admin/schstaffsubmapp";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/schstaffsubmapp/?token=inactive";
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
             SclStfSubMasterModel::select('*')->where('scl_stf_sub_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
    public function show($id) {  

        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_view==0) /**Check the file permission**/
        { return view('backend.pageDined');}

        $schStaff = SclStfSubMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_staff_subject_master.scl_stf_id')
         ->where('at_school_staff_subject_master.scl_stf_sub_id',$id)->get();

         if(isset($schStaff[0]->scl_stf_sub_id))
        {
            $subMapId=$schStaff[0]->scl_stf_sub_id;
            $staffId=$schStaff[0]->scl_stf_id;
        }
        else
        {
             $subMapId='';
             $staffId='';
        }

 
        $classId=SclstaffClassMasterModel::leftJoin('at_school_class_staff_mapping','at_school_class_staff_mapping.sch_cls_stf_id','=','at_school_class_staff_master.sch_cls_stf_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_staff_mapping.sec_id')->leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_staff_mapping.sch_cls_id')->where(['at_school_class_staff_master.scl_stf_id'=>$staffId])->orderBy('at_school_class_master.sch_cls_id')->orderBy('at_school_class_staff_mapping.sec_id')->get(array('at_school_class_master.sch_cls_id','at_school_class_master.sch_class','at_school_section_master.section_name','at_school_section_master.section_name','at_school_section_master.sec_id',DB::raw('""  as SubVal')));
       

        foreach ($classId as $key => $value) {

              $sch_cls_id=$value->sch_cls_id;
              $sec_id=$value->sec_id;

             $getSubject=SclStfSubMapModel::leftJoin('at_school_subject_master','at_school_subject_master.sub_id','=','at_school_staff_subject_mapping.sub_id')->where(['at_school_staff_subject_mapping.scl_stf_sub_id'=>$subMapId,'at_school_staff_subject_mapping.sch_cls_id'=>$sch_cls_id,'at_school_staff_subject_mapping.sec_id'=>$sec_id])->get(array(DB::raw("GROUP_CONCAT(at_school_subject_master.sub_name ) as `subName`")));

           $value->SubVal=$getSubject[0]->subName;
           
        }

//array('at_school_section_master.section_name','at_school_section_master.sec_id','at_school_class_staff_mapping.sch_cls_id')
        //$getSubject=SclSubjectModel::where(['active'=>1,'sch_cls_id'=>$selectClass])->get();

 
        return view('backend.schstfsubjectmapping.show',compact('schStaff','classId'));
       
   }

     
   public function depDropBox(Request $request) //get class list for staff
    {
 
        $inputs=$request->all();
        if($request['mode']=='single')
        {
            $selectCls=$request['selectstaffCls']; 
        }
        else
        {
            $selectCls=$request['selectstaffCls'];
        }

  
       $classId=SclstaffClassMasterModel::leftJoin('at_school_class_staff_mapping','at_school_class_staff_mapping.sch_cls_stf_id','=','at_school_class_staff_master.sch_cls_stf_id')->leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_staff_mapping.sch_cls_id')->where(['at_school_class_staff_master.scl_stf_id'=>$selectCls])->groupBy('at_school_class_staff_mapping.sch_cls_id')->orderBy('at_school_class_staff_mapping.sch_cls_id')->get(array('at_school_class_master.sch_cls_id','at_school_class_master.sch_class'));
          echo json_encode($classId);

        
    }

    public function depdropboxSection(Request $request) //get class list for staff
    {
 
        $inputs=$request->all();
        if($request['mode']=='single')
        {
            $selectStfCls=$request['selectstaffCls'];
            $selectClass=$request['selectClass'];
        }
        else
        {
            $selectStfCls=$request['selectstaffCls'];
            $selectClass=$request['selectClass'];
        }

       
  
       $classId=SclstaffClassMasterModel::leftJoin('at_school_class_staff_mapping','at_school_class_staff_mapping.sch_cls_stf_id','=','at_school_class_staff_master.sch_cls_stf_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_staff_mapping.sec_id')->where(['at_school_class_staff_master.scl_stf_id'=>$selectStfCls,'at_school_class_staff_mapping.sch_cls_id'=>$selectClass])->orderBy('at_school_class_staff_mapping.sec_id')->get(array('at_school_section_master.section_name','at_school_section_master.sec_id'));
        $result['className']=$classId;
        $getSubject=SclSubjectModel::where(['active'=>1,'sch_cls_id'=>$selectClass])->get();
        $result['subName']=$getSubject;
        echo json_encode($result);

        
    }

    



}

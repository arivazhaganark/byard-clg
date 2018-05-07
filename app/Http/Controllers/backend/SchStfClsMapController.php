<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
//use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SclSubjectModel;
use App\Model\backend\SclstaffClassMappModel;
use App\Model\backend\SclstaffClassMasterModel;
use App\Model\backend\SchStaffModel;
use App\Model\backend\SchClsSectionMapModel;
use Redirect;
use Session;
use DB;


class SchStfClsMapController extends Controller
{
     
    public function index(){
        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }

        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
           $schSubjectAll = SclstaffClassMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_class_staff_master.scl_stf_id')->where('at_school_class_staff_master.active',0)->get();
      
        } else {

            $schSubjectAll = SclstaffClassMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_class_staff_master.scl_stf_id')->where('at_school_class_staff_master.active',1)->get();
                     
        }

        $active_count = SclstaffClassMasterModel::where('active', 1)->count();
        $inactive_count = SclstaffClassMasterModel::where('active', 0)->count();
       return view('backend.schstfclsmapping.index', compact('schSubjectAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {
        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $classId = SchClsSectionMapModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_section_mapping.sch_cls_id')->orderBy('at_school_class_section_mapping.sch_cls_id')->distinct()->get(array('at_school_class_section_mapping.sch_cls_id','at_school_class_master.sch_class'));
        $staffAll=SchStaffModel::where('active',1)->get();
        return view('backend.schstfclsmapping.add',compact('classId','staffAll'));
    }
    

    public function store(Request $request) {
            $uPermission=getUserPermission('schstaffsubmapp','school');
            if($uPermission[0]->file_add==0) /**Check the file permission**/
            { return view('backend.pageDined');}

            $inputs=$request->all();
            $clsId=$inputs['selectMulCls'];
            $stfId=$inputs['selectstaffCls'];
            $curr_month=date('m');
            $curr_year=date('y');
            if($curr_month >= 6 )
            { 
            $year=$curr_year.(date('y')+1);
            }
            else
            {
            $year=(date('y')-1).$curr_year;
            }

            if(count($clsId)>0 &&  $stfId>0)
            {

            $chkStaff=SclstaffClassMasterModel::where('scl_stf_id',$stfId)->get()->count();
            if($chkStaff==0)
            {
            $insertData=SclstaffClassMasterModel::create(['scl_stf_id'=>"$stfId"]);
            $lstInsertId=$insertData->sch_cls_stf_id ;
            }
            else
            {

            $getStaffId=SclstaffClassMasterModel::where('scl_stf_id',$stfId)->get(array('sch_cls_stf_id')) ;
            $lstInsertId=$getStaffId[0]->sch_cls_stf_id ;
            }
            for($i=0;$i<count($clsId);$i++)
            {

            $explodeVal=explode('#@#', $clsId[$i]);
            $secId=$explodeVal[0];
            $sclclsId=$explodeVal[1];

            $chkExist=SclstaffClassMappModel::where(['sch_cls_stf_id'=>$lstInsertId,'sec_id'=>$secId,'sch_cls_id'=>$sclclsId])->get()->count();
            if($chkExist==0)
            {

            $insertData=SclstaffClassMappModel::insert(['sec_id'=>$secId,'sch_cls_stf_id'=>$lstInsertId, 'sch_cls_id'=>"$sclclsId",'academic_year'=>"$year"]);

            }


            }

            Session::flash('message', 'Staff with class section mapped successfully.');
            Session::flash('alert-class', 'alert-success');
            return redirect('admin/schstfclsmap/add')->withInput($request->all());
          }
          else
          {
            Session::flash('message', 'Invalid request.');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/schstfclsmap/add')->withInput($request->all());
          }
         
}
  public function edit($sub_id){

        $uPermission=getUserPermission('schstaffsubmapp','school');
        if($uPermission[0]->file_edit==0) /**Check the file permission**/
        { return view('backend.pageDined');}

       $classId = SchClassModel::select('sch_cls_id','sch_class','active')->get();
       $getSub=SclSubjectModel::where('sub_id',$sub_id)->get();
        return view('backend.schstfclsmapping.edit', compact('classId','sub_id','getSub'));
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
            return redirect('admin/schstfclsmap/') ;  
         }
         else
         {

            Session::flash('message', 'Subject name is already exists');
            Session::flash('alert-class', 'alert-warning');
            return Redirect::to('admin/schstfclsmap/edit/'.$id);
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
            $redirect_value = "admin/schstfclsmap";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Staff has been successfully activated.";
            $redirect_value = "admin/schstfclsmap/?token=inactive";
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
                SclstaffClassMasterModel::select('*')->where('sch_cls_stf_id', $update_id)->update($data);
                
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

        $schStaff = SclstaffClassMasterModel::leftJoin('at_school_staff_master','at_school_staff_master.scl_stf_id','=','at_school_class_staff_master.scl_stf_id')->where('at_school_class_staff_master.sch_cls_stf_id',$id)->get();

        $classId = SclstaffClassMappModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_staff_mapping.sch_cls_id')->where('at_school_class_staff_mapping.sch_cls_stf_id',$id )->groupBy('at_school_class_staff_mapping.sch_cls_id')->orderBy('at_school_class_staff_mapping.sch_cls_id')->get(array('at_school_class_staff_mapping.sch_cls_id','at_school_class_master.sch_class',DB::raw('""  as sectionVal')));

        foreach ($classId as $key => $value) {

        $clsId=$value->sch_cls_id;

        $getSectionVal=SclstaffClassMappModel::leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_staff_mapping.sec_id')->where(['sch_cls_stf_id'=>$id,'sch_cls_id'=>$clsId])->orderBy('at_school_section_master.section_name','ASC')->get(array(DB::raw("GROUP_CONCAT(at_school_section_master.section_name ) as `sectionName`")));

        $value->sectionVal=$getSectionVal[0]->sectionName;

        }
 
        return view('backend.schstfclsmapping.show',compact('schStaff','classId'));
       
   }


}

<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\SchSectionModel;
use App\Model\backend\SchClassModel;
use App\Model\backend\SchClsSectionMapModel;
use Redirect;
use Session;
use DB;


class SchClassSectionMappingController extends Controller
{
     
    public function index(){
        $uPermission=getUserPermission('schsectionmap','school');

        if($uPermission[0]->file_add==0 && $uPermission[0]->file_edit==0  && $uPermission[0]->file_view==0   && $uPermission[0]->file_delete==0 )
        {
        return view('backend.pageDined');
        }

        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
        $schsectionAll = SchClsSectionMapModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_section_mapping.sch_cls_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_section_mapping.sec_id')->where('at_school_class_section_mapping.active',0)->get();

        } else {

        $schsectionAll = SchClsSectionMapModel::leftJoin('at_school_class_master','at_school_class_master.sch_cls_id','=','at_school_class_section_mapping.sch_cls_id')->leftJoin('at_school_section_master','at_school_section_master.sec_id','=','at_school_class_section_mapping.sec_id')->where('at_school_class_section_mapping.active',1)->get();


        }

        $active_count = SchClsSectionMapModel::where('active', 1)->count();
        $inactive_count = SchClsSectionMapModel::where('active', 0)->count();
        return view('backend.schClsSectionsMap.index', compact('schsectionAll', 'active_count', 'inactive_count','uPermission'));
    }

    public function create() {

    $uPermission=getUserPermission('schsectionmap','school');
    if($uPermission[0]->file_add==0) /**Check the file permission**/
    { return view('backend.pageDined');}
    $classId = SchClassModel::select('sch_cls_id','sch_class','active')->where('active',1)->get();
    $sectionId = SchSectionModel::select('sec_id','section_name','active')->where('active',1)->get();
    return view('backend.schClsSectionsMap.add',compact('classId','sectionId'));
    }
    

    public function store(Request $request) {
        $uPermission=getUserPermission('schsectionmap','school');
        if($uPermission[0]->file_add==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs=$request->all();
        $clsId=$inputs['selectCls'];
        $selectSec=$inputs['selectSec'];

        $chkCnt = SchClsSectionMapModel::where(['sch_cls_id'=>$clsId,'sec_id'=>$selectSec])->get()->count();

        if($chkCnt==0)
        {

        $sectiondata = SchClsSectionMapModel::insert([
        'sch_cls_id'=>$clsId,   
        'sec_id' =>$selectSec,
        ]);

        Session::flash('message', 'Class section mapped successfully .');
        Session::flash('alert-class', 'alert-success');
        return Redirect::to('admin/schsectionmap/add');

        }
        else
        {

        Session::flash('message', 'Class section already exists .');
        Session::flash('alert-class', 'alert-warning');
        return redirect('admin/schsectionmap/add')->withInput($request->all());

        }
   
}

     

    public function edit($cus_id){
    }

    public function update(Request $request) {
        
    }

    public function actionupdate(Request $request) {

         $uPermission=getUserPermission('schsectionmap','school');
        if($uPermission[0]->file_delete==0) /**Check the file permission**/
        { return view('backend.pageDined');}
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Class section mapped successfully inactivated.";
            $redirect_value = "admin/schsectionmap";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Class section mapped successfully activated.";
            $redirect_value = "admin/schsectionmap/?token=inactive";
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
                SchClsSectionMapModel::select('*')->where('sch_cls_sec_map_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }
    public function show($id) {
         $uPermission=getUserPermission('schsectionmap','school');
        if($uPermission[0]->file_view==0) /**Check the file permission**/
        { return view('backend.pageDined');}
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

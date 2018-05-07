<?php

namespace App\Http\Controllers\backend;

use Illuminate\Http\Request;
use Intervention\Image\Facades\Image as Image;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use App\Model\backend\CustomerModel;
use Redirect;
use Session;
use DB;


class CustomerController extends Controller
{
     
    public function index(){
        $input = Input::all(); 
        if (isset($input['token']) && $input['token'] == 'inactive') {
            $CustomerAll = CustomerModel::where('active', 0)->get();
        
        } else {
            $CustomerAll = CustomerModel::where('active', 1)->get();

        }
     	
        $active_count = CustomerModel::where('active', 1)->count();
        $inactive_count = CustomerModel::where('active', 0)->count();
       return view('backend.customer.index', compact('CustomerAll', 'active_count', 'inactive_count'));
    }

    public function create() {
        /***Generate random key for customer-id **/
        $randNo = 'atnetwork_'.$this->generateRandomString(6);  
        return view('backend.customer.add',compact('randNo'));
    }
    

      public function store(Request $request) {
   
     	$inputs=$request->all();
        $email=$inputs['emailid'];
        $cId=$inputs['cid'];
        
         $GetMailUnique = CustomerModel:: where(['email_id' => "$email"])->get()->count();
         $GetCusId = CustomerModel:: where(['c_id' => "$cId"])->get()->count();

         if($GetMailUnique>0)
         {
            Session::flash('message', 'Mail-id already exits');
            Session::flash('alert-class', 'alert-warning');

             return redirect('admin/customer/add')->withInput($request->all());

         }
         elseif($GetCusId>0)
         {
            Session::flash('message', 'Plese click submit button again..');
            Session::flash('alert-class', 'alert-warning');
            return redirect('admin/customer/add')->withInput($request->all());
         }
         else
         {

            $data = CustomerModel::create([
            'c_name' => $inputs['iname'],
            'email_id' => $inputs['emailid'],
            'c_id' =>$inputs['cid'],
             
        ]);     

       Session::flash('message', 'Customer has been added successfully');
       Session::flash('alert-class', 'alert-success');
       return Redirect::to('admin/customer/add');
    }
}

     

    public function edit($cus_id){  
    	$customer = CustomerModel::where(['cus_id' => "$cus_id"])->get();
        return view('backend.customer.edit', compact('customer'));
    }

    public function update(Request $request) {
        $id = $request->get('hidid');

         
        $cName=$request->get('iname');
        $cMail=$request->get('emailid');
       
        $GetMailUnique = CustomerModel:: where(['email_id' => "$cMail"])->whereNotIn('cus_id', [$id])->get()->count();
        if($GetMailUnique==0)
        {

            $updateCus=CustomerModel::where('cus_id', $id)
                      ->update(["c_name"=> "$cName","email_id"=>"$cMail"]);

            Session::flash('message', 'Updated successfully');
            Session::flash('alert-class', 'alert-success');
             return redirect('admin/customer') ;          


        }
        else
        {

          Session::flash('message', 'Mail is already exists');
            Session::flash('alert-class', 'alert-warning');
        }

        
    }

    public function actionupdate(Request $request) {
        $inputs = $request->all();
        $updated_ids_value = explode(",", $inputs['hid_selected_ids']);
        $column_name = "active";
        $action = $inputs['action'];
        if ($action == 'Inactive') {
            
            $action_value = "0";
            $msg_value = "Customer has been successfully inactivated.";
            $redirect_value = "admin/customer";
            
        } else if ($action == 'Active') {
            
            $action_value = "1";
            $msg_value = "Customer has been successfully activated.";
            $redirect_value = "admin/customer/?token=inactive";
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
                CustomerModel::select('*')->where('cus_id', $update_id)->update($data);
                
            }  
        }
        Session::flash('message', $msg_value);
        Session::flash('alert-class', 'alert-success');
        return Redirect::to($redirect_value);
    }

    public function check_mail(Request $request)
    {
        $inputs = $request->all();       
        $cMail = $inputs['emailid'];
        $id = isset($inputs['id'])?$inputs['id']:'';

        $GetMailUnique = CustomerModel:: where(['email_id' => "$cMail"])->whereNotIn('cus_id', [$id])->get()->count();
        //$mailExits = CustomerModel::where('email_id', $name)->where('cus_id', '!=', $id)->first();
        // $check_name = Testimonials::where('name', $name)->where('id', '!=', $id)->first();
        if($GetMailUnique==0){
             
                return "true";
            }
            else{
                return "false";
            }
         
         
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

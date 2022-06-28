<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Electronicinvoice;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\CustomerAddFormRequest;

class CustomersController extends Controller
{

    public function GetListCustomers()
    {
        $Customers = Customer::where('branch_id',auth()->user()->branch_id)->get();
        
        return response()->json([
            'status'=>true,
            'code'=>200,
            'Customers' =>  $Customers,
        ],200);
    }

    public function ShowCustomer(Request $request)
    {
        if(!empty($request->Customer_id)){
            $Customer = Customer::where('id',$request->Customer_id)->first();
            $Invoices = Electronicinvoice::where('customer_id',$request->Customer_id)->get();
            $Cars     = Car::where('customer_id',$request->Customer_id)->get();
    
            $this->data = [
                'value' => true,
                'data' => [
                        'Customer'  =>  $Customer,
                        'Car'       =>  $Cars,
                        'Invoice'   =>  $Invoices,
                ],
                        'code'      => 200,
            ];
    
            return response()->json($this->data, $this->data['code']);
        }else{
            return response()->json(['status'=>false,'message'=>'There is something wrong','code'=>400],400);
        }
        
    }

    public function AddCustomer(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|regex:/^[(a-zA-Z\s)\p{L}]+$/u|max:50',
            'mail' => 'required|email|unique:customers,mail',
            'phone' => 'required|min:9|max:9|digits:9|regex:/^[- +()]*[0-9][- +()0-9]*$/|unique:customers,phone',
            'address' => 'required', 
        
        ]);
        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }
        $name = $request->name;
		$address = $request->address;
		$phone = $request->phone;
		$mail = $request->mail;

		$customer = new Customer;
		$customer->name = $name;
		$customer->phone = $phone;
		$customer->mail = $mail;
		$customer->address = $address;
		$customer->branch_id = Auth::user()->branch_id;

        $customer->save();

        return response()->json(['status'=>true,
                            'message'=>trans('customer created successfully'),
                            'code'=>201,
                            'data'=>$customer,
                        ],201);
    }
    
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Car;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class CarController extends Controller
{
    public function AddCar(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'manufacturing' => 'required|regex:/^[A-Za-z]+$/',            
            'registration' => 'required',            
            'manufacturing_date' => 'required',            
            'chassis' => 'required|min:17|max:17',            
            'model' => 'required',            
            'customer_id'=> 'required',  
            'reg_chars'=>'required|regex:/^[A-Za-z]+$/' 
        
        ]);

        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }

        $manufacturing = $request->manufacturing;
		$registration = $request->registration;
		$manufacturing_date = $request->manufacturing_date;
		$chassis = $request->chassis;
		$model = $request->model;
        $customer_id=$request->customer_id;
        $reg_chars=$request->reg_chars;
	    
        $car = new Car;
		$car->manufacturing = $manufacturing;
		$car->registration = $registration;
		$car->manufacturing_date = $manufacturing_date;
		$car->chassis = $chassis;
		$car->model = $model;
		$car->customer_id=$customer_id;
		$car->reg_chars=$reg_chars;

		$car->save();

        return response()->json(['status'=>true,
        'message'=>trans('car created successfully'),
        'code'=>201,
        'data'=>$car,
        ],201);
			
    }
}

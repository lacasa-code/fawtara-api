<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Electronicinvoice;


use App\Http\Controllers\Controller;

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

}

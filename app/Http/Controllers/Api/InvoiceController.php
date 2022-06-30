<?php

namespace App\Http\Controllers\Api;

use App\Models\Branch;
use App\Branch as AppBranch;
use Illuminate\Http\Request;
use App\Models\Invoiceservice;
use App\Models\Electronicinvoice;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    //list all final invoices
    public function final_invoice(Request $request)
    {
        $page_size=$request->page_size ?? 10 ;

        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
                                ->where('final',1)
                                ->whereNull('deleted_at')
                                ->orderBy('id','DESC')
                                ->paginate($page_size);
            return response()->json([
                        'status'=>true,
                        'message'=>'final invoices have been shown successfully',
                        'code'=>200,
                        'data'=>$invoice,
                     ],200);

    }

    //list all pending invocies
    public function pending_invoice(Request $request)
    {
        $page_size=$request->page_size ?? 10 ;

        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
                                ->where('final',0)
                                ->whereNull('deleted_at')
                                ->orderBy('id','DESC')
                                ->paginate($page_size);

            return response()->json([
                        'status'=>true,
                        'message'=>'Pending invoices have been shown successfully',
                        'code'=>200,
                        'data'=>$invoice,
                     ],200);

    }

    //show one invoice 
    public function show_final_invoice($id)
    {
        $invoice=Electronicinvoice::where('id',$id)->where('final',1)->first();
        
        return response()->json([
            'status'=>true,
            'message'=>'invoice have been shown successfully',
            'code'=>200,
            'data'=>$invoice,
         ],200);

    }

    public function show_pending_invoice($id)
    {
        $invoice=Electronicinvoice::where('id',$id)->where('final',0)->first();
        
        return response()->json([
            'status'=>true,
            'message'=>'invoice have been shown successfully',
            'code'=>200,
            'data'=>$invoice,
         ],200);

    }

    public function preview_final_invoice($id)
    {
        $invoice=Electronicinvoice::where('electronicinvoices.id',$id)->where('final',1)
                ->join('invoiceservices','invoiceservices.invoice_id','electronicinvoices.id')
                ->first();

        return response()->json([
                    'status'=>true,
                    'message'=>'invoice has been shown successfully',
                    'code'=>200,
                    'data'=>$invoice,
                 ],200);


    }

    public function invoiced_pending($id)
    {
        $invoice=Electronicinvoice::where('id',$id)->where('final',0)->first();
        $invoice->final = 1 ;
        return response()->json([
            'status'=>true,
            'message'=>'pending invoice has been invoiced successfully',
            'code'=>200,
            'data'=>$invoice,
        ],200);
    }

    public function create(Request $request)
    {
		
        $validator = Validator::make($request->all(), [
            'customer_address' => 'required|string', 			
			'Customer'           => 'required|string', 
			'customer_vat'       => 'nullable', 
			'customer_phone'     => 'required|integer|digits_between:9,9|starts_with:5',
			'customer_po_number' => 'nullable|integer', 
			'quotation_number'   => 'nullable', 
			'Discount' => 'nullable|numeric|min:1',
			'model_name'    => 'required|string', 
			'chassis_no'    => 'required', 
			'manufacturer'  => 'required|integer',
			'reg_chars'     => 'required|string|min:1|max:3', 
			'registeration' => 'required|integer|digits_between:1,4', 
			'fleet_number' => 'required|string',   // manufacturer
			'meters_reading' => 'required|numeric|min:1',
			'job_open_date' => 'required',
			'delivery_date' => 'required',
            'customer_id'=>'required', 
        
        ]);
        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }

        $branch_id = auth()->user()->branch_id;
        $services_sum    = 0; //array_sum($request->service_value);
		$paid_amount     = 0;
		$reg_chars       = str_replace(' ', '', $request->reg_chars);
		
	    $invoice = new Electronicinvoice;
    
		    $invoice->Invoice_type       = 'service';
            $invoice->Invoice_Number     = $request->Invoice_Number;
            $invoice->Customer           = $request->Customer;
            $invoice->customer_address   = $request->customer_address;
            $invoice->customer_vat       = $request->customer_vat;
            $invoice->customer_phone     = $request->customer_phone;
            $invoice->phone_code         = $request->phone_code;
           // $invoice->customer_number    = $request->customer_number;
            $invoice->Job_card           = $request->Job_card;
            $invoice->quotation_number   = $request->quotation_number;
            $invoice->customer_po_number = $request->customer_po_number;
            $invoice->branch_name        = $request->branch_name;
            $invoice->meters_reading     = $request->meters_reading;
            $invoice->fleet_number       = $request->fleet_number;
            $invoice->registeration      = $request->registeration;
            $invoice->reg_chars          = $reg_chars; 
            $invoice->manufacturer       = $request->manufacturer;
            $invoice->chassis_no         = $request->chassis_no;
            $invoice->model_name         = $request->model_name;
            $invoice->customer_id        = $request->user_id;
            $invoice->Date               = $request->Date;
            $invoice->Discount           = $request->Discount;
            $invoice->job_open_date      = $request->job_open_date;
            $invoice->delivery_date      = $request->delivery_date;
            $invoice->Details            = $request->Details;
            $invoice->vat_number         = $request->vat_number;
            $invoice->Status             = $request->Status;
            $invoice->Payment_type       = $request->Payment_type;
            //$invoice->services_sum       = $services_sum;

            //$invoice->total_amount       = $services_sum;
            //$invoice->grand_total        = $paid_amount;
            $invoice->tax                = 15;
            //$invoice->paid_amount        = $paid_amount;
            $invoice->branch_id          = auth()->user()->branch_id;

		    $invoice->save();

            return response()->json([
                'status'=>true,
                'message'=>trans('service created successfully'),
                'code'=>200,
                'data' =>  $invoice,
            ],200);
           


    }

    public function service_name(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_name' => 'required|string',
            'service_value' => 'required|numeric',
            'qty' => 'required|integer',
            'invoice_id' => 'required', 
        
        ]);
        if ($validator->fails()) 
        {
            return response()->json(['status'=>false,'message'=>$validator->errors(),'code'=>400],400);
        }
        $service = new Invoiceservice();
        $service->service_name=$request->service_name;
        $service->service_value=$request->service_value;
        $service->qty = $request->qty;
        $service->invoice_id= $request->invoice_id;
        $service->sub_total = $request->qty * $request->service_value ;
        $service->save();
        
        $sub_sum = Invoiceservice::where('invoice_id', $request->invoice_id)->sum('sub_total');
        $invoice = Electronicinvoice::where('id', $request->invoice_id)->first();

        if ($invoice->Discount > 0) {
            $percent = ($sub_sum * $request->Discount ) / 100;
            $total_amount = $sub_sum - $percent;
        }else{
            $total_amount = $sub_sum;
        }

        $tax_percent = ($total_amount * 15) / 100;
        $updated_amount = $total_amount + $tax_percent;

        $invoice->services_sum       = $total_amount;
        $invoice->total_amount       = $total_amount;
        $invoice->paid_amount        = $updated_amount;
        $invoice->save();
        return response()->json([
            'status'=>true,
            'message'=>trans('service created successfully'),
            'code'=>200,
            'data' =>  $service,
        ],200);

    
    }

    public function search_final(Request $request)
    {
        $keyword = $request->input('keyword');
        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
            ->where('final',1)
            ->where(function ($query) use($keyword) {
            $query->where('Invoice_Number', 'like', '%' . $keyword . '%')
               ->orWhere('chassis_no', 'like', '%' . $keyword . '%')
               ->orWhere('created_at', 'like', '%' . $keyword . '%')
               ->orWhere('total_amount', 'like', '%' . $keyword . '%')
               ->orWhere('paid_amount', 'like', '%' . $keyword . '%')
               ->orWhere('reg_chars', 'like', '%' . $keyword . '%')
               ->orWhere('registeration', 'like', '%' . $keyword . '%')
               ->orWhere('Status', 'like', '%' . $keyword . '%')
               ->orWhere('Customer', 'like', '%' . $keyword . '%');
        
          })
            ->get();
        if (!$invoice)
        {
            return response()->json(['status'=>false,
                'message'=>trans('No data found '),
                'code'=>404,
                ],404);
        }
            
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$invoice,
            ],200);
    }

    public function search_pending(Request $request)
    {
        $keyword = $request->input('keyword');
        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
            ->where('final',0)
            ->where(function ($query) use($keyword) {
            $query->where('Invoice_Number', 'like', '%' . $keyword . '%')
               ->orWhere('chassis_no', 'like', '%' . $keyword . '%')
               ->orWhere('created_at', 'like', '%' . $keyword . '%')
               ->orWhere('total_amount', 'like', '%' . $keyword . '%')
               ->orWhere('paid_amount', 'like', '%' . $keyword . '%')
               ->orWhere('reg_chars', 'like', '%' . $keyword . '%')
               ->orWhere('registeration', 'like', '%' . $keyword . '%')
               ->orWhere('Status', 'like', '%' . $keyword . '%')
               ->orWhere('Customer', 'like', '%' . $keyword . '%');
        
          })->get();
        if (!$invoice)
        {
            return response()->json(['status'=>false,
                'message'=>trans('No data found '),
                'code'=>404,
                ],404);
        }
            
        return response()->json([
                'status'=>true,
                'message'=>'search result',
                'code'=>200,
                'data'=>$invoice,
            ],200);
    }

    public function encode_date($id )
    {
        $invoice = Electronicinvoice::where('id',$id)->first();
        $invoice_datetimez     = $invoice->Date .' '.$invoice->created_at->format('H:i:s');
        $seller_name   = $invoice->branch_name;
        //$branch=Branch::where('id',$invoice->branch_id)->first();
        $vat_registration_number    = $invoice->branch->vat_number;
        $invoice_amount    = $invoice->paid_amount;
        $invoice_tax_amount = ( $invoice->total_amount * 15 ) / 100;

        $result = chr(1) . chr( strlen($seller_name) ) . $seller_name;
        $result.= chr(2) . chr( strlen($vat_registration_number) ) . $vat_registration_number;
        $result.= chr(3) . chr( strlen($invoice_datetimez) ) . $invoice_datetimez;
        $result.= chr(4) . chr( strlen($invoice_amount) ) . $invoice_amount;
        $result.= chr(5) . chr( strlen($invoice_tax_amount) ) . $invoice_tax_amount;
        $qr = base64_encode($result); 

        return response()->json([
            'status'=>true,
            'message'=>'encoded data has been generated successfully',
            'code'=>200,
            'data'=>$qr,
         ],200);
    }

}

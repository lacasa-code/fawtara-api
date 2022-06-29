<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Electronicinvoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //list all final invoices
    public function final_invoice()
    {
        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
                                ->where('final',1)
                                ->whereNull('deleted_at')
                                ->orderBy('id','DESC')
                                ->get();

            return response()->json([
                        'status'=>true,
                        'message'=>'final invoices have been shown successfully',
                        'code'=>200,
                        'data'=>$invoice,
                     ],200);

    }

    //list all pending invocies
    public function pending_invoice()
    {
        $invoice=Electronicinvoice::where('branch_id',auth()->user()->branch_id)
                                ->where('final',0)
                                ->whereNull('deleted_at')
                                ->orderBy('id','DESC')
                                ->get();

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
		$this->validate($request, [
			'customer_address' => 'required|string', 
			//'service_name.0'   => 'required',
			//'service_value.0'   => 'required|numeric|min:1',
			//'qty.0'   => 'required|integer|min:1',
			
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
			/*'service_name.1'   => 'nullable',
			'service_value.1'   => 'nullable|numeric',
			'service_name.2'   => 'nullable',
			'service_value.2'   => 'nullable|numeric',
			'service_name.3'   => 'nullable',
			'service_value.3'   => 'nullable|numeric',
			'service_name.4'   => 'nullable',
			'service_value.4'   => 'nullable|numeric',*/
			'job_open_date' => 'required',
			'delivery_date' => 'required',
		]);
        $branch_id = auth()->user()->branch_id;

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

}

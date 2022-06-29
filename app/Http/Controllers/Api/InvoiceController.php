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
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Electronicinvoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
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
}
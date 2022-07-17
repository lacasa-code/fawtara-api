<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Electronicinvoice;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;

class ReportController extends Controller
{

    public function total_reports(Request $request)
    {
        $Total_Invoices = Electronicinvoice::where(['branch_id' => auth()->user()->branch_id,'final' => 1,'deleted_at' => NULL])->count();
       
        $page_size = $request->page_size ?? 10 ;

        $invoices=Electronicinvoice::where('branch_id',auth()->user()->branch_id)->where('final',1)->whereNull('deleted_at')->orderBy('id','DESC')->paginate($page_size);
           
        return response()->json([
            'status'=>true,
            'code'=>200,
            'Total_Invoices' =>  $Total_Invoices,
            'data'=>$invoices,
        ],200);

    }

    public function total_filter_date(Request $request)
    {
        $start = Carbon::parse($request->start_date)->toDateTimeString();

        $end = Carbon::parse($request->end_date)->toDateTimeString();

        $Total_Invoices = Electronicinvoice::where(['branch_id' => auth()->user()->branch_id,'final' => 1,'deleted_at' => NULL])->whereBetween('Date', [$start, $end])->count();
        $page_size = $request->page_size ?? 10 ;

        $invoices=Electronicinvoice::where('branch_id',auth()->user()->branch_id)->where('final',1)->whereNull('deleted_at')->whereBetween('Date', [$start, $end])->orderBy('id','DESC')->paginate($page_size);
           
        return response()->json([
            'status'=>true,
            'message'=>'filter result',
            'code'=>200,
            'Total_Invoices'=> $Total_Invoices,
            'data'=>$invoices,
         ],200);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class FITransactionsController extends Controller
{
    //
    public function index() {
        $ttdate = new Carbon('last day of last month');
        // $tdate = $ttdate->addDay()->toDateString();
        $tdate = $ttdate->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

        $fdate = Input::get('fdate', $fdate);
        $tdate = Input::get('tdate', $tdate);

        $acc = Input::get('acc');
        $amt = Input::get('amt');
        $orderid = Input::get('orderid');
        $material = Input::get('material');
        $vendor = Input::get('vendor');
        $clearing = Input::get('clearing');
        $ttype = Input::get('ttype');
        $remark = Input::get('remark');
        $fromdoc = Input::get('fromdoc');
        $ba = Input::get('ba');
        $brand = Input::get('brand');

        $cfdate = Input::get('cfdate', $fdate);
        $ctdate = Input::get('ctdate', $tdate);

        // dd($isPosted);

        $bas = [1,2];
        $mps = DB::table('atr')->distinct()->get(['mp']);
        
        $tdate1 = new Carbon($tdate);
        $tdate1->endOfDay();
        $qtdate = $tdate1->toDateTimeString();

        $ctdate1 = new Carbon($ctdate);
        $ctdate1->endOfDay();
        $qctdate = $ctdate1->toDateTimeString();

        $fromdocs = DB::table('atr as a')
                        ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
                        ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
                        ->distinct()->get(['fromdoc']);

        $vendors = DB::table('atr as a')
            ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
            ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc',$fromdoc);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['mp as vendor']);

        $ttypes = DB::table('atr as a')
            ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
            ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc',$fromdoc);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['ttype']);

        $brands = DB::table('atr')->distinct()->get(['brand']);

        
        $accs = DB::table('gacc')->distinct()->get(['accid']);

// dd($qtdate,$qctdate);
        // $fitransactions = DB::table('atr')->where('fdate','=',$fdate)->orderby('fromdoc')->orderby('transaction_type')->orderby('amount_type')->orderby('tdate','desc')->simplePaginate(10);
        $fitransactions = DB::table('atr as a')
            ->leftJoin('dist as d', 'd.aid','=','a.keyv')
            ->where('pdate', '>=', $fdate)->where('pdate', '<=', $qtdate)
            ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc', $fromdoc); })
            ->when($acc, function($query) use ($acc) { return $query->where('acc', $acc); })
            ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amt)=?', [$amt]); })
            ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
            ->when($material, function($query) use ($material) { return $query->where('material', $material); })
            ->when($clearing, function($query) use ($clearing) { return $query->where('clearing', $clearing); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
            ->when($remark, function($query) use ($remark) { return $query->where('remark','LIKE', '%'.$remark.'%'); })
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($brand, function($query) use ($brand) { return $query->where('brand', $brand); })
            ->orderby('pdate', 'desc')
            ->orderby('keyv')
            ->orderby('ba')
            ->simplePaginate(10);
        // dd($fitransactions);
            // ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('fitransactions.list', compact('fdate','tdate','acc','amt','orderid','material','vendor','clearing','ttype','remark','fromdoc','ba','brand','cfdate','ctdate','bas','mps','fromdocs','ttypes','brands','vendors','accs','fitransactions') );//
    }

    public function show($id)
    {
        //
		$columns = DB::getSchemaBuilder()->getColumnListing('atr');
		// dd($columns);
        $fitransactions = DB::table('atr')
	        ->where('keyv', $id)
	        ->get();

        return view('fitransactions.show', compact('columns','fitransactions') );//
    }
}

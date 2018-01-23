<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;


class ApayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $ttdate = new Carbon('last day of last month');
        $tdate = $ttdate->addDay()->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->addDay()->toDateString();

        $fdate = Input::get('fdate', '2017-01-01');
        $tdate = Input::get('tdate', $tdate);
        $amt = Input::get('amt');

        $ttype = Input::get('ttype');
        $atype = Input::get('atype');
        $adesc = Input::get('adesc');

        $ba = Input::get('ba');
        $sku = Input::get('sku');
        $isPosted = Input::get('isPosted',2);
        // // dd($isPosted);

        $bas = DB::table('apay2')->distinct()->whereNotNull('ba')->orderby('ba')->get(['ba']);
        $ttypes = DB::table('apay2')->distinct()->get(['transaction_type as ttype']);
        $atypes = DB::table('apay2')->distinct()->get(['amount_type as atype']);
        $adescs = DB::table('apay2')->distinct()->get(['amount_description as adesc']);



        $apays = DB::table('apay2 AS a')
            ->select('a.*','r.cnt')
            ->leftjoin(DB::raw('(SELECT fromdoc,transaction_type,amount_type,amount_description,ttype,count(*) cnt FROM apay2_acc WHERE fromdoc="apay2" group by fromdoc,transaction_type,amount_type,amount_description,ttype) r'), function($join)
            {
                $join->on('a.transaction_type','=','r.transaction_type')
                ->on('a.amount_type','=','r.amount_type')
                ->on('a.amount_description','=','r.amount_description')
                ;
            })
            ->where('currency', '')
            ->where('posted_date', '>=', $fdate)
            ->where('posted_date', '<', $tdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('a.transaction_type', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->where('amount', $amt)->orWhere('amount',$amt*-1); })
            ->when($isPosted, function($query) use($isPosted) {
                    if($isPosted == 1){
                        return $query->whereNotNull('postingflag');
                    }elseif($isPosted == 2){
                        return $query->whereNull('postingflag');
                    }
                }
            )
            ->when($atype, function($query) use ($atype){return $query->where('a.amount_type', $atype); })
            ->when($adesc, function($query) use ($adesc){return $query->where('a.amount_description', $adesc); })
            ->when($sku, function($query) use ($sku) { return $query->where('sku','LIKE', '%'.$sku.'%'); })
            ->orderby('ba')
            ->orderby('posted_date_time')
            ->orderby('no')
            ->simplePaginate(10);

            // ->when($sku, function($query) use ($sku) { return $query->where('sku', $sku); })
                // ->on('b.fromdoc', '=', 'a.fromdoc')
            // ->when($request->customer_id, function($query) use ($request){return $query->where('customer_id', $request->customer_id); })
            // ->when($request->customer_id, function($query) use ($request){return $query->where('customer_id', $request->customer_id); })

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('apays.list', compact('apays','fdate','tdate','isPosted','ba','ttype','amt','sku','bas','ttypes','atypes','adescs','atype','adesc'));//,'mps','vendor','keyword') );//'fdate','tdate','ba',,'skus'
    }

    public function singlelist()
    {
        //
        $ttdate = new Carbon('last day of last month');
        $tdate = $ttdate->addDay()->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->addDay()->toDateString();

        $fdate = Input::get('fdate', '2017-01-01');
        $tdate = Input::get('tdate', $tdate);
        $amt = Input::get('amt');

        $ttype = Input::get('ttype');
        $atype = Input::get('atype');
        $adesc = Input::get('adesc');

        $ba = Input::get('ba');
        $sku = Input::get('sku');
        $isPosted = Input::get('isPosted',2);
        // // dd($isPosted);

        $bas = DB::table('apay2')->distinct()->whereNotNull('ba')->orderby('ba')->get(['ba']);
        $ttypes = DB::table('apay2')->distinct()->get(['transaction_type as ttype']);
        $atypes = DB::table('apay2')->distinct()->get(['amount_type as atype']);
        $adescs = DB::table('apay2')->distinct()->get(['amount_description as adesc']);



        $apays = DB::table('apay2')
            ->select(DB::raw('min(settlement_id) as settlement_id, min(ba) as ba, min(posted_date) as posted_date, max(posted_date) as tdate, min(transaction_type) as transaction_type, min(amount_type) as amount_type, min(amount_description) as amount_description, count(*) as cnt, sum(amount) as amount, min(sku) as sku, sum(quantity_purchased) as quantity_purchased, min(order_id) as order_id, min(order_item_code) as order_item_code,min(no) as no'))
            ->where('currency','=','')
            ->where('posted_date', '>=', $fdate)
            ->where('posted_date', '<', $tdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('transaction_type', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->where('amount', $amt)->orWhere('amount',$amt*-1); })
            ->when($isPosted, function($query) use($isPosted) {
                    if($isPosted == 1){
                        return $query->whereNotNull('postingflag');
                    }elseif($isPosted == 2){
                        return $query->whereNull('postingflag');
                    }
                }
            )
            ->when($atype, function($query) use ($atype){return $query->where('amount_type', $atype); })
            ->when($adesc, function($query) use ($adesc){return $query->where('amount_description', $adesc); })
            ->when($sku, function($query) use ($sku) { return $query->where('sku','LIKE', '%'.$sku.'%'); })
            ->groupBy('transaction_type', 'amount_type','amount_description')
            ->simplePaginate(10);

        return view('apays.list', compact('apays','fdate','tdate','isPosted','ba','ttype','amt','sku','bas','ttypes','atypes','adescs','atype','adesc'));//,'mps','vendor','keyword') );//'fdate','tdate','ba',,'skus'
            // ->orderby('ba')
            // ->orderby('posted_date_time')
            // ->orderby('no')
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}

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
        $tdate = $ttdate->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

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

        $tdate1 = new Carbon($tdate);
        $tdate1->endOfDay();
        $qtdate = $tdate1->toDateTimeString();


        $apays = DB::table('apay2 AS a')
            ->select('a.*','r.cnt','r.cnt as cnt2')
            ->leftjoin(DB::raw('(SELECT fromdoc,transaction_type,amount_type,amount_description,ttype,count(*) cnt FROM apay2_acc WHERE fromdoc="apay2" group by fromdoc,transaction_type,amount_type,amount_description,ttype) r'), function($join)
            {
                $join->on('a.transaction_type','=','r.transaction_type')
                ->on('a.amount_type','=','r.amount_type')
                ->on('a.amount_description','=','r.amount_description')
                ;
            })
            ->where('currency', '')
            ->where('posted_date', '>=', $fdate)->where('posted_date', '<=', $qtdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('a.transaction_type', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amount)=?', [$amt]); })
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


        $apays = DB::table('apay2 as a2')
            ->select(DB::raw('min(settlement_id) as settlement_id, min(ba) as ba, min(posted_date) as posted_date, max(posted_date) as tdate, min(a2.transaction_type) as transaction_type, min(a2.amount_type) as amount_type, min(a2.amount_description) as amount_description, count(*) as cnt, sum(amount) as amount, min(sku) as sku, sum(quantity_purchased) as quantity_purchased, min(order_id) as order_id, min(order_item_code) as order_item_code,min(no) as no, min(a.cnt2) as cnt2'))
            ->leftjoin(DB::raw('(SELECT fromdoc,transaction_type,amount_type,amount_description,ttype,count(*) cnt2 FROM apay2_acc WHERE fromdoc="apay2" group by fromdoc,transaction_type,amount_type,amount_description,ttype) a'), function($join)
            {
                $join->on('a2.transaction_type','=','a.transaction_type')
                ->on('a2.amount_type','=','a.amount_type')
                ->on('a2.amount_description','=','a.amount_description')
                ;
            })
            ->where('currency','=','')
            ->where('posted_date', '>=', $fdate)
            ->where('posted_date', '<', $tdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('a2.transaction_type', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->where('amount', $amt)->orWhere('amount',$amt*-1); })
            ->when($isPosted, function($query) use($isPosted) {
                    if($isPosted == 1){
                        return $query->whereNotNull('postingflag');
                    }elseif($isPosted == 2){
                        return $query->whereNull('postingflag');
                    }
                }
            )
            ->when($atype, function($query) use ($atype){return $query->where('a2.amount_type', $atype); })
            ->when($adesc, function($query) use ($adesc){return $query->where('a2.amount_description', $adesc); })
            ->when($sku, function($query) use ($sku) { return $query->where('sku','LIKE', '%'.$sku.'%'); })
            ->groupBy('a2.transaction_type', 'a2.amount_type','a2.amount_description')
            ->simplePaginate(10);

        return view('apays.list', compact('apays','fdate','tdate','isPosted','ba','ttype','amt','sku','bas','ttypes','atypes','adescs','atype','adesc'));//,'mps','vendor','keyword') );//'fdate','tdate','ba',,'skus'
            // ->orderby('ba')
            // ->orderby('posted_date_time')
            // ->orderby('no')
    }

    public function showdeposit()
    {
        //
        $ttdate = new Carbon('last day of last month');
        $tdate = $ttdate->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

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

        $tdate1 = new Carbon($tdate);
        $tdate1->endOfDay();
        $qtdate = $tdate1->toDateTimeString();


        $apays = DB::table('apay2 AS a')
            ->select('a.*','r.cnt','r.cnt as cnt2')
            ->leftjoin(DB::raw('(SELECT fromdoc,transaction_type,amount_type,amount_description,ttype,count(*) cnt FROM apay2_acc WHERE fromdoc="apay2" group by fromdoc,transaction_type,amount_type,amount_description,ttype) r'), function($join)
            {
                $join->on('a.transaction_type','=','r.transaction_type')
                ->on('a.amount_type','=','r.amount_type')
                ->on('a.amount_description','=','r.amount_description')
                ;
            })
            ->where('currency', '')
            ->where('posted_date', '>=', $fdate)->where('posted_date', '<=', $qtdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('a.transaction_type', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amount)=?', [$amt]); })
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
        return view('apays.deposits', compact('apays','fdate','tdate','isPosted','ba','ttype','amt','sku','bas','ttypes','atypes','adescs','atype','adesc'));//,'mps','vendor','keyword') );//'fdate','tdate','ba',,'skus'
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

    public function post()
    {
        //
        $ttdate = new Carbon('last day of last month');
        $tdate = $ttdate->addDay()->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

        $fromdoc = Input::get('fromdoc');
        $fdate = Input::get('fdate', $fdate);
        $tdate = Input::get('tdate', $tdate);

        $ttype = Input::get('ttype');
        $atype = Input::get('atype');
        $adesc = Input::get('adesc');
        $cdate = \Carbon\Carbon::now();
        $udate = \Carbon\Carbon::now();


        if($fromdoc){

            $res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,qty,orderid,itemid,mp,clearing,ttype,fromdoc,ba, remark, brand, material, created_at,updated_at) SELECT aa.aseq,ap.no,posted_date_time,acc, if((acc='ainv' or acc='pcgs'), ifnull(m.map,5)*greatest(quantity_purchased,1)*dir, amount*dir ), quantity_purchased,order_id,order_item_code, 'AMZ', if((acc='abank_memo' AND ap.amount_description='Payable to Amazon') OR (acc='abank_memo' AND ap.amount_description='Successful charge'), concat(right( EXTRACT(YEAR_MONTH FROM posted_date + interval 4 day),4), date_format(posted_date + interval 4 day, '%d')) , settlement_id ) clearing, ttype, fromdoc, ap.ba, concat(ap.transaction_type,'-',ap.amount_type,'-',ap.amount_description) remark, a.brand,a.matid,?,? FROM apay2 ap JOIN apay2_acc aa ON ap.transaction_type = aa.transaction_type AND ap.amount_type = aa.amount_type AND ap.amount_description = aa.amount_description LEFT OUTER JOIN ainv a on a.sku=ap.sku LEFT OUTER JOIN mat m on m.vendor=a.brand AND m.matid=a.matid WHERE  fromdoc=? AND ap.postingflag IS NULL AND aa.transaction_type = ? AND aa.amount_type = ? AND aa.amount_description = ? AND posted_date_time >= ? AND posted_date_time < ?", [$cdate,$udate,$fromdoc,$ttype,$atype,$adesc,$fdate,$tdate]);

                // SELECT ac.aseq,ap.no,posted_date_time,acc,amount*dir,quantity_purchased,order_id,order_item_code,'AMZ', if((acc='abank_memo' AND ap.amount_description='Payable to Amazon') OR (acc='abank_memo' AND ap.amount_description='Successful charge'), concat(right( EXTRACT(YEAR_MONTH FROM posted_date + interval 4 day),4), date_format(posted_date + interval 4 day, '%d')) , settlement_id ) clearing,ttype, fromdoc, ap.ba,concat(ap.transaction_type,'-',ap.amount_type,'-',ap.amount_description) remark, a.brand,a.matid,?,? FROM apay2 ap JOIN apay2_acc ac on ap.transaction_type = ac.transaction_type AND ap.amount_type = ac.amount_type AND ap.amount_description = ac.amount_description LEFT JOIN ainv a ON a.sku=ap.sku WHERE postingflag IS NULL AND fromdoc=? AND ap.transaction_type = ? AND ap.amount_type = ? AND ap.amount_description = ? AND posted_date_time >= ? AND posted_date_time < ?



            if($res){

                DB::table('apay2 as a2')
                    ->join('apay2_acc as aa', function($join){
                        $join->on('a2.transaction_type','=','aa.transaction_type');
                        $join->on('a2.amount_type','=','aa.amount_type');
                        $join->on('a2.amount_description','=','aa.amount_description');
                    })
                    ->where('aa.fromdoc',$fromdoc)
                    ->where('aa.transaction_type',$ttype)
                    ->where('aa.amount_type',$atype)
                    ->where('aa.amount_description',$adesc)
                    ->where('a2.posted_date','>=',$fdate)
                    ->where('a2.posted_date','<',$tdate)
                    ->update([
                        'postingflag' => $udate //\Carbon\Carbon::now(),  // \Datetime()
                    ]);

            }
        }
        
        return redirect('/apay');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class BankController extends Controller
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
        $keyword = Input::get('keyword');
        $ba = Input::get('ba');
        $ttype = Input::get('ttype');
        $vendor = Input::get('vendor');
        $material = Input::get('material');
        $isPosted = Input::get('isPosted',2);
        // dd($isPosted);

        $bas = DB::table('bank')->distinct()->get(['ba']);
        $mps = DB::table('bank')->distinct()->get(['mp']);
        $ttypes = DB::table('bank')->distinct()->get(['TType']);


        // $banks = DB::table('bank')->where('fdate','=',$fdate)->orderby('fromdoc')->orderby('transaction_type')->orderby('amount_type')->orderby('tdate','desc')->simplePaginate(10);
        $banks = DB::table('bank')
            ->where('tDate', '>=', $fdate)->where('tDate', '<', $tdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
            ->when($material, function($query) use ($material) { return $query->where('material', $material); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })
            ->when($keyword, function($query) use ($keyword) { return $query->where('tDesc','LIKE', '%'.$keyword.'%'); })
            ->when($isPosted, function($query) use($isPosted) {
                    if($isPosted == 1){
                        return $query->whereNotNull('postingflag');
                    }elseif($isPosted == 2){
                        return $query->whereNull('postingflag');
                    }
                }
            )->orderby('tDate')
            ->orderby('ba')
            ->orderby('no')
            ->simplePaginate(10);

            // ->when($request->customer_id, function($query) use ($request){return $query->where('customer_id', $request->customer_id); })
            // ->when($request->customer_id, function($query) use ($request){return $query->where('customer_id', $request->customer_id); })
            // ->when($request->customer_id, function($query) use ($request){return $query->where('customer_id', $request->customer_id); })

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('banks.list', compact('banks','bas','mps','ttypes','ba','vendor','isPosted','ttype','fdate','tdate','keyword','amt') );//'fdate','tdate','ba',,'materials'
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

    public function deactivate(Request $request)
    {

        $len = count($request->no);
        // $aa = "";
        // $aa .= $len.";";


        for($i=0; $i<$len; $i++){
            // $aa .= $request->no[$i];
            // $aa .= ',';

        //     if($request->amt[$i] != 0){

                DB::table('bank')->where('no',$request->no[$i])->update([

                    'postingflag' => '9999-12-31 23:59:59'

                ]);

        //     }
        }
        // dd($aa);
        return redirect('/bank');


    }
}

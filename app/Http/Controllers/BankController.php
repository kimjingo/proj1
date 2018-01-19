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
        $banks = DB::table('bank as b')
            ->select('b.*','a.cnt')
            ->leftjoin(DB::raw('(SELECT fromdoc,transaction_type,amount_type,amount_description,ttype,count(*) cnt FROM apay2_acc WHERE fromdoc="bank" group by fromdoc,transaction_type,amount_type,amount_description,ttype) a'), function($join)
            {
                $join->on('b.TType','=','a.transaction_type')
                ->on('b.mp','=','a.amount_type')
                ->on('b.material','=','a.amount_description')
                ;
            })
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
                // ->on('b.fromdoc', '=', 'a.fromdoc')

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

    public function post(Request $request)
    {

        // dd($request->submit);
        $len = count($request->no);
        
        switch ($request->submit) {
            case 'Deactivate' :
                // dd('deactivate');

                // for($i=0; $i<$len; $i++){

                //     DB::table('bank')->where('no',$request->no[$i])->update([

                //         'postingflag' => '9999-12-31 23:59:59'

                //     ]);

                // }
                break;

            case 'Post' :
                // dd('post');

                for($i=0; $i<$len; $i++){

                    $posts = DB::table('bank AS b')
                        ->select(DB::raw('b.no,tdate AS pdate,acc,amt*dir AS amt,b.material,b.mp,b.ttype,if(a.acc="lstax_state", concat(year(tdate-interval 2 month), quarter(tdate-interval 2 month)), clearingkey ) AS clearing,fromdoc,b.ba,tdesc AS remark'))
                        ->join(DB::raw('(SELECT * FROM apay2_acc WHERE fromdoc="bank") a'), function($join) {
                            $join->on('b.ttype','=','a.transaction_type')
                            ->on('b.mp','=','a.amount_type')
                            ->on('b.material','=','a.amount_description')
                            ->on('b.ttype','=','a.ttype')
                            ;
                        })
                        ->where('b.no',$request->no[$i])
                        ->whereNull('postingflag')
                        ->orderBy('a.aseq')
                        ->get();
// dd($posts);
                        foreach($posts as $post) {
                            DB::insert('insert into atr(tid,no,pdate,acc,amt,material,mp,ttype,clearing,fromdoc,ba,remark) values (?,?,?,?,?,?,?,?,?,?,?,?)', [
                                $i,$post->no,$post->pdate,$post->acc,$post->amt,$post->material,$post->mp,$post->ttype,$post->clearing,$post->fromdoc,$post->ba,$post->remark]);
                        }


                        // ->select('b.no','tdate AS pdate','acc','amt*dir AS amt','b.material','b.mp','b.ttype','if(ac.acc="lstax_state", concat(year(tdate-interval 2 month), quarter(tdate-interval 2 month)), clearingkey ) AS clearing', 'fromdoc','b.ba','tdesc AS remark')
                        DB::table('bank')->where('no',$request->no[$i])->update([
                            'postingflag' => Carbon::now()
                        ]);
                }
                break;

        }

        return redirect('/bank');


    }
}

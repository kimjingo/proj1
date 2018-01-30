<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BSComparesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

// bs monthly : yy, mm, acc, sum(amt) group by yy, mm into bsyymm


        // $bsyymm = DB::table('bsyymm')
        //     ->join('reconcile', 'bscheckpoints.recid','=','reconcile.id')
        //     ->orderby('checkdate')->get();

        // $bsyymm = DB::table('bscheckpoints')
        //     ->join('reconcile', 'bscheckpoints.recid','=','reconcile.id')
        //     ->orderby('checkdate')->get();

        $bscompares = DB::select ("select b.checkdate yymm,r.accid,sum(b.amt) aamt, min(bym.amt) camt from bscheckpoints b left join reconcile r on b.recid=r.id left join bsyymm bym on bym.yymm=b.checkdate and bym.accid=r.accid group by  order by accid, b.checkdate");
        // $users = DB::table('bscheckpoints')
        //     ->leftjoin('reconcile', 'reconcile.id', '=', 'bscheckpoints.user_id')
        //     ->leftjoin('bsyymm', 'bsyymm.id', '=', 'orders.user_id')
        //     ->select(b.checkdate,r.accid,sum(b.amt) aamt, min(bym.amt) camt)
        //     ->groupby(b.checkdate,accid)
        //     ->get();

        //      select b.checkdate,r.accid,sum(b.amt) aamt, min(bym.amt) camt from bscheckpoints b left join reconcile r on b.recid=r.id left join bsyymm bym on bym.yymm=b.checkdate and bym.accid=r.accid group by b.checkdate,accid

        // $bscompares = [

        //         'id' => 1,

        //         'ymm' => '201712',

        //         'acc' => 'abank',

        //         'aamt' => 2000.00,

        //         'camt' => 1999.00
        // ];
// dd($bscompares['id']);
        $navs = DB::table('sidemenus')->where('menu','compare')->get();
        return view('bscompares.list',compact('bscompares','navs') );
    }

    public function accupdate($ddate,$accid) {

// dd($ddate);
        return $this->acp($accid, $ddate);

    }

    private function acp($accid, $bsdate)
    {
        //

        $bsresult = DB::insert('INSERT INTO bsyymm(yymm,accid,amt,created_at,updated_at) SELECT ?, acc,SUM(amt),now(),now() FROM atr WHERE pdate < ? + interval 1 day and acc = ? GROUP BY acc ON DUPLICATE KEY UPDATE amt = VALUES(amt),updated_at = VALUES(updated_at)',[$bsdate,$bsdate,$accid]);

        return redirect('/bscompares');
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

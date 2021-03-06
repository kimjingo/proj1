<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BSCheckPointsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $bscheckpoints = DB::table('bscheckpoints')
            ->join('reconcile', 'bscheckpoints.recid','=','reconcile.id')
            ->orderby('accid')
            ->orderby('checkdate')
            ->get();
            // dd($bscheckpoints);
        return view('bscheckpoints.list', compact('bscheckpoints'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        //
        $bscheckpoints = DB::table('bscheckpoints')
            ->join('reconcile', 'bscheckpoints.recid','=','reconcile.id')
            ->orderby('checkdate')->get();

        $cdate = new Carbon('last day of last month');

        // dd($cdate);
        $bstochecks = DB::table('reconcile')->get();
        return view('bscheckpoints.add',[
            'cdate' => $cdate,
            'bstochecks' => $bstochecks,
            'bscheckpoints' => $bscheckpoints
        ]);
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

        $this->validate(request(),[
            'checkdate' => 'required',
            'accid' => 'required',
            'amt' => 'required'
        ]);

        DB::table('bscheckpoints')->insert([

            'checkdate' => $request->checkdate,
            'recid' => $request->accid,
            'amt' => $request->amt,

            //composer require nesbot/carbon

            'created_at' => \Carbon\Carbon::now(),  // \Datetime()


            'updated_at' => \Carbon\Carbon::now(),  // \Datetime()



        ]);

        // $this->acp($request->accid, $request->checkdate);
        return redirect('/bscheckpoint/add');

    }

   // private function acp($accid, $bsdate)
   //  {

   //      $bsresult = DB::insert('INSERT INTO bsyymm(yymm,accid,amt,created_at,updated_at) SELECT ?, acc,SUM(amt),now(),now() FROM atr WHERE pdate < ? + interval 1 day and acc = ? GROUP BY acc ON DUPLICATE KEY UPDATE amt = VALUES(amt),updated_at = VALUES(updated_at)',[$bsdate,$bsdate,$accid]);


   //  }


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
       $reconcile = DB::table('bscheckpoints')->delete($id);
    // dd($bscheckpoints);
        return redirect('/bscheckpoint');
    }
}

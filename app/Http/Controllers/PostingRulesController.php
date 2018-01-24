<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;


class PostingRulesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $fromdoc = Input::get('fromdoc', 'bank');
        $trtype = Input::get('trtype');
        $vendor = Input::get('vendor');
        $material = Input::get('material');
        $ttype = Input::get('ttype');

        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $trtypes = DB::table('apay2_acc')->distinct()->get(['transaction_type']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $vendors = DB::table('apay2_acc')->distinct()->get(['amount_type']);

        $rules = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->when($trtype, function($query) use ($trtype) { return $query->where('transaction_type',$trtype);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('transaction_type',$ttype);})
            ->when($vendor, function($query) use ($vendor) { return $query->where('amount_type',$vendor);})
            ->when($material, function($query) use ($material) { return $query->where('amount_description','LIKE', '%'.$material.'%'); })
            ->orderby('fromdoc')
            ->orderby('transaction_type')
            ->orderby('amount_type')
            ->orderby('amount_description')
            ->orderby('ttype')
            ->orderby('aseq')
            ->simplePaginate(10);

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('postingrules.list',compact('rules','fromdoc','trtype','ttype','vendor','material','fromdocs','trtypes','ttypes','vendors') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $trtypes = DB::table('apay2_acc')->distinct()->get(['transaction_type']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $vendors = DB::table('apay2_acc')->distinct()->get(['amount_type']);

        // $rules = DB::table('apay2_acc')->where('fromdoc','=',$fromdoc)->orderby('fromdoc')->orderby('transaction_type')->orderby('acc')->orderby('aseq')->simplePaginate(10);

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('postingrules.create',compact('fromdocs','trtypes','ttypes','vendors') );
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

        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $checksum = 0;
        // $strrr = "";
        for($i=0; $i<2; $i++){
            // $strrr .= $request->acc[$i].":".$request->amt ."*". $request->dir[$i] ."*". $accCal[$request->acc[$i]]['dir'] ."*". $accCal[$request->acc[$i]]['gdir'] ."<br>";

            $checksum += $request->dir[$i] * $accCal[$request->acc[$i]]['dir'] * $accCal[$request->acc[$i]]['gdir'] ;
        }        

        // dd($strrr);
        // dd($checksum);

        if(!$checksum){

            for($i=0; $i<2; $i++){

                DB::table('apay2_acc')->insert([

                    'fromdoc' => $request->fromdoc,
                    'transaction_type' => $request->ttype,
                    'amount_type' => $request->atype,
                    'amount_description' => $request->adesc,
                    'acc' => $request->acc[$i],
                    'dir' => $request->dir[$i],
                    'aseq' => $request->seq[$i],
                    'ttype' => $request->atrtype,
                    'ba' => $request->ba,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),

                ]);

            }

        }
        
        return redirect('/apay');
        // return redirect()->back();


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

    public function duplicate($id)
    {
        //
        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $trtypes = DB::table('apay2_acc')->distinct()->get(['transaction_type AS trtype']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $vendors = DB::table('apay2_acc')->distinct()->get(['amount_type AS vendor']);
        $materials = DB::table('apay2_acc')->distinct()->get(['amount_description AS material']);
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        $accs = DB::table('gacc')->distinct()->get(['accid AS acc']);
        
        $rules = DB::table('apay2_acc as a1')
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS trtype', 'a1.amount_type AS vendor', 'a1.amount_description AS material', 'a1.acc', 'a1.dir', 'a1.aseq AS seq', 'a1.ttype', 'a1.no', 'a1.ba')
            ->join(DB::raw('(SELECT * FROM apay2_acc WHERE no='.$id.') a2'), function($join)
            {
                $join->on('a1.fromdoc', '=', 'a2.fromdoc')
                ->on('a1.transaction_type','=','a2.transaction_type')
                ->on('a1.amount_type','=','a2.amount_type')
                ->on('a1.amount_description','=','a2.amount_description')
                ->on('a1.ttype','=','a2.ttype')
                ;
            })
            ->orderBy('a1.fromdoc')
            ->orderBy('a1.transaction_type')
            ->orderBy('a1.amount_type')
            ->orderBy('a1.amount_description')
            ->orderBy('a1.ttype')
            ->orderBy('a1.aseq')
            ->get();

// dd($fromdocs);

        return view('postingrules.duplicate',compact('rules','fromdocs','trtypes','ttypes','vendors','materials','bas','accs') );
    }

    public function addwithdata()
    {
        //
        $fromdoc = Input::get('fromdoc');
        switch($fromdoc){
            case "apay2":
                $dr = 'aar_amz';
                break;
        }
        
        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);
        // dd($accCalJSON);

        // $fromdoc = 'bank';
        $dirs = [1,-1];
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        $atrtypes = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['ttype']);
        // $accs = DB::table('gacc')->get(['accid AS acc']);


        $ttype = Input::get('ttype');
        $atype = Input::get('atype');
        $adesc = Input::get('adesc');

        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }

        return view('postingrules.create',compact('dr','fromdoc','ttype','atype','adesc','accs','accCalJSON','dirs','atrtypes') );
        
        // $rules = DB::table('apay2_acc as a1')
        //     ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS trtype', 'a1.amount_type AS vendor', 'a1.amount_description AS material', 'a1.acc', 'a1.dir', 'a1.aseq AS seq', 'a1.ttype', 'a1.no', 'a1.ba')
        //     ->join(DB::raw('(SELECT * FROM apay2_acc WHERE no='.$id.') a2'), function($join)
        //     {
        //         $join->on('a1.fromdoc', '=', 'a2.fromdoc')
        //         ->on('a1.transaction_type','=','a2.transaction_type')
        //         ->on('a1.amount_type','=','a2.amount_type')
        //         ->on('a1.amount_description','=','a2.amount_description')
        //         ->on('a1.ttype','=','a2.ttype')
        //         ;
        //     })
        //     ->orderBy('a1.fromdoc')
        //     ->orderBy('a1.transaction_type')
        //     ->orderBy('a1.amount_type')
        //     ->orderBy('a1.amount_description')
        //     ->orderBy('a1.ttype')
        //     ->orderBy('a1.aseq')
        //     ->get();

// dd($fromdocs);

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

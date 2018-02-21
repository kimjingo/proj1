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

    private function get_rule_by_id($id)
    {
        //
        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);

        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $atts = DB::table('apay2_acc')->distinct()->get(['transaction_type AS att']);
        $aats = DB::table('apay2_acc')->distinct()->get(['amount_type AS aat']);
        $aads = DB::table('apay2_acc')->distinct()->get(['amount_description AS aad']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        // $accs = DB::table('gacc')->distinct()->get(['accid AS acc']);
        
        $ruleheader = DB::table('apay2_acc as a1')
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS aat', 'a1.amount_description AS aad', 'a1.ba')
            ->where('no',$id)
            ->first();

        $rules = DB::table('apay2_acc as a1')
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS aat', 'a1.amount_description AS aad', 'a1.acc', 'a1.dir', 'a1.aseq AS seq', 'a1.ttype', 'a1.no', 'a1.ba')
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

        return compact('id','ruleheader','rules','fromdocs','atts','aats','aads','ttypes','bas','accs','accCalJSON') ;
    }

    public function index()
    {
        //
        $fromdoc = Input::get('fromdoc', 'apay2');
        $att = Input::get('att');
        $aat = Input::get('aat');
        $aad = Input::get('aad');
        $material = Input::get('material');
        $ttype = Input::get('ttype');

        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $atts = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->distinct()
            ->get(['transaction_type as att']);
        $aats = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->when($att, function($query) use ($att) { return $query->where('transaction_type',$att);})
            ->distinct()
            ->get(['amount_type as aat']);
        $aads = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->when($att, function($query) use ($att) { return $query->where('transaction_type',$att);})
            ->when($aat, function($query) use ($aat) { return $query->where('amount_type',$aat);})
            ->distinct()
            ->get(['amount_description as aad']);
        $ttypes = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->when($att, function($query) use ($att) { return $query->where('transaction_type',$att);})
            ->when($aat, function($query) use ($aat) { return $query->where('amount_type',$aat);})
            ->when($aad, function($query) use ($aad) { return $query->where('amount_description',$aad);})
            ->distinct()->get(['ttype']);

// dd($aat);
        $rules = DB::table('apay2_acc')
            ->where('fromdoc',$fromdoc)
            ->when($att, function($query) use ($att) { return $query->where('transaction_type',$att);})
            ->when($aat, function($query) use ($aat) { return $query->where('amount_type',$aat);})
            ->when($aad, function($query) use ($aad) { return $query->where('amount_description',$aad);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->when($material, function($query) use ($material) { return $query->where('amount_description','LIKE', '%'.$material.'%'); })
            ->orderby('fromdoc')
            ->orderby('transaction_type')
            ->orderby('amount_type')
            ->orderby('amount_description')
            ->orderby('ttype')
            ->orderby('aseq')
            ->simplePaginate(10);
            // ->toSql();

            // dd($rules);
        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('postingrules.list',compact('rules','fromdoc','att','aat','aad','ttype','material','fromdocs','atts','aats','aads','ttypes') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //


        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);



        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $atts = DB::table('apay2_acc')->distinct()->get(['transaction_type AS att']);
        $aats = DB::table('apay2_acc')->distinct()->get(['amount_type AS aat']);
        $aads = DB::table('apay2_acc')->distinct()->get(['amount_description AS aad']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        // $accs = DB::table('gacc')->distinct()->get(['accid AS acc']);
        
        return view('postingrules.create',compact('fromdocs','atts','aats','aads','ttypes','bas','accs','accCalJSON') );
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
        $len = count($request->seq);
        $now = \Carbon\Carbon::now();


        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $checksum = 0;
        for($i=0; $i < $len; $i++){

            $checksum += $request->dir[$i] * $accCal[$request->acc[$i]]['dir'] * $accCal[$request->acc[$i]]['gdir'] ;

        }        

        if(!$checksum){

            for($i=0; $i < $len; $i++){

                DB::insert("INSERT IGNORE INTO apay2_acc(fromdoc, transaction_type, amount_type, amount_description, acc, dir, aseq, ttype, ba, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?)", [$request->fromdoc,$request->att,$request->aat,$request->aad,$request->acc[$i],$request->dir[$i],$request->seq[$i],$request->ttype[$i],$request->ba,$now,$now] ) ;

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
        // $aa = $this->get_rule_by_id($id);
        // dd($aa);

        return view('postingrules.edit', $this->get_rule_by_id($id));
    }

    public function duplicate($id)
    {
        //
        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);

        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $atts = DB::table('apay2_acc')->distinct()->get(['transaction_type AS att']);
        $aats = DB::table('apay2_acc')->distinct()->get(['amount_type AS aat']);
        $aads = DB::table('apay2_acc')->distinct()->get(['amount_description AS aad']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        // $accs = DB::table('gacc')->distinct()->get(['accid AS acc']);
        
        $ruleheader = DB::table('apay2_acc as a1')
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS aat', 'a1.amount_description AS aad', 'a1.ba')
            ->where('no',$id)
            ->first();

        $rules = DB::table('apay2_acc as a1')
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS aat', 'a1.amount_description AS aad', 'a1.acc', 'a1.dir', 'a1.aseq AS seq', 'a1.ttype', 'a1.no', 'a1.ba')
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

        return view('postingrules.duplicate',compact('ruleheader','rules','fromdocs','atts','aats','aads','ttypes','bas','accs','accCalJSON') );
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
        $atts = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['transaction_type as att']);
        $aats = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['amount_type as aat']);
        $aads = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['amount_description as aad']);
        $ttypes = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['ttype as ttype']);
        $fromdocs = DB::table('apay2_acc')->where('fromdoc',$fromdoc)->distinct()->get(['fromdoc']);


        // $accs = DB::table('gacc')->get(['accid AS acc']);

        $att = Input::get('att');
        $aat = Input::get('aat');
// dd($aat);
        $aad = Input::get('aad');

        return view('postingrules.create',compact('dr','fromdoc','att','aat','aad','accs','accCalJSON','dirs','atts','atts','aats','aads','fromdocs','ttypes','bas') );
        
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
        $len = count($request->seq);
        $now = \Carbon\Carbon::now();

        $q = 'DELETE a FROM apay2_acc a, (select fromdoc,transaction_type,amount_type,amount_description from apay2_acc where no=?) i where a.fromdoc=i.fromdoc and a.transaction_type=i.transaction_type and a.amount_type=i.amount_type and a.amount_description=i.amount_description'; 
        $status = DB::delete($q, array($id));

        if($status){

            $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
            foreach($accs as $acc) {
                $accCal[$acc->accid] = array(
                    "dir" => $acc->dir,
                    "gdir" => $acc->gdir
                );
            }
            $checksum = 0;
            for($i=0; $i < $len; $i++){

                $checksum += $request->dir[$i] * $accCal[$request->acc[$i]]['dir'] * $accCal[$request->acc[$i]]['gdir'] ;

            }        

            if(!$checksum){

                for($i=0; $i < $len; $i++){

                    DB::insert("INSERT IGNORE INTO apay2_acc(fromdoc, transaction_type, amount_type, amount_description, acc, dir, aseq, ttype, ba, created_at, updated_at) values (?,?,?,?,?,?,?,?,?,?,?)", [$request->fromdoc,$request->att,$request->aat,$request->aad,$request->acc[$i],$request->dir[$i],$request->seq[$i],$request->ttype[$i],$request->ba,$now,$now] ) ;

                }

            }

        }
        
        
        return redirect('/postingrules');

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

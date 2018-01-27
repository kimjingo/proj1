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
        // $selections = DB::table('apay2_acc')->distinct()->get([
        //     'fromdoc',
        //     'transaction_type AS att',
        //     'amount_type AS aat',
        //     'amount_description AS aad',
        //     'ttype',
        // ]);

        // foreach($selections as $selection) {
        //     if( !isset( $PRSelections[$selection->fromdoc]) ) {
        //         //echo $i."1:".$row["sku"]."-".$row["brand"]."=".$row["style"]."=".$row["wsku"]."=".$row["size"].$row["qtytarget"]."<br>";
        //         $PRSelection[$selection->fromdoc] = array(
        //             $selection->att => array(
        //                 $selection->aat => array(
        //                     $selection->aad = array(
        //                         $selection->ttype
        //                     )
        //                 )
        //             )
        //         );
        //     } elseif( !isset( $PRSelections[$selection->fromdoc][$selection->att]) ) {
        //         $PRSelection[$selection->fromdoc][$selection->att] = array(
        //             $selection->aat => array(
        //                 $selection->aad => array(
        //                     $selection->ttype
        //                 )
        //             )
        //         );
        //     }elseif( !isset( $PRSelections[$selection->fromdoc][$selection->att][$selection->aat]) ) {
        //         $PRSelection[$selection->fromdoc][$selection->att][$selection->aat] = array(
        //             $selection->aad => array(
        //                 $selection->ttype
        //             )
        //         );
        //     }elseif( !isset( $PRSelections[$selection->fromdoc][$selection->att][$selection->att][$selection->aad]) ) {
        //         $PRSelection[$selection->fromdoc][$selection->att][$selection->aat][$selection->aad] = array(
        //             $selection->ttype 
        //         );
        //     }else {
        //         $PRSelection[$selection->fromdoc][$selection->att][$selection->aat][$selection->aad][] = $selection->ttype;
        //     }
        // }

        // dd($PRSelection);
        // $PRSJSON = json_encode($PRSelection);

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

        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $checksum = 0;
        // $strrr = "";
        for($i=0; $i < $len; $i++){
            // $strrr .= $request->acc[$i].":".$request->amt ."*". $request->dir[$i] ."*". $accCal[$request->acc[$i]]['dir'] ."*". $accCal[$request->acc[$i]]['gdir'] ."<br>";

            $checksum += $request->dir[$i] * $accCal[$request->acc[$i]]['dir'] * $accCal[$request->acc[$i]]['gdir'] ;

        }        

        // dd($strrr);
        // dd($checksum);

        if(!$checksum){

            for($i=0; $i < $len; $i++){
                $a = array(
                    'fromdoc' => $request->fromdoc,
                    'transaction_type' => $request->att,
                    'amount_type' => $request->aat,
                    'amount_description' => $request->aad,
                    'acc' => $request->acc[$i],
                    'dir' => $request->dir[$i],
                    'aseq' => $request->seq[$i],
                    'ttype' => $request->ttype,
                    'ba' => $request->ba,
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now(),
                );

                DB::insert('INSERT IGNORE INTO apay2_acc ('.implode(',',array_keys($a)).') values (?'. str_repeat(',?',count($a)-1).')', array_values($a) ) ;
                // DB::table('apay2_acc')->updateOrCreate([

                //     'fromdoc' => $request->fromdoc,
                //     'transaction_type' => $request->att,
                //     'amount_type' => $request->aat,
                //     'amount_description' => $request->aad,
                //     'acc' => $request->acc[$i],
                //     'dir' => $request->dir[$i],
                //     'aseq' => $request->seq[$i],
                //     'ttype' => $request->ttype,
                //     'ba' => $request->ba,
                //     'created_at' => \Carbon\Carbon::now(),
                //     'updated_at' => \Carbon\Carbon::now(),
                // ]);


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
            ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS aat', 'a1.amount_description AS aad', 'a1.ttype','a1.ba')
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
        $aad = Input::get('aad');

        // $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        // foreach($accs as $acc) {
        //     $accCal[$acc->accid] = array(
        //         "dir" => $acc->dir,
        //         "gdir" => $acc->gdir
        //     );
        // }

        return view('postingrules.create',compact('dr','fromdoc','att','aat','aad','accs','accCalJSON','dirs','atts','atts','aats','aads','fromdocs','ttypes','bas') );
        
        // $rules = DB::table('apay2_acc as a1')
        //     ->select('a1.fromdoc AS fromdoc', 'a1.transaction_type AS att', 'a1.amount_type AS vendor', 'a1.amount_description AS material', 'a1.acc', 'a1.dir', 'a1.aseq AS seq', 'a1.ttype', 'a1.no', 'a1.ba')
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

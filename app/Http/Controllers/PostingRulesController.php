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
                //
        $len = count($request->fromdoc);

        for($i=0; $i<$len; $i++){

            // if($request->amt[$i] != 0){

                $no = DB::table('apay2_acc')->insert([

                    'fromdoc' => $request->fromdoc[$i],
                    'transaction_type' => $request->trtype[$i],
                    'amount_type' => $request->vendor[$i],
                    'amount_description' => $request->material[$i],
                    'acc' => $request->acc[$i],
                    'dir' => $request->dir[$i],
                    'aseq' => $request->seq[$i],
                    'ttype' => $request->ttype[$i],
                    'ba' => $request->ba[$i],

                ]);
                $nos[] = $no;
            // }
        }

        // dd($nos);

        return redirect('/postingrules?fromdoc='.$request->fromdoc[0].'&trtype='.$request->trtype[0].'&vendor='.$request->vendor[0].'&material='.$request->material[0].'&acc='.$request->acc[0].'&dir='.$request->dir[0].'&aseq='.$request->seq[0].'&ttype='.$request->ttype[0].'&ba='.$request->ba[0]);
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

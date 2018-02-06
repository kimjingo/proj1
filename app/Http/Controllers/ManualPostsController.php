<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;

class ManualPostsController extends Controller
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
        // $tdate = $ttdate->addDay()->toDateString();
        $tdate = $ttdate->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

        $fdate = Input::get('fdate', $fdate);
        $tdate = Input::get('tdate', $tdate);

        $amt = Input::get('amt');
        $material = Input::get('material');
        $vendor = Input::get('vendor');
        $ttype = Input::get('ttype');
        $remark = Input::get('remark');
        $ba = Input::get('ba');
        $isPosted = Input::get('isPosted',2);

        $cfdate = Input::get('cfdate', $fdate);
        $ctdate = Input::get('ctdate', $tdate);

        $tdate1 = new Carbon($tdate);
        $tdate1->endOfDay();
        $qtdate = $tdate1->toDateTimeString();

        $ctdate1 = new Carbon($ctdate);
        $ctdate1->endOfDay();
        $qctdate = $ctdate1->toDateTimeString();
        // dd($isPosted);

        $bas = [1,2];
        $vendors = DB::table('atr')
            ->where('fromdoc', 'manualpost')
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['mp as vendor']);
        
        $fromdocs = DB::table('atr')
            ->where('fromdoc', 'manualpost')
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['fromdoc']);

        $ttypes = DB::table('atr')
            ->where('fromdoc', 'manualpost')
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['ttype']);
        
        $brands = DB::table('atr')->distinct()->get(['brand']);
        
        // $manualinputs = DB::table('manualposts')->where('created_at','>','2018-01-08')->orderby('updated_at','desc')->limit(100)->get();

        // dd($qtdate);

        $manualinputs = DB::table('manualposts')
            ->where('pdate', '>=', $fdate)->where('pdate', '<=', $qtdate)
            ->where('created_at', '>=', $fdate)->where('created_at', '<=', $qctdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
            ->when($material, function($query) use ($material) { return $query->where('material', $material); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amt)=?', [$amt]); })
            ->when($remark, function($query) use ($remark) { return $query->where('remark','LIKE', '%'.$remark.'%'); })
            ->when($isPosted, function($query) use($isPosted) {
                    if($isPosted == 1){
                        return $query->whereNotNull('posting');
                    }elseif($isPosted == 2){
                        return $query->whereNull('posting');
                    }
                }
            )
            ->orderby('pdate')
        ->orderby('created_at','desc')->simplePaginate(10);

            // ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })
        return view('manualposts.list',compact('manualinputs','tdate','fdate','amt','material','vendor','ttype','remark','ba','isPosted','cfdate','ctdate','bas','ttypes','brand','vendors') );

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $ttypes = DB::table('manualposts')->distinct()->get(['ttype']);
        // $ttypes = ['aa','bb','cc'];
        $mps = DB::table('manualposts')->distinct()->get(['mp']);
        $paidbys = DB::table('manualposts')->distinct()->get(['paidby']);
        $pdate = new Carbon('last day of last month');
        $bas = [1,2];
// dd($ttypes);
        return view('manualposts.create',compact('ttypes', 'mps', 'paidbys','pdate','bas'));


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
            'pdate' => 'required',
            'amt' => 'required',
            'mp' => 'required',
        ]);

        $len = count($request->pdate);

        for($i=0; $i<$len; $i++){

            if($request->amt[$i] != 0){
                if (!$request->paidby[$i]){
                    $paidby = '';
                } else{
                    $paidby = $request->paidby[$i];
                }

                if (!$request->ttype[$i]){
                    $ttype = '';
                }else{
                    $ttype = $request->ttype[$i];
                }

                if (!$request->material[$i]){
                    $material = '';
                }else{

                    $material = $request->material[$i];
                }
                if (!$request->remark[$i]) {
                    $remark = '';

                }else{
                    $remark = $request->remark[$i];
                    
                }
                if (!$request->checkno[$i]){
                 $checkno = '';

                }else{
                    $checkno = $request->checkno[$i];
                    
                }

                if (!$request->dr_clearing[$i]){
                 $dr_clearing = '';

                }else{
                    $dr_clearing = $request->dr_clearing[$i];
                    
                }
                
                if (!$request->ba[$i]){
                 $ba = 1;

                }else{
                    $ba = $request->ba[$i];
                    
                }


                DB::table('manualposts')->insert([

                    'pdate' => $request->pdate[$i],
                    'amt' => $request->amt[$i],
                    'mp' => $request->mp[$i],
                    'ttype' => $ttype,
                    'material' => $material,
                    'remark' => $remark,
                    'checkno' => $checkno,
                    'dr_clearing' => $dr_clearing,
                    'paidby' => $paidby,
                    'ba' => $ba,

                    //composer require nesbot/carbon

                    'created_at' => \Carbon\Carbon::now(),  // \Datetime()

                    'updated_at' => \Carbon\Carbon::now(),  // \Datetime()



                ]);

            }
        }
        // // Save it to the database
        // $post->save();
        // Post::create(request()->all());

        // And then redirect to the home page
        // dd($aa);
        return redirect('/manualposts');
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
        $ttypes = DB::table('manualposts')->distinct()->get(['ttype']);
        // $ttypes = ['aa','bb','cc'];
        $mps = DB::table('manualposts')->distinct()->get(['mp']);
        $paidbys = DB::table('manualposts')->distinct()->get(['paidby']);
        $pdate = new Carbon('last day of last month');
        $bas = [1,2];

        $manualinput = DB::table('manualposts')->find($id);
        //where('id','=', $id)->get();

        // $manualinput = $manualinputs[0];
// dd($manualinput[0]->pdate);

        return view('manualposts.edit',compact('ttypes', 'mps', 'paidbys','pdate','bas','manualinput','id') );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        //
        $this->validate(request(),[
            'pdate' => 'required',
            'amt' => 'required',
            'mp' => 'required',
        ]);


        DB::table('manualposts')->where('id',$request->id)
            ->update([

            'pdate' => $request->pdate,
            'amt' => $request->amt,
            'ttype' => $request->ttype,
            'mp' => $request->mp,
            'material' => $request->material,
            'remark' => $request->remark,
            'checkno' => $request->checkno,
            'posting' => $request->posted_at,
            'paidby' => $request->paidby,
            'ba' => $request->ba,

            //composer require nesbot/carbon

            'updated_at' => \Carbon\Carbon::now(),  // \Datetime()

        ]);

        return redirect('/manualposts');

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
        $manualpost = DB::table('manualposts')->delete($id);
    // dd($manualpost);
        return redirect('/manualposts');
    }

    public function manualpost($id)
    {
        # code...
        // dd("aa");
        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }

        $accCalJSON = json_encode($accCal);
        // dd($accCalJSON);

        $fromdoc = 'manualpost';
        // $dr = 'abank';
        $dirs = [1,-1];
        $bas = DB::table('apay2_acc')->distinct()->get(['ba']);
        $vendors = DB::table('manualposts')->distinct()->get(['mp as vendor']);
        $materials = DB::table('manualposts')->distinct()->get(['material']);
        $ttypes = DB::table('manualposts')->distinct()->get(['ttype']);
        $paidbys = DB::table('manualposts')->distinct()->get(['paidby']);
        
        $manualpostheader = DB::table('manualposts')
            ->select('id','pdate','amt','mp as vendor','material','remark','ttype','paidby','ba','checkno')
            ->find($id);

        $manualpost = DB::table('manualposts')
            ->select('cr','cr_dir','cr_clearing','dr','dr_dir','dr_clearing')
            ->find($id);

// dd($fromdocs);
        // dd($bank);
        return view('manualposts.manualpost',compact('manualpostheader','manualpost','fromdoc','bas','accs','accCalJSON','dirs','vendors','materials','ttypes','paidbys') );

    }

    public function post(Request $request, $id)
    {
        //
        // dd($request->amt*$request->cr_dir);
        $udate = \Carbon\Carbon::now();
        
        $acc = ['cr','dr'];
        $dir = ['cr_dir','dr_dir'];
        $clearing = ['cr_clearing','dr_clearing'];

        for($i=0;$i<2;$i++){
            $accname = $acc[$i];
            $dirname = $dir[$i];
            $clearingname = $clearing[$i];
            $amt = $request->amt*$request->$dirname;
            // dd($amt);
            $res = DB::insert("INSERT INTO atr(tid,no,pdate, acc,amt,mp, material,inputtype,ttype, clearing,ba,remark, fromdoc,created_at,updated_at) values(?,?,?, ?,?,?, ?,'manual',?, ?,?,?, ?,?,?);", [$id,$request->seq[$i],$request->pdate, $request->$accname,$amt,$request->vendor, $request->material,$request->ttype, $request->$clearingname,$request->ba,$request->remark, $request->fromdoc,$udate,$udate ]);

            // $res = DB::insert('atr')->insert([
            //     'tid'   =>  $id,
            //     'no'    =>  $request->seq[$i],
            //     'pdate' =>  $request->pdate,
            //     'orderid'   =>  1,
            //     'itemid'    =>  1,
            //     'material'  =>  $request->material,
            //     'mp'    =>  $request->vendor,
            //     'ttype' =>  $request->ttype,
            //     'inputtype' =>  'manual',
            //     'remark'    =>  $request->remark,
            //     'fromdoc'   =>  $request->fromdoc,
            //     'ba'    =>  $request->ba,

            //     'acc'   =>  $request->cr,
            //     'amt'   =>  $request->amt*$request->cr_dir,
            //     'clearing'  =>  $request->cr_clearing,

            //     'created_at' => $udate,
            //     'updated_at' => $udate
            // ]);
        }
                // 'acc'   =>  $request->{$aac[$i]},
                // 'amt'   =>  $request->cr*$request->{$dir[$i]},
                // 'clearing'  =>  $request->{$clearing[$i]},

        if($res){

            DB::table('manualposts')
                ->where('id',$id)
                ->update([
                    'cr' => $request->cr,
                    'cr_dir' => $request->cr_dir,
                    'cr_clearing' => $request->cr_clearing,
                    'dr' => $request->dr,
                    'dr_dir' => $request->dr_dir,
                    'dr_clearing' => $request->dr_clearing,
                    'mp' => $request->vendor,
                    'material' => $request->material,
                    'ba' => $request->ba,
                    'posting' => $udate //\Carbon\Carbon::now(),  // \Datetime()
                ]);

        }
        
        return redirect('/manualposts');
    }

    public function postbybatch(Request $request)
    {
        //
        for($i=0;$i<count($request->id);$i++){

            $now = \Carbon\Carbon::now();
            $fromdoc = 'manualpost';

            $res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt, mp,material, clearing,ttype,fromdoc, ba,remark,created_at,updated_at) SELECT ap.id,aa.aseq,ap.pdate,aa.acc,ap.amt*aa.dir, ap.mp,ap.material, if((ap.ttype='check' and aa.acc!='lck'), ap.dr_clearing, ap.checkno),ap.ttype,?, ap.ba,ap.remark,?,? FROM manualposts ap, apay2_acc aa WHERE  aa.fromdoc=? AND ap.posting IS NULL AND ap.mp=aa.transaction_type AND ap.material=aa.amount_type AND ap.ttype=aa.ttype AND ap.id=?", [$fromdoc,$now,$now,$fromdoc,$request->id[$i]]);

            if($res){
                DB::table('manualposts')
                    ->where('id',$request->id[$i])
                    ->update([
                        'posting' => $now //\Carbon\Carbon::now(),  // \Datetime()
                    ]);
            }
        }
        return redirect('/manualposts');
        
    }
}

// select acc, if((ap.ttype='check' and aa.acc!='lck'), ap.dr_clearing, ap.checkno) 
// FROM manualposts ap, apay2_acc aa 
// WHERE
// ap.mp=aa.transaction_type AND ap.material=aa.amount_type AND ap.ttype=aa.ttype
// limit 10;
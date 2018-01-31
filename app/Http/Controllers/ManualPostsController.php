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
        $tdate = $ttdate->addDay()->toDateString();

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
        $manualinputs = DB::table('manualposts')
          ->where('pdate', '>=', $fdate)->where('pdate', '<', $tdate)
            ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
            ->when($material, function($query) use ($material) { return $query->where('material', $material); })
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
            ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })
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
        // dd($request->pdate);
        // $this->validate(request(),[
        //     'pdate' => 'required',
        //     'amt' => 'required',
        //     'mp' => 'required',
        //     'paidbys' => 'required',
        //     'amt' => 'required'
        // ]);
        
        // $aa ="";
        $len = count($request->pdate);
        // $aa .= $len.";";

        for($i=0; $i<$len; $i++){

        // $aa .= $request->pdate[$i].",";
        // $aa .= $request->amt[$i].",";
        // $aa .= $request->checkno[$i].";";

            if($request->amt[$i] != 0){

                DB::table('manualposts')->insert([

                    'pdate' => $request->pdate[$i],
                    'amt' => $request->amt[$i],
                    'ttype' => $request->ttype[$i],
                    'mp' => $request->mp[$i],
                    'material' => $request->material[$i],
                    'remark' => $request->remark[$i],
                    'checkno' => $request->checkno[$i],
                    'posting' => $request->posted_at[$i],
                    'paidby' => $request->paidby[$i],
                    'ba' => $request->ba[$i],

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
            'paidby' => 'required',
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

                $res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,qty,orderid,itemid,mp,clearing,ttype,fromdoc,ba, remark, brand, material, created_at,updated_at) 

                    SELECT aa.aseq,ap.no,posted_date_time,acc, if((acc='ainv' or acc='pcgs'), ifnull(m.map,5)*greatest(quantity_purchased,1)*dir, amount ), quantity_purchased,order_id,order_item_code, 'AMZ', if((acc='abank_memo' AND ap.amount_description='Payable to Amazon') OR (acc='abank_memo' AND ap.amount_description='Successful charge'), concat(right( EXTRACT(YEAR_MONTH FROM posted_date + interval 4 day),4), date_format(posted_date + interval 4 day, '%d')) , settlement_id ) clearing, ttype, fromdoc, ap.ba, concat(ap.transaction_type,'-',ap.amount_type,'-',ap.amount_description) remark, a.brand,a.matid,?,? 

                    FROM manualposts ap 
                    JOIN apay2_acc aa ON ap.transaction_type = aa.transaction_type AND ap.amount_type = aa.amount_type AND ap.amount_description = aa.amount_description 
                    LEFT OUTER JOIN ainv a on a.sku=ap.sku 
                    LEFT OUTER JOIN mat m on m.vendor=a.brand AND m.matid=a.matid 
                    WHERE  fromdoc=? AND ap.postingflag IS NULL AND aa.transaction_type = ? AND aa.amount_type = ? AND aa.amount_description = ? AND posted_date_time >= ? AND posted_date_time < ?"
                    , [$cdate,$udate,$fromdoc,$ttype,$atype,$adesc,$fdate,$tdate]);


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
        }
        
        return redirect('/manualposts');
    }
}
}

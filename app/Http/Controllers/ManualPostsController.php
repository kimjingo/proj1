<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Pagination\Paginator;

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
        // dd('aa');
        // $manualinputs = DB::table('manualposts')->where('created_at','>','2018-01-08')->orderby('updated_at','desc')->limit(100)->get();
        $manualinputs = DB::table('manualposts')->orderby('updated_at','desc')->simplePaginate(10);
        return view('manualposts.list',compact('manualinputs') );

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

        $manualinputs = DB::table('manualposts')->where('id','=', $id)->get();

        $manualinput = $manualinputs[0];
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
}

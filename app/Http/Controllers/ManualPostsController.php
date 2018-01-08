<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        $manualinputs = DB::table('manualposts')->orderby('pdate','desc')->limit(10)->get();
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
        dd($request->pdate);
        // $this->validate(request(),[
        //     'pdate' => 'required',
        //     'amt' => 'required',
        //     'mp' => 'required',
        //     'paidbys' => 'required',
        //     'amt' => 'required'
        // ]);
$aa = "";
        foreach($request as $row){
            $aa .= $row->pdate;
            // DB::table('manualposts')->insert([

            //     'pdate' => $row->pdate,
            //     'amt' => $row->amt,
            //     'ttype' => $row->ttype,
            //     'mp' => $row->mp,
            //     'material' => $row->material,
            //     'remark' => $row->remark,
            //     'checkno' => $row->checkno,
            //     'posting' => $row->posted_at,
            //     'paidbys' => $row->paidbys,
            //     'ba' => $row->ba,


            //     //composer require nesbot/carbon

            //     'created_at' => \Carbon\Carbon::now(),  // \Datetime()


            //     'updated_at' => \Carbon\Carbon::now(),  // \Datetime()



            // ]);
        }
        // // Save it to the database
        // $post->save();
        // Post::create(request()->all());

        // And then redirect to the home page
        dd($aa);
        // return redirect('/manualposts');
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

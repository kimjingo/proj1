<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ReconcilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $reconciles = DB::table('reconcile')->get();

        $navs = DB::table('sidemenus')->where('menu','compare')->get();
        return view('reconciles.list', compact('reconciles','navs'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function add()
    {
        //
        $parent = DB::table('gacc')->where('bc','=',1)->get();
        return view('reconciles.add',compact('parent'));
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
            'acc' => 'required',
            'child' => 'required'
        ]);

        DB::table('reconcile')->insert([

            'accid' => $request->acc,

            'toreconcile' => $request->child,

            //composer require nesbot/carbon

            'created_at' => \Carbon\Carbon::now(),  // \Datetime()


            'updated_at' => \Carbon\Carbon::now(),  // \Datetime()



        ]);
        // // Save it to the database
        // $post->save();
        // Post::create(request()->all());

        // And then redirect to the home page
        return redirect('/reconcile');
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
        $reconcile = DB::table('reconcile')->delete($id);
    // dd($reconcile);
        return redirect('reconcile');
    
    }
}

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
        $trtype = Input::get('trtype', '');
        $ttype = Input::get('ttype', '');
        $vendor = Input::get('vendor', '');
        $material = Input::get('material', '');

        $fromdocs = DB::table('apay2_acc')->distinct()->get(['fromdoc']);
        $trtypes = DB::table('apay2_acc')->distinct()->get(['transaction_type']);
        $ttypes = DB::table('apay2_acc')->distinct()->get(['ttype']);
        $vendors = DB::table('apay2_acc')->distinct()->get(['amount_type']);

        $rules = DB::table('apay2_acc')->where('fromdoc','=',$fromdoc)->orderby('fromdoc')->orderby('transaction_type')->orderby('acc')->orderby('aseq')->simplePaginate(10);

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

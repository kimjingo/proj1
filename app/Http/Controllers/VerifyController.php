<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VerifyController extends Controller
{
    //
    public function index()
    {
        //

        $zerosum = DB::table('atr AS a')
        	->select(DB::raw('sum(amt*dir*gdir) as result'))
            ->join('gacc as g', 'g.accid', '=', 'a.acc')
            // ->where('pdate','>=','2017-01-01')
            ->first();
// dd($zerosum->result);

        return view('verify.list', compact('zerosum') );
    }

    public function bal()
    {
        //

        $bals = DB::table('bal')->get();

        return view('verify.list', compact('bals') );
    }
}

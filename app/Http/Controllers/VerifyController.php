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

        $bas = DB::table('apay2')->distinct()->whereNotNull('ba')->orderby('ba')->get(['ba']);
        $ttypes = DB::table('apay2')->distinct()->get(['transaction_type as ttype']);
        $atypes = DB::table('apay2')->distinct()->get(['amount_type as atype']);
        $adescs = DB::table('apay2')->distinct()->get(['amount_description as adesc']);



        $total = DB::table('atr AS a')
        	->select(DB::raw('sum(amt*dir*gdir)'))
        	->join('gacc as g', 'g.accid', '=', 'a.acc')
            ->first();

        $bals = DB::table('bal')->get();

        return view('verify.list', compact('total','bals') );
    }
}

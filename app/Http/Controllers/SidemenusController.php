<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;

class SidemenusController extends Controller
{
    //
    public function index()
    {
        //
        // dd('aa');
        $menu = Input::get('menu', 'home');
        // $sidemenus = DB::table('sidemenus')->orderby('menu')->orderby('seq')->simplePaginate(10);
        $sidemenus = DB::table('sidemenus')->simplePaginate(10);
        return view('sidemenus.list',compact('sidemenus','menu') );

    }

    public function create()
    {
        //
        // dd('aa');
        // $menu = Input::get('menu', 'home');
        // $manualinputs = DB::table('manualposts')->where('created_at','>','2018-01-08')->orderby('updated_at','desc')->limit(100)->get();
        // $sidemenus = DB::table('sidemenus')->orderby('menu')->orderby('seq')->simplePaginate(10);
        return view('sidemenus.create');

    }

    public function duplicate($id)
    {
        //
        // $menu = Input::get('menu', 'home');
        // $manualinputs = DB::table('manualposts')->where('created_at','>','2018-01-08')->orderby('updated_at','desc')->limit(100)->get();
        $sidemenu = DB::table('sidemenus')->find($id);
        return view('sidemenus.duplicate',compact('sidemenu'));

    }

    public function store(Request $request)
    {
        //
        DB::table('sidemenus')->insert([

            'menu' => $request->menu,
            'displayname' => $request->displayname,
            'link' => $request->link,
            'seq' => $request->seq,
            //composer require nesbot/carbon

            'created_at' => \Carbon\Carbon::now(),  // \Datetime()

            'updated_at' => \Carbon\Carbon::now(),  // \Datetime()

        ]);

        return redirect('/sidemenus?menu='.$request->menu);
    }

    public function destroy($id)
    {
        //
       $reconcile = DB::table('sidemenus')->delete($id);
    // dd($bscheckpoints);
        return redirect('/sidemenus');
    }
}

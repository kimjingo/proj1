<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Input;
use Carbon\Carbon;
use Illuminate\Support\Facades\Redirect;

class DistributeController extends Controller
{
    //
    public function index() {
        $ttdate = new Carbon('last day of last month');
        $tdate = $ttdate->toDateString();

        $ffdate = new Carbon('first day of last year');
        $fdate = $ffdate->toDateString();

        $fdate = Input::get('fdate', $fdate);
        $tdate = Input::get('tdate', $tdate);

        $acc = Input::get('acc');
        $amt = Input::get('amt');
        $orderid = Input::get('orderid');
        $material = Input::get('material');
        $vendor = Input::get('vendor');
        $clearing = Input::get('clearing');
        $ttype = Input::get('ttype');
        $remark = Input::get('remark');
        $fromdoc = Input::get('fromdoc');
        $ba = Input::get('ba');
        $brand = Input::get('brand');

        $cfdate = Input::get('cfdate', $fdate);
        $ctdate = Input::get('ctdate', $tdate);

        // dd($isPosted);

        $bas = [1,2];
        $mps = DB::table('atr')->distinct()->get(['mp']);
        
        $tdate1 = new Carbon($tdate);
        $tdate1->endOfDay();
        $qtdate = $tdate1->toDateTimeString();

        $ctdate1 = new Carbon($ctdate);
        $ctdate1->endOfDay();
        $qctdate = $ctdate1->toDateTimeString();

        $fromdocs = DB::table('atr as a')
                        ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
                        ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
                        ->distinct()->get(['fromdoc']);

        $vendors = DB::table('atr as a')
            ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
            ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc',$fromdoc);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['mp as vendor']);

        $ttypes = DB::table('atr as a')
            ->where('pdate', '>=', $fdate)->where('pdate', '<', $qtdate)
            ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc',$fromdoc);})
            ->when($ttype, function($query) use ($ttype) { return $query->where('ttype',$ttype);})
            ->distinct()->get(['ttype']);

        $brands = DB::table('atr')->distinct()->get(['brand']);

        
        $accs = DB::table('gacc')->distinct()->get(['accid']);

// dd($qtdate,$qctdate);
        // $distribute = DB::table('atr')->where('fdate','=',$fdate)->orderby('fromdoc')->orderby('transaction_type')->orderby('amount_type')->orderby('tdate','desc')->simplePaginate(10);
        $distribute = DB::table('dist as d')
            ->Join('atr as a', 'd.aid','=','a.keyv')
            // ->where('pdate', '>=', $fdate)->where('pdate', '<=', $qtdate)
            // ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
            // ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc', $fromdoc); })
            // ->when($acc, function($query) use ($acc) { return $query->where('acc', $acc); })
            // ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amt)=?', [$amt]); })
            // ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
            // ->when($material, function($query) use ($material) { return $query->where('material', $material); })
            // ->when($clearing, function($query) use ($clearing) { return $query->where('clearing', $clearing); })
            // ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
            // ->when($remark, function($query) use ($remark) { return $query->where('remark','LIKE', '%'.$remark.'%'); })
            // ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
            // ->when($brand, function($query) use ($brand) { return $query->where('brand', $brand); })
            // ->orderby('pdate', 'desc')
            // ->orderby('keyv')
            // ->orderby('ba')
            ->simplePaginate(10);
        // dd($distribute);
            // ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('distribute.list', compact('fdate','tdate','acc','amt','orderid','material','vendor','clearing','ttype','remark','fromdoc','ba','brand','cfdate','ctdate','bas','mps','fromdocs','ttypes','brands','vendors','accs','distribute') );//
	}

	private function getMat($id)
    {
        //
		$columns = DB::getSchemaBuilder()->getColumnListing('atr');
		// $dcolumns = DB::getSchemaBuilder()->getColumnListing('dist');
		// dd($columns);
		$todistribute = DB::table('dist as d')
            ->Join('atr as a', 'd.aid','=','a.keyv')
	        ->where('d.aid',$id)
	        ->first();

	    $originaltotal = $todistribute->amt;
        $dd = json_decode($todistribute->dd);
        $dlen = count($dd->data);
        $amtperd = ceil($todistribute->amt * 100 / $dlen)/100;
        $totaltodistribute = $todistribute->amt;
        $remainingtotal = $todistribute->amt;

        // foreach($dd->data as $row){
    	foreach ($dd->data as $key => $row){
	        if($remainingtotal){
	        	if($remainingtotal > $amtperd){
		        	$wskudata[] = [
		        		"vendor" => $row->vendor,
		        		"wsku" => $row->wsku,
		        		"amt" => $amtperd
		        	];

		        	$remainingtotal = $remainingtotal - $amtperd;

	        	}else{
	        		$wskudata[] = [
		        		"vendor" => $row->vendor,
		        		"wsku" => $row->wsku,
		        		"amt" => $remainingtotal
		        	];

		        	$remainingtotal = 0;
	        	}
	        }
        }

    	foreach ($wskudata as $key => $rr){
    		// dd($rr['vendor']);
    		$m2d = DB::table('mat')
    			->select('vendor', 'matid')
    			->where('vendor', $rr['vendor'])
    			->where('wsku', $rr['wsku'])
    			->whereNull('invalid')
    			->get();

    		$remainingtotal = $rr['amt'];
    		$mlen = count($m2d);
    		$amtperm = ceil($remainingtotal * 100 / $mlen) / 100;

    		foreach($m2d as $key => $fval){

		        if($remainingtotal){
		        	if($remainingtotal > $amtperm){
			        	$matdata[] = [
			        		"vendor" => $fval->vendor,
			        		"wsku" => $rr['wsku'],
			        		"wamt" => $rr['amt'],
			        		"matid" => $fval->matid,
			        		"amt" => $amtperm
			        	];

			        	$remainingtotal = $remainingtotal - $amtperm;

		        	}else{
		        		$matdata[] = [
			        		"vendor" => $fval->vendor,
			        		"wsku" => $rr['wsku'],
			        		"wamt" => $rr['amt'],
			        		"matid" => $fval->matid,
			        		"amt" => $remainingtotal
			        	];

			        	$remainingtotal = 0;
		        	}
		        }
    		}
        }

        


        // dd($matdata);
	    // dd($todistribute->amt, $dlen, $amtperd,$wskudata);

        return compact('matdata', 'todistribute' ,'columns','originaltotal') ;//
        // return ['matdata' => $matdata, 'todistribute' => $todistribute, 'columns' => $columns, 'originaltotal' => $originaltotal ];
    }


	public function post($id)
    {
        //
        $now = \Carbon\Carbon::now();
		$d2d = $this->getMat($id);
		$i=0;
		foreach($d2d['matdata'] as $mat){

			$res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,qty,orderid,itemid,mp,clearing,ttype,fromdoc,ba, remark, brand, material, created_at,updated_at) SELECT tid,?,pdate,acc,?,qty,orderid,itemid,mp,clearing,ttype,?,ba,remark,?, ?, ?,? from atr where keyv=?", [$i,$mat['amt'],'distribute',$mat['matid'],$mat['vendor'],$now,$now,$id]);
			$i++;
		}

		if($res) {
			$res1 = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,qty,orderid,itemid,mp,clearing,ttype,fromdoc,ba, remark, brand, material, created_at,updated_at) SELECT tid,no,pdate,acc,amt*-1,qty,orderid,itemid,mp,clearing,ttype,?,ba,remark,brand,material,?,? from atr where keyv=?", ['distribute',$now,$now,$id]);
            
            if($res1){

                DB::table('dist')
                    ->where('aid',$id)
                    ->update([
                        'posted_at' => $now //\Carbon\Carbon::now(),  // \Datetime()
                    ]);

            }
		}

        return redirect('/distribute');
    }

	public function show($id)
    {
        //
        $data2distribute = $this->getMat($id);
        // dd($data2distribute);
		// $columns = DB::getSchemaBuilder()->getColumnListing('atr');
		// // $dcolumns = DB::getSchemaBuilder()->getColumnListing('dist');
		// // dd($columns);
		// $todistribute = DB::table('dist as d')
  //           ->Join('atr as a', 'd.aid','=','a.keyv')
	 //        ->where('d.aid',$id)
	 //        ->first();

	 //    $originaltotal = $todistribute->amt;
  //       $dd = json_decode($todistribute->dd);
  //       $dlen = count($dd->data);
  //       $amtperd = ceil($todistribute->amt * 100 / $dlen)/100;
  //       $totaltodistribute = $todistribute->amt;
  //       $remainingtotal = $todistribute->amt;

  //       // foreach($dd->data as $row){
  //   	foreach ($dd->data as $key => $row){
	 //        if($remainingtotal){
	 //        	if($remainingtotal > $amtperd){
		//         	$wskudata[] = [
		//         		"vendor" => $row->vendor,
		//         		"wsku" => $row->wsku,
		//         		"amt" => $amtperd
		//         	];

		//         	$remainingtotal = $remainingtotal - $amtperd;

	 //        	}else{
	 //        		$wskudata[] = [
		//         		"vendor" => $row->vendor,
		//         		"wsku" => $row->wsku,
		//         		"amt" => $remainingtotal
		//         	];

		//         	$remainingtotal = 0;
	 //        	}
	 //        }
  //       }

  //   	foreach ($wskudata as $key => $rr){
  //   		// dd($rr['vendor']);
  //   		$m2d = DB::table('mat')
  //   			->select('vendor', 'matid')
  //   			->where('vendor', $rr['vendor'])
  //   			->where('wsku', $rr['wsku'])
  //   			->whereNull('invalid')
  //   			->get();

  //   		$remainingtotal = $rr['amt'];
  //   		$mlen = count($m2d);
  //   		$amtperm = ceil($remainingtotal * 100 / $mlen) / 100;

  //   		foreach($m2d as $key => $fval){

		//         if($remainingtotal){
		//         	if($remainingtotal > $amtperm){
		// 	        	$matdata[] = [
		// 	        		"vendor" => $fval->vendor,
		// 	        		"wsku" => $rr['wsku'],
		// 	        		"wamt" => $rr['amt'],
		// 	        		"matid" => $fval->matid,
		// 	        		"amt" => $amtperm
		// 	        	];

		// 	        	$remainingtotal = $remainingtotal - $amtperm;

		//         	}else{
		//         		$matdata[] = [
		// 	        		"vendor" => $fval->vendor,
		// 	        		"wsku" => $rr['wsku'],
		// 	        		"wamt" => $rr['amt'],
		// 	        		"matid" => $fval->matid,
		// 	        		"amt" => $remainingtotal
		// 	        	];

		// 	        	$remainingtotal = 0;
		//         	}
		//         }
  //   		}
  //       }


        // dd($matdata);
	    // dd($todistribute->amt, $dlen, $amtperd,$wskudata);

        // return view('distribute.show', compact('wskudata','matdata','todistribute','columns','originaltotal','id') );//
        return view('distribute.show', compact('data2distribute','id') );//
    }
}

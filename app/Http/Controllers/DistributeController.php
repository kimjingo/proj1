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
        $now = \Carbon\Carbon::now();
        // dd($now);

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

        $table = Input::get('table');


        $cfdate = Input::get('cfdate', $fdate);
        
        $snow = $now->toDateString();
        $ctdate = Input::get('ctdate', $snow);

        // dd($isPosted);

        $isPosted = Input::get('isPosted',2);
        if( Input::get('submit') == 'BULKPOST' ) $isPosted = 2;

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

        
        $accs = DB::table('gacc')->where('accgrp', 'profitloss')->distinct()->get(['accid']);
        // $tables = DB::table('dist')->select(DB::raw('dd->"$.table" `table`'))->groupBy('table')->get();
        // dd($tables);

// dd($qtdate,$qctdate);
        // $distribute = DB::table('atr')->where('fdate','=',$fdate)->orderby('fromdoc')->orderby('transaction_type')->orderby('amount_type')->orderby('tdate','desc')->simplePaginate(10);
            $distribute = DB::table('dist as d')
                ->Join('atr as a', 'd.aid','=','a.keyv')
                    ->when($isPosted, function($query) use($isPosted) {
                        if($isPosted == 1){
                            return $query->whereNotNull('posted_at');
                        }elseif($isPosted == 2){
                            return $query->whereNull('posted_at');
                        }
                    })
                    ->where('pdate', '>=', $fdate)->where('pdate', '<=', $qtdate)
                    ->where('a.created_at', '>=', $cfdate)->where('a.created_at', '<=', $qctdate)
                    ->when($fromdoc, function($query) use ($fromdoc) { return $query->where('fromdoc', $fromdoc); })
                    ->when($acc, function($query) use ($acc) { return $query->where('acc', $acc); })
                    ->when($amt, function($query) use ($amt) { return $query->whereRaw('abs(amt)=?', [$amt]); })
                    ->when($vendor, function($query) use ($vendor) { return $query->where('mp', $vendor); })
                    ->when($material, function($query) use ($material) { return $query->where('material', $material); })
                    ->when($clearing, function($query) use ($clearing) { return $query->where('clearing', $clearing); })
                    ->when($ttype, function($query) use ($ttype) { return $query->where('ttype', $ttype); })
                    ->when($remark, function($query) use ($remark) { return $query->where('remark','LIKE', '%'.$remark.'%'); })
                    ->when($ba, function($query) use ($ba) { return $query->where('ba', $ba); })
                    ->when($brand, function($query) use ($brand) { return $query->where('brand', $brand); })
                ->orderby('pdate', 'desc')
                ->orderby('keyv')
                ->orderby('ba')
                ->simplePaginate(10);
            // dd($distribute);
            if(Input::get('submit') == 'BULKPOST'){
                // dd(Input::get('submit'));
                foreach($distribute as $dist){
                    // dd("aa");
                    $this->postbyid($dist->aid);
                    // echo $dist->aid;
                    // echo "<br>";
                }
            }
            // ->when($amt, function($query) use ($amt) { return $query->where('amt', $amt)->orWhere('amt',$amt*-1); })

        // return view('postingrules.list',compact('rules','fromdoc','fromdocs') );
        return view('distribute.list', compact('fdate','tdate','acc','amt','orderid','material','vendor','clearing','ttype','remark','fromdoc','ba','brand','cfdate','ctdate','bas','mps','fromdocs','ttypes','brands','vendors','accs','distribute','isPosted') );//
    }


    private function &getMatFromFbasf($id,&$dd){
            // case "fbasf":

        $key = $dd->data[0]->feedate;        
        // fbasf
        $totalofthemonth = DB::table('fbasf')
            ->where('monthofcharge',$key)
            ->sum('monthly_storage_fee');

        $fbasfs = DB::table('fbasf as f')
            ->select(DB::raw('brand, matid, sum( Average_quantity_on_hand+Average_quantity_pending_removal) qty, sum(monthly_storage_fee) as rate'))
            ->Join('ainv as a', 'f.fnsku','=','a.fnsku')
            ->where('monthofcharge',$key)
            ->groupBy('brand', 'matid')
            ->orderBy('rate', 'desc')
            ->get();
        
        $matdata = array(
            'total' => $totalofthemonth,
            'mats' => $fbasfs
        );

        return $matdata;
        // return { 'total'}
        // select asin,fnsku,monthly_storage_fee from fbasf where monthofcharge="2017-01-00 00:00:00";
        // dd($key);

            

    }

    private function &getMatFromLtsf($id,&$dd){
            // case "ltsf":
        $key = $dd->data[0]->feedate;        
        // fbasf
        $totalofthemonth = DB::table('ltsf')
            ->select(DB::raw('SUM(fee6+fee12) as total'))
            ->where('snapshot_date',$key)
            ->first();
            // ->sum('fee6+fee12');
        // dd($totalofthemonth->total);

        $fbasfs = DB::table('ltsf as f')
            ->select(DB::raw('brand, matid, sum( qty6+qty12) qty, sum(fee6+fee12) as rate'))
            ->Join('ainv as a', 'f.fnsku','=','a.fnsku')
            ->where('snapshot_date',$key)
            ->groupBy('brand', 'matid')
            ->orderBy('rate', 'desc')
            ->get();
        
        $matdata = array(
            'total' => $totalofthemonth->total,
            'mats' => $fbasfs
        );

        return $matdata;

    }

    private function &getMatFromFbash($id,&$dd){
            // case "fbash":
        $key = $dd->data[0]->fshipmentid;        
        // fbasf
        $totalofthemonth = DB::table('fbash')
            ->select(DB::raw('SUM(qtysent) as total'))
            ->where('fshipmentid',$key)
            ->first();
            // ->sum('fee6+fee12');
        // dd($totalofthemonth->total);

        $fbasfs = DB::table('fbash as f')
            ->select(DB::raw('brand, matid, sum(qtysent) qty, sum(qtysent) as rate'))
            ->Join('ainv as a', 'f.fnsku','=','a.fnsku')
            ->where('fshipmentid',$key)
            ->groupBy('brand', 'matid')
            ->orderBy('rate', 'desc')
            ->get();
        
        $matdata = array(
            'total' => $totalofthemonth->total,
            'mats' => $fbasfs
        );

        return $matdata;

    }

    private function &getMatFromMonthlyStock($id,&$dd){
            // case "monthly_brand_mat_qty":
        $key = $dd->data[0]->feedate;        
        // fbasf
        $totalofthemonth = DB::table('monthly_brand_mat_qty')
            ->select(DB::raw('SUM(qty) as total'))
            ->where('pdate',$key)
            ->first();
            // ->sum('fee6+fee12');
        // dd($totalofthemonth->total);

        $fbasfs = DB::table('monthly_brand_mat_qty as f')
            ->select(DB::raw('brand, mat matid, sum(qty) qty, sum(qty) as rate'))
            ->where('pdate',$key)
            ->groupBy('brand', 'matid')
            ->orderBy('rate', 'desc')
            ->get();
        
        $matdata = array(
            'total' => $totalofthemonth->total,
            'mats' => $fbasfs
        );

        return $matdata;

    }

    private function &getMatFromWsku($id,&$dd)
    {
        //
        $total = 0;

        // dd($dd->data);

        foreach ($dd->data as $val){
            // dd($val['vendor']);
            $m2d = DB::table('mat')
                ->select('vendor as brand', 'matid')
                ->where('vendor', $val->brand)
                ->where('wsku', $val->wsku)
                ->whereNull('invalid')
                ->get();

            $mlen = count($m2d);

            foreach($m2d as $key => $fval){

                $mmm = [
                    "brand" => $fval->brand,
                    "matid" => $fval->matid,
                    "qty" => 1,
                    "rate" => 100 / $mlen
                ];
                $mats[] = (object)$mmm;

                $total += 100 / $mlen;
            }
        }

        // // $mm = (object)$mats;
        // foreach($mm as $val){
        //     $out .= $val->matid;
        // }
        // dd($out);
        // dd($mats);

        $matdata = array(
            'total' => $total,
            'mats' => $mats
        );
        // dd($matdata);
        return $matdata;
    }

    private function getMatAmt($id)
    {
        //
        // case : mat,fbasf,ltsf,fbass,fbash
        // $dcolumns = DB::getSchemaBuilder()->getColumnListing('dist');
        // dd($columns);
        $todistribute = DB::table('dist as d')
            ->Join('atr as a', 'd.aid','=','a.keyv')
            ->where('d.aid',$id)
            ->first();

            // ->select(DB::raw('amt, dd'))
        // $originaltotal = $todistribute->amt;
        $dd = json_decode($todistribute->dd);

        switch ($dd->table){
            case "mat":
                $matdata_ref = $this->getMatFromMat($id,$dd);
            break;

            case "wsku":
                $matdata_ref = $this->getMatFromWsku($id,$dd);
            break;

            case "fbasf":
                $matdata_ref = $this->getMatFromFbasf($id,$dd);

            break;

            case "ltsf":
                $matdata_ref = $this->getMatFromLtsf($id,$dd);

            break;

            case "fbash":
                $matdata_ref = $this->getMatFromFbash($id,$dd);


            break;

            case "monthly_brand_mat_qty":
                $matdata_ref = $this->getMatFromMonthlyStock($id,$dd);

            break;

        }

        $matdata['rtotal'] = $matdata_ref['total'];
        $remainingtotal = $todistribute->amt;

        foreach($matdata_ref['mats'] as $key => $rval){
            // ceil($todistribute->amt * 100 / $dlen)/100
            $amtperm = ceil($todistribute->amt * 100 * $rval->rate / $matdata_ref['total'])/100;

            if($remainingtotal){
                if($remainingtotal > $amtperm){
                    $matdata['mats'][] = array(
                        "brand" => $rval->brand,
                        "matid" => $rval->matid,
                        "qty" => $rval->qty,
                        "rate" => $rval->rate,
                        "amt" => $amtperm,
                    );

                    $remainingtotal = $remainingtotal - $amtperm;

                }else{
                    $matdata['mats'][] = array(
                        "brand" => $rval->brand,
                        "matid" => $rval->matid,
                        "qty" => $rval->qty,
                        "rate" => $rval->rate,
                        "amt" => $remainingtotal
                    );

                    $remainingtotal = 0;
                }
            }
        }

        // dd($matdata);
        // return $matdata;
        return array('matdata'=>$matdata, 'todistribute'=>$todistribute) ;//
        // $columns = DB::getSchemaBuilder()->getColumnListing('atr');

        // dd($todistribute->amt, $dlen, $amtperd,$wskudata);

        // return ['matdata' => $matdata, 'todistribute' => $todistribute, 'columns' => $columns, 'originaltotal' => $originaltotal ];
    }

    public function postbyid($id)
    {
        //
        $now = \Carbon\Carbon::now();
        $d2d = $this->getMatAmt($id);

        $i=0;
        foreach($d2d['matdata']['mats'] as $mat){

            $res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,qty,orderid,itemid,mp,clearing,ttype,fromdoc,ba, remark, brand, material, created_at,updated_at) SELECT tid, ?,pdate,acc, ?, ?,orderid,itemid,mp,clearing,ttype, ?,ba,remark, ?, ?, ?, ? from atr where keyv=?", [$i, $mat['amt'], $mat['qty'], 'distribute',  $mat['brand'],$mat['matid'], $now, $now, $id]);
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

    }

    public function post($id)
    {
        //
        $this->postbyid($id);
        return redirect('/distribute');
    }

	public function show($id)
    {
        //
        // $d2d = $this->getMatAmt($id);
        // $d2d = 
        $d2d = $this->getMatAmt($id);
        // dd($d2d['matdata']);
        // dd($d2d);
        return view('distribute.show', compact('d2d') );//
    }

    public function test($id)
    {
        //
        $todistribute = DB::table('dist as d')
            ->Join('atr as a', 'd.aid','=','a.keyv')
            ->where('d.aid',$id)
            ->first();

        $originaltotal = $todistribute->amt;
        $dd = json_decode($todistribute->dd);

        $this->getMatFromFbasf($id,$dd);
    }
}

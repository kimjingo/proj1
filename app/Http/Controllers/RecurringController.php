<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RecurringController extends Controller
{
    //
    public function get_enum_values( $table_name, $field )
	{
		$result = DB::select("show columns from {$table_name} WHERE Field = '{$field}'");
		// foreach ($columns as $value) {
		//    echo "'" . $value->Field . "' => '" . $value->Type . "|" . ( $value->Null == "NO" ? 'required' : '' ) ."', <br/>" ;
		// }
	    $type = $result[0]->Type;
	    preg_match("/^enum\(\'(.*)\'\)$/", $type, $matches);
	    $enum = explode("','", $matches[1]);
	    return $enum;
	}
    
	public function get_clearing_from_date($cycle,$dtPdate)
	{
        // dd($cycle);
        switch ($cycle) {
            case 'biennially':
                $clearing = date('Y',$dtPdate);

            break;

            case 'yearly':
                $clearing = date('Y',$dtPdate);

            break;

            case 'biannually':
                $clearing = date('Ym',$dtPdate);

            break;

            case 'quarterly':
                $clearing = date('Ym',$dtPdate);
            break;

            case 'bimonthly':
                $clearing = date('Ym',$dtPdate);

            break;

            case 'monthly':
                $clearing = date('Ym',$dtPdate);

            break;

            case 'biweekly':
                $clearing = date('YW',$dtPdate);
            break;

            case 'weekly':
                $clearing = date('YW',$dtPdate);
            break;

            case 'daily':
                $clearing = date('Ymd',$dtPdate);
            break;

            case 'hourly':
                $clearing = date('Ymdh',$dtPdate);
            break;
        }
        return $clearing;

    }

	public function get_next_pdate( $cycle, $dtLastposted, $dd, $mm )
	{
        switch ($cycle) {
            case 'biennially':
                $dtPdate = strtotime('+2 year',$dtLastposted);
                // $clearing = date('Y',$dtPdate);

            break;

            case 'yearly':
                $dtPdate = strtotime('+1 year',$dtLastposted);
                // $clearing = date('Y',$dtPdate);

            break;

            case 'biannually':
                $dtPdate = strtotime('+6 month',$dtLastposted);
                // $clearing = date('Ym',$dtPdate);

            break;

            case 'quarterly':
                $dtPdate = strtotime('+3 month',$dtLastposted);
                // $clearing = date('Ym',$dtPdate);
            break;

            case 'bimonthly':
                $recurringdate = ($dd==0)? 't':$dd;
                $dtPdate = strtotime(date('Y-m-'.$dd, strtotime('+2 month',$dtLastposted)));
                // $clearing = date('Ym',$dtPdate);

            break;

            case 'monthly':
                $recurringdate = ($dd==0)? 't':$dd;
                // dd( $cycle,date('Y-m-d',$dtLastposted),$dd,date('Y-m-1', strtotime('+1 month',$dtLastposted)) );
                
                $dtPdate = strtotime(date('Y-m-'.$dd, strtotime('+1 month',$dtLastposted)));
                // $clearing = date('Ym',$dtPdate);

            break;

            case 'biweekly':
            	$recurringdate = ($dd)? $dd:0;
            	$dayofweek = date('w', $dtLastposted);
				$dtPdate = strtotime(($recurringdate - $dayofweek).' day', strtotime('+2 week',$dtLastposted));
                // $clearing = date('YW',$dtPdate);
            break;

            case 'weekly':
            	$dtPdate = strtotime('+1 week',$dtLastposted);
                // $clearing = date('YW',$dtPdate);
            break;

            case 'daily':
            	$dtPdate = strtotime('+1 day',$dtLastposted);
                // $clearing = date('Ymd',$dtPdate);
            break;

            case 'hourly':
            	$dtPdate = strtotime('+1 hour',$dtLastposted);
                // $clearing = date('Ymdh',$dtPdate);
            break;
        }		
        // dd($dtPdate);
        // return ['pdate' => $dtPdate, 'clearing' => $clearing];
        return $dtPdate;
	}

	public function index()
    {
        //
        $recurrings = DB::table('recurring')
            // ->join('apay2_acc', 'recurring.fromdoc','=','reconcile.id')
            // ->orderby('accid')
            // ->orderby('checkdate')
            ->get();
            // dd($recurring);
        return view('recurring.list', compact('recurrings'));
    }

    public function add()
    {
        //
		$accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);
        
        $columns = DB::select("show columns from recurring");
        // dd($columns);
        // $cycles = ['yearly','monthly','biweekly','weekly','daily','hourly'];
        $cycles = $this->get_enum_values('recurring','cycle');
        // $types = ['expense','storage','others'];
        $types = $this->get_enum_values('recurring','type');
        $options = [
        	'cycle' => $cycles,
        	'type' => $types
        ];
        // $types = (object) array('expense','storage','others');
        // $mats[] = (object)$mmm;

        return view('recurring.add',compact('columns','accCalJSON','accs','options'));
    }

    public function duplicate($id)
    {
        //
        $rec = DB::table('recurring')->find($id);
        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $accCalJSON = json_encode($accCal);
        
        $columns = DB::select("show columns from recurring");
        // dd($columns);
        // $cycles = ['yearly','monthly','biweekly','weekly','daily','hourly'];
        $cycles = $this->get_enum_values('recurring','cycle');
        // $types = ['expense','storage','others'];
        $types = $this->get_enum_values('recurring','type');
        $options = [
            'cycle' => $cycles,
            'type' => $types
        ];
        // $types = (object) array('expense','storage','others');
        // $mats[] = (object)$mmm;

        return view('recurring.add',compact('columns','accCalJSON','accs','options','rec'));
    }


    public function store(Request $request)
    {
        //
        $this->validate($request, [
        	'name' => 'required|max:255',
        	'vendor' => 'required|max:20',
        	'material' => 'required|max:40',
        	'type' => 'required',
        	'cycle' => 'required',
        	'startdate' => 'required',
        	'amt' => 'required|min:0.01',
	    ]);

        $len = count($request->seq);
        // dd($len);
        $now = \Carbon\Carbon::now();


        $accs = DB::table('gacc')->select('accid','dir','gdir')->get();
        foreach($accs as $acc) {
            $accCal[$acc->accid] = array(
                "dir" => $acc->dir,
                "gdir" => $acc->gdir
            );
        }
        $checksum = 0;
        for($i=0; $i < $len; $i++){
            $checksum += $request->rate[$i] * $request->dir[$i] * $accCal[$request->acc[$i]]['dir'] * $accCal[$request->acc[$i]]['gdir'] ;

        }        
         $enddate = ($request->enddate > '2038-01-17' )? '2038-01-17':$request->enddate;

        if(!$checksum){
        	DB::table('recurring')->insert([
    			'name' => $request->name,
				'startdate' => $request->startdate,
				'enddate' => $enddate,
				'recurringdate' => $request->recurringdate,
				'cycle' => $request->cycle,
				'type' => $request->type,
				'vendor' => $request->vendor,
				'material' => $request->material,
				'amt' => $request->amt,
				'clearing' => $request->clearing,
				'created_at' => $now,
				'updated_at' => $now,
    		]);
			// DB::insert("INSERT INTO recurring(name,startdate,enddate,lastposted_date,recurringdate,cycle,type,vendor,material,amt,clearing,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?,?)", [$request->name,$request->startdate,$request->enddate,$request->lastposted_date,$request->recurringdate,$request->cycle,$request->type,$request->vendor,$request->material,$request->amt,$request->clearing,$now,$now] ) ;
// $aa = "";
            for($i=0; $i < $len; $i++){
            	// $aa .= $request->acc[$i];
                DB::insert("INSERT IGNORE INTO apay2_acc(fromdoc,transaction_type,amount_type,amount_description,acc,dir,aseq,ttype,rate,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?,?)", ['recurring',$request->type,$request->vendor,$request->material,$request->acc[$i],$request->dir[$i],$request->seq[$i],$request->type,$request->rate[$i],$now,$now]) ;
            }
            // dd($aa);

        }
        
        return redirect('/recurring');
        // return redirect()->back();


    }

    public function post(Request $request)
    {
//         //
        $now = \Carbon\Carbon::now();
        $dtNow = strtotime($now);

        for($i=0; $i < count($request->id); $i++){
            $rec = DB::table('recurring')->find($request->id[$i]);
//         //  $thispdate = strtotime(date('Y-m-1',time());
//         //  $lastestdatetopost;
//         //  $now;
            $dtStartdate = strtotime($rec->startdate);
            $dtEnddate = strtotime(date('Y-m-d 23:59:59',strtotime($rec->enddate)));
            // dd(date('Y-m-d',$dtEnddate));
//             // $lastposted_date = '2017-10-01';

//             $pdate = date('Y-m-d',strtotime(date('Y-m-'.$recurringdate, strtotime('+1 month',strtotime($lastposted_date)))));
//                 dd($pdate);



            if($rec->lastposted_date) {
                $dtLastposted = strtotime($rec->lastposted_date);
                // $next2post = $this->get_next_pdate( $rec->cycle, $dtLastposted, $rec->recurringdate, $rec->recurringmonth );
                $dtPdate = $this->get_next_pdate( $rec->cycle, $dtLastposted, $rec->recurringdate, $rec->recurringmonth );

            } else {
                $dtPdate = $dtStartdate;
                // $next2post = [
                //     'pdate' => $dtPdate, 
                //     'clearing' => $this->get_clearing_from_date($rec->cycle,$dtPdate)
                // ];
            }

// //         dd($pdate);

// // // dd($rec->lastposted_date);
// //         //  if($startdate < $now && $now < $enddate){

// //         //  }

//             // do while($dtStartdate <= $dtPdate && $lastposted_date <= $pdate && $pdate < $endate) {
            $j = 0;
            while( $dtPdate <= $dtEnddate && $dtPdate <= $dtNow && $j < 20 ) {
// echo $i.','.$j.','.date('Y-m-d',$dtPdate).','.$clearing.','.$clearing.','.$now.','.$now.','.$request->id[$i];
// echo "<br>";
                $clearing = ($rec->clearing)? $rec->clearing : $this->get_clearing_from_date($rec->cycle,$dtPdate) ;
                $res = DB::insert("INSERT INTO atr(tid,no,pdate,acc,amt,material,mp,clearing,ttype,fromdoc,remark,created_at,updated_at) SELECT a.aseq,r.id,?,a.acc,r.amt*a.dir*a.rate,r.material,r.vendor,?,r.type,a.fromdoc,concat(r.name,?),?,? FROM recurring r, apay2_acc a WHERE a.transaction_type = r.type AND a.amount_type = r.vendor AND a.amount_description = r.material AND id = ? AND a.fromdoc= 'recurring'", [date('Y-m-d H:i',$dtPdate),$clearing,$clearing,$now,$now,$request->id[$i]]);

                if($res){
                    DB::table('recurring')
                        ->where('id',$request->id[$i])
                        ->update([
                            'lastposted_date' => date('Y-m-d H:i',$dtPdate) //\Carbon\Carbon::now(),  // \Datetime()
                        ]);
				    $dtLastposted = $dtPdate;
                    $dtPdate = $this->get_next_pdate( $rec->cycle, $dtLastposted, $rec->recurringdate, $rec->recurringmonth );
                // dd($rec->cycle, date('Y-m-d',$dtLastposted, $rec->recurringdate, $rec->recurringmonth);
                } else {
                    break;
                }
                $j++;
            }
        }
        return redirect('/recurring');
    }
}

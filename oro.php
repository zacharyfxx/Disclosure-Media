<!doctype html>

<?php
include('simple_html_dom.php');
date_default_timezone_set('America/Los_Angeles');
/*
3537577

//Conrad Human​!oro , add a % of lake , eg af/ 100%﻿ af
​//%Full﻿ of Total Reservoir Capacity=Storage(cdec)/ Total Reservoir Capacity (Known constant) x100%
​// past hour and 24 hr rise for !oro
*/


//https://pastebin.com/6eBCAZ2c
//​xxx = (mean_inflow - mean_outflow)﻿ / 170000


$url = "https://cdec.water.ca.gov/cgi-progs/queryF?ORO";
$urldate = '&d='.date('d-M-Y+H:i');
$url .= $urldate."&span=25hours";
// echo $urldate.'<br/>';
//echo $url;
$data = file_get_html($url);
foreach($data->find('table tr') as $r) {
	//$arr[] = trim($r->innertext); // rows
	$date[] = strip_tags(trim($r->find('td',0)));
	$res_ele[] = (float)strip_tags(trim($r->find('td',1)));
	$storage[] = (int)strip_tags(trim($r->find('td',3)));
	$outflow[] = (int)strip_tags(trim($r->find('td',5)));
	$inflow[] = (int)strip_tags(trim($r->find('td',7)));
	$rivrel[] = strip_tags(trim($r->find('td',9)));
	$rain[] = strip_tags(trim($r->find('td',11)));
}
for($h = sizeof($outflow)-1; $outflow[$h] == "--"; $h--) {
	array_pop($storage);
	array_pop($outflow);
	array_pop($inflow);

}


$cap = ($storage[$h]/3537577)*100;
$cap = round($cap,2)."%";

$ele_rise1 = sprintf("%+.02f",$res_ele[$h]-$res_ele[$h-1]);
$ele_rise24 = sprintf("%+.02f",$res_ele[$h]-$res_ele[3]);
$ele_rise1 = ($ele_rise1>0)?'+'.number_format($ele_rise1,2,'.',',') : number_format($ele_rise1,2,'.',',');
$ele_rise24 = ($ele_rise24>0)?'+'.number_format($ele_rise24,2,'.',',') : number_format($ele_rise24,2,'.',',');

//echo "<br>".$ele_rise24."<br>";

$storage_rise1 = $storage[$h]-$storage[$h-1];
$storage_rise24 = $storage[$h]-$storage[3];
//+/-
$storage_rise1 = ($storage_rise1>0)?'+'.number_format($storage_rise1,0,'.',',') : number_format($storage_rise1,0,'.',',');
$storage_rise24 = ($storage_rise24>0)?'+'.number_format($storage_rise24,0,'.',',') : number_format($storage_rise24,0,'.',',');

$net1 = $inflow[$h]-$outflow[$h];
$net1 = ($net1>0)?'+'.number_format($net1,0,'.',',') : number_format($net1,0,'.',',');
$net1 = " Δ".$net1."cfs";

$rain24 = $rain[$h]-$rain[3];
if($rain24>0) {	
	$rain24 = sprintf("%+.02f",round($rain24, 2));
	$rain_d = " Rain:".$rain[$h].'"(24h:'.$rain24.'")';
} else { $rain_d = ""; }



//print($arr);

//echo $arr[1];
if(isset($_GET['avg'])) {
	$h = $_GET['avg'];
	$y = sizeof($date)-1;
	$in_t;
	$out_t;
	$in_=array();
	$out_=array();

	if($h <= 24 && $h > 1) {
		
		//for($i=3; $i<sizeof($storage); $i++) {
		// for($i=3; $i+$h<$h+4; $i++,$y++) {
		// 	$in_[] = $inflow[$i];
		// 	$out_[] = $outflow[$i];
		// 	//echo "[{$y}] ".$inflow[$i];
		// 	//if($h>)
			
		// }

		//$in_ = array_reverse($in_);
		//$out_ = array_reverse($out_);
		
		$in_ = array_slice($inflow, -$h, $h);
		$out_ = array_slice($outflow, -$h, $h);

		//$y-=2;
//		print($in_[$h]." : ");
		// var_dump($in_);
		// var_dump($out_);
		// var_dump($date);


		$output = "[".substr($date[$h+3],11,5)."] ";
		$output .= ($h)."hr Average: ";
		$output .= "In: ".number_format((int)(array_sum($in_)/$h),0,'.',',')."cfs ";
		$output .= "(Max: ".number_format((int)max($in_),0,'.',',')."cfs, ";
		$output .= "Min: ".number_format((int)min($in_),0,'.',',')."cfs), ";
		$output .= "Out: ".number_format((int)(array_sum($out_)/$h),0,'.',',')."cfs ";
		$output .= "(Max: ".number_format((int)max($out_),0,'.',',')."cfs, ";
		$output .= "Min: ".number_format((int)min($out_),0,'.',',')."cfs)";

	} else {$output = "!avg (2-24)";}

} else {
$output = "[".substr($date[$h],11,5)."] Oroville ".$cap." Storage: ".number_format($storage[$h],0,'.',',')."AF (".$storage_rise1."AF, 24h".$storage_rise24."AF) Elevation: ".$res_ele[$h]."'(".$ele_rise1."', 24h".$ele_rise24."') Out:".number_format($outflow[$h],0,'.',',')."cfs In:".number_format($inflow[$h],0,'.',',')."cfs ".$net1.$rain_d ;
}

//echo $arr[14];

//print_r($storage);

echo (strlen($output)>200)? ">200" : preg_replace('/\s+/', ' ',$output);
//echo $data;
//echo $table;

?>



</html>
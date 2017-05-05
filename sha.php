<?php
include('simple_html_dom.php');
date_default_timezone_set('America/Los_Angeles');
//4552000
$url = "https://cdec.water.ca.gov/cgi-progs/queryF?SHA";
$urldate = '&d='.date('d-M-Y+H:i');
$url .= $urldate."&span=25hours";
// echo $urldate.'<br/>';

$data = file_get_html($url);
foreach($data->find('table tr') as $r) {
	$arr[] = trim($r->innertext); // rows
	$date[] = strip_tags(trim($r->find('td',0)));
	$res_ele[] = (float)strip_tags(trim($r->find('td',1)));
	$storage[] = (int)strip_tags(trim($r->find('td',3)));
	$outflow[] = (int)strip_tags(trim($r->find('td',5)));
	$inflow[] = (int)strip_tags(trim($r->find('td',7)));
}

for($h = sizeof($date)-1; $outflow[$h] == "--"; $h--) {}

//$b = $h-(sizeof($date)-1);
//if($b<0) { echo $b."hr"; }


$cap = ($storage[$h]/4552000)*100;
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
$net1 = " (Δ ".$net1."CFS)";

//print_r($arr);

//echo $arr[1];
$output = "[".substr($date[$h],11,5)."] Shasta ".$cap." Storage: ".number_format($storage[$h],0,'.',',')." AF (".$storage_rise1."AF, 24h".$storage_rise24."AF) Elevation: ".number_format($res_ele[$h],0,'.',',')."'(".$ele_rise1."', 24h:".$ele_rise24."') Outflow: ".number_format($outflow[$h],0,'.',',')."CFS Inflow: ".number_format($inflow[$h],0,'.',',')."CFS ".$net1;

//echo $arr[14];

//print_r($storage);
echo preg_replace('/\s+/', ' ',$output);
//echo $data;
//echo $table;

?>
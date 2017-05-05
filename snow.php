<?php
//ini_set('memory_limit', '256M');
include('simple_html_dom.php');
 
date_default_timezone_set('America/Los_Angeles');
//Snow
//http://cdec.water.ca.gov/cgi-progs/products/PLOT_SWC.pdf

$url = "http://cdec.water.ca.gov/cgi-progs/querySWC?reg=CENTRAL";
$html = mb_substr(file_get_contents($url),0,30000);
$data = str_get_html($html);
//$line = $data->find('tr',1);
//$table = $data->find("table")->plaintext;
foreach($data->find('table tr') as $r) {
	//$arr[] = trim($r->innertext); // rows
	$date[] = str_replace("&nbsp;&nbsp", '', preg_replace('/\s+/', '',strip_tags($r->find('td',0))));
	$swc[] = (float)preg_replace('/\s+/', '',strip_tags($r->find('td',3)));
	$apr[] = (int)preg_replace('/\s+/', '',strip_tags($r->find('td',4)));
	$norm[] = (int)preg_replace('/\s+/', '',strip_tags($r->find('td',5)));

}

$swc_d = sprintf("%+.1f",$swc[1]-$swc[2],2);
$apr_d = sprintf("%+d",$apr[1]-$apr[2],2);
$norm_d = sprintf("%+d",$norm[1]-$norm[2],2);

//echo $arr[0];
 // print_r($swc);
$str = 'Central Watershed Snow: '.$date[1].' Avg. SWC: '.$swc[1].'"('.$swc_d.'") April 1: '.$apr[1].'%('.$apr_d.'%) Normal: '.$norm[1]."%(".$norm_d."%)";

//str_replace(" ",'',$str);
echo utf8_encode($str);

?>
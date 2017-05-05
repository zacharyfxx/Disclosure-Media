<?php

$dtype = "forecast/daily?";
$days = "&cnt=4";
$link = "http://api.openweathermap.org/data/2.5/".$dtype;
$coords = "lat=39.511261&lon=-121.475189";
$id="&id=5379759";
$zip = "&zip=95966,us";
$OWM_api_key = "&APPID=631d58029795a1c4e9af8814c776ceb6";

$url = $link.$zip.$days.$OWM_api_key;

  

$page = file_get_contents($url."&mode=xml");
//$json = json_decode($page);
$xml = simplexml_load_string($page);
//var_export($json);
//pretty_var($xml);
$json = json_encode($xml);
$json = json_decode($json,true);
//print_r($json);


//print_r($json['forecast']['time'][0]['temperature']['@attributes']);

$temp_high = array();
$temp_low = array();
$date = array();
for ($i = 1; $i <= 3; $i++) {
  $att = $json['forecast']['time'][$i];
  $temp_high[$i] = $att['temperature']['@attributes']['max'];
  $temp_low[$i] = $att['temperature']['@attributes']['min'];
  $date[$i] = $att['@attributes']['day'];
}
//print_r($temp_high);
//print_r($temp_low);
//print_r($date);

//$forecast = $temp_high_1;
$forecast = "Weather forecast for {$json['location']['name']}: ";
// [".$dt1."] (".F($temp_high[0])."/".F($temp_low[0])."),
foreach ($temp_high as $i=>$high) {
  $date[$i] = str_replace("-","/",$date[$i]);
  $forecast .= "[".substr($date[$i],5,5)."] <(".F($high)."/".F($temp_low[$i])."), (".C($high)."/".C($temp_low[$i]).")>".($i==count($temp_high) ? "" : ", ");
}


echo $forecast;



//°F & °C conversion
function F($k) {
  $f = ($k-273.15)*(9/5)+32;
  return (int)$f."°F";
}

function C($k) {
  $c = $k-273.15;
  return (int)$c."°C";
}



?>
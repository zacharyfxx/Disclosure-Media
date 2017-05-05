<?php
//weather data
//7262050	Oroville East	39.511261	-121.475189	US
if(isset($_GET['f'])) {
  $dtype = "forecast/daily?";
  $days = "&cnt=4";
}else{
  $dtype = "weather?";
  $days = "";

}
/*
$link = "http://api.openweathermap.org/data/2.5/".$dtype;
$coords = "lat=39.511261&lon=-121.475189";
$id="&id=5379759";
$zip = "&zip=95966,us";
$OWM_api_key = "&APPID=631d58029795a1c4e9af8814c776ceb6";

$url = $link.$zip.$days.$OWM_api_key;
//print($url);
*/

if(isset($_GET['f'])) {

//!oro





/*
  $page = file_get_contents($url."&mode=xml");
  //$json = json_decode($page);
  $xml = simplexml_load_string($page);
  //var_export($json);
  //pretty_var($xml);
  $json = json_encode($xml);
  $json = json_decode($json,true);
  
  print_r($json['sun']);


  //print_r($json['forecast']['time'][0]['temperature']['@attributes']);
  //$sunset = $json['sun']['@attributes']['set'];
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


  // $rainfall = round($json_rain->list[0]->forecast->{'3h'}, 2);


  $forecast = "Weather forecast for {$json['location']['name']}: ";
  // [".$dt1."] (".F($temp_high[0])."/".F($temp_low[0])."),
  foreach ($temp_high as $i=>$high) {
    $date[$i] = str_replace("-","/",$date[$i]);
    $forecast .= "[".substr($date[$i],5,5)." (".F($high)."/".F($temp_low[$i])."), (".C($high)."/".C($temp_low[$i]).")]".($i==count($temp_high) ? "" : ", ");
  }
 // $forecast = $sunset;
 */





} else {
/*
  $page = file_get_contents($url);
  $json = json_decode($page);
  //var_export($json);
  //pretty_var($json);

  $temp = $json->main->temp;
  $temp_low = $json->main->temp_min;
  $temp_high = $json->main->temp_max;
  $weather = $json->weather[0]->description;
  $wind_speed = $json->wind->speed;
  $wind_dir = getDirection($json->wind->deg);
  $humidity = $json->main->humidity;
  $pressure = $json->main->pressure;

  //echo("Current weather in Oroville: ".$weather." ".F($temp)." (".F($temp_low)."/".F($temp_high)."), \n".C($temp)." (".C($temp_low)."/".C($temp_high).") \nWind: ".$wind_dir."@".$wind_speed."mph \nHumidity: ".$humidity."% \nBarometer: ".$pressure." hpa");

  $current = ("Current weather in Oroville: ".$weather." ".F($temp)." (".F($temp_low)."/".F($temp_high)."), \n".C($temp)." (".C($temp_low)."/".C($temp_high).") \nWind: ".$wind_dir."@".$wind_speed."mph \nHumidity: ".$humidity."% \nBarometer: ".$pressure." hpa");
*/

}


if(isset($_GET['f'])) {
	echo $forecast;
} else {
	echo $current;
}


//Functions

//°F & °C conversion
function F($k) {
	$f = ($k-273.15)*(9/5)+32;
	return (int)$f."°F";
}

function C($k) {
	$c = $k-273.15;
	return (int)$c."°C";
}

//bearing to cardinal
function getDirection($bearing)
{
 $cardinalDirections = array( 
  'N' => array(337.5, 22.5), 
  'NE' => array(22.5, 67.5), 
  'E' => array(67.5, 112.5), 
  'SE' => array(112.5, 157.5), 
  'S' => array(157.5, 202.5), 
  'SW' => array(202.5, 247.5), 
  'W' => array(247.5, 292.5), 
  'NW' => array(292.5, 337.5) 
 ); 
 
 foreach ($cardinalDirections as $dir => $angles)
 { 
  if ($bearing >= $angles[0] && $bearing < $angles[1])
  { 
   $direction = $dir; 
   break;
  } 
 } 
 return $direction;
}

function pretty_var($myArray)
{ 
    echo "<pre>";
    var_export($myArray);
    echo "</pre>";
} 

?>
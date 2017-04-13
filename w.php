<?php
//weather data
//7262050	Oroville East	39.511261	-121.475189	US

$link = "http://api.openweathermap.org/data/2.5/forecast?";
$coords = "lat=39.511261&lon=-121.475189";
$OWM_api_key = "&APPID=631d58029795a1c4e9af8814c776ceb6";

$page = file_get_contents($link.$coords.$OWM_api_key);
$json = json_decode($page);
//var_export($json);
//pretty_var($json);

$temp = $json->list[0]->main->temp;
$temp_low = $json->list[0]->main->temp_min;
$temp_high = $json->list[0]->main->temp_max;
$weather = $json->list[0]->weather[0]->description;
$wind_speed = $json->list[0]->wind->speed;
$wind_dir = getDirection($json->list[0]->wind->deg);
$rainfall = round($json->list[0]->rain->{'3h'}, 2);
$humidity = $json->list[0]->main->humidity;
$pressure = $json->list[0]->main->pressure;

$temp_high_1 = $json->list[1]->main->temp_min;
//$temp_low_1

//$temp_high_2
//$temp_low_2
if(isset($_POST["f"]) {
	echo $temp_high_1;

} else {
echo("Current weather in Oroville: ".$weather." ".F($temp)." (".F($temp_low)."/".F($temp_high)."), \n".C($temp)." (".C($temp_low)."/".C($temp_high).") \nWind: ".$wind_dir."@".$wind_speed."mph \nRainfall(3h): ".$rainfall." \nHumidity: ".$humidity."% \nBarometer: ".$pressure." hpa");
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
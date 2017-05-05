<?php
date_default_timezone_set('America/Los_Angeles');
//Accuweather

$url = "http://dataservice.accuweather.com";
$key = "apikey=%09akpcYGWsGJEdMBRowXClBii4pgiixHOI";
  $key .= "&details=true";
// if($_GET['l'] == "sha") {
//   $id = "3039566?";//shasta
//   $loc = "Shasta";
// } else {
  $id = "331994?";//oroville
  $loc = "Oroville";
// }

$fore = $url."/forecasts/v1/daily/5day/".$id.$key;
$cur = $url."/currentconditions/v1/".$id.$key;

$wf = json_decode(file_get_contents($fore), true);
$cw = json_decode(file_get_contents($cur), true);
$daily = $wf['DailyForecasts'];
  
//  echo $fore;

$sunrise = $daily[0]['Sun']['EpochRise'];
$sunset = $daily[0]['Sun']['EpochSet'];
$day;
//echo "rise0:".$sunrise." set0:".$sunset." dateu:".date('U');
$udate = date('U');
if ($udate<$sunrise || $udate>$sunset) { 
  $day = "Night";
  $sun = "Sunrise:".date("g:ia",$sunrise);
} else {
  $day = "Day";
  $sun = "Sunset:".date("g:ia",$sunset);
}


if(isset($_GET['f'])) {
  $headline = $wf['Headline']['Text'];

  //"to".strtolower($day);
  $output = $loc." Forecast: ".$headline.".";

  for($i=1; $i<4; $i++) {
    $t = $i-1;
    $temp_max_f[] = $daily[$i]['Temperature']['Maximum']['Value'];
    $temp_min_f[] = $daily[$i]['Temperature']['Minimum']['Value'];
    $temp_max_c[] = C($temp_max_f[$t]);
    $temp_min_c[] = C($temp_min_f[$t]);
    $rain_dp[] = $daily[$i]['Day']['RainProbability'];
    $rain_np[] = $daily[$i]['Night']['RainProbability'];
    $r = "(".$temp_max_c[$t]."/".$temp_min_c[$t]."°C)";

    $output .= " [".date('M/d',$daily[$i]['EpochDate'])."]".$temp_max_f[$t]."/".$temp_min_f[$t]."°F (".$temp_max_c[$t]."/".$temp_min_c[$t]."°C) ☔:(".$rain_dp[$t]."%/".$rain_np[$t]."%), ";
  }
  $output = substr($output,0,-2);//."(Day%/Night%)";

} else {

//print_r($cw);


$time = date('M/d g:ia');
$date = substr($daily[0]['Date'],5,5);

$cw_text = $cw[0]['WeatherText'];
$temp_f = $cw[0]['Temperature']['Imperial']['Value'];
$temp_max_f = $daily[0]['Temperature']['Maximum']['Value'];
$temp_min_f = $daily[0]['Temperature']['Minimum']['Value'];
$temp_c = C($temp_f);
$temp_max_c = C($temp_max_f);
$temp_min_c = C($temp_min_f);

$wind_speed_i = $cw[0]['Wind']['Speed']['Imperial']['Value'];
$wind_speed_m = $cw[0]['Wind']['Speed']['Metric']['Value'];
$wind_dir = $cw[0]['Wind']['Direction']['English'];

$humidity = $cw[0]['RelativeHumidity'];

$uv_i = $cw[0]['UVIndex'];
$uv_text = $cw[0]['UVIndexText']; 

$dew_i = $cw[0]['DewPoint']['Imperial']['Value'];
$dew_m = $cw[0]['DewPoint']['Metric']['Value'];

$rain24_i = $cw[0]['PrecipitationSummary']['Past24Hours']['Imperial']['Value'];
$rain24_m = $cw[0]['PrecipitationSummary']['Past24Hours']['Metric']['Value'];
$rain24 = ($rain24_m > 0) ? "24hr Rain ".$rain24_i."in(".$rain24_m."mm), " : "";

if((int)$daily[0]['Day']['RainProbability'] == 0 && (int)$daily[0]['Night']['RainProbability'] == 0) {
  $r = "";
} else {
  $rain = $daily[0]['Day']['RainProbability']."%/"
        . $daily[0]['Night']['RainProbability']."%";
  
  $r =  "Rain☔:(".$rain.")";
}


$output = "{$time} {$loc} Weather: ".$cw_text.", ".$temp_f."°F(".$temp_max_f."/".$temp_min_f.") ".$temp_c."°C(".$temp_max_c."/".$temp_min_c.") Wind: ".$wind_dir."@".$wind_speed_i."mph(".$wind_speed_m."km), Humidity: ".$humidity."%,".$r." ".$rain24."UV: ".$uv_i." ".$uv_text.", Dew: ".$dew_i."°F(".$dew_m."°C), ".$sun;




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

echo $output;


//Functions


function C($f) {
  return (int)(($f-32)/(9/5));
}












//°F & °C conversion
function F($k) {
	$f = ($k-273.15)*(9/5)+32;
	return (int)$f."°F";
}

function KtoC($k) {
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
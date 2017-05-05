<?php
if(isset($_GET['p']) && substr($_GET['p'],0,4) == 'http') {
	$p = $_GET['p'];

	// set post fields
	$post = [
	    '' => 'bot',
	    ''   => 'insider@live.com',
	    '' => $p,
	];
	$url = 'http://www.orovilledaminfo.com/';
	foreach($post as $key=>$val) {

	}

	echo $p;
	


	// $ch = curl_init($url);
	// curl_setopt($ch,CURLOPT_URL, $url);
	// curl_setopt($curl_connection, CURLOPT_USERAGENT,"Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
	// curl_setopt($ch,CURLOPT_POST, count($fields));
	// // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	// curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	// $response = curl_exec($ch);
	// curl_close($ch);
	// var_dump($response);

} else {
	echo "http://www.orovilledaminfo.com/";
}







?>
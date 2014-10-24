<?php

$proxy = 'http://1.160.222.34:8088';
$url_page = "http://r3.ykimg.com/051500005428C46D6737B324C0015669";
$user_agent = "Mozilla/4.0";
$result = curl_string($url_page,$user_agent,$proxy);

file_put_contents('tmp_proxy.jpg', $result); 

function curl_string ($url,$user_agent,$proxy){
 
$ch = curl_init();
curl_setopt ($ch, CURLOPT_PROXY, $proxy);
curl_setopt ($ch, CURLOPT_URL, $url);
curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
curl_setopt ($ch, CURLOPT_COOKIEJAR, "c:\cookie.txt");
curl_setopt ($ch, CURLOPT_HEADER, 0);
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt ($ch, CURLOPT_TIMEOUT, 120);
$result = curl_exec ($ch);
curl_close($ch);
return $result;
 
}
 



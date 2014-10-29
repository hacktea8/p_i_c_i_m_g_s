<?php

$ch = function_exists('hmac_sha1');
var_dump($ch);exit;

$sign = hmac_sha1('dfgvwq', "wwwwweeeeeeefffsd");
$t = time();
$tt = date('Y-m-d H:i:s',$t);

echo $sign,' | '.$tt,"\n";

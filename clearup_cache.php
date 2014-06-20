<?php

$cur_dir = dirname(__FILE__).'/';
$cache_dir = $cur_dir.'cache/images/';

$cache_list = scandir($cache_dir);

$cur_time = time();

foreach($cache_list as $v){
 if($v == '.' || $v == '..'){
  continue;
 }
 echo $v,"\n";
 $cf = $cache_dir.$v;
 if($cur_time - filemtime($cf) > 24*3600){
  $cmd = " rm -f $cf";
  echo "\n===== $cmd ====\n";
  exec($cmd);
 }
# exit;
}

echo "clearUp is OK!\n";

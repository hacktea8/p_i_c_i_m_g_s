<?php

$dbase = array('emuweb','imgweb','comicweb','emuweb','sitetoolweb','videoweb');
$date = date('Y_m_d_');
$bkp = 'cache/backup/';
$svp = $bkp.'%s.sql.gz';
$cmd = 'mysqldump --opt -uroot -pmolong1992JBhk8 %s | gzip > %s';
$split = 'split -d -b 50m %s %s';
$root = dirname(__FILE__).'/';
$limitSize = 100*1024*1024;

foreach($dbase as $db){
  $bk = sprintf($svp,$date.$db);
  $order = sprintf($cmd,$db,$bk);
  exec($order);
  @chmod($bk,0777);
  $size = @filesize($root.$bk);
  if(!$size || $size > $limitSize){
    $sp = sprintf($split, $bk, $bkp.$date.$db);
    exec($sp);
    @unlink($bk);
  }
  echo $bk," === OK ==\n";
  sleep(15);
  
}

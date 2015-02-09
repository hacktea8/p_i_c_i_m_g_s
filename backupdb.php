<?php

$dbase = array('emuweb','buzhdweb','qvodweb','imgweb','btvideoweb','comicweb','sitetoolweb','jokerweb');
//$dbase = array('emuweb','imgweb','btvideoweb','sitetoolweb','jokerweb');
$date = date('Y_m_d_');
//$date = '2014_10_29';
$bkp = '/data/backup/';
$param = 't';
$param = 'd';
$svp = $bkp.'%s_'.$param.'.sql.gz';
$cmd = 'mysqldump --opt -'.$param.' -uroot -pmolong1992JBhk8 --skip-comments %s | gzip > %s';
$split = 'split -d -b 50m %s %s';
$root = dirname(__FILE__).'/';
$limitSize = 100*1024*1024;

foreach($dbase as $db){
  $bk = sprintf($svp,$date.$db);
  $order = sprintf($cmd,$db,$bk);
echo $order;exit;
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

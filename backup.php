<?php

class Imgsmodel{
  protected $db='';

  public function __construct(){
    $this->db = new DB_MYSQL();
  }
  public function getAppDiskToken($flag){
    if( !$flag)
       return false;

    $where = sprintf(' WHERE `flag`=%d LIMIT 1 ', $flag);
    $sql='SELECT * FROM `appdisk` '.$where;
    return $this->db->row_array($sql);
  }
}

function getextname($fname=''){
   if(!$fname){
     return false;
   }
   $extend =explode('.' , $fname);
   return strtolower(end($extend));
}

$root = dirname(__FILE__);

require_once($root.'/application/libraries/Baidupcs.php');
require_once($root.'/cront/db.class.php');

//var_dump($key);exit;
$path = $root.'/cache/backup/';
$targetPath = '/apps/picimgs/adminalbum/';
$imgsmodel = new Imgsmodel();
$access_tokeninfo = $imgsmodel->getAppDiskToken($flag = 9);

//var_dump($access_tokeninfo);exit;
if( !isset($access_tokeninfo['access_token'])){
   echo "=== No access_token =====\n";exit;
}

//var_dump($access_tokeninfo);exit;
$baidupcs = new Baidupcs();
$baidupcs->setAccessToken($access_tokeninfo['access_token']);
$fileList = scandir($path);

//var_dump($fileList);exit;
foreach($fileList as &$val){
  if( in_array($val, array('.', '..'))){
    continue;
  }
  $upload_name = $path.$val;
  $savename = $val;
  $res = $baidupcs->upload(file_get_contents($upload_name), $targetPath, $savename);
  $res = json_decode($res, 1);
  //var_dump($res);
  if(isset($res['error_code'])){
    if(31061 == $res['error_code']){
      die(trim($access_tokeninfo['uid'].'_'.$upload_name));
    }
  }
  if(isset($res['path'])){
    echo "== $upload_name backup success! ==\n";
    @unlink($upload_name);
  }
  sleep(30);
}

//var_dump($data);exit;
?>

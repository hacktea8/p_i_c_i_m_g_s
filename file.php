<?php defined('SHOW_IMG')||exit();
class Imgsmodel{
  protected $db='';

  public function __construct(){
    $this->db=new DB_MYSQL();
  }
  public function getAppDiskToken($uid){
    if(substr($uid,0,1) < 1)
       return false;

    $where=sprintf(' WHERE `uid`=\'%s\' LIMIT 1 ',mysql_real_escape_string($uid));
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
function trimBOM ($contents) {
 $charset = array();
 $charset[1] = substr($contents, 0, 1);
 $charset[2] = substr($contents, 1, 1);
 $charset[3] = substr($contents, 2, 1);
 if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
   return substr($contents, 3);
 }
 return $contents;
}
function getBdTokenByAPI(){
 $opts = array(
  'http'=>array(
  'method'=>"GET",
  'timeout'=>30,
  )
 );
 $context = stream_context_create($opts);
 $html = file_get_contents('http://img.hacktea8.com/api/getAppTokenList', false, $context);
 $token = json_decode(trimBOM($html), 1);
 return $token;
 var_dump($html);
 var_dump($token);exit;
}

$info = explode('_',$key);
//var_dump($key);exit;
$uid = $info[0];
$path = $info[1];

$site = 1;

require_once($root.'/application/libraries/Baidupcs.php');

if($site){
 require_once($root.'/cront/db.class.php');
 $imgsmodel = new Imgsmodel();
 $access_tokeninfo = $imgsmodel->getAppDiskToken($uid);
}else{
 require_once $root.'/application/libraries/memcached.php';
 $mem = new Memcached();
 $key = 'get_all_bdapp_token';
 $allToken = $mem->get($key);
 if( !$allToken){
  $allToken = getBdTokenByAPI();
  $mem->set($key,$allToken,86400);
 }
 $access_tokeninfo = $allToken[$uid];
}

if(!isset($access_tokeninfo['access_token'])){
   return false;
}
$imgpath = $imgpath ? $imgpath : $access_tokeninfo['path'];
$path = $imgpath.$path;
//var_dump($access_tokeninfo);exit;
$baidupcs = new Baidupcs();
$baidupcs->setAccessToken($access_tokeninfo['access_token']);
$data = $baidupcs->download($path);
$type = getextname($path);
//var_dump($data);exit;
ob_start();
ob_clean();
$width = isset($_GET['w'])?intval($_GET['w']):'';
$height = isset($_GET['h'])?intval($_GET['h']):'';
if($width || $height){
require_once 'thumbClass.php';
$imgh = new thumbClass($data,$type,1,$width,$height);

exit;
}
$size = @strlen($data);
if(in_array($type,array('jpg','png','bmp','gif','jpeg'))){
$type = $type == 'jpg'?'jpeg':$type;
header("Content-Type: image/{$type}");
}else{
$filename = isset($_GET['filename']) ?$_GET['filename'].'.'.$type:$key;
header("Content-type: application/octet-stream");
$ua = $_SERVER["HTTP_USER_AGENT"];
$encoded_filename = rawurlencode($filename);
if (preg_match("/MSIE/", $ua)) {
   header('Content-Disposition: attachment; filename="' . $encoded_filename . '"');
 } else if (preg_match("/Firefox/", $ua)) {
   header("Content-Disposition: attachment; filename*=\"utf8''" . $filename . '"');
 } else {
   header('Content-Disposition: attachment; filename="' . $filename . '"');
 }
}
header('cache-control: must-revalidate');
$offset = 60 * 60 * 24 * 7;//缓存距离现在的过期时间，这里设置为一天
$expire = 'expires: ' . gmdate('D, d M Y H:i:s', time() + $offset) . ' GMT';
header($expire);
header("Content-Length: $size");
echo $data;
?>

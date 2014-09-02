<?php
$key = isset($_GET['key'])?trim($_GET['key']):'';
if(!$key){
 header("HTTP/1.1 404 Not Found");  
 header("Status: 404 Not Found");  
 exit;
//   $key = '3958009_0000671092.jpg';
}
define('SHOW_IMG',1);
$root = dirname(__FILE__);
$imgpath = '';
$ext = strrchr($key,'.');
//echo $ext;exit;
require_once($root.'/file.php');
?>

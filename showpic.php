<?php
$key=isset($_GET['key'])?trim($_GET['key']):'';
if(!$key){
   $key = '3958009_0000671092.jpg';
}
define('SHOW_IMG',1);
$root=dirname(__FILE__);
$imgpath='/apps/picimgs/adminalbum/';
//echo $root;exit;
require_once($root.'/img.php');
?>

<?php

class Bdtoken{
 static public $API = '';
 const CONFIG = array(
 'buzhd' => array('client_id'=>'n6ctdsxlsmo6i9CMdlyv9tSW','client_secret'=>'aWmYVvmIgECQww6UeXw7Z08Ayw893A0j','app_id'=>3494921)
 );
 const SCOPE = 'basic netdisk public';
 public $config = 'config/token_';
 public $access_token = '';

 public function __construct(){
 }
 public function init($config = array()){
  foreach($config as $k => $v){
   
  }
  $app = self::CONFIG[$config['site']];
  $this->config .= $config['site'].'.php';
  self::$API = sprintf("https://openapi.baidu.com/oauth/2.0/token?grant_type=client_credentials&client_id=%s&client_secret=%s&scope=%s",$app['client_id'],$app['client_secret'],self::SCOPE);
 }
 public function getHtml($url){
  $opts = array(
  'http'=>array(
    'method'=>"GET",
    'timeout'=>60,
   )
  );

  $context = stream_context_create($opts);
  $html = file_get_contents(self::$API, false, $context);
  return $html;
 }
 public function access_token(){
  $html = $this->getHtml($url);
  $html = json_decode($html, 1);
  return isset($html['access_token'])? $html: 0;
 }
 public function getToken(){
  if(file_exists($this->config)){
   require_once $this->config;
   $tokeninfo = json_decode($tokeninfo, 1);
   if( (time() - $tokeninfo['ctime'])/(24*3600) < 27){
     return $tokeninfo;
   }
  }
  $tokeninfo = $this->access_token();
  if($tokeninfo){
   $tokeninfo['ctime'] = time();
   $this->access_token = $tokeninfo['access_token'];
   $tokeninfojson = json_encode($tokeninfo);
   $data = "<?php\r\n\$tokeninfo = '$tokeninfojson';";
   file_put_contents($this->config, $data);
  }
  return $tokeninfo;
 }
 public function getUinfo(){
  $url = 'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser?access_token='.$this->access_token;
  $html = $this->getHtml($url);
  $json = json_decode($html, 1);
  if(isset($json['uid'])){
   return $json;
  }
  return 0;
 }
}

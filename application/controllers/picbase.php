<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Picbase extends CI_Controller {
 public $redis = '';
 public function __construct(){
  parent::__construct();
  $this->load->library('rediscache');
  $this->redis = &$this->rediscache;
 }
 protected function curl_string ($url){
  $user_agent = 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.117 Safari/537.36';
  $ch = curl_init();
  curl_setopt ($ch, CURLOPT_PROXY, $proxy);
  curl_setopt ($ch, CURLOPT_URL, $url);
  curl_setopt ($ch, CURLOPT_USERAGENT, $user_agent);
#  curl_setopt ($ch, CURLOPT_COOKIEJAR, "cache/cookie/cookie.txt");
  curl_setopt ($ch, CURLOPT_HEADER, 0);
  curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt ($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt ($ch, CURLOPT_TIMEOUT, 10);
  $result = curl_exec ($ch);
  curl_close($ch);
  return $result;

 }
 protected function getProxy(){
  $ctx = stream_context_create(array( 
   'http' => array('timeout' => 5, 
             ) 
   ) 
  );
  $url = 'http://www.71daili.com/free.asp';
  $html = file_get_contents($url, False, $ctx);
  $html = mb_convert_encoding($html,'UTF-8','GBK');
  preg_match_all('#<tr class="odd">\s+<td class="style1">([\d\.]+)</td>\s+<td class="style2">(\d+)</td>\s*<td class="style3">[^<]*</td>\s*<td class="style4">([^<]*)</td>\s*<td class="style5">[^<]*</td>\s*<td class="style6">[^<]*</td>\s*<td class="style7">[^<]*</td>\s*</tr>#Uis',$html,$match);
  $r = array();
  $l = $match[1];
  foreach($l as $k => $v){
   if(stripos()){
    
   }
  }
  echo "<pre>";var_dump($match[1]);exit;
 }
}

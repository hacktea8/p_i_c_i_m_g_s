<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ttkapi extends CI_Controller {
 static public $key = array(
 'btv'=>array('app'=>'d2ed9f1901f8f54e7a64c7a567dc6d6c63e854c3'
  ,'key'=>'88c44b19064c0ed9dd634c7d31cbce9920aa379e')
 
 );
 static public $allowext = array('.gif','.jpg','.jpeg','.png','.bmp');
 static public $album = array(
 'btv'=>array('cover'=>15867,'w0'=>15868,'w1'=>15869,'w2'=>15870
  ,'w3'=>15871,'w4'=>15872,'w5'=>15873,'w6'=>15874)
 );
 public function index(){
 } 
 public function uploadurl(){
  $err = array('flag'=>-1,'msg'=>'未知错误');
  $seqcode = $this->input->get('seq');
  $seq = '';
  if($seqcode != $seq){
   $err['msg'] = 'seq 不正确';
   die(json_encode($err));
  }	   
  $imgurl = $this->input->post('imgurl',0);
  $referer = $this->input->post('referer','');
  $referer = $referer ? "Referer: $referer" : '';
  $filename = $this->input->post('filename','');
  $site = $this->input->post('site','');
  if( !isset(self::$key[$site])){
   $err['msg'] = 'site empty';
   die(json_encode($err));
  }
  $album = $this->input->post('album','');
  //var_dump($imgurl);exit;
  if( !$imgurl){
   $err['msg'] = 'imgurl empty';
   die(json_encode($err));
  }
  $imginfo = array('title'=>$filename);
  $imginfo['ext'] = self::getextname($filename);
/**/
  $data = array('url'=>$imgurl,'referer'=>$referer);
  $html = self::getHtml($data);;
  $imgurl = ROOTPATH.'cache/images/ttk'.$imginfo['title'];
  @file_put_contents($imgurl, $html);
  @chmod($imgurl, 0777);
  if(!file_exists($imgurl) || filesize($imgurl) <2000){
   @unlink($imgurl);
   $err['msg'] = 'file Down err Or size too small';
   die(json_encode($err));
  }
  if(in_array($imginfo['ext'], self::$allowext)){
   $imgurl_w = ROOTPATH.'cache/images/ttkw'.$imginfo['title'];
   $cmd = "convert {$imgurl} {$imgurl}";
   @exec($cmd);
   $water = ROOTPATH.'public/images/water/emuwater.png';
   $this->load->library('gickimg');
//var_dump($this->gickimg);exit;
   $this->gickimg->waterMark($imgurl,$water,$imgurl_w);
   @chmod($imgurl_w, 0777);
   $upFile = &$imgurl_w;
   if( !file_exists($imgurl_w) || filesize($imgurl_w) <2000){
    $upFile = &$imgurl;
    @unlink($imgurl_w);
   }
  }else{
   $upFile = &$imgurl;
//exit;
  }
/**/
  if( isset(self::$album[$site][$album])){
   $albumid = self::$album[$site][$album];
  }else{
   $wk = 'w'.date('w');
   $albumid = self::$album[$site][$wk];
  }
  $this->load->library('tietuku');
  $this->tietuku->init(self::$key[$site]);
  $json = $this->tietuku->uploadFile($albumid,$upFile);
  @unlink($imgurl);
  @unlink($upFile);
  //$json = $this->tietuku->uploadFile($albumid);
  /*
  ["width"]=>
  int(1024)
  ["height"]=>
  int(768)
  ["type"]=>
  string(3) "jpg"
  ["size"]=>
  int(173869)
  ["ubburl"]=>
  string(100) "[url=http://tietuku.com/cc0875dc8b2133f1][img]http://i2.tietuku.com/cc0875dc8b2133f1.jpg[/img][/url]"
  ["linkurl"]=>
  string(42) "http://i2.tietuku.com/cc0875dc8b2133f1.jpg"
  ["htmlurl"]=>
  string(122) "<a href='http://tietuku.com/cc0875dc8b2133f1' target='_blank'><img src='http://i2.tietuku.com/cc0875dc8b2133f1.jpg' /></a>"
  ["s_url"]=>
  string(43) "http://i2.tietuku.com/cc0875dc8b2133f1s.jpg"
  ["t_url"]=>
  string(43) "http://i2.tietuku.com/cc0875dc8b2133f1t.jpg"
  */
  //var_dump($json);exit;
  $iurl = @$json['linkurl'];
  if( !$iurl){
   $err['msg'] = 'save file failed';
   die(json_encode($err));
  }
  $r = self::parse_info($iurl);
  if( !$r){
   $err['msg'] = 'parse url failed';
   die(json_encode($err));
  }
  $r['flag'] = 1;
  die(json_encode($r));
 }
 static protected function getextname($fname=''){
  if(!$fname){
   return false;
  }
  $extend =explode("." , $fname);
  $ext = strtolower(end($extend));
  return '.'.$ext; 
 }
 static protected function parse_info($url){
  $uinfo = parse_url($url);
  $r = array();
  $host = @$uinfo['host'];
  $host = explode('.',$host);
  $r['host'] = @$host[0];
  $host = ltrim(@$uinfo['path'],'/');
  $host = explode('.',$host);
  $r['key'] = @$host[0];
  if( !$r['host'] || !$r['key']){
   return 0;
  }
  $r['url'] = $url;
  return $r;
 }
 static protected function getHtml($data = array()){
  if(!isset($data['url'])){
   return false;
  }
  $curl = curl_init();
  $url = $data['url'];
  $referer = @$data['referer'];
  unset($data['url']);
  unset($data['referer']);
  curl_setopt($curl, CURLOPT_URL, $url);
  curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.3 (Windows; U; Windows NT 5.3; zh-TW; rv:1.9.3.25) Gecko/20110419 Firefox/3.7.12');
  // curl_setopt($curl, CURLOPT_PROXY ,"http://189.89.170.182:8080");
  if(count($data)){
   curl_setopt($curl, CURLOPT_POST, 1);
   curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
  }
  curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
  if($referer){
   curl_setopt($curl, CURLOPT_REFERER, $referer);
  }else{
   curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
  }
  curl_setopt($curl, CURLOPT_HEADER, 0);
  curl_setopt($curl, CURLOPT_TIMEOUT, 10);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $tmpInfo = curl_exec($curl);
  if(curl_errno($curl)){
   echo 'error',curl_error($curl),"\r\n";
   return false;
  }
  curl_close($curl);
  return $tmpInfo;
 }
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Picapi extends CI_Controller {
 public $targetPath='/apps/picimgs/admattach/';
 public $allowext=array('.gif','.jpg','.jpeg','.png','.bmp');
 public function uploadurl(){
  $seqcode=$this->input->get('seq');
  $seq='';
  if($seqcode!=$seq){
   die(json_encode('500'));
  }
		   
  $imgurl = $_POST['imgurl'];
  $referer = $_POST['referer'];
  $referer = $referer ? "Referer: $referer" : '';
  $filename = $_POST['filename'];
//var_dump($imgurl);exit;
  if(!$imgurl){
   die(json_encode('404'));
  }
  $imginfo = array();
  $imginfo['title'] = $filename ? $filename : basename($imgurl);
  $this->load->model('imgsmodel');
  //$this->imgsmodel->getimginfoByid($row);
  $imginfo['flag']=4;
  $imginfo['public']=0;
  $imginfo['ext'] = $this->getextname($imginfo['title']);
  $imginfo['size']=0;
  $imginfo['uid']=2;
  $imginfo['abmid']=0;
  $imginfo['intro']='';
  $default_opts = array(
  'http'=>array(
    'method'=>"GET",
    'header'=>"User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/29.0.1547.66 Safari/537.36\r\n".
    "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8\r\n".
    "Accept-Language: zh-cn,zh;q=0.8,en-us;q=0.5,en;q=0.3\r\n".
    "Cache-Control: max-age=0\r\n".
    $referer

  )
);
  $default = stream_context_get_default($default_opts);
  $context = stream_context_create($default_opts);
  $html =  file_get_contents($imgurl, false, $context);
  $imgurl = 'cache/images/btv'.$imginfo['title'];
  file_put_contents($imgurl, $html);
  chmod($imgurl, 0777);
  if(!file_exists($imgurl) || filesize($imgurl) <2000){
   @unlink($imgurl);
   die('0');
  }
  if(in_array($imginfo['ext'],$this->allowext)){
   $imgurl_w = 'cache/images/picw'.$imginfo['title'];
   $cmd = "convert {$imgurl} {$imgurl}";
   exec($cmd);
   $water = 'public/images/water/btvwater.jpg';
   $this->load->library('imagelib');
   $this->imagelib->init($imgurl,3,$water,9,$imgurl_w);
   $this->imagelib->outimage();
   chmod($imgurl, 0777);
   $imghtml = file_get_contents($imgurl_w);
   unlink($imgurl_w);
   if(!file_exists($imgurl_w) || filesize($imgurl_w)<2000){
     $imghtml = file_get_contents($imgurl);
   }
  }else{
   $imghtml = file_get_contents($imgurl);
//exit;
  }
  $imginfo['hash'] = md5_file($imgurl);
  unlink($imgurl);
//exit;//var_dump($imginfo);exit;
  $check=$this->imgsmodel->getfileinfoById($imginfo['hash'],'admin');
  $key = isset($check['id'])?$check['id']:0;
  $key=sprintf('%010d',$key);
  $access_tokeninfo=$this->imgsmodel->getAppToken(1,9);
  if(isset($check['flag']) && $check['flag']){
   #echo $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];exit;
  }
  $this->load->library('baidupcs');
  $this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
  $res = $this->baidupcs->upload($imghtml, $this->targetPath, $key.$imginfo['ext']);
  $res = json_decode($res,1);
  if(isset($res['error_msg']) && 'Access token expired' == $res['error_msg']){
   die('44');
   $this->load->library('baidupcstoken');
   $appconfig=$this->config->item('baiduPcs_app');
   $appconfig['refresh_token'] = $access_tokeninfo['refresh_token'];
   $this->baidupcstoken->init($appconfig);
   $res=$this->baidupcstoken->getTokenByRefresh();
   var_dump($res);exit;
   $res=json_decode($res,1);
  }
  if(isset($res['path']) || $res['error_code']==31061){
   $this->imgsmodel->setfileinfoByHash($imginfo['hash']);
   //$key=sprintf('%010d',$key);
   echo $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];exit;
  }
  if(isset($res['error_code'])){
   $key = '0';//var_dump($res);exit;
  }
  die(json_encode($key));
 }
 public function delfile($fname = ''){
  $fname = str_replace('..','',$fname);
  $fl = strlen($fname);
  if($fl<10||$fl>30){
    die('-1'.$fname);
  }
exit;
  $this->load->model('imgsmodel');
  $access_tokeninfo=$this->imgsmodel->getAppToken(1,9);
  $this->load->library('baidupcs');
  $this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
  $fpath = $this->targetPath.$fname;
  $res = $this->baidupcs->deleteSingle($fpath);
  var_dump($res);
 }
protected function getextname($fname=''){
 if(!$fname){
  return false;
 }
 $extend =explode("." , $fname);
 $ext = strtolower(end($extend));
 $ext = strlen($ext)>4?'jpg':$ext;
 return '.'.$ext; 
}
protected function getHtml($data = array()){
 if(!isset($data['url'])){
  return false;
 }
 $curl = curl_init();
 $url = $data['url'];
 unset($data['url']);
 curl_setopt($curl, CURLOPT_URL, $url);
 curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.3 (Windows; U; Windows NT 5.3; zh-TW; rv:1.9.3.25) Gecko/20110419 Firefox/3.7.12');
  // curl_setopt($curl, CURLOPT_PROXY ,"http://189.89.170.182:8080");
 curl_setopt($curl, CURLOPT_POST, count($data));
 curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
 curl_setopt($curl,CURLOPT_FOLLOWLOCATION,true);
 curl_setopt($curl, CURLOPT_AUTOREFERER, 1);
 curl_setopt($curl, CURLOPT_HEADER, 0);
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

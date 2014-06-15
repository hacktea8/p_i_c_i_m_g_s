<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mhzapi extends CI_Controller {
    public $targetPath='/apps/picimgs/adminalbum/';
	public $allowext=array('.gif','.jpg','.jpeg','.png');
	public function uploadurl()
	{
		$seqcode=$this->input->get('seq');
		$seq='';
		if($seqcode!=$seq){
                  die(json_encode('500'));
		}
		   
		$imgurl = $this->input->post('imgurl');
		$referer = $this->input->post('referer');
                $referer = $referer ? "Referer: $referer" : '';
                if(!$imgurl){
                   die(json_encode('404'));
                }
                $imginfo = array();
                $imginfo['title'] = basename($imgurl);
		$this->load->model('imgsmodel');
		$imginfo['flag']=4;
		$imginfo['public']=0;
		$imginfo['ext']=$this->getextname($imginfo['title']);
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
                $imgurl = 'cache/images/ed2kmhz'.basename($imgurl);
                file_put_contents($imgurl, $html);
                chmod($imgurl, 0777);
                $cmd = "convert {$imgurl} {$imgurl}";
                exec($cmd);
                chmod($imgurl, 0777);
                $imghtml = file_get_contents($imgurl);
                $imginfo['md5'] = md5_file($imgurl);
                @unlink($imgurl);
//exit;//var_dump($imginfo);exit;
        if(!in_array($imginfo['ext'],$this->allowext)){
                  die(json_encode('20'));
		}
        $key=$this->imgsmodel->setimginfoByInfo($imginfo,'admin');
        if($key){
          $check=$this->imgsmodel->getimginfoById($key);
	  $id=$key;
	  $key=sprintf('%010d',$key);
	  $access_tokeninfo=$this->imgsmodel->getAppToken(1,8);
          if(isset($check['flag'])&&$check['flag']==1){
            echo $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];exit;
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
     if(isset($res['error_code']) && 31061 == $res['error_code']){
       echo $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];exit;
     }
     if(isset($res['path'])){
       $data=array();
       $data['id']=$id;
       $data['flag']=1;
       $data['size']=$res['size'];
       $data['pic']=$res['path'];
       $this->imgsmodel->updateimginfoByData($data,'admin');
       echo $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];exit;
     }
//var_dump($res);exit;
    }
//var_dump($res);exit;
    die(json_encode($key));
  }
  protected function getextname($fname=''){
	    if(!$fname){
		   return false;
		}
		$extend =explode("." , $fname);
        return '.'.strtolower(end($extend)); 
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

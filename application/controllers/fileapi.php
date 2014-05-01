<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Fileapi extends CI_Controller {
    public $targetPath='/apps/picimgs/admattach/';
	public $allowext=array('.gif','.jpg','.jpeg','.png','.bmp');
	/** flag 8=admin 6=user
         * $datainfo['id']=$info['id'];
	   $datainfo['uid']=$data['uid'];
	   $datainfo['tuid']=$tuid['uid'];
	   $datainfo['abmid']=$data['abmid'];
	   $datainfo['title']=$data['title'];
	   $datainfo['intro']=$data['intro'];
	   $datainfo['size']=$data['size'];
	   $datainfo['pic']=$uploadinfo['pic'];
	   $datainfo['ext']=$data['ext'];
	   $datainfo['md5']=$data['md5'];
	   $datainfo['atime']=time();
	   $datainfo['public']=$data['public'];
	   $datainfo['flag']=$data['flag'];
	 */
	public function index()
	{
		$seqcode=$this->input->get('seq');
		$seq='';
		if($seqcode!=$seq){
                  die(json_encode(0));
		}
		   
		$imginfo=$this->input->post('imginfo');
//var_dump($_FILES);exit;
                
		$this->load->model('imgsmodel');
		//$this->imgsmodel->getimginfoByid($row);
		$imginfo['flag']=4;
		$imginfo['public']=0;
		$imginfo['ext']=$this->getextname($imginfo['title']);
		$imginfo['size']=0;
                $tmp_name=$_FILES['file']['tmp_name'];
                $upload_name=dirname(__FILE__).'/../tmp/'.$_FILES['file']['name'];
//var_dump($upload_name);exit;
                move_uploaded_file($tmp_name,$upload_name);
                @chmod($upload_name,0777);
                if(!file_exists($upload_name)){
                  die(json_encode(0));
                }
                $imginfo['md5']=md5_file($upload_name);
//var_dump($imginfo);exit;
        if(!in_array($imginfo['ext'],$this->allowext)){
                  die(json_encode(0));
		}
        $key=$this->imgsmodel->setimginfoByInfo($imginfo,'admin');
//var_dump($key);exit;
        if($key){
//判断ID是否已上传
                        $check=$this->imgsmodel->getimginfoById($key);
			$id=$key;
			$key=sprintf('%010d',$key);
//var_dump($key);exit;
			$access_tokeninfo=$this->imgsmodel->getAppToken(1,8);
//var_dump($check);exit;
                        if(isset($check['flag'])&&$check['flag']==1){
                           //删除文件
                           @unlink($upload_name);
                           die(($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
                        }
//var_dump($access_tokeninfo);exit;
			$this->load->library('baidupcs');
			$this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
//$rs=$this->baidupcs->makeDirectory($this->targetPath);
//$rs=$this->baidupcs->getQuota();
//var_dump($rs);exit;
                        $ctx = stream_context_create(array(    
                                 'http' => array(        
                                    'timeout' => 1 //设置一个超时时间，单位为秒        
                        )    ) ); 
			$res=$this->baidupcs->upload(file_get_contents($upload_name,0,$ctx), $this->targetPath, $key.$imginfo['ext']);
                        //删除文件
                        @unlink($upload_name);
                        $res=json_decode($res,1);
//var_dump($res);exit;
			if(isset($res['path'])){
				$data=array();
				$data['id']=$id;
				$data['flag']=1;
				$data['size']=$res['size'];
				$data['pic']=$res['path'];
			    $this->imgsmodel->updateimginfoByData($data,'admin');
//var_dump($res);exit;
           die(($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
			}
		}
//var_dump($res);exit;
		die(json_encode(0));
	}
        public function refresh(){
                $config = $this->config->item('baiduPcs_app');
                $this->load->model('imgsmodel');
                $access_tokeninfo = $this->imgsmodel->getAppToken(1,6); 
                $this->load->library('baidupcs');
//var_dump($access_tokeninfo);exit;
//var_dump($config);exit;
                $info = array('refresh_token' => $access_tokeninfo['refresh_token'], 'client_id' => $config['apikey'], 'client_secret' => $config['secretkey']);
                $html = $this->baidupcs->refreshToken($info);
                var_dump($html);
                if(!$html){
                   return false;
                }
                $data = array('uid' => $access_tokeninfo['uid'], 'access_token' => $html['access_token'], 'refresh_token' => $html['refresh_token'], 'session_key' => $html['session_key'], 'session_secret' => $html['session_secret']);
                $this->imgsmodel->setAppDiskToken($data);
        }
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
                $filename = $this->input->post('filename');
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
         if(in_array($imginfo['ext'],$this->allowext)){
           $imgurl_w = 'cache/images/btvw'.$imginfo['title'];
           chmod($imgurl, 0777);
           $cmd = "convert {$imgurl} {$imgurl}";
           exec($cmd);
           $water = 'public/images/water/btvwater.jpg';
           $this->load->library('imagelib');
           $this->imagelib->init($imgurl,3,$water,9,$imgurl_w);
           $this->imagelib->outimage();
           chmod($imgurl, 0777);
           $imghtml = file_get_contents($imgurl_w);
           unlink($imgurl_w);
         }else{
           chmod($imgurl, 0777);
           $imghtml = file_get_contents($imgurl);
         }
         $imginfo['hash'] = md5_file($imgurl);
         unlink($imgurl);
//exit;//var_dump($imginfo);exit;
        $check=$this->imgsmodel->getfileinfoById($imginfo['hash'],'admin');
        $key = isset($check['id'])?$check['id']:0;
	$key=sprintf('%010d',$key);
        $access_tokeninfo=$this->imgsmodel->getAppToken(1,9);
        if(isset($check['flag']) && $check['flag']){
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
	public function upload()
	{
		$seqcode=$this->input->get('seq');
		$seq='';
		if($seqcode!=$seq){
                  die(json_encode('error code!'));
		}
		   
		$imginfo=$this->input->post('imginfo');
//var_dump($_FILES);exit;
                
		$this->load->model('imgsmodel');
		//$this->imgsmodel->getimginfoByid($row);
		$imginfo['flag'] = 4;
		$imginfo['public'] = 1;
                if(!isset($_FILES['file'])){
                   die("No image!");
                }
                if(!isset($imginfo['title'])){
                   $imginfo['title'] = $_FILES['file']['name'];
                }

                if(!isset($imginfo['intro'])){
                   $imginfo['intro'] = '';
                }
                if(!isset($imginfo['abmid'])){
                   $imginfo['abmid'] = 0;
                }
                if(!isset($imginfo['uid'])){
                   $imginfo['uid'] = 2;
                }

		$imginfo['ext'] = $this->getextname($imginfo['title']);
		$imginfo['size'] = 0;
                $tmp_name = $_FILES['file']['tmp_name'];
                $upload_name = dirname(__FILE__).'/../tmp/'.$_FILES['file']['name'];
//var_dump($upload_name);exit;
                move_uploaded_file($tmp_name,$upload_name);
                @chmod($upload_name,0777);
                if(!file_exists($upload_name)){
                  die(json_encode('Move files Failed!'));
                }
                $imginfo['md5'] = md5_file($upload_name);
//var_dump($imginfo);exit;
        if(!in_array($imginfo['ext'],$this->allowext)){
                  @unlink($upload_name);
                  die(json_encode('ext error!'));
		}
        $key=$this->imgsmodel->setimginfoByInfo($imginfo,'user');
//var_dump($key);
        if($key){
//判断ID是否已上传
                        $check=$this->imgsmodel->getimginfoById($key,'user');
			$id=$key;
			$key=sprintf('%010d',$key);
//var_dump($key);exit;
			$access_tokeninfo=$this->imgsmodel->getAppToken(1,8);
//var_dump($check);exit;
                        if(isset($check['flag'])&&$check['flag']==1){
                           @unlink($upload_name);
                           die(json_encode($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
                        }
//var_dump($access_tokeninfo);exit;
			$this->load->library('baidupcs');
			$this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
//$rs=$this->baidupcs->makeDirectory($this->targetPath);
//$rs=$this->baidupcs->getQuota();
//var_dump($rs);exit;
                        $ctx = stream_context_create(array(
                                 'http' => array(
                                    'timeout' => 120 //设置一个超时时间，单>位为秒        
                        )    ) );
			$res=$this->baidupcs->upload(file_get_contents($upload_name), $this->targetPath, $key.$imginfo['ext']);
                        @unlink($upload_name);
                        $res=json_decode($res,1);
//var_dump($res);
                        if(isset($res['error_code'])){
                           if(31061 == $res['error_code']){
                              die(trim($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
                           }
                        }
			if(isset($res['path'])){
				$data=array();
				$data['id']=$id;
				$data['flag']=1;
				$data['size']=$res['size'];
				$data['pic']=$res['path'];
			    $this->imgsmodel->updateimginfoByData($data,'user');
//var_dump($res);exit;
           die(trim($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
			}
		}
                @unlink($upload_name);
//var_dump($res);exit;
		die(json_encode('upload failed!'));
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

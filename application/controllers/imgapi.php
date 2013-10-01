<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imgapi extends CI_Controller {
    public $targetPath='/apps/picimgs/adminalbum/';
	public $allowext=array('.gif','.jpg','.jpeg','.png');
	/**
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
                           die(($access_tokeninfo['uid'].'_'.$key.$imginfo['ext']));
                        }
//var_dump($access_tokeninfo);exit;
			$this->load->library('baidupcs');
			$this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
//$rs=$this->baidupcs->makeDirectory($this->targetPath);
//$rs=$this->baidupcs->getQuota();
//var_dump($rs);exit;

			$res=$this->baidupcs->upload(file_get_contents($upload_name), $this->targetPath, $key.$imginfo['ext']);
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
	protected function getextname($fname=''){
	    if(!$fname){
		   return false;
		}
		$extend =explode("." , $fname);
        return '.'.strtolower(end($extend)); 
	}
}

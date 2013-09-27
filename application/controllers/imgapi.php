<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Imgapi extends CI_Controller {
    public $targetPath='/apps/adminalbum/';
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
			return false;
		}
		   
		$imginfo=$this->input->post('imginfo');
		$this->load->model('imgsmodel');
		//$this->imgsmodel->getimginfoByid($row);
		$imginfo['flag']=4;
		$imginfo['md5']=md5($imginfo['file']);
		$imginfo['public']=0;
		$imginfo['ext']=$this->getextname($imginfo['title']);
		$imginfo['size']=0;
        if(!in_array($imginfo['ext'],$this->allowext)){
		   return false;
		}
        $key=$this->imgsmodel->setimginfoByInfo($imginfo,'admin');
        if($key){
			$id=$key;
			$key=sprintf('%010d',$key);
			$access_tokeninfo=$this->imgsmodel->getAppToken(1,8);
			$this->load->library('baidupcs',$access_tokeninfo['access_token']);
			//$this->baidupcs->setAccessToken($access_tokeninfo['access_token']);
			$res=$this->baidupcs->upload($imginfo['file'], $this->targetPath, $key.$imginfo['ext']);
			if(isset($res['path'])){
				$data=array();
				$data['id']=$id;
				$data['size']=$res['size'];
				$data['pic']=$res['path'];
			    $this->imgsmodel->updateimginfoByData($res,'admin');
			}
           return $access_tokeninfo['uid'].'_'.$key.$imginfo['ext'];
		}
		return false;
	}
	protected function getextname($fname=''){
	    if(!$fname){
		   return false;
		}
		$extend =explode("." , $file_name);
        return '.'.strtolower(end($extend)); 
	}
}
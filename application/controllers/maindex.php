<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('webbase.php');

class Maindex extends Webbase {
     protected $imgpath='';

	 //protected


	/**
	 * 
	 */
	public function userlogin($mod=1)
	{

		if($this->checkLogin() &&$mod){
		   redirect('/');
		   return false;
		}
		if($mod==1){

		   $this->load->view('loginui',$this->viewData);
		}else{
		   $this->loginout($mod);
		}
	}
	public function getuser(){
		var_dump($this->userInfo);
	}
	public function uploads(){
	    $row=$this->input->post('imginfo');
		if($row){
		   //校验 安全码
		   if(true){
		      $pid=$this->imgsmodel->uploadimgs($row);
			  if($pid){
			     redirect('/maindex/linksrv/'.$pid);
			  }
		   }
		}

		$this->load->view('uploadimgui',$this->viewData);
	}
	public function linksrv($pid=''){
	   if(!$pid){
	      redirect('/');
	   }
       $info=$this->imgsmodel->getimginfoByid($pid);
	   if(!$info){
	      redirect('/');
	   }
       $this->setviewData(array(
	    'info'=>$info   
	   ));
       $this->load->view('linksrvui',$this->viewData);
	}

    /*
	key=uid_title-ext;
	*/
	public function showimg($key){
		 //check code 

        $info=explode('_',$key);
		$uid=$info[0];
		$path=$info[2];
		$path=$this->imgpath.$path;
	    $access_tokeninfo=$this->imgsmodel->getAppTokenByUid($uid);
        $this->getimgdata($access_tokeninfo['access_tokenin'],$path)
	}
	protected function getimgdata($access_tokenin,$path){
	   
	   $this->load->library('baidupcs',$access_token);
	  
	   $data=$this->baidupcs->download($path);
       $imgdata['data']=&$data;
	   $imgdata['type']=&$type;
	   $this->img($imgdata);
	}
	protected function img(&$imgdata){
	   if(!$imgdata){
	      return false;
	   }
	  
       
	   $this->load->library('imglib');
	   $this->imglib->config=array(
		   'imgdata'=>$imgdata['data'],
		   'imgtype'=>$imgdata['type']);
	   $this->imglib->init();
	   $this->imglib->showimg();
	   unset($imgdata);
	}
}
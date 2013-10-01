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
                // check code
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

        public function showpic($key=''){
                 if(!$key){
                   return false;
                 }
//echo 444;exit;
                 $this->imgpath='/apps/picimgs/adminalbum/';
                 $this->showimg($key);
        }
    /*
	key=uid_title-ext;
	*/
	public function showimg($key=''){
                if(!$key){
                  return false;
                }
		 //check code 

        $info=explode('_',$key);
//var_dump($key);exit;
		$uid=$info[0];
		$path=$info[1];
		$path=$this->imgpath.$path;
	    $access_tokeninfo=$this->imgsmodel->getAppDiskToken($uid);
//var_dump($access_tokeninfo);exit;
        $this->getimgdata($access_tokeninfo['access_token'],$path);
	}
	protected function getimgdata($access_token,$path){
	   
	   $this->load->library('baidupcs');
	   $this->baidupcs->setAccessToken($access_token);  
	   $data=$this->baidupcs->download($path);
//var_dump($data);exit;
       $imgdata['data']=$data;
	   $imgdata['type']=$this->getextname($path);
	   $this->img($imgdata);
	}
	protected function img(&$imgdata){
	   if(!$imgdata){
	      return false;
	   }
	  
//var_dump($imgdata);exit;
       
	   $this->load->library('imglib');
//var_dump($this->imglib->checksupport());exit;
	   $this->imglib->config=array(
		   'imgdata'=>$imgdata['data'],
		   'imgtype'=>$imgdata['type']);
	   $this->imglib->init();
//var_dump($this->imglib);exit;
	   $this->imglib->showimg();
	   unset($imgdata);
	}
}

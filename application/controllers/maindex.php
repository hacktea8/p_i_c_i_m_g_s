<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require('webbase.php');

class Maindex extends Webbase {
     
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
       $info=$this->imgsmodel->getimginfoByid($row);
	   if(!$info){
	      redirect('/');
	   }
       $this->setviewData(array(
	    'info'=>$info   
	   ));
       $this->load->view('linksrvui',$this->viewData);
	}
}
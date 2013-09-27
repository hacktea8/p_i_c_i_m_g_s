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
}
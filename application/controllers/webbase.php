<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webbase extends CI_Controller {
     public $viewData=array();



	/**
	 * 
	 */
	public function setviewData($data=array())
	{
		foreach($data as $key=>$val){
		   $this->viewData[$key]=$val;
		}
	}
}
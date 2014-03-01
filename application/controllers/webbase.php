<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Webbase extends CI_Controller {
     public $viewData = array();
     protected $userInfo = array();
     public $adminList = array(1);
     protected $isadmin = 0;

     public function __construct(){
	parent::__construct();
	$this->load->model('imgsmodel');
	$this->load->model('usermodel');
        $this->userInfo = $this->session->userdata('user_logindata');
        if(empty($userInfo)){
           //解析UID
           $uinfo = getSynuserUid();
           if($uinfo){
             $this->userInfo['uname'] = $uinfo['uname'];
             $uinfo = getSynuserInfo($uinfo['uid']);
             $uinfo['uname'] = $this->userInfo['uname'];
             $uinfo = $this->usermodel->getUserInfo($uinfo);
             if($uinfo){
               $this->userInfo = array_merge($this->userInfo,$uinfo);
               $this->userInfo['isadmin'] = $this->checkIsadmin($return = 1);
               $this->session->set_userdata(array('user_logindata'=>$this->userInfo));
             }
          }
        }else{
          $this->userInfo = $userInfo;
        }
//var_dump($this->userInfo);exit;
        $this->setviewData(array('seo_title'=>'','seo_keywords'=>'','seo_description'=>'','base_url'=>$this->config->item('base_url'),'domain_name'=>'',
	'site_name'=>'图享网'));
        $this->loginurl = $this->config->item('loginurl');
        
     }
     public function checkLogin(){
	 if(isset($this->userInfo['uid']) &&$this->userInfo['uid']>0){
	   return true;
	 }else{
	   return false;
	 } 
     }
     public function checkIsadmin(){
         if(!$this->checkLogin()){
           redirect('/maindex/');
	  }
	  if(in_array($this->userInfo['groupid'],$this->adminList)){
	    return true;
	  }
	  foreach($this->userInfo['group'] as $gid){
	    if(in_array($gid,$this->adminList)){
	      return true;
	    }
	  }
		return false;
      }
         
	/**
	 * 
	 */
	public function setviewData($data=array())
	{
		foreach($data as $key=>$val){
		   $this->viewData[$key]=$val;
		}
	}
	public function getSecode($k='',$mode=1){
	    $str='';
		$end=32;
		$key=$k?$k:$this->config->item('login_send_sec_key');
		$spool='1234567890qwertyuioplkjhgfdsazxcvbnmQWERTYUIOPLKJHGFDSAMNBVCXZ!@#%&*-_+=,~`.,;';
		if($mode){
             $t=date('YmdHm');
		     $str=md5(md5($t).md5($key));
			 $str=substr($str,6,16).substr(md5($spool),4,16);
             
		}else{
			
		    $len=strlen($spool)-1;
		    for($i=0;$i<$end;$i++){
		       $str.=$spool[mt_rand(0,$len)];
	    	}
            $str=md5($str).md5($key);
		}
		return $str;
	}
	protected function getHtml($param){
	 //open connection  
	 $ch = curl_init() ;  
	 //set the url, number of POST vars, POST data  
	 curl_setopt($ch, CURLOPT_URL,$param['url']) ;  
	 unset($param['url']);
	 curl_setopt($ch, CURLOPT_POST,count($param)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
	 curl_setopt($ch, CURLOPT_POSTFIELDS,$param); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名  
	 //curl_setopt($ch,CURLOPT_PROTOCOLS,CURLPROTO_HTTPS);
	 curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
	 $html=curl_exec($ch);  
	 if($html===false){
		echo 'Curl error: ' . curl_error($ch); 
	 }

	 //close connection  
	 curl_close($ch) ;   
	 return $html;
  }
  
  protected function getextname($fname=''){
         if(!$fname){
            return false;
          }
          $extend =explode('.' , $fname);
          return strtolower(end($extend));
        }
}

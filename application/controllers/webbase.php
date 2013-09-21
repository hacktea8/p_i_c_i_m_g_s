<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Webbase extends CI_Controller {
     public $viewData=array();
     protected $userInfo=array();
     public $adminList=array(3);
     protected $isadmin=0;
     public $loginurl='http://bbs.hacktea8.com/pw_userapi.php';

     public function __construct(){
		parent::__construct();
		$this->load->helper('url');
		$this->load->library('session');
	    $this->userInfo=$this->session->userdata('userInfo');
        $this->setviewData(array('seo_title'=>'','seo_keywords'=>'','seo_description'=>'','base_url'=>$this->config->item('base_url'),'domain_name'=>'',
			'site_name'=>'图享网'));
                $this->loginurl=$this->config->item('loginurl');
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
			redirect('/maindex/userlogin/1');
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
	 public function loginout($mod=1){
	    if($mod==2){
		   $row=$this->input->post('row');
		   if(empty($row)){
		       redirect('/maindex/userlogin/1');
		   }
		   //var_dump($row);exit;
		   $seq=substr($this->getSecode(),0,16);
		   //echo $seq;'<br />';
		   $row['logintype']=0;
		   $param=array(
		   'url'=>$this->loginurl,
		   'uname'=>trim($row['email_name']), 
		   'upwd'=>md5(trim($row['pass'])),
		   'logintype'=>$row['logintype'],
		   'seq'=>$seq,
		   'action'=>'login'
		   );
           $html=$this->getHtml($param);
		   //var_dump($html);exit;
		   $html=json_decode($html,1);
		   $oseq=substr($html['seq'],0,16);
                   $code=$this->config->item('login_recv_sec_key');
		   $seq=substr($this->getSecode($code),0,16);

		   if($oseq==$seq){
			   unset($html['seq']);
			   unset($html['safecv']);
			   unset($html['synlogin']);
			   $html['groups']=trim($html['groups'],',');
			   if(empty($html['groups'])){
			      $html['groups']=array();
			   }else{
			      $html['groups']=explode(',',$html['groups']);
			   }
			  // var_dump($row);exit;
		      $this->session->set_userdata(array('userInfo'=>$html));
              $info=$this->session->userdata('userInfo');
			  // var_dump($info);exit;
			  //DB
			  redirect('/');
		   }else{
			   //echo $oseq.'='.$seq;
			   redirect('/maindex/userlogin/1');
		      //return false;
		   }
		}else{
		   // Login Out
		   $this->session->unset_userdata('userInfo');
		   redirect('/');
		}
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
}

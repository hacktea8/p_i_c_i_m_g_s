<?php


class BaiduPCSToken{
  //请根据实际情况更新$access_token与$appName参数
  protected $access_token = '';
  protected $refresh_token = '';
  public $AuthorizationCodeUrl='https://openapi.baidu.com/oauth/2.0/authorize';
  public $RefreshTokenUrl='https://openapi.baidu.com/oauth/2.0/token';
  public $redirect_uri='http://img.hacktea8.com/admin/yundisk_add';
  //public $redirect_uri='oob';
  protected $client_id='UqgQ8DgIQeZC4E5eiVjhz8U6';//API Key
  protected $client_secret='oTdMd6dvlRHq1fLKRL1vAFniU7tRw8Ew';//Secret Key
  protected $response_type='code';
  protected $session_key;
  protected $session_secret;


  function init($config){
      isset($config['apikey']) &&$this->client_id=$config['apikey'];
      isset($config['secretkey']) &&$this->client_secret=$config['secretkey'];
  }
  function setTokenValue($access_token,$refresh_token,$session_key,$session_secret){
     $info="<?php\r\n";
     $info.="\$access_token='$access_token';\r\n";
     $info.="\$refresh_token='$refresh_token';\r\n";
     $info.="\$session_key='$session_key';\r\n";
     $info.="\$session_secret='$session_secret';\r\n";
     return file_put_contents($this->token_config,$info);
  }

  function getAuthorizationCodeUrl($seq){
	  $par='';
	  if($seq){
	     $par='&seq='.$seq;
	  }
     return $this->AuthorizationCodeUrl.'?response_type='.$this->response_type.'&client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri).'&scope=netdisk'.$par;
  }
  function getHtml($param){
     //open connection  
     $ch = curl_init() ;  
     //set the url, number of POST vars, POST data  
     curl_setopt($ch, CURLOPT_URL,$param['url']) ;  
     unset($param['url']);
     curl_setopt($ch, CURLOPT_POST,count($param)) ; // 启用时会发送一个常规的POST请求，类型为：application/x-www-form-urlencoded，就像表单提交的一样。  
     curl_setopt($ch, CURLOPT_POSTFIELDS,$param); // 在HTTP中的“POST”操作。如果要传送一个文件，需要一个@开头的文件名  
     curl_setopt($ch,CURLOPT_PROTOCOLS,CURLPROTO_HTTPS);
     curl_setopt($ch,CURLOPT_RETURNTRANSFER,true); 
     $html=curl_exec($ch);  
 //var_dump($html); 
     //close connection  
     curl_close($ch) ;   
     return $html;
  }
  function getTokenByInit($code){
     $param=array(
     'url'=>$this->RefreshTokenUrl,
     'grant_type'=>'authorization_code',
     'code'=>$code,
     'client_id'=>$this->client_id,
     'client_secret'=>$this->client_secret,
     'redirect_uri'=>$this->redirect_uri
     );
     $html=$this->getHtml($param);
     $html=json_decode($html,1);
//var_dump($html);
     if(isset($html['access_token'])){
        return $html;//return $this->setTokenValue($html['access_token'],$html["refresh_token"],
        //$html["session_key"],$html["session_secret"]);
     }
//var_dump($html);
     return false;
  }

  function getTokenByRefresh(){
     $param=array(
     'url'=>$this->RefreshTokenUrl,
     'grant_type'=>'refresh_token',
     'refresh_token'=>$this->refresh_token,
     'client_id'=>$this->client_id,
     'client_secret'=>$this->client_secret
     );
     $html=$this->getHtml($param);
     $html=json_decode($html,1);
     if(isset($html['access_token'])){
		$this->access_token=$html['access_token'];
        return $html;//return $this->setTokenValue($html['access_token'],$html["refresh_token"],
        //$html["session_key"],$html["session_secret"]); 
     }
     return false;
  }
  function getUserInfo(){
     $param=array(
     'url'=>'https://openapi.baidu.com/rest/2.0/passport/users/getLoggedInUser',
     'access_token'=>$this->access_token
     );
     $html=$this->getHtml($param);
     $html=json_decode($html,1);
	 if(isset($html['uid']))
		 return $html;

	 return false;
  }
}
?>

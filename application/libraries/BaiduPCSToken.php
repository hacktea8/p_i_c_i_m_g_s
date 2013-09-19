<?php

require_once '../libs/BaiduPCS.class.php';

class BaiduPanAPI{
  //请根据实际情况更新$access_token与$appName参数
  protected $access_token = '';
  protected $refresh_token = '';
  public $token_config = 'token_config.php';
  public $AuthorizationCodeUrl='https://openapi.baidu.com/oauth/2.0/authorize';
  public $RefreshTokenUrl='https://openapi.baidu.com/oauth/2.0/token';
  //public $redirect_uri='http://www.picture.com/bd_pcs/demos/baidupanapi.php';
  public $redirect_uri='oob';
  protected $client_id='UqgQ8DgIQeZC4E5eiVjhz8U6';//API Key
  protected $client_secret='oTdMd6dvlRHq1fLKRL1vAFniU7tRw8Ew';//Secret Key
  protected $response_type='code';
  protected $session_key;
  protected $session_secret;
  public $appPath='/apps/我的云盘/';


  function __construct(){
     if($this->getTokenValue()){
     }else{
        return '请初始化Token_key!';
     }
  }

  function getInstence(){
     $pcs = new BaiduPCS($this->access_token);
     $result = $pcs->getMeta($this->appPath);
     return $result;
  }
//获取当前用户空间配额信息
  function getQuotaInfo(){
     $url='https://pcs.baidu.com/rest/2.0/pcs/quota?method=info&access_token='.$this->access_token;
     echo $url;
     $res=file_get_contents($url);
     $res=json_decode($res,1);
     if(isset($res['quota'])){
       $res['quota']=$res['quota']/(1024*1024);
       $res['used']=$res['used']/(1024*1024);
       $res['unit']='mb';
       return $res;
     }
     return false;
  }
  function uploadOnlyFile($fname,&$file,$ondup=0){
     $ondup=$ondup?'overwrite':'newcopy';
     $param=array(
     'url'=>'https://c.pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'upload',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$fname),
     'file'=>$file,
     'ondup'=>$ondup
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['md5'])){
       return $res;
     }
     return false;
  }
  function uploadSliceFile($file){
     $param=array(
     'url'=>'https://c.pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'upload',
     'access_token'=>$this->access_token,
     'type'=>'tmpfile',
     'file'=>$file
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['md5'])){
       return $res;
     }
     return false;
  }
  function mergeUploadFile($path,&$block_list,$ondup=0){
     if(count($block_list)<2){
       return false;
     }
     $ondup=$ondup?'overwrite':'newcopy';
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'createsuperfile',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$path),
     'param'=>$block_list,
     'ondup'=>$ondup
     ); 
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['md5'])){
       return $res;
     }
     return false;
  }
  function downloadFile($path){
     $param=array(
     'url'=>'https://d.pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'download',
     'path'=>urlencode($this->appPath.$path),
     'access_token'=>$this->access_token
     );
     return $this->getHtml($param);
  }
  function mkdirFloder($path){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'mkdir',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$path)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['path'])){
       return $res;
     }
     return false;
  }
  function getFileInfo($path){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'meta',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$path)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['path'])){
       return $res;
     }
     return false;
  }
//array('list'=>array(array('path'=>''),array('path'=>'')));
  function batchGetFileInfo($path_list){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'meta',
     'access_token'=>$this->access_token,
     'param'=>json_encode($path_list)
     ); 
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['path'])){
       return $res;
     }
     return false;
  }
/*
排序字段，缺省根据文件类型排序：
time（修改时间）
name（文件名）
size（大小，注意目录无大小）
“asc”或“desc”，缺省采用降序排序。
asc（升序）
desc（降序）
返回条目控制，参数格式为：n1-n2。
返回结果集的[n1, n2)之间的条目，缺省返回所有条目；n1从0开始。
*/
  function getFloderFileList($path,$by=0,$order=0,$limit=0){
     if($by)
       $param['by']=$by;
     if($order)
       $param['order']=$order;
     if($limit)
       $param['limit']=$limit;

     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'list',
     'access_token'=>$this->access_token,
     'path'=>$this->appPath.$path
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['path'])){
       return $res;
     }
     return false;
  }
  function moveFile($from,$to){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'move',
     'from'=>urlencode($this->appPath.$from),
     'to'=>urlencode($this->appPath.$to),
     'access_token'=>$this->access_token
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['from'])){
       return $res;
     }
     return false;
  }
//array('list'=>array(array('from'=>'','to'=>),array('from'=>,'to'=>'')));
  function batchMoveFile($list){
     foreach($list['list'] as $k=>$v){
        $list['list'][$k]=array('from'=>$this->appPath.$v['from'],
                               'to'=>$this->appPath.$v['to']);
     }
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'move',
     'access_token'=>$this->access_token,
     'param'=>json_encode($list)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['from'])){
       return $res;
     }
     return false;
  }
  function copyFile($from,$to){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'copy',
     'from'=>urlencode($this->appPath.$from),
     'to'=>urlencode($this->appPath.$to),
     'access_token'=>$this->access_token
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['from'])){
       return $res;
     }
     return false;
  }
  function batchCopyFile($list){
     foreach($list['list'] as $k=>$v){
        $list['list'][$k]=array('from'=>$this->appPath.$v['from'],
                               'to'=>$this->appPath.$v['to']);
     }
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'copy',
     'access_token'=>$this->access_token,
     'param'=>json_encode($list)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['from'])){
       return $res;
     }
     return false;
  }
  function deleteFile(){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'delete',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$path)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }
  function batchDelFile($list){
     foreach($list['list'] as $k=>$v){
        $list['list'][$k]=array('from'=>$this->appPath.$v['from'],
                               'to'=>$this->appPath.$v['to']);
     }
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'delete',
     'access_token'=>$this->access_token,
     'param'=>json_encode($list)
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }
  function searchFile($path,$wd,$re=0){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'search',
     'access_token'=>$this->access_token,
     'path'=>json_encode($this->appPath.$path),
     'wd'=>$wd,
     're'=>$re
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }
  function getPicThumbnail($path,$width,$height,$quality=100){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/thumbnail',
     'method'=>'generate',
     'access_token'=>$this->access_token,
     'path'=>urlencode($this->appPath.$path),
     'quality'=>$quality,
     'width'=>$width,
     'height'=>$height
     );
     $res=$this->getHtml($param);
     return $res;
  }
  function getVideoTranscode($path,$type,&$video){
     $param='https://pcs.baidu.com/rest/2.0/pcs/file?';
     $param.='method=streaming&'.'access_token='.$this->access_token;
     $param.='&path='.urlencode($this->appPath.$path);
     $param.='&type='.$type;
     $video=file_get_contents($param);
  }
  function getStreamFileList($type,$start=0,$limit=0,$filter_path=0){
     $p='';
     if($start)
       $p.='&start='.$start;
     if($limit)
       $p.='&limit='.$limit;
     if($filter_path)
       $p.='&filter_path='.$filter_path;
     $param='https://pcs.baidu.com/rest/2.0/pcs/stream?';
     $param.='method=list&access_token='.$this->access_token;
     $param.='&type='.$type.$p;
     $res=file_get_contents($param);
     $res=json_decode($res);
     return isset($res['list'])?$res:false;
  } 
  function downloadStreamFile($path,&$video){
     $param='https://d.pcs.baidu.com/rest/2.0/pcs/file';
     $param.='?method=download&access_token='.$this->access_token;
     $param.='&path='.urlencode($this->appPath.$path);
     $video=file_get_contents($param);
  }
  
  function secondUploadFile(){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
     'method'=>'rapidupload',
     'access_token'=>$this->access_token,
     'path'=>,
     'content-length'=>,
     'content-md5'=>,
     'slice-md5'=>,
     'content-crc32'=>,
     'ondup'=>$ondup
     );
  }

  function offlineDownload($url){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/services/cloud_dl',
     'method'=>'add_task',
     'access_token'=>$this->access_token,
     'save_path'=>urlencode($this->appPath.'offlinedw/'.basename($url)),
     'source_url'=>$url
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }

  function queryOfflineTask($task_ids,$op_type){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/service/cloud_dl',
     'method'=>'query_task',
     'access_token'=>$this->access_token,
     'task_ids'=>$task_ids,
     'op_type'=>$op_type
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }

  function cannelOfflineTask($task_id){
     $param=array(
     'url'=>'https://pcs.baidu.com/rest/2.0/pcs/services/cloud_dl',
     'method'=>'cancel_task',
     'access_token'=>$this->access_token,
     'task_id'=>$task_id
     );
     $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error'])){
       return false;
     }
     return $res;
  }

//Get
  function queryRecycleFile($start,$limit){
    $param=array(
    'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
    'method'=>'listrecycle',
    'start'=>$start,
    'access_token'=>$this->access_token,
    'limit'=>$limit
    );
    $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error_code'])){
       return false;
     }
     return $res;
  }

  function resetRecycleFile($fs_id){
    $param=array(
    'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
    'method'=>'restore',
    'access_token'=>$this->access_token,
    'fs_id'=>$fs_id
    );
    $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error_code'])){
       return false;
     }
     return $res;
  }

/*{"list":[{"fs_id":"4059450057"},{"fs_id":"2959141864"}]}*/
  function resetRecycleFile(){
    $param=array(
    'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file';
    'method'=>'restore',
    'access_token'=>$this->access_token,
    'param'=>json_encode($list),
    );
    $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error_code'])){
       return false;
     }
     return $res;
  }

  function emptyRecycle(){
    $param=array(
    'url'=>'https://pcs.baidu.com/rest/2.0/pcs/file',
    'method'=>'delete',
    'access_token'=>$this->access_token,
    'type'=>'recycle'
    );
    $res=$this->getHtml($param);
     $res=json_decode($res);
     if(isset($res['error_code'])){
       return false;
     }
     return $res;
  }

  function getTokenValue(){
     if(file_exists($this->token_config)){
        require($this->token_config);
        $this->access_token=$access_token;
        $this->refresh_token=$refresh_token;
        $this->session_key=$session_key;
        $this->session_secret=$session_secret;
        if($this->access_token && $this->refresh_token && $this->session_key&& $this->session_secret){
           return true;
        }
        @unlink($this->token_config);
     }
     return false;
  }
  
  function setTokenValue($access_token,$refresh_token,$session_key,$session_secret){
     $info="<?php\r\n";
     $info.="\$access_token='$access_token';\r\n";
     $info.="\$refresh_token='$refresh_token';\r\n";
     $info.="\$session_key='$session_key';\r\n";
     $info.="\$session_secret='$session_secret';\r\n";
     return file_put_contents($this->token_config,$info);
  }

  function getAuthorizationCodeUrl(){
     return $this->AuthorizationCodeUrl.'?response_type='.$this->response_type.'&client_id='.$this->client_id.'&redirect_uri='.urlencode($this->redirect_uri);
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
 var_dump($html); 
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
var_dump($html);
     if(isset($html['access_token'])){
        return $this->setTokenValue($html['access_token'],$html["refresh_token"],
        $html["session_key"],$html["session_secret"]);
     }
var_dump($html);
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
        return $this->setTokenValue($html['access_token'],$html["refresh_token"],
        $html["session_key"],$html["session_secret"]); 
     }
     return false;
  }
}
?>

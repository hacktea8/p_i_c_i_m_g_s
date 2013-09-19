<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
include_once('adm_webbase.php');

class Admin extends Adm_webbase {

     public function __construct(){
	    parent::__construct();
		$this->load->model('imgsmodel');
	 }
	/**
	 * 
	 *
	 */
	public function index()
	{
		
		$this->load->view('index');
	}
	public function index_top(){
	    $this->load->view('index_top');
	}
	public function index_left(){
	    $this->load->view('index_left');
	}
	public function index_share(){
	    $this->load->view('index_share');
	}
	public function index_album(){
	    $this->load->view('index_album');
	}
	public function album_cate($p=1){
		$p=intval($p);
		$p=$p>0?$p:1;
		$limit=10;
        $list=$this->imgsmodel->getCateInfoList($p,$limit);
		$total=$this->imgsmodel->getCateCount();
		$psize=ceil($total/$limit);
		$pstr=$this->getpagestr('/admin/album_cate/',$p,$psize,5);
        $this->setviewData(array('list'=>$list,'ptotal'=>$total,'psize'=>$psize,'pagestr'=>$pstr));
	    $this->load->view('album_cate',$this->viewData);
	}
	public function ablum_cate_detail($cid=0){
        $row=$this->input->post('row');
		$rootCate=$this->imgsmodel->getRootCateInfo();
		if($row['title']){
			//echo '<pre>';var_dump($row);exit;
		   $cid=$this->imgsmodel->updateCate($row);
		}
		if($cid){
		   $info=$this->imgsmodel->getCateInfoByCid($cid);
		   $this->viewData['info']=$info;
	    }
		$this->setviewData(array('rootCate'=>$rootCate));
	    $this->load->view('ablum_cate_detail',$this->viewData);
	}
	public function index_user(){
	    $this->load->view('index_user');
	}
    public function user_setting(){
	    $this->load->view('user_setting');
	}
	public function index_system(){
	    $this->load->view('index_system');
	}
	public function system_config(){
	    $this->load->view('system_config');
	}
	public function yundisk_list(){
	    
	    $this->load->view('yundisk_list',$this->viewData);
	}
    public function yundisk_add(){
	    require('baidupan.inc.php');

$api=new BaiduPanAPI;

/*
//$code=isset($_GET['code'])?$_GET['code']:0;
$code='1081f2a179068bf543402d290347674d';
if($code){
echo $code;
  $api->getTokenByInit($code);
}else{
  header('Location: '.$api->getAuthorizationCodeUrl());
}
*/
	    echo $_GET['code'];exit;
	}
	public function index_change(){
	    $this->load->view('index_change');
	}
	public function index_main(){
	     $this->load->view('index_main');
	}
	public function index_footer(){
	     $this->load->view('footer');
	}

	public function getpagestr($url,$p=1,$end=1,$size=5){
	  $str='';
	  $start=($p-$size)>0?($p-ceil($size/2)):1;
	  $len=$end;
	  if($start>1){
	     $len=$p+ceil($size/2);
	  }
	  $len=$len>$end?$end:$len;
      if($end>1){
		  // last page
	     if($p>1){
		    $str.="<a href='{$url}".($p-1)."'>上一页</a> &nbsp;";
		 }
         for($i=$start;$i<=$len;$i++){
			 if($i==$p){
			    $pp="<span class='current'>{$i}</span>&nbsp;";
			 }else{
			    $pp="<a href='{$url}{$i}'>&nbsp;{$i}&nbsp;</a>&nbsp;";
			 }
		     $str.=$pp;
		 }
         // next page
		 if($p<$end){
		    $str.="<a href='{$url}".($p+1)."'>下一页</a>";
		 }
	  }
	  return $str;
	}
}
<?php
class Imgsmodel extends CI_Model {
   
   function __construct(){
	  //$this->load->database('default',true);
      parent::__construct();
	 // 
   }
   function getImgTable($pid=0,$type='admin'){
	   $table='imgs';
       if($type){
	      return $table;
	   }
	   return $table.($pid%10);
   }
   function getImgId($hash){
       $sql=sprintf("SELECT `id`, `hash` FROM `imgmap` WHERE `hash`='%s' LIMIT 1",mysql_real_escape_string($hash));
       $query=$this->db->query($sql);
       return $query->row_array();

   }
   function setImgMapInfo($hash){
       $row=$this->getImgId($hash);
	   if($row){
		  $row['flag']=1;
	      return $row;
	   }
	   $sql=sprintf("INSERT INTO `imgmap`(`hash`) VALUES ('%s')",$hash);
	   $this->db->query($sql);
       $row=$this->getImgId($hash);
	   if($row){
	      $row['flag']=0;
		  return $row;
	   }
	   return false;
   }
   function getAppToken($limit='',$flag=1){
       $limits=$limit?" LIMIT {$limit} ":'';
	   $sql=sprintf("SELECT * FROM `appdisk` WHERE `flag`=%d ORDER BY `sort` %s",$flag,$limits);
	   $query=$this->db->query($sql);
       if($limit==1){
	      return $query->row_array();
	   }
	   return $query->result_array();
   }
   function getimginfoById($id='',$table='imgs'){
       if(!$id){
	      return false;
	   }
	   $sql=sprintf("SELECT * FROM %s WHERE `id`=%d LIMIT 1",$table,$id);
       $query=$this->db->query($sql);
       return $query->row_array();   
   }
   function updateimginfoByData($data='',$type=''){
//var_dump($data);exit;
       if(!$data){
	      return false;
	   }
       if(isset($data['id'])){
	     $id=intval($data['id']);
		 unset($data['id']);
		 $tname=$this->getImgTable($id,$type);
	     $this->db->update($tname, $data, array('id' => $id));
		 return true;
	  }
       return false;
   }
   function setimginfoByInfo($data,$type=''){
	   $info=$this->setImgMapInfo($data['md5']);
	   if(!$info){
	      return false;
	   }
	   if($info['flag']==1){
	      return $info['id'];
	   }

       $tname=$this->getImgTable($info['id'],$type);
	   $tuid=$this->getAppToken(1,8);
//var_dump($tuid);exit;
	   if(!isset($tuid['uid'])){
	      return false;
	   }
	   $datainfo=array();
	   $datainfo['id']=$info['id'];
	   $datainfo['uid']=$data['uid'];
	   $datainfo['tuid']=$tuid['uid'];
	   $datainfo['abmid']=$data['abmid'];
	   $datainfo['title']=$data['title'];
	   $datainfo['intro']=$data['intro'];
	   $datainfo['size']=$data['size'];
	   $datainfo['pic']='';
	   $datainfo['ext']=$data['ext'];
	   $datainfo['md5']=$data['md5'];
	   $datainfo['atime']=time();
	   $datainfo['public']=$data['public'];
	   $datainfo['flag']=$data['flag'];
       $this->db->insert($tname, $datainfo); 
       $row=$this->getimginfoById($info['id']);
	   if(isset($row['id'])){
	      return $row['id'];
	   }
	   return false;
   }

   function updateCate($data=''){
      if(!$data){
	     return false;
	  }
	  if(isset($data['cid'])){
	     $cid=intval($data['cid']);
		 unset($data['cid']);
	     $this->db->update('cate', $data, array('cid' => $cid));
	  }else{
	     $this->db->insert('cate', $data); 
		 $row=$this->getCateByName($data['title']);
		 return $row['cid'];
	  }
   }

   function getRootCateInfo(){
     $sql=sprintf('SELECT * FROM `cate` WHERE `flag`=1 AND `fid`=0 ');
	 $query=$this->db->query($sql);
     $res=$query->result_array();
	 if($res){
	    return $res;
	 }
	 return array();
   }
   function getCateCount(){
     $sql=sprintf('SELECT count(*) as total FROM `cate` WHERE `flag`=1 ');
	 $query=$this->db->query($sql);
     $res=$query->row_array();
	 if($res){
	    return $res['total'];
	 }
	 return 0;
   }
   function getCateInfoList($p,$limit=20){
	 $p=($p-1)*$limit;
     $sql=sprintf('SELECT * FROM `cate` WHERE `flag`=1 LIMIT %d,%d ',$p,$limit);
	 $query=$this->db->query($sql);
     $res=$query->result_array();
	 if($res){
	    return $res;
	 }
	 return array();
   }

   function getYundiskInfoList($p,$limit=20){
     $p=($p-1)*$limit;
     $sql=sprintf('SELECT * FROM `appdisk` ORDER BY `sort` LIMIT %d,%d ',$p,$limit);
	 $query=$this->db->query($sql);
     $res=$query->result_array();
	 if($res){
	    return $res;
	 }
	 return array();
   }
   function getYundiskCount(){
     $sql=sprintf('SELECT count(*) as total FROM `appdisk` ');
	 $query=$this->db->query($sql);
     $res=$query->row_array();
	 if($res){
	    return $res['total'];
	 }
	 return 0;
   }
   function getCateInfoByCid($cid=0){
     $cid=intval($cid);
	 $where=$cid?' AND cid='.$cid.' LIMIT 1 ':'';
	 $sql=sprintf('SELECT * FROM `cate` WHERE `flag`=1 %s ',$where);
	 $query=$this->db->query($sql);
	 if($cid){
	    return $query->row_array();
	 }
	 return $query->result_array();
   }
   function getCateByName($title=''){
     if(!$title){
	    return false;
	 }
	 $sql=sprintf('SELECT * FROM `cate` WHERE `flag`=1 AND `title`=\'%s\' LIMIT 1',mysql_real_escape_string($title));
	 return $this->db->query($sql)->row_array();
   }
   // Config param
   function updateConfigByKey($data=''){
     if(!$data){
	    return false;
	 }
	 $row=$this->getConfigByKey($data['var']);
	 $var=$data['var'];
	 unset($data['var']);
	 $val=serialize($data);
	 $data=array();
	 $data['var']=$var;
	 $data['val']=$val;
     if(isset($row['var'])){
	   // $var=$data['var'];
		unset($data['var']);
	    $this->db->update('config', $data, array('var' => $var));
	 }else{
	    $this->db->insert('config', $data); 
	    
	  }
   }
   function setAppDiskToken($data){
      if(!isset($data['uid'])){
	      return false;
	  }
      $row=$this->getAppDiskToken($data['uid']);
	  if(isset($row['uid'])){
	     $uid=$data['uid'];
		 unset($data['uid']);
         $this->db->update('appdisk', $data, array('uid' => $uid));
         return $uid;
	  }else{
	     $this->db->insert('appdisk', $data); 
	  }
   }
   function getAppDiskToken($uid=''){
	   $where=' ORDER BY sort ';
       if($uid){
	      $where=sprintf(' WHERE `uid`=%s LIMIT 1 ',mysql_real_escape_string($uid));
	   }
	   $sql='SELECT * FROM `appdisk` '.$where;
	   $query=$this->db->query($sql);
       if($uid){
	      return $query->row_array();
	   }
	   return $query->result_array();
   }
   function getConfigByKey($key=''){
	  $where='';
      if($key){
	     $where=sprintf('WHERE `var`=\'%s\' LIMIT 1',mysql_real_escape_string($key));
	  }
	  $sql='SELECT * FROM `config` '.$where;
	  $query=$this->db->query($sql);
	  if($key)
		  return $query->row_array();

	  return $query->result_array();
   }
}

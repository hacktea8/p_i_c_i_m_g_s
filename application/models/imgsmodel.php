<?php
class Imgsmodel extends CI_Model {
   
   function __construct(){
	  //$this->load->database('default',true);
      parent::__construct();
	 // 
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
}
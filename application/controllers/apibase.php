<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apibase extends CI_Controller {
 static public $mem = '';
 static public $allowIP = array('127.0.0.1','103.27.109.108','204.44.65.209');
 public function __construct(){
  parent::__construct();
  $ip = @$_SERVER['REMOTE_ADDR'];
  if( !in_array($ip, self::$allowIP)){
   die('Deny '.$ip);
  }
  if( !self::$mem){
   $this->load->library('memcached');
   self::$mem = &$this->memcached;
  }
 }
 
}

<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'apibase.php';
class Api extends Apibase {
 public function __construct(){
  parent::__construct();
 }
 public function getAppTokenList(){
  $this->load->model('imgsModel');
  $list = $this->imgsModel->getAllAPPTokenList();
  die(json_encode($list));
 }
}

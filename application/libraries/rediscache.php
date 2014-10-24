<?php
defined('BASEPATH') || exit('Forbidden');

class Rediscache{
  protected $_config = array(
    'host'   => '127.0.0.1',
    'port'   => 6379,
    'timeout'  => 10800
  );
  public $redis=null;
  public $pre='emu';
  public function __construct() {
    $this->redis=new Redis();
    $this->redis->pconnect($this->_config['host'],$this->_config['port'],$this->_config['timeout']);
  }
  public function getkey($key){
    return substr($key,0,strlen($this->pre)) == $this->pre?$key:$this->pre.$key;
  }
  public function set($key, $data, $ttl = 3600){
    try{
      $key = $this->getkey($key);
      $this->redis->setex($key, $ttl, $data);
    }catch(Exception $e){
      return false;
    }
  }
  public function exists($key = ''){
    if(!$key){
       return false;
    }
    $key = $this->getkey($key);
    return $this->redis->exists($key);
  }
  public function keys($key = ''){
    if( !$key){
       return false;
    }
    $key = $this->getkey($key);
    return $this->redis->keys($key);
  }
  public function mset($data){
    if(is_array($data))
      return $this->redis->mset($data);
    else
      return false;
  }

  public function get($key){
    try{
      if(is_array($key)){
        foreach($key as &$v){
          $v=$this->getkey($v);
        }
        return $this->redis->getMultiple($key);
      }else{
        $key = $this->getkey($key);
        return $this->redis->get($key);
      }
    }catch(Exception $e){
      return null;
    }
  }
  public function delete($key){
    $key = $this->getkey($key);
    $this->redis->delete($key);
  }
}
?>

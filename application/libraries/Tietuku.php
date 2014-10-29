<?php
date_default_timezone_set('PRC');

//1262499086@qq.com
include_once('TieTuKu.class.php');
define('MY_ACCESSKEY', 'd2ed9f1901f8f54e7a64c7a567dc6d6c63e854c3');
//获取地址:http://open.tietuku.com/manager
define('MY_SECRETKEY', '88c44b19064c0ed9dd634c7d31cbce9920aa379e');
//获取地址:http://open.tietuku.com/manager
class Tietuku{
 static public $ttk = '';
 
/**
 * 构造函数
 * 
 * @access public
 * @param mixed $accesskey 贴图库平台accesskey
 * @param mixed $secretkey 贴图库平台secretkey
 * @return void
 */
 public function __construct(){
  self::$ttk = new TTKClient(MY_ACCESSKEY,MY_SECRETKEY);
 }
 public function init($config = array()){
  self::$ttk = new TTKClient($config['app'],$config['key']);
 }
 /**
  * 通过相册ID分页查询相册中的图片 每页30张图片
  *
  * 对应API：{@link http://open.tietuku.com/doc#list-album}
  *
  * @access public
  * @param int $aid 相册ID。
  * @param int $page_no 页数，默认为1。
  * @param boolean $createToken 是否只返回Token，默认为false。
  * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
  */
  public function getPicListByAlbum($albumid,$p = 1){
   $r = self::$ttk->getAlbumPicByAid($albumid, $p);
   return $r;
  }
 /**
  * 根据用户ID查询用户相册列表
  *
  * 对应API：{@link http://open.tietuku.com/doc#album-get}
  *
  * @access public
  * @param int $uid 用户ID
  * @param boolean $createToken 是否只返回Token，默认为false。
  * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
  */
 public function getAllAlbumListByUid($uid){
   $r = self::$ttk->getAlbumByUid($uid);
   return $r;
 }
 /**
  * 创建相册
  *
  * 对应API：{@link http://open.tietuku.com/doc#album-create}
  *
  * @access public
  * @param string $albumname 相册名称。
  * @param boolean $createToken 是否只返回Token，默认为false。
  * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
  */
 public function createAlbum($name){
  $r = self::$ttk->createAlbum($name);
  return $r;
 }
	/**
	 * 根据 图片ID 查询相应的图片详细信息
	 *
	 * 对应API：{@link http://open.tietuku.com/doc#pic-getonepic}
	 *
	 * @access public
	 * @param int $id 图片ID。
	 * @param boolean $createToken 是否只返回Token，默认为false。
	 * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
	 */
	//$res=$ttk->getOnePicById(13557);
	/**
	 * 根据 图片find_url 查询相应的图片详细信息
	 *
	 * 对应API：{@link http://open.tietuku.com/doc#pic-getonepic}
	 *
	 * @access public
	 * @param string $find_url 图片find_url
	 * @param boolean $createToken 是否只返回Token，默认为false。
	 * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
	 */
	//$res=$ttk->getOnePicByFind_url('c55d2882a24f519f');
	/**
	 * 通过一组图片ID 查询图片信息
	 *
	 * 对应API：{@link http://open.tietuku.com/doc#list-getpicbyids}
	 *
	 * @access public
	 * @param mix $ids 图片ID数组。(1.多个ID用逗号隔开 2.传入数组)
	 * @param boolean $createToken 是否只返回Token，默认为false。
	 * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
	 */
	//$res=$ttk->getPicByIds('13557,13558');
	/**
	 * 查询所有的分类
	 *
	 * 对应API：{@link http://open.tietuku.com/doc#catalog-getall}
	 *
	 * @access public
	 * @param boolean $createToken 是否只返回Token，默认为false。
	 * @return string 如果$createToken=true 返回请求接口的json数据否则只返回Token
	 */
	//$res=$ttk->getCatalog();
 /**
  * 上传单个文件到贴图库 
  *
  * 对应API：{@link http://open.tietuku.com/doc#upload}
  *
  * @access public
  * @param int $aid 相册ID
  * @param array $file 上传的文件。
  * @return string 如果$file!=null 返回请求接口的json数据否则只返回Token
  */
 public function uploadFile($albumid,$filename){
  $r = self::$ttk->uploadFile($albumid, $filename);
  $r = json_decode($r, 1);
  return $r;
 }
 /**
	 * 上传多个文件到贴图库 
	 *
	 * 对应API：{@link http://open.tietuku.com/doc#upload}
	 *
	 * @access public
	 * @param int $aid 相册ID
	 * @param string $filename 文件域名字
	 * @return string 如果$file!=null 返回请求接口的json数据否则只返回Token
	 */
	//$res=$ttk->curlUpFile('你的相册ID','file');

 /**
  * 上传网络文件到贴图库 (只支持单个连接)
  *
  * 对应API：{@link http://open.tietuku.com/doc#upload-url}
  *
  * @access public
  * @param int $aid 相册ID
  * @param string $fileurl 网络图片地址
  * @return string 如果$fileurl!=null 返回请求接口的json数据否则只返回Token
  */
 public function uploadRemoteFile($albumid,$url){
  $r = self::$ttk->uploadFromWeb($albumid,$url);
  $r = json_decode($r, 1);
  return $r;
 }
//比var_dump更友好的格式化输出 从 ThinkPHP 提取
 public function dump($var, $echo=true, $label=null, $strict=true) {
    $label = ($label === null) ? '' : rtrim($label) . ' ';
    if (!$strict) {
        if (ini_get('html_errors')) {
            $output = print_r($var, true);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        } else {
            $output = $label . print_r($var, true);
        }
    } else {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();
        if (!extension_loaded('xdebug')) {
            $output = preg_replace('/\]\=\>\n(\s+)/m', '] => ', $output);
            $output = '<pre>' . $label . htmlspecialchars($output, ENT_QUOTES) . '</pre>';
        }
    }
    if ($echo) {
        echo($output);
        return null;
    }else
        return $output;
 }
}
?>

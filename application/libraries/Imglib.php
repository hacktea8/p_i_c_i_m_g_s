<?php
class Imglib{
  protected static $im=0;
  protected $thumb=0;
  public static $imgdata='';
  protected $imgtype='';
  protected $sizeinfo='';
  public static $config=array();
  public static $watermark='';
  public static $postion='';
  public static $waterfont='';
  public static $mass=80;//80% 图片质量
  public static $dstimg=NULL;
  public static $iscut=0;//是否切图
  public static $wsize=array();//水印图大小
  public static $size=array();//原图大小
  public static $resize=array();//缩略大小

//检查环境
  public static function checksupport(){
     if(function_exists(gd_info)){
        return gd_info();
     }
     return false;
  }
//初始化配置
  public static function init(){
    if(isset($config['imgdata'])){
       $this->imgdata=$config['imgdata'];
       $this->im=imagecreatefromstring($this->imgdata);
    }
    if(isset($config['imgtype'])){
       $this->imgtype='image'.strtolower($config['imgtype']);
    }
    if(isset($config['watermark'])){
       $this->watermark=$config['watermark'];
       $this->wim=imagecreatefromstring($this->watermark);
    }
    if(isset($config['postion'])){
       $this->postion=$config['postion'];
    }
	$this->getimgsizefromstring();
  }
  
//从内容获取图片句柄
  public static function getimgsizefromstring(){
     $this->sizeinfo=getimagesizefromstring($this->imgdata);
  }
//增加缩略图
  public static function getthumbimg(){
        //改变后的图象的比例
        $resize_ratio = ($this->resize['width'])/($this->resize['height']);
        //实际图象的比例
        $ratio = ($this->size['width'])/($this->size['height']);
        if(($this->cut))
        //裁图
        {
            $this->thumb = imagecreatetruecolor($this->resize['width'],$this->resize['height']);
            if($ratio>=$resize_ratio)
            //高度优先
            {
                imagecopyresampled($this->thumb, $this->im, 0, 0, 0, 0, $this->resize['width'],$this->resize['height'], (($this->size['height'])*$resize_ratio), $this->size['height']);
            }
            if($ratio<$resize_ratio)
            //宽度优先
            {
                imagecopyresampled($this->thumb, $this->im, 0, 0, 0, 0, $this->resize['width'], $this->resize['height'], $this->size['width'], (($this->size['width'])/$resize_ratio));
            }
        }
        else
        //不裁图
        {
            if($ratio>=$resize_ratio)
            {
                $this->thumb = imagecreatetruecolor($this->resize['width'],($this->resize['width'])/$ratio);
                imagecopyresampled($this->thumb, $this->im, 0, 0, 0, 0, $this->resize['width'], ($this->resize['width'])/$ratio, $this->size['width'], $this->size['height']);
            }
            if($ratio<$resize_ratio)
            {
                $this->thumb = imagecreatetruecolor(($this->resize['height'])*$ratio,$this->resize['height']);
                imagecopyresampled($this->thumb, $this->im, 0, 0, 0, 0, ($this->resize['height'])*$ratio, $this->resize['height'], $this->size['width'], $this->size['height']);
            }
        }

       // ImageJpeg ($newimg,$this->dstimg);
  }
//保存图片
  public static function saveimg(){
     imagejpeg($this->im, $this->dstimg, $this->mass);
     imagedestroy($this->im);
  }
//增加水印
  public static function setwatermark(){
     $watersize=getimagesizefromstring($this->watermark);
     $wim=imagecreatefromstring($this->watermark);
     $w_w=0;$posX=0;
     $w_h=0;$posY=0;
     if($this->size['width']<300 &&$this->size['height']<300){
        return false;
     }
     switch($this->postion){
//中间
     case 0:
       $posX=($this->size['width']-$w_w)/2
       $posY=$this->size['height']/2;
     break;
//左上角
     case 1:
       $posX=$this->w_w/2+10;
       $posY=$this->w_h/2+10;
     break;
//右上角
     case 2:
       $posX=$this->size['width']-10-$this->w_w;
       $posY=$this->w_h/2+10;
     break;
//左下角
     case 3:
       $posX=10;
       $posY=($this->size['height']-$this->w_h)/2-10;
     break;
//右下角
     default:
       $posX=($this->size['width']-$this->w_w)/2-10;
       $posY=($this->size['height']-$this->w_h)-10;
     
     }
     imagecopy($this->im, $this->wim, $posX, $posY, 0, 0, $w_w,$w_h);//拷贝水印到目标文件  
  }
//展示图片
  public static function showimg(){
     header("Content-Type: ".$this->sizeinfo['mime']);
     ob_clean();
     $this->imgtype($this->im);
     if(is_resource($this->im)){
         imagedestroy($this->im);
     }
  }
}
?>

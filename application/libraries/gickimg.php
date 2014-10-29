<?php
/**
install http://www.hacktea8.com/forum.php?mod=viewthread&tid=9787
*/

class Gickimg{
 static public $convert = '/usr/local/bin/convert';
 
 public function cropThumbImage($img, $width = '', $height = '', $out = ''){
  if( !file_exists($img)){
   return false;
  }
  $out = $out?$out:$img;
  $cmd = sprintf('%s %s -resize %sx%s -gravity center +profile "*" %s',self::$convert,$img,$width,$height,$out);
  @exec($cmd);
  return $out;
 }
 public function convert($img,$out = ''){
  $out = $out?$out:$img;
  $cmd = sprintf('%s -strip +profile "*" %s %s',self::$convert,$img, $out);
  @exec($cmd);
 }
 public function cropImage($img,$width,$height,$lx,$ly,$out = ''){
  if( !file_exists($img)){
   return false;
  }
  $out = $out?$out:$img;
  $cmd = sprintf('%s -crop %dx%d+%d+%d +profile "*" %s %s',self::$convert,$width,$height,$lx,$ly,$img,$out);
  return $out;
 }
 public function resizeImage($img,$width,$height,$out = ''){
  if( !file_exists($img)){
   return false;
  }
  $out = $out?$out:$img;
  $cmd = sprintf('%s -resize %sx%s +profile "*" %s %s',self::$convert,$width,$height,$img,$out);
  @exec($cmd);
  return $out;
 }
 public function waterMark($img,$wimg,$out = '', $wmpos = 0){
  //$size = $this->getmarklocation($im, $this->wim, $wmpos);
  $out = $out?$out:$img;
  $cmd = sprintf('%s %s %s -gravity southeast -geometry +10+15 -composite %s',self::$convert,$img,$wimg,$out);
  @exec($cmd);
  return $out;
 }
 private function getmarklocation(&$imgsize,&$watersize,$wmpos = 0){
//右中
    if(15 == $wmpos){
       return array('w'=>$imgsize->getImageWidth()-$watersize->getImageWidth()-10,
               'h'=>($imgsize->getImageHeight()-$watersize->getImageHeight()-10)/2);
    }else if(12 == $wmpos){
//下中
       return array('w'=>($imgsize->getImageWidth()-$watersize->getImageWidth()-10)/2,
                'h'=>$imgsize->getImageHeight()-$watersize->getImageHeight()-10);
    }else if(13 == $wmpos){
//上中
       return array('w'=>($imgsize->getImageWidth()-$watersize->getImageWidth()-10)/2,
                   'h'=>10);
    }else if(14 == $wmpos){
//左中
       return array('w'=>10,
              'h'=>($imgsize->getImageHeight()-$watersize->getImageHeight()-10)/2);
    }
//右下角
    return array('w'=>$imgsize->getImageWidth()-$watersize->getImageWidth()-10,
                   'h'=>$imgsize->getImageHeight()-$watersize->getImageHeight()-10);

 }
}
?>

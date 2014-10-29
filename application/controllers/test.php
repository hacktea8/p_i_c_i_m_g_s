<?php

$url = 'http://img.hacktea8.com/ttkapi/index';
$post_data = array('img'=> new CURLFile('/var/www/html/images/cache/images/ttkw1.jpg'));
post($url,$post_data);exit;


function post($url,$post_data){
                $headerArr = array('Expect:');
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_TIMEOUT,300);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION  ,1);
                curl_setopt($ch, CURLOPT_HEADER ,1);
                curl_setopt ($ch, CURLOPT_HTTPHEADER , $headerArr );
                $output = curl_exec($ch);
$info = curl_getinfo($ch);
var_dump($output);
var_dump($info);
var_dump($post_data);exit;
                curl_close($ch);
                return $output;
        }


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $seo_title;?></title>
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<meta name="keywords" content="<?php echo $seo_keywords;?>" />
<meta name="description" content="<?php echo $seo_description;?>" />
<link rel="icon" href="/favicon.ico" type="image/x-icon" />
<script src="/public/js/index_top.js" type="text/javascript"></script>
<script src='/public/js/ZeroClipboard.js' type="text/javascript"></script>
   <script language="JavaScript">
var clip = null;
function init_copy() {
clip = new ZeroClipboard.Client();
clip.setHandCursor( true );
clip.glue('ClipBoard');
clip.addEventListener('mouseOver', function (client) {
// update the text on mouse over
clip.setText( document.getElementById('invite_link').value );
});
clip.addEventListener('onMouseUp', function (client) {
alert('复制成功');

});
}

</script>


 
<link rel="stylesheet" type="text/css" href="/public/css/index_top.css" media="all"/>
    <link rel="stylesheet" type="text/css" href="/public/css/loginreg.css" />
<script type="text/javascript">
var SITE_PATH = '/';
var SITE_URL = '<?php echo $base_url;?>';
var TPL_PATH = '/public/images/';
var PUBLIC_PATH	 = '/public/';
var MODULE_NAME	 = 'User';
var ACTION_NAME	 = 'login';
var COOKIE_PRE = "Jdlq_2132_";
var TPL_NAME = 'zhimei';
</script>
</head>
<body>


<div id="pagewrap">
<!--新头部开始-->
        <div id="header" class="cf">
            <!--newhead-->
            <div id="header-1" class="header-1">
                <div class="topsysmenu cf" id="topsysmenu">
                    <div class="topsearch">
                        <div class="searchrelt cf">
                            <form action="/book/search" method="post" onsubmit="return false;">
                                <input type="text" class="inputtext" id="jquery-search" autocomplete="off" tooltip="搜索你感兴趣的">
                                <input type="submit" value=" " class="searchbt" id="fm_hd_btm_shbx_bttn" />
                                <input type="hidden" name="action" value="search" />
                            </form>
                        </div>
                    </div>
                    <div class="sysmenu">
<ul id="sysmenus">
<li><a href="/user/login" class="regmenu">登陆</a></li>
<li><a href="/user/register" class="regmenu">注册</a></li>
<li>
            <a href="javascript:void(0)" class="topothermenu"><span>更多</span></a>
            <div class="clear"></div>
<div class="topdmenu dmenu_location2 dmenu_with dmenuhide" style="display: none; ">
            	<img class="f_login" src="/public/images/icon_sina.png"><a href="/login.php?mod=sina">微博登录</a>
<img class="f_login" src="/public/images/icon_qq_qq.png"><a href="/login.php?mod=qq">QQ登录</a>
                <img class="f_login" src="/public/images/tao.png"><a href="/login.php?mod=taobao">淘宝登录</a>
                <img class="f_login" src="/public/images/icon_qq.png"><a href="/login.php?mod=tqq">腾讯微博</a>
</div>
</li>
</ul>
</div>
                    <a href="<?php echo $base_url;?>" class="mainlogo"><img src="/public/images/index_logo.gif" /></a>
                    
                </div>
            </div>
            <div id="header-2" class="header-2">
                <div class="topchannel" id="topchannel">
                  
                    <div class="channelmenu">
                        <a href="/sy/hot"  class="current" >热门</a>
                        <span class="dot"></span>
                        <a href="/sy/all" >全部</a>
                    </div>
                </div>
            </div>
        <div>
                    <div class="altbartop" id="unlogin-alterbartop" style="display: none; ">
                <span class="yahei16"><?php echo $site_name;?>，找到真正想要的。</span>
                <a href="/user/login"  class="floatbutton" >登陆</a>&nbsp;&nbsp;或&nbsp;&nbsp;
                <a href="/user/register"  class="rightbutton">注册</a>
            </div>
                </div>
    </div>
<div class="clear"></div>
<!--新头部结束-->
<div id="body_wrap"><div id="body" class="fm960">
<div class="piece1">
<div class="piece1_hd"></div>
<div class="piece1_bd clearfix">
<div id="content" style="width:960px;">
<div class="lg_left">
<h1>登陆最旅</h1>
<div class="lg_form">
<form id="loginForm" name="loginForm" action="/maindex/userlogin/2" method="post">
<div class="lg_name">
<span>用户名：</span>
<input type="text" value="" tooltip="邮箱 或 昵称" name="row[email_name]" class="text" maxlength="32" />
</div>
<div class="err_name"><span></span></div>
<div class="clear"></div>
<div class="lg_pass">
<span>密　码：</span>
<input type="password" value="" name="row[pass]" class="text" maxlength="32" />
</div>
<div class="err_pass"><span></span></div>
<div class="clear"></div>
<div class="iserror" id="iserror">
<div class="war"><img src="/public/images/error_02.png"><span>登录名或密码错误</span></div>
<div class="content">
<pre>1、如果登录名是邮箱地址，请输入全称<br/> 如：share@qq.com<br/>2、请检查登录名大小写是否正确。<br/>3、请检查密码大小写是否正确。</pre>
</div>
</div>
<div class="iserror iserror2" id="iserror2">
<div class="war"><img src="/public/images/error_02.png"><span>用户被锁定，无法登录！</span></div>
<div class="content">
<pre>1.可以联系管理员进行解锁。<br/>2.重新注册一个账户！</pre>
</div>
</div>
<div class="lg_remember">
<label>
<input type="checkbox" name="remember" class="checkbox" checked="checked" value="1209600">
<span>记住我（两周免登陆）</span>
</label>
</div>
<div class="lg_login">
<input type="submit" value=" " class="sub" id="login_submit" />
<a href="/user/forgetpassword">忘记密码？</a>
</div>
<div class="clear"></div>
<div class="lg_login_loading">
<span>登陆中，请稍候...</span>
</div>
<input name="rhash" value="73c6d9b5" type="hidden"/>
<input name="refer" value="/me" type="hidden"/>
<input name="action" value="ajax_login" type="hidden"/>
</form>
<div class="ot_login">
<span>您也可以用以下方式登录：</span>
<div class="ot_btn"></div>
</div>
</div>
</div>
<div class="lg_right">
<h1>注册</h1>
<span>还没有最旅帐号？</span>
<a href="/maindex/userlogin/3"></a>
</div>
</div>
</div>
<div class="piece1_ft"></div>
</div>
</div>	</div>
    
    <div id="footer" >
<p id="footer-p">方维兴趣分享系统 系统版本：v2.0版权所有&copy; 方维</p>
    </div>
<div class="totop" id="backtotop" style="display: block; "><a href="javascript:void(0);">返回</a></div>
</div> 
</body>
<script src="/public/js/index_bdy.js" type="text/javascript" defer="true"></script><script src="/public/js/loginreg.js" type="text/javascript"></script>
<script type="text/javascript">
var USER_ID = 0;
var URL_MODEL = "1";
var DOMAIN = "<?php echo $domain_name;?>";
var MANAGES = "";
</script>
<script type="text/javascript">
jQuery(function($){
$(".lazyload").lazyload({"placeholder":"/public/images/lazyload.gif"});
$("#fm_hd_btm_shbx_bttn").click(function(){
var keyword = $("#jquery-search").val();
if(keyword == '搜索你感兴趣的' || keyword == '')
{
return false;
}
$.Head_Search(keyword,'share');
return false;
});

$('#sysmenus li').hover(function(){
$(this).find('.topdmenu').slideDown(200);
$(this).find('.topothermenu').addClass('topmenuover');
$(this).find('.topusername').addClass('topmenuover');
$('#topdmenu1').width($(this).innerWidth()-2);
},function(){
$(this).find('.topdmenu').hide();
$(this).find('.topothermenu').removeClass('topmenuover');
$(this).find('.topusername').removeClass('topmenuover');
});
if($("#unlogin-alterbartop").length==1) {
setTimeout("$('#unlogin-alterbartop').slideDown()",1000);
}
if($("#firstload").length == 1)
{
if($("#firstload").css("display") == "block")
{
setTimeout("$('#firstload').hide();$('#body').show();$('#firstfastbox').hide();",300);

}
}

$(window).scroll(function(){
if($(this).scrollTop()>61){
$('#header-2').addClass('topfixed');
} 
else 
{
$('#header-2').removeClass('topfixed');
}

if ($(document.documentElement).scrollTop() > 0 || $(document.body).scrollTop() > 0) {
            $("#backtotop").show();
            $("#backtotop").die().live("click",
            function() {
                $("body,html").animate({
                    scrollTop: 0
                },
                500)
            })
        } else {
            $("#backtotop").hide()
        }
});
});
 
</script>
</html>
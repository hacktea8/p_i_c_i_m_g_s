<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="/public/css/admin/style.css" rel="stylesheet" />
<script type="text/javascript" src="/public/js/jquery.js"></script>
<script type="text/javascript" src="/public/js/base.js"></script>
<script type="text/javascript" src="/public/js/json.js"></script>
<script type="text/javascript" src="/public/js/jquery.pngFix.js"></script>
<script type="text/javascript">
<!--
//指定当前组模块URL地址 
var URL = '/admin/index.php/Share';
var ROOT_PATH = '';
var APP	 =	 '/admin/index.php';
var STATIC = '/admin/Tpl/Default/Static';
var VAR_MODULE = 'm';
var VAR_ACTION = 'a';
var CURR_MODULE = 'Share';
var CURR_ACTION = 'index';

//定义JS中使用的语言变量
var CONFIRM_DELETE = '删除后将不可恢复，确定删除吗？';
var AJAX_LOADING = '提交请求中，请稍候...';
var AJAX_ERROR = 'AJAX请求发生错误！';
var ALREADY_REMOVE = '已删除';
var SEARCH_LOADING = '搜索中...';
var CLICK_EDIT_CONTENT = '点击修改内容';
//-->
</script>
</head>
<body>
	<div class="fanwe-body">
		<div class="fb-title"><div><p><span>分享 > 分享列表</span></p></div></div>
		<div class="fb-body">
			<table class="body-table" cellpadding="0" cellspacing="1" border="0">
				<tr>
					<td class="body-table-td">
						<div class="body-table-div">
<script type="text/javascript" src="/public/js/dataList.js"></script>
<div class="handle-btns">
	<div class="img-button "><p><input type="button" id="editShare" name="editShare" value="编辑" onclick="editData(this,'checkList','share_id')" class="editShare"></p></div>
	<div class="img-button "><p><input type="button" id="removeShare" name="removeShare" value="删除" onclick="removeData(this,'checkList')" class="removeShare"></p></div>
</div>
<div class="search-box">
    <form action="/admin/index_share">
		<span>相册ID</span>
		<input class="textinput" type="text" value="" name="ablumid" size="12" />
		<small></small>
		<span>用户ID</span>
		<input class="textinput" type="text" value="" name="userid" id="user_name" size="8" />
		<small></small>
		<span>分享类型</span>
		<select name="type">
			<option value="0"  >全部</option>
			<option value="album_best"  >推荐杂志社</option>
		</select>
		<small></small>
		<span>审核</span>
		<select name="status">
			<option value="0"  >全部</option>
			<option value="1" selected="selected" >未审核</option>
			<option value="2"  >已审核</option>
		</select>
		<input class="submit_btn" type="submit" value="搜索" />
		
	</form>
</div>
<!-- Think 系统列表组件开始 -->
<table id="checkList" class="table-list list" cellpadding="0" cellspacing="0" border="0">
  <thead>
    <tr><th width="30" class="first"><input type="checkbox" onclick="checkAll('checkList')"></th><th width="50" ><a href="javascript:sortBy('share_id','','index')" title="按照编号 ">编号</a></th><th ><a href="javascript:sortBy('content','','index')" title="按照相册 ">相册</a></th><th width="100" ><a href="javascript:sortBy('cate_name','','index')" title="按照分享分类 ">分享分类</a></th><th width="100" ><a href="javascript:sortBy('user_name','','index')" title="按照用户名 ">用户名</a></th><th width="100" ><a href="javascript:sortBy('create_time','','index')" title="按照发布时间 ">发布时间</a></th><th width="90" ><a href="javascript:sortBy('type','','index')" title="按照分享类型 ">分享类型</a></th><th width="60" ><a href="javascript:sortBy('status','','index')" title="按照审核 ">审核</a></th><th width="240">操作</th></tr></thead><tbody></tbody></table>
<!-- Think 系统列表组件结束 -->

<div class="pager"></div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="ajax-loading"></div>
</body>
<script type="text/javascript">
jQuery(function($){
	updateBodyDivHeight();
	$(window).resize(function(){
		updateBodyDivHeight();
	});
});

function updateBodyDivHeight()
{
	jQuery(".body-table-div").height(jQuery(".fanwe-body").height() - 36);
	if(jQuery(".body-table-div").get(0).scrollHeight > jQuery(".body-table-div").height())
	{
		var width = jQuery(".body-table-div").width() - 16;
		jQuery(".body-table-div > *").each(function(){
			if(!$(this).hasClass('ajax-loading'))
			{
				$(this).width(width)	
			}
		});
	}
}
</script>
</html>
<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="renderer" content="webkit">

<title>明日科技后台</title>

<link href="/Public/css/bootstrap.min.css?v=3.4.0" rel="stylesheet">
<link href="/Public/font-awesome/css/font-awesome.css?v=4.3.0" rel="stylesheet">
<link href="/Public/css/animate.css" rel="stylesheet">
<link href="/Public/css/admin-style.css?v=2.2.0" rel="stylesheet">

<!-- Mainly scripts -->
<script src="/Public/js/jquery-2.1.1.min.js"></script>
<script src="/Public/js/bootstrap.min.js?v=3.4.0"></script>
<!-- 折叠插件 -->
<script src="/Public/js/jquery.metisMenu.js"></script>
<!-- 滚动条插件 -->
<script src="/Public/js/jquery.slimscroll.min.js"></script>
<script src="/Public/js/hplus.js?v=2.2.0"></script>

<!--Layer弹层插件-->
<script src="/Public/static/layer/layer.js"></script>
<!-- 表单验证插件 -->
<script src="/Public/js/myFormValidate.js"></script>

<!-- 自定义插件 -->
<script type="text/javascript" src="/Public/js/admin.js"></script>


<!--<script type="text/javascript" src="/Public/js/plugins/plupload/plupload.js"></script>-->






</head>
<body>
<div id="wrapper">
    <script>
    $(function(){
        var controller = "<?php echo CONTROLLER_NAME;?>";
        var action = "<?php echo ACTION_NAME;?>";
        var nav    = $('.nav-second-level a');
        nav.each(function(){
            var url = $(this).attr('href');
            if(url.indexOf('/'+controller+"/"+action) > 0 ){
                $(this).parent().parent().addClass('in');
                $(this).parent().addClass('active');
                $(this).parent().parent().parent().addClass('active');
                return false;
            }
        });
    });
</script>

<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav" id="side-menu">
            <li class="nav-header">

                <div class="dropdown profile-element"> <span>
                            <img alt="image" class="img-circle" src="/Public/images/profile_small.jpg" />
                             </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="index.html#">
                                    <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo ($user['id']); ?></strong>
                             </span> <span class="text-muted text-xs block">超级管理员 <b class="caret"></b></span> </span>
                    </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="<?php echo U('Admin/changePassword');?>">修改密码</a></li>
                        <li><a href="<?php echo U('Admin/logout');?>">安全退出</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    H+
                </div>

            </li>
            <li>
                <a href="index.html"><i class="fa fa-th-large"></i>
                    <span class="nav-label">系统设置</span>
                    <span class="fa arrow"></span></a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('Index/index');?>">首页</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-users"></i> <span class="nav-label">会员管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('User/index');?>">会员列表</a></li>
                    <li><a href="<?php echo U('User/levelList');?>">会员等级</a></li>
                </ul>

            </li>
            <li>
                <a href="javascript:;"><i class="fa fa fa-book"></i> <span class="nav-label">商品管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('Goods/categoryList');?>">商品分类</a></li>
                    <li><a href="<?php echo U('Goods/goodsList');?>">商品列表</a></li>
                    <li><a href="<?php echo U('GoodsType/goodsTypeList');?>">商品类型</a></li>
                    <li><a href="<?php echo U('Spec/specList');?>">商品规格</a></li>
                    <li><a href="<?php echo U('GoodsAttribute/goodsAttributeList');?>">商品属性</a></li>
                    <li><a href="<?php echo U('Comment/index');?>">商品评论</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-shopping-cart"></i> <span class="nav-label">订单管理</span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('Order/index');?>">订单列表</a></li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-buysellads"></i> <span class="nav-label">广告管理 </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('Ad/adList');?>">广告列表</a>
                    </li>
                    <li><a href="<?php echo U('Ad/positionList');?>">广告位置</a>
                    </li>
                </ul>
            </li>
            <li>
                <a href="javascript:;">
                    <i class="fa fa-buysellads"></i> <span class="nav-label">文章管理 </span>
                    <span class="fa arrow"></span>
                </a>
                <ul class="nav nav-second-level">
                    <li><a href="<?php echo U('Article/articleList');?>">文章列表</a></li>
                    <li><a href="<?php echo U('Article/categoryList');?>">文章分类</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>







    
	<div id="page-wrapper" class="gray-bg">
		<div class="row border-bottom">
    <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
        <div class="navbar-header">
            <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <ul class="nav navbar-top-links navbar-right">
            <li>
                <span class="m-r-sm text-muted welcome-message">
                    <a href="<?php echo U('Index/index');?>" title="返回首页"><i class="fa fa-home"></i>欢迎使用商城后台系统</a>
                </span>
            </li>
            <li>
                <a href="<?php echo U('Admin/logout');?>">
                    <i class="fa fa-sign-out"></i> 退出
                </a>
            </li>
        </ul>
    </nav>
</div>
		<div class="wrapper wrapper-content">
			<div class="row">
				<div class="col-lg-12">
					<div class="ibox float-e-margins">
						<div class="ibox-title">
							<h5>文章分类</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<div class="box-body">
									<div class="row">
										<div class="col-sm-12">
										 <div class="box-header">
											<form class="navbar-form form-inline" action="" method="post" id="catform">
												<div class="form-group">
													<button class="btn bg-navy" type="button" onclick="tree_open(this);"><i class="fa fa-angle-double-down"></i>展开</button>
												</div>
												<div class="form-group">
													<select name="cat_type" class="form-control" style="width:200px;" onchange="$('#catform').submit();">
														<option value="">选择分组</option>
														<option value="0">默认</option>
														<option value="1">系统帮助</option>
														<option value="2">系统公告</option>
													</select>
												</div>
												<div class="form-group pull-right">
													<a href="<?php echo U('Article/category');?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增分类</a>
												</div>
											  </form>
										 </div><!-- /.box-header -->
										 <div class="box-body">
										   <div class="row">
											<div class="col-sm-12">
											  <table id="list-table" class="table table-bordered table-striped">
												 <thead>
												   <tr role="row">
													   <th  style="width: 350px;">分类名称</th>
													   <th>所属分组</th>
													   <th>描述</th>
													   <th>是否显示</th>
													   <th>排序</th>
													   <th>操作</th>
												   </tr>
												 </thead>
												<tbody>
												  <?php if(is_array($cat_list)): foreach($cat_list as $k=>$vo): ?><tr role="row" align="center" class="<?php echo ($vo["level"]); ?>" id="<?php echo ($vo["level"]); ?>_<?php echo ($vo["cat_id"]); ?>">
													 <td class="sorting_1" align="left" style="padding-left:<?php echo ($vo[level] * 4); ?>em">
													  <?php if($vo["is_leaf"] != 1): ?><span class="glyphicon glyphicon-minus btn-warning"  id="icon_<?php echo ($vo["level"]); ?>_<?php echo ($vo["id"]); ?>" aria-hidden="true" onclick="rowClicked(this)"  style="margin-left: 10px"></span>&nbsp;<?php endif; ?><span><?php echo ($vo["name"]); ?></span>
													 </td>
													 <td><?php echo ($type_arr[$vo[cat_type]]); ?></td>
													 <td><?php echo ($vo["cat_desc"]); ?></td>
													 <td>
														 <?php if($vo[show_in_nav] == 1): ?>显示
														 <?php else: ?> 隐藏<?php endif; ?>
													 </td>
													<td>
														<input type="text"  class="input-sm" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onchange="updateSort('article_cat','cat_id','<?php echo ($vo["cat_id"]); ?>','sort_order',this)" size="4" value="<?php echo ($vo["sort_order"]); ?>" />
													 </td>
													 <td>
													  <a class="btn btn-primary" href="<?php echo U('Article/category',array('act'=>'edit','cat_id'=>$vo['cat_id']));?>"><i class="fa fa-pencil"></i></a>
													  <?php if($vo["cat_type"] != 1): ?><a class="btn btn-danger" href="javascript:void(0)" data-url="<?php echo U('Article/categoryHandle');?>" data-id="<?php echo ($vo["cat_id"]); ?>" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
													  <?php else: ?>
														<a class="btn btn-default disabled" href="javascript:void(0)"><i class="fa fa-trash-o"></i></a><?php endif; ?>
													</td>
												   </tr><?php endforeach; endif; ?>
												   </tbody>
											   </table>
											</div>
										   </div>
                                         </div><!-- /.box-body -->
                                        </div><!-- /.box -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script type="text/javascript">
	function  tree_open(obj){
		var tree = $('#list-table tr[id^="1_"],#list-table tr[id^="2_"], #list-table tr[id^="3_"] '); //,'table-row'
		if(tree.css('display')  == 'table-row')
		{
			$(obj).html("<i class='fa fa-angle-double-down'></i>展开");
			tree.css('display','none');
			$("span[id^='icon_']").removeClass('glyphicon-minus');
			$("span[id^='icon_']").addClass('glyphicon-plus');
		}else
		{
			$(obj).html("<i class='fa fa-angle-double-up'></i>收缩");
			tree.css('display','table-row');
			$("span[id^='icon_']").addClass('glyphicon-minus');
			$("span[id^='icon_']").removeClass('glyphicon-plus');
		}
	}
     
    // 以下是 bootstrap 自带的  js
    function rowClicked(obj){
      span = obj;

      obj = obj.parentNode.parentNode;

      var tbl = document.getElementById("list-table");

      var lvl = parseInt(obj.className);

      var fnd = false;

      var sub_display = $(span).hasClass('glyphicon-minus') ? 'none' : '' ? 'block' : 'table-row' ;
      //console.log(sub_display);
      if(sub_display == 'none'){
          $(span).removeClass('glyphicon-minus btn-info');
          $(span).addClass('glyphicon-plus btn-warning');
      }else{
          $(span).removeClass('glyphicon-plus btn-info');
          $(span).addClass('glyphicon-minus btn-warning');
      }

      for (i = 0; i < tbl.rows.length; i++)
      {
          var row = tbl.rows[i];

          if (row == obj)
          {
              fnd = true;
          }
          else
          {
              if (fnd == true)
              {
                  var cur = parseInt(row.className);
                  var icon = 'icon_' + row.id;
                  if (cur > lvl)
                  {
                      row.style.display = sub_display;
                      if (sub_display != 'none')
                      {
                          var iconimg = document.getElementById(icon);
                          $(iconimg).removeClass('glyphicon-plus btn-info');
                          $(iconimg).addClass('glyphicon-minus btn-warning');
                      }else{
                          $(iconimg).removeClass('glyphicon-minus btn-info');
                          $(iconimg).addClass('glyphicon-plus btn-warning');
                      }
                  }
                  else
                  {
                      fnd = false;
                      break;
                  }
              }
          }
      }

      for (i = 0; i < obj.cells[0].childNodes.length; i++)
      {
          var imgObj = obj.cells[0].childNodes[i];
          if (imgObj.tagName == "IMG")
          {
              if($(imgObj).hasClass('glyphicon-plus btn-info')){
                  $(imgObj).removeClass('glyphicon-plus btn-info');
                  $(imgObj).addClass('glyphicon-minus btn-warning');
              }else{
                  $(imgObj).removeClass('glyphicon-minus btn-warning');
                  $(imgObj).addClass('glyphicon-plus btn-info');
              }
          }
      }

    }
    // 删除分类
    function delfun(obj){
        layer.confirm('确认删除',function(){
            $.ajax({
                type : 'post',
                url : $(obj).attr('data-url'),
                data : {act:'del',cat_id:$(obj).attr('data-id')},
                dataType : 'json',
                success : function(data){
                    if (data) {
                        layer.msg('删除成功', {icon: 1, time: 1000}, function () {
                            $(obj).parent().parent().remove();
                            layer.closeAll();
                        });
                    } else {
                        layer.alert('删除失败', {icon: 2});  //alert('删除失败');
                    }
                }
            })
        })
    }
</script>

</div>
</body>
</html>
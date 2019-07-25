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







    
    <div id="page-wrapper" class="gray-bg dashbard-1">
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
</div> <!--顶部-->
        <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>添加或编辑分类</h5>
                </div>
                <div class="ibox-content">
                        <!-- /.box-header -->
                        <form action="<?php echo U('Goods/addEditCategory');?>" method="post" class="form-horizontal" id="category_form">
                            <div class="box-body">
                                <div class="form-group">
                                     <label class="col-sm-2 control-label">分类名称</label>
                                     <div class="col-sm-6">
                                        <input type="text" placeholder="名称" class="form-control large" name="name" value="<?php echo ($goods_category_info["name"]); ?>">
                                        <span class="help-inline text-nowrap" style="color:#F00; display:none;" id="err_name"></span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label0 class="control-label col-sm-2">上级分类</label0>
                                    <div class="col-sm-3">
                                        <select name="parent_id_1" id="parent_id_1" onchange="get_category(this.value,'parent_id_2','0');" class="small form-control">
                                            <option value="0">顶级分类</option>
                                            <?php if(is_array($cat_list)): foreach($cat_list as $key=>$v): ?><option value="<?php echo ($v[id]); ?>"><?php echo ($v[name]); ?></option><?php endforeach; endif; ?>
                                        </select>
                                    </div>
                                    <div class="col-sm-3">
                                      <select name="parent_id_2" id="parent_id_2"  class="small form-control">
                                        <option value="0">请选择商品分类</option>
                                      </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label col-sm-2">导航显示</label>
                                    <div class="col-sm-10">
                                        <label>
                                            <?php if(($goods_category_info[is_show] == 1) or ($goods_category_info[is_show] == NULL)): ?><input checked="checked" type="radio" name="is_show" value="1"> 是
                                                <input type="radio" name="is_show" value="0"> 否
                                            <?php else: ?>
                                                <input type="radio" name="is_show" value="1"> 是
                                                <input checked="checked" type="radio" name="is_show" value="0"> 否<?php endif; ?>
                                        </label>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="col-sm-offset-2">
                                        <div id="logo_upload_area" style='padding-top: 5px'>
                                            <div id='photos_area' class="photos_area clearfix">

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="control-label col-sm-2">显示排序</label>
                                    <div class="col-sm-1">
                                        <input type="text" placeholder="50" class="form-control large" name="sort_order" value="<?php echo ($goods_category_info["sort_order"]); ?>"/>
                                        <span class="help-inline text-nowrap" style="color:#F00; display:none;" id="err_sort_order"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="hidden" name="id" value="<?php echo ($goods_category_info["id"]); ?>">
                                <div class="col-sm-4 col-sm-offset-3">
                                    <button type="button" onclick="ajax_submit_form('category_form','<?php echo U('Goods/addEditCategory?is_ajax=1');?>');" class="btn btn-primary "><i class="icon-ok"></i>提交  </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



<script>
/** 以下是编辑时默认选中某个商品分类*/
$(document).ready(function(){
	<?php if($level_cat['2'] > 0): ?>// 如果当前是二级分类就让一级父id默认选中
		 $("#parent_id_1").val('<?php echo ($level_cat[1]); ?>'); 
		 get_category('<?php echo ($level_cat[1]); ?>','parent_id_2','0');<?php endif; ?>	 
	<?php if($level_cat['3'] > 0): ?>// 如果当前是三级分类就一级和二级父id默认 都选中
		 $("#parent_id_1").val('<?php echo ($level_cat[1]); ?>');		 	
		 get_category('<?php echo ($level_cat[1]); ?>','parent_id_2','<?php echo ($level_cat[2]); ?>');<?php endif; ?>	
});
 
</script>


</div>
</body>
</html>
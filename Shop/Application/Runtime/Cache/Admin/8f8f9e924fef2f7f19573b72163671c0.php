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
							<h5>文章列表管理</h5>
						</div>
						<div class="ibox-content">
							<div class="row">
								<!--搜索开始-->
								<form class="navbar-form form-inline" action="<?php echo U('Admin/Article/articleList');?>" method="post">
									<div class="form-group">
										<input type="text" name="keywords" class="form-control" placeholder="搜索">
									</div>
									<div class="form-group">
										<select name="cat_id" class="form-control" style="width:200px;">
											<option value="">选择文章类别</option>
											<?php if(is_array($cats)): foreach($cats as $key=>$vo): ?><option value="<?php echo ($vo["cat_id"]); ?>" <?php if($vo[cat_id] == $cat_id): ?>selected<?php endif; ?>><?php echo ($vo["cat_name"]); ?></option><?php endforeach; endif; ?>
										</select>
									</div>
									<button type="submit" class="btn btn-primary">提交</button>
								</form>
								<!--搜索结束-->
								<!-- 新增和删除开始 -->
								<div class="col-sm-4">
									<div class="input-button">
										<a href="<?php echo U('Admin/Article/article');?>">
											<button class="btn btn-primary add" type="button"><i class="fa fa-plus"></i>&nbsp;新增</button>
										</a>
										<button class="btn btn-warning delete-all" type="button" url="<?php echo U('delAll');?>"><i class="fa fa-minus "></i>&nbsp;删除</button>
									</div>
								</div>
								<!-- 新增和删除结束 -->
							</div>
							<!--表格开始    -->
							<div class="box-body">
								<div class="row">
									<div class="col-sm-12">
										<table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
											<thead>
											<tr role="row">
                                                <th class="text-center">
                                                    <input type="checkbox" id="checkAll" class="check-all regular-checkbox">
                                                    <label for="checkAll"></label>
                                                </th>
												<th class="text-center">文章标题</th>
												<th class="text-center">文章类别</th>
												<th class="text-center">显示</th>
												<th class="text-center">发布时间</th>
												<th class="text-center">操作</th>
											</tr>
											</thead>
											<tbody>
											<?php if(is_array($list)): foreach($list as $k=>$vo): ?><tr role="row" align="center">
                                                    <td>
                                                        <input class="ids regular-checkbox" type="checkbox" value="<?php echo ($vo["article_id"]); ?>" name="ids[]" id="check_<?php echo ($list["article_id"]); ?>">
                                                        <label for="check_<?php echo ($list["article_id"]); ?>"></label>
                                                    </td>
													<td><?php echo (getSubstr($vo["title"],0,33)); ?></td>
													<td><?php echo ($vo["category"]); ?></td>
													<td>
														<img width="20" height="20" src="/Public/images/<?php if($vo[is_open] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('Article','article_id','<?php echo ($vo["article_id"]); ?>','is_open',this)"/>
													</td>
													<td><?php echo ($vo["add_time"]); ?></td>
													<td>
														<a target="_blank" href="<?php echo U('Home/Article/detail',array('article_id'=>$vo['article_id']));?>" data-toggle="tooltip" title="" class="btn btn-info" data-original-title="查看详情"><i class="fa fa-eye"></i></a>
														<a class="btn btn-primary" href="<?php echo U('Article/article',array('act'=>'edit','article_id'=>$vo['article_id']));?>"><i class="fa fa-pencil"></i></a>
														<a class="btn btn-danger" href="javascript:void(0)" data-url="<?php echo U('Article/aticleHandle');?>" data-id="<?php echo ($vo["article_id"]); ?>" onclick="delfun(this)"><i class="fa fa-trash-o"></i></a>
													</td>
												</tr><?php endforeach; endif; ?>
											</tbody>
										</table>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6 text-left"></div>
									<div class="col-sm-6 text-right"><?php echo ($page); ?></div>
								</div>
							</div>
							<!--表格结束-->
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <script src="/Public/js/delete.js"></script>
	<script>
		function delfun(obj) {
            layer.confirm('确认删除', function () {
                $.ajax({
                    type: 'post',
                    url: $(obj).attr('data-url'),
                    data: {act: 'del', article_id: $(obj).attr('data-id')},
                    dataType: 'json',
                    success: function (res) {
                        if (res.status == 1) {
                            layer.msg(res.msg, {icon: 1, time: 1000}, function () {
                                $(obj).parent().parent().remove();
                                layer.closeAll();
                            });
                        } else {
                            layer.alert(res.msg, {icon: 2});  //alert('删除失败');
                        }
                    }
                })
            })
        }
	</script>

</div>
</body>
</html>
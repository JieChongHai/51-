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
        <section class="content">
            <div class="row">
                <div class="col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <nav class="navbar navbar-default">
                                <div class="collapse navbar-collapse">
                                    <div class="navbar-form row">
                                        <div class="col-md-1">
                                            <button class="btn bg-navy" type="button" onclick="tree_open(this);"><i class="fa fa-angle-double-down"></i>展开</button>
                                        </div>
                                        <div class="col-md-9">
                                            <span class="warning">温馨提示：顶级分类（一级大类）设为推荐时才会在首页楼层中显示</span>
                                        </div>
                                        <div class="col-md-2">
                                            <a href="<?php echo U('Goods/addEditCategory');?>" class="btn btn-primary pull-right"><i class="fa fa-plus"></i>新增分类</a>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div><!-- /.box-header -->
                        <div class="box-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <table id="list-table" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
                                        <thead>
                                        <tr role="row">
                                            <th valign="middle">分类ID</th>
                                            <th valign="middle">分类名称</th>
                                            <th valign="middle">是否推荐</th>
                                            <th valign="middle">是否显示</th>
                                            <th valign="middle">排序</th>
                                            <th valign="middle">操作</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php if(is_array($cat_list)): foreach($cat_list as $k=>$vo): ?><tr role="row" align="center" class="<?php echo ($vo["level"]); ?>" id="<?php echo ($vo["level"]); ?>_<?php echo ($vo["id"]); ?>" <?php if($vo[level] > 1): ?>style="display:none"<?php endif; ?>> <!-- 隐藏二级和三级分类 -->
                                            <td><?php echo ($vo["id"]); ?></td>
                                            <td align="left" style="padding-left:<?php echo ($vo[level] * 5); ?>em">
                                                <?php if($vo["have_son"] == 1): ?><!-- 存在二级或三级分类 -->
                                                    <span class="glyphicon glyphicon-plus btn-warning" style="padding:2px; font-size:12px;"  id="icon_<?php echo ($vo["level"]); ?>_<?php echo ($vo["id"]); ?>" aria-hidden="false" onclick="rowClicked(this)" ></span>&nbsp;<?php endif; ?>
                                                <span><?php echo ($vo["name"]); ?></span>
                                            </td>
                                            <td>
                                                <img width="20" height="20" src="/Public/images/<?php if($vo[is_hot] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods_category','id','<?php echo ($vo["id"]); ?>','is_hot',this)"/>
                                            </td>
                                            <td>
                                                <img width="20" height="20" src="/Public/images/<?php if($vo[is_show] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods_category','id','<?php echo ($vo["id"]); ?>','is_show',this)"/>
                                            </td>
                                            <td>
                                                <input type="text" onchange="updateSort('goods_category','id','<?php echo ($vo["id"]); ?>','sort_order',this)"   onkeyup="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" size="4" value="<?php echo ($vo["sort_order"]); ?>" class="input-sm" />
                                            </td>
                                            <td>
                                                <a class="btn btn-primary" href="<?php echo U('Goods/addEditCategory',array('id'=>$vo['id']));?>"><i class="fa fa-pencil"></i></a>
                                                <a class="btn btn-danger" href="javascript:del_fun('<?php echo U('Goods/delGoodsCategory',array('id'=>$vo['id']));?>');"><i class="fa fa-trash-o"></i></a>
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
        </section>
    </div>
    <script type="text/javascript">

        // 展开收缩
        function  tree_open(obj)
        {
            var tree = $('#list-table tr[id^="2_"], #list-table tr[id^="3_"] '); //,'table-row'
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
        function rowClicked(obj)
        {
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
    </script>

</div>
</body>
</html>
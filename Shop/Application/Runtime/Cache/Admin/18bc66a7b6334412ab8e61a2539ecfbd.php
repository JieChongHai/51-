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







    
    <link href="/Public/plugins/daterangepicker/daterangepicker-bs3.css" rel="stylesheet" type="text/css" />
    <script src="/Public/plugins/daterangepicker/moment.min.js" type="text/javascript"></script>
    <script src="/Public/plugins/daterangepicker/daterangepicker.js" type="text/javascript"></script>
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
        <style>
            .search select { height: 34px}
        </style>
        <div class="wrapper wrapper-content">
            <div class="row">
                <div class="col-lg-12">
                    <div class="ibox float-e-margins">
                        <div class="ibox-title">
                            <h5>订单列表</h5>
                        </div>
                        <div class="ibox-content">
                            <div class="row">
                                <!--搜索开始-->
                                <form action="<?php echo U('Admin/order/export_order');?>" id="search-form2" class="navbar-form form-inline" method="post">
                                    <div class="form-group">
                                        <label class="control-label" for="input-order-id">收货人</label>
                                        <div class="input-group">
                                            <input type="text" name="consignee" placeholder="收货人" id="input-member-id" class="input-sm" style="width:100px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="input-order-id">订单编号</label>
                                        <div class="input-group">
                                            <input type="text" name="order_sn" placeholder="订单编号" id="input-order-id" class="input-sm" style="width:100px;">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label" for="input-date-added">下单日期</label>
                                        <div class="input-group">
                                            <input type="text" name="timegap" value="<?php echo ($timegap); ?>" placeholder="下单日期"  id="add_time" class="input-sm">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <select name="pay_status" class="input-sm" style="width:100px;">
                                            <option value="">支付状态</option>
                                            <option value="0">未支付</option>
                                            <option value="1">已支付</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="shipping_status" class="input-sm" style="width:100px;">
                                            <option value="">发货状态</option>
                                            <option value="0">未发货</option>
                                            <option value="1">已发货</option>
                                            <option value="2">部分发货</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <select name="order_status" class="input-sm" style="width:100px;">
                                            <option value="">订单状态</option>
                                            <?php if(is_array($order_status)): $k = 0; $__LIST__ = $order_status;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($k % 2 );++$k;?><option value="<?php echo ($k-1); ?>"><?php echo ($v); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
                                        </select>
                                        <input type="hidden" name="order_by" value="order_id">
                                        <input type="hidden" name="sort" value="desc">
                                        <input type="hidden" name="user_id" value="<?php echo ($_GET[user_id]); ?>">
                                    </div>
                                    <div class="form-group">
                                        <a href="javascript:void(0)" onclick="ajax_get_table('search-form2',1)" id="button-filter search-order" class="btn btn-primary"><i class="fa fa-search"></i> 筛选</a>
                                    </div>
                                </form>
                                <!--搜索结束-->
                            </div>
                            <!--表格开始    -->
                            <div id="ajax_return"> </div>
                            <!--表格结束-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function(){
            ajax_get_table('search-form2',1);   // 获取页面内容
            // 调用时间插件
            $('#add_time').daterangepicker({
                format:"YYYY/MM/DD",
                singleDatePicker: false,
                showDropdowns: true,
                minDate:'2016/01/01',
                maxDate:'2030/01/01',
                startDate:'2016/01/01',
                locale : {
                    applyLabel : '确定',
                    cancelLabel : '取消',
                    fromLabel : '起始时间',
                    toLabel : '结束时间',
                    customRangeLabel : '自定义',
                    daysOfWeek : [ '日', '一', '二', '三', '四', '五', '六' ],
                    monthNames : [ '一月', '二月', '三月', '四月', '五月', '六月','七月', '八月', '九月', '十月', '十一月', '十二月' ],
                    firstDay : 1
                }
            });
        });

        // ajax 抓取页面
        function ajax_get_table(tab,page){
            cur_page = page; //当前页面 保存为全局变量
            $.ajax({
                type : "POST",
                url:"/index.php/Admin/order/ajaxindex/p/"+page,//+tab,
                data : $('#'+tab).serialize(),// 你的formid
                success: function(data){
                    $("#ajax_return").html('');
                    $("#ajax_return").append(data);
                }
            });
        }

        // 点击排序
        function sort(field)
        {
            $("input[name='order_by']").val(field);
            var v = $("input[name='sort']").val() == 'desc' ? 'asc' : 'desc';
            $("input[name='sort']").val(v);
            ajax_get_table('search-form2',cur_page);
        }
    </script>

</div>
</body>
</html>
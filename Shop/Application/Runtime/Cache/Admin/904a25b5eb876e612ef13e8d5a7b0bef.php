<?php if (!defined('THINK_PATH')) exit();?><form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
    <div class="table-responsive">
        <table class="table  table-bordered table-hover">
            <thead>
            <tr>
                <th >
                    <input type="checkbox" id="checkAll" class="check-all regular-checkbox">
                    <label for="checkAll"></label>
                </th>
                <th>ID</th>
                <th style="width:200px">商品名称</th>
                <th>货号</th>
                <th>分类</th>
                <th>价格</th>
                <th>库存</th>
                <th>上架</th>
                <th>推荐</th>
                <th>新品</th>
                <th>热卖</th>
                <th>排序</th>
                <th>操作</th>
            </tr>
            </thead>
            <!-- 列表 -->
            <tbody>
            <?php if(is_array($goodsList)): $i = 0; $__LIST__ = $goodsList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                    <td>
                        <input class="ids regular-checkbox" type="checkbox" value="<?php echo ($list["goods_id"]); ?>" name="ids[]" id="check_<?php echo ($list["goods_id"]); ?>">
                        <label for="check_<?php echo ($list["goods_id"]); ?>"></label>
                    </td>
                    <td><?php echo ($list["goods_id"]); ?></td>
                    <td><?php echo (getSubstr($list["goods_name"],0,15)); ?></td>
                    <td><?php echo ($list["goods_sn"]); ?></td>
                    <td><?php echo ($catList[$list[cat_id]][name]); ?></td>
                    <td><?php echo ($list["shop_price"]); ?></td>
                    <td>
                        <input type="text" onkeyup="this.value=this.value.replace(/[^\d.]/g,'')" onpaste="this.value=this.value.replace(/[^\d.]/g,'')"  onchange="ajaxUpdateField(this);" name="store_count" size="4" data-table="goods" data-id="<?php echo ($list["goods_id"]); ?>" value="<?php echo ($list["store_count"]); ?>"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_on_sale] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_on_sale',this)"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_recommend] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_recommend',this)"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_new] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_new',this)"/>
                    </td>
                    <td class="text-center">
                        <img width="20" height="20" src="/Public/images/<?php if($list[is_hot] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods','goods_id','<?php echo ($list["goods_id"]); ?>','is_hot',this)"/>
                    </td>
                    <td class="text-center">
                        <input type="text" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')" onchange="updateSort('goods','goods_id','<?php echo ($list["goods_id"]); ?>','sort',this)" size="4" value="<?php echo ($list["sort"]); ?>" />
                    </td>
                    <td class="text-right">
                        <a  target="_blank" href="<?php echo U('Home/Goods/goodsInfo',array('id'=>$list['goods_id']));?>" class="btn btn-info" title="查看详情"><i class="fa fa-eye"></i></a>
                        <a href="<?php echo U('Admin/Goods/addEditGoods',array('id'=>$list['goods_id']));?>" class="btn btn-primary" title="编辑"><i class="fa fa-pencil"></i></a>
                        <a href="javascript:void(0);" onclick="del('<?php echo ($list[goods_id]); ?>')" class="btn btn-danger" title="删除">
                            <i class="fa fa-trash-o"></i>
                        </a>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>

        </table>
        <!--分页开始-->
        <div class="row">
            <div class="col-sm-3 text-left"></div>
            <div class="col-sm-9 text-right"><?php echo ($page); ?></div>
        </div>
        <!--分页结束-->
    </div>
</form>
<script type="text/javascript" src="/Public/js/delete.js"></script> <!-- 引入多选和全选删除js -->
<script>
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_table('search-form2',cur_page);
    });
</script>
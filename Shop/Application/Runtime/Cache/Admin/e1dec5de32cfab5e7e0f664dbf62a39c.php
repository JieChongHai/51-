<?php if (!defined('THINK_PATH')) exit();?><form method="post" enctype="multipart/form-data" target="_blank" id="form-goodsType">
    <div class="table-responsive">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <th >
                    <input type="checkbox" id="checkAll" class="check-all regular-checkbox">
                    <label for="checkAll"></label>
                </th>
                <th class="sorting text-left">ID</th>
                <th class="sorting text-left">属性名称</th>
                <th class="sorting text-left">商品类型</th>
                <th class="sorting text-left">属性值的输入方式</th>
                <th class="sorting text-left">可选值列表</th>
                <th class="sorting text-center">筛选</th>
                <th class="sorting text-left">排序</th>
                <th class="sorting text-right">操作</th> 
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($goodsAttributeList)): $i = 0; $__LIST__ = $goodsAttributeList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                    <td>
                        <input class="ids regular-checkbox" type="checkbox" value="<?php echo ($list["attr_id"]); ?>" name="ids[]" id="check_<?php echo ($list["attr_id"]); ?>">
                        <label for="check_<?php echo ($list["attr_id"]); ?>"></label>
                    </td>
                    <td class="text-right"><?php echo ($list["attr_id"]); ?></td>
                    <td class="text-left"><?php echo ($list["attr_name"]); ?></td>
                    <td class="text-left"><?php echo ($goodsTypeList[$list[type_id]]); ?></td>
                    <td class="text-left"><?php echo ($attr_input_type[$list[attr_input_type]]); ?></td>
                    <td class="text-left"><?php echo (mb_substr($list["attr_values"],0,30,'utf-8')); ?></td>
                    <td class="text-center">                        
                        <img width="20" height="20" src="/Public/images/<?php if($list[attr_index] == 1): ?>yes.png<?php else: ?>cancel.png<?php endif; ?>" onclick="changeTableVal('goods_attribute','attr_id','<?php echo ($list["attr_id"]); ?>','attr_index',this)"/>
                    </td>                    
                    <td class="text-left">
                        <input type="text" onchange="updateSort('goods_attribute','attr_id','<?php echo ($list["attr_id"]); ?>','order',this)" onkeyup="this.value=this.value.replace(/[^\d]/g,'')" onpaste="this.value=this.value.replace(/[^\d]/g,'')"  size="4" value="<?php echo ($list["order"]); ?>"/>
                    </td>
                    <td class="text-right">                       
                        <a href="<?php echo U('Admin/goods/addEditGoodsAttribute',array('attr_id'=>$list['attr_id']));?>" data-toggle="tooltip" title="" class="btn btn-primary" data-original-title="编辑"><i class="fa fa-pencil"></i></a>
                        <a href="javascript:del_fun('<?php echo U('delGoodsAttribute',array('id'=>$list['attr_id']));?>');" id="button-delete6" data-toggle="tooltip" title="" class="btn btn-danger" data-original-title="删除"><i class="fa fa-trash-o"></i></a></td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</form>
<div class="row">
    <div class="col-sm-6 text-left"></div>
    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
</div>
<script type="text/javascript" src="/Public/js/delete.js"></script> <!-- 引入多选和全选删除js -->
<script>
    // 点击分页触发的事件
    $(".pagination  a").click(function(){
        cur_page = $(this).data('p');
        ajax_get_table('search-form2',cur_page);
    });
 
</script>
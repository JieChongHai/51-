<?php if (!defined('THINK_PATH')) exit();?><form method="post" enctype="multipart/form-data" target="_blank" id="form-order">
    <div class="table-responsive">
        <table class="table table-bordered table-hover" style="font-size:12px;">
            <thead>
            <tr>
                <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);"></td>
                <td class="text-center">
                    <a href="javascript:sort('order_sn');">订单编号</a>
                </td>
                <td class="text-center">
                    <a href="javascript:sort('consignee');">收货人</a>
                </td>
                <td class="text-center">
                    <a href="">总金额</a>
                </td>
                <td class="text-center">
                    <a href="">应付金额</a>
                </td>
                <td class="text-center">
                    <a href="javascript:sort('order_status');">订单状态</a>
                </td>
                <td class="text-center">支付状态</td>
                <td class="text-center">发货状态</td>
                <td class="text-center">
                    <a href="javascript:sort('add_time');">下单时间</a>
                </td>
                <td class="text-center">操作</td>
            </tr>
            </thead>
            <tbody>
            <?php if(is_array($orderList)): $i = 0; $__LIST__ = $orderList;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
                    <td class="text-center">
                        <input type="checkbox" name="selected[]" value="6">
                        <input type="hidden" name="shipping_code[]" value="flat.flat">
                    </td>
                    <td class="text-center"><?php echo ($list["order_sn"]); ?></td>
                    <td class="text-center"><?php echo ($list["consignee"]); ?>:<?php echo ($list["mobile"]); ?></td>
                    <td class="text-center"><?php echo ($list["goods_price"]); ?></td>
                    <td class="text-center"><?php echo ($list["order_amount"]); ?></td>
                    <td class="text-center"><?php echo ($order_status[$list[order_status]]); ?></td>
                    <td class="text-center"><?php echo ($pay_status[$list[pay_status]]); ?></td>
                    <td class="text-center" id="shipping_status"><?php echo ($shipping_status[$list[shipping_status]]); ?></td>
                    <td class="text-center"><?php echo (date('Y-m-d H:i',$list["add_time"])); ?></td>
                    <td class="text-center">
                       <a href="<?php echo U('Admin/order/detail',array('order_id'=>$list['order_id']));?>" data-toggle="tooltip" title="查看详情" class="btn btn-info"><i class="fa fa-eye"></i></a>
                       <a href="javascript:;" data-toggle="tooltip" title="发货" class="btn btn-primary" value="<?php echo ($list['order_id']); ?>" onclick="sendGoods(<?php echo ($list['order_id']); ?>,<?php echo ($list[shipping_status]); ?>)"><i class="fa fa-car"></i></a>
                       <?php if(($list['order_status'] == 3) or ($list['order_status'] == 5)): ?><a href="<?php echo U('Admin/order/deleteOrder',array('order_id'=>$list['order_id']));?>"  data-toggle="tooltip" class="btn btn-danger" title="删除"><i class="fa fa-trash-o"></i></a>
                       <?php else: ?>
                            <a href="javascript:void(0)" onclick="layer.msg('该订单不得删除',{icon:2,time:1000})" data-toggle="tooltip" class="btn btn-default" title="删除"><i class="fa fa-trash-o"></i></a><?php endif; ?>
                    </td>
                </tr><?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>
    </div>
</form>
<div class="row">
    <div class="col-sm-6 text-left"></div>
    <div class="col-sm-6 text-right"><?php echo ($page); ?></div>
</div>
<script>
    $(".pagination  a").click(function(){
        var page = $(this).data('p');
        ajax_get_table('search-form2',page);
    });
    // 发货
    function sendGoods(order_id,shipping_status){
        if(shipping_status == 1){
            layer.msg('该订单已经发货了',{icon:2,time:1000});
            return false;
        }
        // 执行发货操作
        layer.confirm('是否确认发货？',function(){
            $.ajax({
                type:"POST",
                url:"<?php echo U('sendGoods');?>",
                data:{order_id:order_id},
                success:function(res){
                    if(res.status == 1){
                        layer.msg(res.message,{icon:1,time:1000},function(){
                           location.reload();
                        });
                    }else{
                        layer.msg(res.message,{icon:2,time:1000});
                    }
                }
            });
        });
    }
</script>
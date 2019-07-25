<?php
return array(
    //数据库配置
    'DB_TYPE' => 'mysql',       //数据库类型
    'DB_HOST' => '127.0.0.1',   //host地址
    'DB_USER' => 'root',        //数据库用户名
    'DB_PWD' =>  'root',        //数据库密码
    'DB_NAME' => 'shop',        //数据名


    'ORDER_STATUS' => array(
        0 => '待确认',
        1 => '已确认',
        2 => '已收货',
        3 => '已取消',
        4 => '已完成',//评价完
        5 => '已作废',
    ),
    'SHIPPING_STATUS' => array(
        0 => '未发货',
        1 => '已发货',
        2 => '部分发货'
    ),
    'PAY_STATUS' => array(
        0 => '未支付',
        1 => '已支付',
    ),

    // 订单用户端显示状态
    'WAITPAY'=>' AND pay_status = 0 AND order_status = 0 AND pay_code !="cod" ', //订单查询状态 待支付
    'WAITSEND'=>' AND (pay_status=1 OR pay_code="cod") AND shipping_status !=1 AND order_status in(0,1) ', //订单查询状态 待发货
    'WAITRECEIVE'=>' AND shipping_status=1 AND order_status = 1 ', //订单查询状态 待收货
    'WAITCCOMMENT'=> ' AND order_status=2 ', // 待评价 确认收货     //'FINISHED'=>'  AND order_status=1 ', //订单查询状态 已完成
    'FINISH'=> ' AND order_status = 4 ', // 已完成
    'CANCEL'=> ' AND order_status = 3 ', // 已取消
    'CANCELLED'=> 'AND order_status = 5 ',//已作废
    // 订单状态
    'ORDER_STATUS_DESC' => array(
        'WAITPAY' => '待支付',
        'WAITSEND'=>'待发货',
        'WAITRECEIVE'=>'待收货',
        'WAITCCOMMENT'=> '待评价',
        'CANCEL'=> '已取消',
        'FINISH'=> '已完成',
        'CANCELLED'=> '已作废'
    ),

);

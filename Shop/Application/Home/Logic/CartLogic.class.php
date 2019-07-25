<?php

namespace Home\Logic;
use Think\Model\RelationModel;

/**
 * 购物车 逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class CartLogic extends RelationModel
{

    
    /**
     * 加入购物车方法
     * @param type $goods_id  商品id
     * @param type $goods_num   商品数量
     * @param type $goods_spec  选择规格 
     * @param type $user_id 用户id
     */
    function addCart($goods_id,$goods_num,$goods_spec,$session_id,$user_id = 0)
    {       
        
        $goods = M('Goods')->where("goods_id = $goods_id")->find(); // 找出这个商品        
        $specGoodsPriceList = M('SpecGoodsPrice')->where("goods_id = $goods_id")->getField("key,key_name,price,store_count,sku"); // 获取商品对应的规格价钱 库存 条码

		$where = " session_id = '$session_id' ";
        $user_id = $user_id ? $user_id : 0;
		if($user_id){
		    $where .= "  or user_id= $user_id ";
            $catr_count = M('Cart')->where($where)->count(); // 查找购物车商品总数量
        }
        if($catr_count >= 20){
            return array('status'=>-9,'msg'=>'购物车最多只能放20种商品','result'=>'');
        }
        if(!empty($specGoodsPriceList) && empty($goods_spec)){ // 有商品规格 但是前台没有传递过来
            return array('status'=>-1,'msg'=>'必须传递商品规格','result'=>'');
        }
        if($goods_num <= 0){
            return array('status'=>-2,'msg'=>'购买商品数量不能为0','result'=>'');
        }
        if(empty($goods)){
            return array('status'=>-3,'msg'=>'购买商品不存在','result'=>'');
        }
        if(($goods['store_count'] < $goods_num)){
            return array('status'=>-4,'msg'=>'商品库存不足','result'=>'');
        }
        foreach($goods_spec as $key => $val) // 处理商品规格
            $spec_item[] = $val; // 所选择的规格项                            
        if(!empty($spec_item)) // 有选择商品规格
        {
            sort($spec_item);
            $spec_key = implode('_', $spec_item);
            if($specGoodsPriceList[$spec_key]['store_count'] < $goods_num) 
                return array('status'=>-4,'msg'=>'商品库存不足','result'=>'');
            $spec_price = $specGoodsPriceList[$spec_key]['price']; // 获取规格指定的价格
        }
                
        $where = " goods_id = $goods_id and spec_key = '$spec_key' "; // 查询购物车是否已经存在这商品
        if($user_id > 0){
            $where .= " and (session_id = '$session_id' or user_id = $user_id) ";
        }else{
            $where .= " and  session_id = '$session_id' ";
        }
        $catr_goods = M('Cart')->where($where)->find(); // 查找购物车是否已经存在该商品
        $price = $spec_price ? $spec_price : $goods['shop_price']; // 如果商品规格没有指定价格则用商品原始价格

        $data = array(                    
            'user_id'         => $user_id,   // 用户id
            'session_id'      => $session_id,   // sessionid
            'goods_id'        => $goods_id,   // 商品id
            'goods_sn'        => $goods['goods_sn'],   // 商品货号
            'goods_name'      => $goods['goods_name'],   // 商品名称
            'market_price'    => $goods['market_price'],   // 市场价
            'goods_price'     => $price,  // 购买价
            'member_goods_price' => $price,  // 会员折扣价 默认为 购买价
            'goods_num'       => $goods_num, // 购买数量
            'spec_key'        => "{$spec_key}", // 规格key
            'spec_key_name'   => "{$specGoodsPriceList[$spec_key]['key_name']}", // 规格 key_name
            'sku'        => "{$specGoodsPriceList[$spec_key]['sku']}", // 商品条形码
            'add_time'        => time(), // 加入购物车时间
        );                

       // 如果商品购物车已经存在 
       if($catr_goods){
           // 如果购物车的已有数量加上这次要购买的数量，大于库存数，则不再增加数量
            if(($catr_goods['goods_num'] + $goods_num) > $goods['store_count']){
                $goods_num = 0;
            }
            M('Cart')->where("id =".$catr_goods[id])->save(  array("goods_num"=> ($catr_goods['goods_num'] + $goods_num)) ); // 数量相加
            $cart_count = cart_goods_num($user_id,$session_id); // 查找购物车数量
            setcookie('cn',$cart_count,null,'/');
            return array('status'=>1,'msg'=>'成功加入购物车','result'=>$cart_count);
       }
       else{
             $insert_id = M('Cart')->add($data);
             $cart_count = cart_goods_num($user_id,$session_id); // 查找购物车数量
             setcookie('cn',$cart_count,null,'/');
             return array('status'=>1,'msg'=>'成功加入购物车','result'=>$cart_count);
       }     
            $cart_count = cart_goods_num($user_id,$session_id); // 查找购物车数量 
            return array('status'=>-5,'msg'=>'加入购物车失败','result'=>$cart_count);        
    }
    
    /**
     * 购物车列表 
     * @param type $user   用户
     * @param type $session_id  session_id
     * @param type $selected  是否被用户勾选中的 0 为全部 1为选中  一般没有查询不选中的商品情况
     * $mode 0  返回数组形式  1 直接返回result
     */
    function cartList($user = array() , $session_id = '', $selected = 0,$mode =0){
        $where = " 1 = 1 ";
        if($user[user_id]){ // 如果用户已经登录则按照用户id查询
                     $where .= " and user_id = $user[user_id] ";
        }else{
            $where .= " and session_id = '$session_id'";
            $user[user_id] = 0;
        }
                                
        $cartList = M('Cart')->where($where)->select();  // 获取购物车商品 
        $anum = $total_price =  $cut_fee = 0;

        foreach ($cartList as $k=>$val){
        	$cartList[$k]['goods_fee'] = $val['goods_num'] * $val['member_goods_price'];
        	$cartList[$k]['store_count']  = getGoodNum($val['goods_id'],$val['spec_key']); // 最多可购买的库存数量        	
            $anum += $val['goods_num'];
                
            // 如果要求只计算购物车选中商品的价格和数量，并且当前商品没选择，则跳过
            if($selected == 1 && $val['selected'] == 0)
                continue;

            $cut_fee += $val['goods_num'] * $val['market_price'] - $val['goods_num'] * $val['member_goods_price'];
        	$total_price += $val['goods_num'] * $val['member_goods_price'];
        }

        $total_price = array('total_fee' =>$total_price , 'cut_fee' => $cut_fee,'num'=> $anum,); // 总计
        setcookie('cn',$anum,null,'/');
        if($mode == 1) return array('cartList' => $cartList, 'total_price' => $total_price);
        return array('status'=>1,'msg'=>'','result'=>array('cartList' =>$cartList, 'total_price' => $total_price));
    }    
    

  
    /**
     * 获取用户可以使用的优惠券
     * @param type $user_id  用户id 
     * @param type $coupon_id 优惠券id
     * $mode 0  返回数组形式  1 直接返回result
     */
    public function getCouponMoney($user_id, $coupon_id,$mode)
    {
        $couponlist = M('CouponList')->where("uid = $user_id and id = $coupon_id")->find(); // 获取用户的优惠券
        if(empty($couponlist)) {
            if($mode == 1) return 0;    
            return array('status'=>1,'msg'=>'','result'=>0);
        }            
        
        $coupon = M('Coupon')->where("id = {$couponlist['cid']}")->find(); // 获取 优惠券类型表
        $coupon['money'] = $coupon['money'] ? $coupon['money'] : 0;
       
        if($mode == 1) return $coupon['money'];
        return array('status'=>1,'msg'=>'','result'=>$coupon['money']);        
    }
    
    /**
     * 根据优惠券代码获取优惠券金额
     * @param type $couponCode 优惠券代码
     * @param type $order_momey Description 订单金额
     * return -1 优惠券不存在 -2 优惠券已过期 -3 订单金额没达到使用券条件
     */
    public function getCouponMoneyByCode($couponCode,$order_momey)
    {
        $couponlist = M('CouponList')->where("code = '$couponCode'")->find(); // 获取用户的优惠券
        if(empty($couponlist)) 
            return array('status'=>-9,'msg'=>'优惠券码不存在','result'=>'');
        $coupon = M('Coupon')->where("id = {$couponlist['cid']}")->find(); // 获取优惠券类型表
        if(time() > $coupon['use_end_time'])  
            return array('status'=>-10,'msg'=>'优惠券已经过期','result'=>'');
        if($order_momey < $coupon['condition'])
            return array('status'=>-11,'msg'=>'金额没达到优惠券使用条件','result'=>'');
        if($couponlist['order_id'] > 0)
            return array('status'=>-12,'msg'=>'优惠券已被使用','result'=>'');
        
        return array('status'=>1,'msg'=>'','result'=>$coupon['money']);
    }
    
    /**
     *  添加一个订单
     * @param type $user_id  用户id     
     * @param type $address_id 地址id
     * @param type $car_price 应付价格和商品价格数组
     * @return type $order_id 返回新增的订单id
     */
    public function addOrder($user_id,$address_id,$car_price)
    {
        
        // 防止灌水，1天最多只能下50单
        $order_count = M('Order')->where("user_id= $user_id and order_sn like '".date('Ymd')."%'")->count(); // 查找购物车商品总数量
        if($order_count >= 50) 
            return array('status'=>-9,'msg'=>'一天最多只能下50个订单','result'=>'');
        
         // 1.插入订单 order表
        $address  = M('UserAddress')->where("address_id = $address_id")->find(); // 查找用户地址信息
        $data = array(
            'order_sn'     => date('YmdHis').rand(1000,9999), // 订单编号
            'user_id'      => $user_id, // 用户id
            'consignee'    => $address['consignee'], // 收货人
            'province'     => $address['province'],//'省份id',
            'city'         => $address['city'],//'城市id',
            'district'     => $address['district'],//'县',
            'twon'         => $address['twon'],// '街道',
            'address'      => $address['address'],//'详细地址',
            'mobile'       => $address['mobile'],//'手机',
            'zipcode'      => $address['zipcode'],//'邮编',
            'email'        => $address['email'],//'邮箱',
            'goods_price'  => $car_price['goodsFee'],//'商品价格',
            'total_amount' => $car_price['goodsFee'],// 订单总额
            'order_amount' => $car_price['goodsFee'],//'应付款金额',
            'add_time'     => time(), // 下单时间
        );
        
        $order_id = M("Order")->data($data)->add(); // 订单信息写入Oder表
        if(!$order_id){
            return array('status'=>-8,'msg'=>'添加订单失败','result'=>NULL);
        }

        logOrder($order_id,'您提交了订单，请等待系统确认','提交订单',$user_id);// 记录订单操作日志
        $order = M('Order')->where("order_id = $order_id")->find(); // 查找订单信息
            
        // 2.插入order_goods 表
        $cartList = M('Cart')->where("user_id = $user_id and selected = 1")->select();
        foreach($cartList as $key => $val)
        {
           $goods = M('goods')->where("goods_id = {$val['goods_id']} ")->find();
           $data2['order_id']           = $order_id; // 订单id
           $data2['goods_id']           = $val['goods_id']; // 商品id
           $data2['goods_name']         = $val['goods_name']; // 商品名称
           $data2['goods_sn']           = $val['goods_sn']; // 商品货号
           $data2['goods_num']          = $val['goods_num']; // 购买数量
           $data2['market_price']       = $val['market_price']; // 市场价
           $data2['goods_price']        = $val['goods_price']; // 商品价
           $data2['spec_key']           = $val['spec_key']; // 商品规格
           $data2['spec_key_name']      = $val['spec_key_name']; // 商品规格名称
           $data2['sku']           		= $val['sku']; // 商品sku
           $data2['member_goods_price'] = $val['member_goods_price']; // 会员折扣价
           $data2['cost_price']         = $goods['cost_price']; // 成本价
           $data2['give_integral']      = $goods['give_integral']; // 购买商品赠送积分         
           $data2['prom_type']          = $val['prom_type']; // 0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠
           $data2['prom_id']            = $val['prom_id']; // 活动id
           $order_goods_id              = M("OrderGoods")->data($data2)->add();
        }
        // 3.购物车中，删除已提交订单商品
        M('Cart')->where("user_id = $user_id and selected = 1")->delete();
        return array('status'=>1,'msg'=>'提交订单成功','result'=>$order_id); // 返回新增的订单id        
    }
    
    /**
     * 查看购物车的商品数量
     * @param type $user_id
     * $mode 0  返回数组形式  1 直接返回result
     */
    public function cart_count($user_id,$mode = 0){
        $count = M('Cart')->where("user_id = $user_id and selected = 1")->count();
        if($mode == 1) return  $count;
        
        return array('status'=>1,'msg'=>'','result'=>$count);         
    }
        
   /**
    * 获取商品团购价
    * 如果商品没有团购活动 则返回 0
    * @param type $attr_id
    * $mode 0  返回数组形式  1 直接返回result
    */
   public function get_group_buy_price($goods_id,$mode=0)
   {
       $group_buy = M('GroupBuy')->where("goods_id = $goods_id and ".time()." >= start_time and ".time()." <= end_time ")->find(); // 找出这个商品                      
       if(empty($group_buy))       
            return 0;
       
        if($mode == 1) return $group_buy['groupbuy_price'];
        return array('status'=>1,'msg'=>'','result'=>$group_buy['groupbuy_price']);       
   }  
   
   /**
    * 用户登录后 需要对购物车 一些操作
    * @param type $session_id
    * @param type $user_id
    */
   public function login_cart_handle($session_id,$user_id)
   {
	   if(empty($session_id) || empty($user_id))
	     return false;
        // 登录后将购物车的商品的 user_id 改为当前登录的id            
        M('cart')->where("session_id = '$session_id'")->save(array('user_id'=>$user_id));                    
        
        $Model = new \Think\Model();
        // 查找购物车两件完全相同的商品
        $cart_id_arr = $Model->query("select id from `__PREFIX__cart` where user_id = $user_id group by  goods_id,spec_key having count(goods_id) > 1");
        if(!empty($cart_id_arr))
        {
            $cart_id_arr = get_arr_column($cart_id_arr, 'id');
            $cart_id_str = implode(',', $cart_id_arr);
            M('cart')->delete($cart_id_str); // 删除购物车完全相同的商品
        }
   }
}
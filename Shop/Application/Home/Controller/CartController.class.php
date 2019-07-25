<?php

namespace Home\Controller;

class CartController extends BaseController {
    
    public $cartLogic; // 购物车逻辑操作类
    public $user_id = 0;
    public $user = array();    
    /**
     * 初始化函数
     */
    public function _initialize() {       
        parent::_initialize();
        $this->cartLogic = new \Home\Logic\CartLogic();
        
        if(session('?user'))
        {
        	$user = session('user');
                $user = M('users')->where("user_id = {$user['user_id']}")->find();
                session('user',$user);  //覆盖session 中的 user
        	$this->user = $user;
        	$this->user_id = $user['user_id'];
        	$this->assign('user',$user); //存储用户信息
        }                        
    }

    public function cart(){           
        $this->display();
    }
    
    public function index(){
    	$this->display('cart');
    }

    /**
     * ajax 将商品加入购物车
     */
    function ajaxAddCart()
    {
        $goods_id = I("goods_id"); // 商品id
        $goods_num = I("goods_num");// 商品数量
        $goods_spec = I("goods_spec"); // 商品规格            
        $result = $this->cartLogic->addCart($goods_id, $goods_num, $goods_spec,$this->session_id,$this->user_id); // 将商品加入购物车                     
        exit(json_encode($result));       
    }
    
    /**
     * ajax 删除购物车的商品
     */
    public function ajaxDelCart()
    {       
        $ids = I("ids"); // 商品 ids        
        $result = M("Cart")->where(" id in ($ids)")->delete(); // 删除id为5的用户数据
        $return_arr = array('status'=>1,'msg'=>'删除成功','result'=>''); // 返回结果状态
        $this->ajaxReturn($return_arr);
    }
    
    
    /*
     * ajax 请求获取购物车列表
     */
    public function ajaxCartList(){
        $post_goods_num   = I("goods_num");     // 获取goods_num 购物车商品数量
        $post_cart_select = I("cart_select");   // 获取购物车选中状态
        $where = " session_id = '$this->session_id' "; // 默认按照 session_id 查询
        $this->user_id && $where = " user_id = ".$this->user_id; // 如果这个用户已经登录，则按照用户id查询
        $cartList = M('Cart')->where($where)->getField("id,goods_num,selected,prom_type,prom_id");
        if($post_goods_num){
            // 修改购物车数量 和勾选状态
            foreach($post_goods_num as $key => $val)
            {   
                $data['goods_num'] = $val < 1 ? 1 : $val;
                $data['selected'] = $post_cart_select[$key] ? 1 : 0 ;                               
                if(($cartList[$key]['goods_num'] != $data['goods_num']) || ($cartList[$key]['selected'] != $data['selected'])) 
                    M('Cart')->where("id = $key")->save($data);
            }
            $this->assign('select_all', $_POST['select_all']); // 全选框
        }
                
        $result = $this->cartLogic->cartList($this->user, $this->session_id,1,1); // 筛选选中的商品
        // 如果总价为0，则令其余变量也为0
        if(empty($result['total_price'])){
            $result['total_price'] = Array( 'total_fee' =>0, 'cut_fee' =>0, 'num' => 0);
        }
        $this->assign('cartList', $result['cartList']);         // 购物车的商品
        $this->assign('total_price', $result['total_price']);   // 总计
        $this->display('ajax_cart_list');
    }
    /**
     * 购物车第二步确定页面
     */
    public function step2()
    {        
        
        if($this->user_id == 0)
            $this->error('请先登录',U('Home/User/login'));
        
        if($this->cartLogic->cart_count($this->user_id,1) == 0 ) 
            $this->error ('你的购物车没有选中商品','Cart/cart');
        
        $result = $this->cartLogic->cartList($this->user, $this->session_id,1,1); // 获取购物车商品
        $this->assign('cartList', $result['cartList']); // 购物车的商品                
        $this->assign('total_price', $result['total_price']); // 总计
        $this->display();
    }
   
    /*
     * ajax 获取用户收货地址 用于购物车确认订单页面
     */
    public function ajaxAddress(){
        $model = M('UserAddress');
        $address_list = $model->where('user_id = '.$this->user_id.' AND is_pickup = 0')->select();
        if($address_list){
        	$area_id = array();
        	foreach ($address_list as $val){
        		$area_id[] = $val['province'];
                        $area_id[] = $val['city'];
                        $area_id[] = $val['district'];
                        $area_id[] = $val['twon'];                        
        	}    
                $area_id = array_filter($area_id);
        	$area_id = implode(',', $area_id);
        	$regionList = M('region')->where("id in ($area_id)")->getField('id,name');
        	$this->assign('regionList', $regionList);
        }
        $address_where['is_default'] = 1;
        $c = $model->where('user_id = '.$this->user_id.' AND is_default=1 AND is_pickup = 0')->count(); // 看看有没默认收货地址
        if((count($address_list) > 0) && ($c == 0)) // 如果没有设置默认收货地址, 则第一条设置为默认收货地址
            $address_list[0]['is_default'] = 1;
                     
        $this->assign('address_list', $address_list);
        $this->display('ajax_address');
    }


    /**
     * ajax 获取订单商品价格 或者提交 订单
     */
    public function step3(){
        if($this->user_id == 0){
            exit(json_encode(array('status'=>-100,'msg'=>"登录超时请重新登录!",'result'=>null))); // 返回结果状态
        }
        $address_id = I("address_id"); //收货地址id
        /** 判断购物车中是否有商品 **/
        if($this->cartLogic->cart_count($this->user_id,1) == 0 ) {
            $this->ajaxReturn(array('status'=>-2,'msg'=>'你的购物车没有选中商品','result'=>null)); // 返回结果状态
        }
        /** 判断是否有收获地址信息 **/
        if(!$address_id){
            $this->ajaxReturn(array('status'=>-3,'msg'=>'请先填写收货人信息','result'=>null)); // 返回结果状态
        }
		$address = M('UserAddress')->where("address_id = $address_id")->find(); // 查找收获地址
		$order_goods = M('cart')->where("user_id = {$this->user_id} and selected = 1")->select(); //查找购物车
        $result = calculate_price($this->user_id,$order_goods); //计算商品价钱，并添加到原数组
		if($result['status'] < 0){
            $this->ajaxReturn($result); // 返回结果状态
        }
        $car_price = array(
            'payables'     => $result['result']['order_amount'], // 应付金额
            'goodsFee'     => $result['result']['goods_price'],// 商品价格
        );

        // 提交订单        
        if($_REQUEST['act'] == 'submit_order')
        {
            $result = $this->cartLogic->addOrder($this->user_id,$address_id,$car_price); // 添加订单
            exit(json_encode($result));            
        }
        $return_arr = array('status'=>1,'msg'=>'计算成功','result'=>$car_price); // 返回结果状态
        exit(json_encode($return_arr));
    }

    /*
     * 订单支付页面
     */
    public function step4(){
        $order_id = I('order_id');        
        $order = M('Order')->where("order_id = $order_id")->find();
        // 如果已经支付过的订单直接到订单详情页面. 不再进入支付页面
        if($order['pay_status'] == 1){
            $order_detail_url = U("Home/User/order_detail",array('id'=>$order_id));
            header("Location: $order_detail_url");
        }
        $this->assign('order',$order);
        $this->display();                   
    }

    /**
     * 支付完成
     */
    public function getPay(){
        $order_sn = I('order_sn');
        if(update_pay_status($order_sn)){
            $this->ajaxReturn('1'); // 支付成功
        }else{
            $this->ajaxReturn('0'); // 支付失败
        }
    }
 
    
    //ajax 请求购物车列表
    public function header_cart_list()
    {
    	$cart_result = $this->cartLogic->cartList($this->user, $this->session_id,0,1);
    	if(empty($cart_result['total_price']))
    		$cart_result['total_price'] = Array( 'total_fee' =>0,  'num' => 0);
    	
    	$this->assign('cartList', $cart_result['cartList']); // 购物车的商品
    	$this->assign('cart_total_price', $cart_result['total_price']); // 总计
        $template = I('template','header_cart_list');    	 
        $this->display($template);		 
    }
}

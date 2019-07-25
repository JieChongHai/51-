<?php

namespace Home\Logic;

use Think\Model\RelationModel;
use Think\Page;

/**
 * 分类逻辑定义
 * Class CatsLogic
 * @package Home\Logic
 */
class UsersLogic extends RelationModel
{
    /*
     * 登录
     */
    public function login($username,$password){
    	$result = array();
        if(!$username || !$password){
            $result= array('status'=>0,'msg'=>'请填写账号或密码');
        }
        $user = M('users')->where("mobile='{$username}' OR email='{$username}'")->find(); // 查找用户记录
        if(!$user){
           $result = array('status'=>-1,'msg'=>'账号不存在!');
        }elseif(md5($password) != $user['password']){
           $result = array('status'=>-2,'msg'=>'密码错误!');
        }elseif($user['is_lock'] == 1){
           $result = array('status'=>-3,'msg'=>'账号异常已被锁定！！！');
        }else{
            //查询用户信息之后, 查询用户的登记昵称
            $levelId   = $user['level'];
            $levelName = M("user_level")->where("level_id = {$levelId}")->getField("level_name"); 
            $user['level_name'] = $levelName;
            $result = array('status'=>1,'msg'=>'登录成功','result'=>$user);
        }
        return $result;
    }


    /**
     * 注册
     * @param $username  邮箱或手机
     * @param $password  密码
     * @param $password2 确认密码
     * @return array
     */
    public function register($email,$password,$mobile){
        if(check_email($email)){
            $map['email'] = $email;
        }else{
            return array('status'=>-1,'msg'=>'邮箱格式错误');
        }

        if(check_mobile($mobile)){
            $map['nickname'] = $map['mobile'] = $mobile;
        }else{
            return array('status'=>-1,'msg'=>'手机号格式错误');
        }

        if(!$email || !$password){
            return array('status'=>-1,'msg'=>'请输入用户名或密码');
        }
        //验证是否存在用户名
        if(get_user_info($email,1)||get_user_info($mobile,2)){
            return array('status'=>-1,'msg'=>'账号已存在');
        }
        $map['password'] = md5($password);
        $map['reg_time'] = time();

        $map['token'] = md5(time().mt_rand(1,99999));
        $user_id = M('users')->add($map);
        if(!$user_id){
            return array('status'=>-1,'msg'=>'注册失败');
        }
        $user = M('users')->where("user_id = {$user_id}")->find();
        return array('status'=>1,'msg'=>'注册成功','result'=>$user);
    }

     /*
      * 获取当前登录用户信息
      */
     public function get_info($user_id){
         if(!$user_id > 0)
             return array('status'=>-1,'msg'=>'缺少参数','result'=>'');
         $user_info = M('users')->where("user_id = {$user_id}")->find();
         if(!$user_info)
            return false;

         $user_info['collect_count'] = M('goods_collect')->where(array('user_id'=>$user_id))->count(); //获取收藏数量         
                                    
         $user_info['waitPay']     = M('order')->where("user_id = $user_id ".C('WAITPAY'))->count(); //待付款数量
         $user_info['waitSend']    = M('order')->where("user_id = $user_id ".C('WAITSEND'))->count(); //待发货数量         
         $user_info['waitReceive'] = M('order')->where("user_id = $user_id ".C('WAITRECEIVE'))->count(); //待收货数量                  
         $user_info['order_count'] = $user_info['waitPay'] + $user_info['waitSend'] + $user_info['waitReceive'];
         return array('status'=>1,'msg'=>'获取成功','result'=>$user_info);
     }
     
    /*
     * 获取最近一笔订单
     */
    public function get_last_order($user_id){
        $last_order = M('order')->where("user_id = {$user_id}")->order('order_id DESC')->find();
        return $last_order;
    }


    /*
     * 获取订单商品
     */
    public function get_order_goods($order_id){
        $sql = "SELECT og.*,g.original_img FROM order_goods og LEFT JOIN goods g ON g.goods_id = og.goods_id WHERE order_id = ".$order_id;
        $goods_list = $this->query($sql);

        $return['status'] = 1;
        $return['msg'] = '';
        $return['result'] = $goods_list;
        return $return;
    }




    /**
     * 获取商品收藏列表
     * @param $user_id  用户id
     */
    public function get_goods_collect($user_id){
        $count = M('goods_collect')->where(array('user_id'=>$user_id))->count();
        $page = new Page($count,8);
        $show = $page->show();
        //获取我的收藏列表
        $sql = "SELECT c.collect_id,c.add_time,g.goods_id,g.goods_name,g.shop_price,g.original_img FROM goods_collect c ".
            "inner JOIN goods g ON g.goods_id = c.goods_id ".
            "WHERE c.user_id = ".$user_id.
            " ORDER BY c.add_time DESC LIMIT {$page->firstRow},{$page->listRows}";
        $result = M()->query($sql);
        $return['status'] = 3;
        $return['msg'] = '获取成功';
        $return['result'] = $result;
        $return['show'] = $show;        
        return $return;
    }

    /**
     * 获取评论列表
     * @param $user_id 用户id
     * @param $status  状态 0 未评论 1 已评论
     * @return mixed
     */
    public function get_comment($user_id,$status=2){
        if($status == 1){
            //已评论
            $count2 = M()->query("select count(*) as count from comment  as c inner join order_goods as g on c.goods_id = g.goods_id and c.order_id = g.order_id where c.user_id = $user_id");
            $count2 = $count2[0]['count'];
            
            $page = new Page($count2,10);
            $sql = "select c.*,g.*,(select order_sn from  `order` where order_id = c.order_id ) as order_sn  from  comment as c inner join order_goods as g on c.goods_id = g.goods_id and c.order_id = g.order_id where c.user_id = $user_id order by c.add_time desc LIMIT {$page->firstRow},{$page->listRows}";
        }else{        	
        	$countsql = " select count(1) as comment_count from order_goods as og
        	left join `order` as o on o.order_id = og.order_id where o.user_id = $user_id  and o.shipping_status = 1 ";
        	$where = '';
        	if($status == 0){
        		$countsql .= $where = " and og.is_comment = 0 ";
        	}
        	$comment = M()->query($countsql);
        	$count1 = $comment[0]['comment_count']; // 待评价
            $page = new Page($count1,3);
            $sql =" select *  from order_goods as og
            left join  `order` as o on o.order_id = og.order_id  where o.user_id = $user_id and o.shipping_status = 1
            $where order by o.order_id desc  LIMIT {$page->firstRow},{$page->listRows}";            
        }
        
        $show = $page->show();
        $comment_list = M()->query($sql);
        if($comment_list){
        	$return['result'] = $comment_list;
        	$return['show'] = $show; //分页
        	return $return;
        }else{
        	return array();
        }
    }
    
    /**
     * 添加评论
     * @param $order_id  订单id
     * @param $goods_id  商品id
     * @param $user_email用户邮箱地址
     * @param $username  用户名
     * @return bool
     */
    public function add_comment($add){
        if(!$add['order_id'] || !$add['goods_id'])
            return array('status'=>-1,'msg'=>'非法操作','result'=>'');
        
        //检查订单是否已完成
        $order = M('order')->where("order_id = {$add['order_id']} AND user_id = {$add['user_id']}")->find();
        if($order['order_status'] != 2)
//            return array('status'=>-1,'msg'=>'该笔订单还未确认收货','result'=>'');
            return array('status'=>-1,'msg'=>$order['order_status'],'result'=>'');

        //检查是否已评论过
        $goods = M('comment')->where("order_id = {$add['order_id']} AND goods_id = {$add['goods_id']}")->find();
        if($goods)            
            return array('status'=>-1,'msg'=>'您已经评论过该商品','result'=>'');        
        
        $row = M('comment')->add($add);
        if($row)
        {
            //更新订单商品表状态
            M('order_goods')->where(array('goods_id'=>$add[goods_id],'order_id'=>$add[order_id]))->save(array('is_comment'=>1));
            M('goods')->where(array('goods_id'=>$add[goods_id]))->setInc('comment_count',1); // 评论数加一  
            // 查看这个订单是否全部已经评论,如果全部评论了 修改整个订单评论状态            
            $comment_count   = M('order_goods')->where("order_id ='{$add[order_id]}' and is_comment = 0")->count();
            if($comment_count == 0) // 如果所有的商品都已经评价了 订单状态改成已评价
            {
                M('order')->where("order_id ='{$add[order_id]}'")->save(array('order_status'=>4));
            }
            return array('status'=>1,'msg'=>'评论成功','result'=>'');
        }
        return array('status'=>-1,'msg'=>'评论失败','result'=>'');
    }

    /**
     * 邮箱或手机绑定
     * @param $email_mobile  邮箱或者手机
     * @param int $type  1 为更新邮箱模式  2 手机
     * @param int $user_id  用户id
     * @return bool
     */
    public function update_email_mobile($email_mobile,$user_id,$type=1){
        //检查是否存在邮件
        if($type == 1)
            $field = 'email';
        if($type == 2)
            $field = 'mobile';
        $condition['user_id'] = array('neq',$user_id);
        $condition[$field] = $email_mobile;

        $is_exist = M('users')->where($condition)->find();
        if($is_exist)
            return false;
        unset($condition[$field]);
        $condition['user_id'] = $user_id;
        $validate = $field.'_validated';
        M('users')->where($condition)->save(array($field=>$email_mobile,$validate=>1));
        return true;
    }

    /**
     * 更新用户信息
     * @param $user_id
     * @param $post  要更新的信息
     * @return bool
     */
    public function update_info($user_id,$post=array()){
        $model = M('users')->where("user_id = ".$user_id);
        $row = $model->setField($post);
        if($row === false)
           return false;
        return true;
    }

    /**
     * 地址添加/编辑
     * @param $user_id 用户id
     * @param $user_id 地址id(编辑时需传入)
     * @return array
     */
    public function add_address($user_id,$address_id=0,$data){
        $post = $data;
        if($address_id == 0)
        {
            $c = M('UserAddress')->where("user_id = $user_id")->count();
            if($c >= 20)
                return array('status'=>-1,'msg'=>'最多只能添加20个收货地址','result'=>'');
        }        
        
        //检查手机格式
        if($post['consignee'] == '')
            return array('status'=>-1,'msg'=>'收货人不能为空','result'=>'');
        if(!$post['province'] || !$post['city'] || !$post['district'])
            return array('status'=>-1,'msg'=>'所在地区不能为空','result'=>'');
        if(!$post['address'])
            return array('status'=>-1,'msg'=>'地址不能为空','result'=>'');
        if(!check_mobile($post['mobile']))
            return array('status'=>-1,'msg'=>'手机号码格式有误','result'=>'');

        //编辑模式
        if($address_id > 0){

            $address = M('user_address')->where(array('address_id'=>$address_id,'user_id'=> $user_id))->find();
            if($post['is_default'] == 1 && $address['is_default'] != 1)
                M('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0));
            $row = M('user_address')->where(array('address_id'=>$address_id,'user_id'=> $user_id))->save($post);
            if(!$row)
                return array('status'=>-1,'msg'=>'操作完成','result'=>'');
            return array('status'=>1,'msg'=>'编辑成功','result'=>'');
        }
        //添加模式
        $post['user_id'] = $user_id;
        
        // 如果目前只有一个收货地址则改为默认收货地址
        $c = M('user_address')->where("user_id = {$post['user_id']}")->count();        
        if($c == 0)  $post['is_default'] = 1;
        
        $address_id = M('user_address')->add($post);
        //如果设为默认地址
        $insert_id = M()->getLastInsID();
        $map['user_id'] = $user_id;
        $map['address_id'] = array('neq',$insert_id);
               
        if($post['is_default'] == 1)
            M('user_address')->where($map)->save(array('is_default'=>0));
        if(!$address_id)
            return array('status'=>-1,'msg'=>'添加失败','result'=>'');
        
        
        return array('status'=>1,'msg'=>'添加成功','result'=>$address_id);
    }



    /**
     * 设置默认收货地址
     * @param $user_id
     * @param $address_id
     */
    public function set_default($user_id,$address_id){
        M('user_address')->where(array('user_id'=>$user_id))->save(array('is_default'=>0)); //改变以前的默认地址地址状态
        $row = M('user_address')->where(array('user_id'=>$user_id,'address_id'=>$address_id))->save(array('is_default'=>1));
        if(!$row)
            return false;
        return true;
    }

    /**
     * 修改密码
     * @param $user_id  用户id
     * @param $old_password  旧密码
     * @param $new_password  新密码
     * @param $confirm_password 确认新 密码
     */
    public function password($user_id,$old_password,$new_password,$confirm_password,$is_update=true){
        $data = $this->get_info($user_id);
        $user = $data['result'];
        if(strlen($new_password) < 6)
            return array('status'=>-1,'msg'=>'密码不能低于6位字符','result'=>'');
        if($new_password != $confirm_password)
            return array('status'=>-1,'msg'=>'两次密码输入不一致','result'=>'');
        //验证原密码
        if($is_update && ($user['password'] != '' && encrypt($old_password) != $user['password']))
            return array('status'=>-1,'msg'=>'密码验证失败','result'=>'');
        $row = M('users')->where("user_id='{$user_id}'")->save(array('password'=>encrypt($new_password)));
        if(!$row)
            return array('status'=>-1,'msg'=>'修改失败','result'=>'');
        return array('status'=>1,'msg'=>'修改成功','result'=>'');
    }

    /**
     * 取消订单
     */
    public function cancel_order($user_id,$order_id){
        $order = M('order')->where(array('order_id'=>$order_id,'user_id'=>$user_id))->find();
        //检查是否未支付订单 已支付联系客服处理退款
        if(empty($order))
            return array('status'=>-1,'msg'=>'订单不存在','result'=>'');
        //检查是否未支付的订单
        if($order['pay_status'] > 0 || $order['order_status'] > 0)
            return array('status'=>-1,'msg'=>'支付状态或订单状态不允许','result'=>'');
        //获取记录表信息
        //$log = M('account_log')->where(array('order_id'=>$order_id))->find();
        //有余额支付的情况
        if($order['user_money'] > 0 || $order['integral'] > 0){
            accountLog($user_id,$order['user_money'],$order['integral'],"订单取消，退回{$order['user_money']}元,{$order['integral']}积分");
        }

        $row = M('order')->where(array('order_id'=>$order_id,'user_id'=>$user_id))->save(array('order_status'=>3));
				
        $data['order_id'] = $order_id;
        $data['action_user'] = $user_id;
        $data['action_note'] = '您取消了订单';
        $data['order_status'] = 3;
        $data['pay_status'] = $order['pay_status'];
        $data['shipping_status'] = $order['shipping_status'];
        $data['log_time'] = time();
        $data['status_desc'] = '用户取消订单';        
        M('order_action')->add($data);//订单操作记录		
		
        if(!$row)
            return array('status'=>-1,'msg'=>'操作失败','result'=>'');
        return array('status'=>1,'msg'=>'操作成功','result'=>'');

    }

}
<?php

namespace Home\Controller;
use Home\Logic\UsersLogic;
use Think\Page;
use Think\Verify;

class UserController extends BaseController {

	public $user_id = 0;
	public $user = array();
	
    public function _initialize() {      
        parent::_initialize();
        if(session('?user'))
        {
        	$user = session('user');
            $user = M('users')->where("user_id = {$user['user_id']}")->find();
            session('user',$user);  //覆盖session 中的 user               
        	$this->user = $user;
        	$this->user_id = $user['user_id'];
        	$this->assign('user',$user); //存储用户信息
        	$this->assign('user_id',$this->user_id);
        }else{
            // 不需要登录的数组
        	$nologin = array(
        			'login','register','pop_login','do_login','logout','verify','set_pwd','check_username',
        	);
        	if(!in_array(ACTION_NAME,$nologin)){
        		header("location:".U('Home/User/login'));
        		exit;
        	}
        }
        //用户中心面包屑导航
        $navigate_user = navigate_user();
        $this->assign('navigate_user',$navigate_user);        
    }

    /*
     * 用户中心首页
     */
    public function index(){
        $logic = new UsersLogic();
        $user = $logic->get_info($this->user_id);
        $user = $user['result'];
        $level = M('user_level')->select();
        $level = convert_arr_key($level,'level_id');
        $this->assign('level',$level);
        $this->assign('user',$user);
        $this->display();
    }

    /**
     * 退出
     */
    public function logout(){
    	setcookie('uname','',time()-3600,'/');
    	setcookie('cn','',time()-3600,'/');
    	setcookie('user_id','',time()-3600,'/');
        session_unset();
        session_destroy();
        //$this->success("退出成功",U('Home/Index/index'));
        header("location:".U('Home/Index/index'));
        exit;
    }

    /**
     *  登录
     */
    public function login(){
        // 判断用户是否已经登录，如果已经登录，跳转到用户中心页面
        if($this->user_id > 0){
        	header("Location: ".U('Home/User/index'));
        }           
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index"); // 上次路径
        $this->assign('referurl',$referurl);    // 模板赋值
        $this->display();
    }

    /**
     * 上次登录页面
     */
    public function pop_login(){
    	if($this->user_id > 0){
    		header("Location: ".U('Home/User/Index'));
    	}
        $referurl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : U("Home/User/index");
        $this->assign('referurl',$referurl);
    	$this->display();
    }

    /**
     * 登录验证
     */
    public function do_login(){
    	$username = trim(I('post.username')); // 获取用户名并去除首尾空格
        $password = trim(I('post.password')); // 获取密码并去除首尾空格
    	         
    	$logic = new UsersLogic();
    	$res = $logic->login($username,$password);        
        
    	if($res['status'] == 1){
    		$res['url'] =  urldecode(I('post.referurl'));
    		session('user',$res['result']);
    		setcookie('user_id',$res['result']['user_id'],null,'/');
    		setcookie('is_distribut',$res['result']['is_distribut'],null,'/');
    		$nickname = empty($res['result']['nickname']) ? $username : $res['result']['nickname'];
            setcookie('uname',urlencode($nickname),null,'/');
            setcookie('cn',0,time()-3600,'/');
    		$cartLogic = new \Home\Logic\CartLogic();
    		$cartLogic->login_cart_handle($this->session_id,$res['result']['user_id']);  //用户登录后 需要对购物车 进行一些操作
    	}
    	exit(json_encode($res));
    }
    /**
     *  注册
     */
    public function register(){
    	if($this->user_id > 0) header("Location: ".U('Home/Index/index')); //如果已经登录，直接跳转到首页
        /** 表单提交操作 **/
        if(IS_POST){
            $logic = new UsersLogic();
            $email = I('post.email','');                //接收传递的邮箱
            $password = I('post.password','');          //接收传递的密码
            $mobile   = I('post.mobile','');           //接收传递的确认密码
            $data = $logic->register($email,$password,$mobile);
            /** 注册成功后相关操作 **/
            if($data['status'] == 1){
                session('user',$data['result']);
                setcookie('user_id',$data['result']['user_id'],null,'/');
                $nickname = empty($data['result']['nickname']) ? $email : $data['result']['nickname'];
                setcookie('uname',$nickname,null,'/');
                $cartLogic = new \Home\Logic\CartLogic();
                $cartLogic->login_cart_handle($this->session_id,$data['result']['user_id']);  //用户登录后 需要对购物车 一些操作
            }
            $this->ajaxReturn($data); //返回数据
        }
        /** 非表单提交，直接显示页面 **/
        $this->display();
    }

    /*
     * 订单列表
     */
    public function order_list(){
        $where = ' user_id='.$this->user_id;
        //条件搜索
       if(I('get.type')){
           $where .= C(strtoupper(I('get.type')));  // config.php中查找相应的type类型
       }
       // 搜索订单 根据商品名称 或者 订单编号
       $search_key = trim(I('search_key'));       
       if($search_key)
       {
          $where .= " and (order_sn like '%$search_key%' or order_id in (select order_id from order_goods where goods_name like '%$search_key%') ) ";
       }
       
        $count = M('order')->where($where)->count();
        $Page = new Page($count,10);

        $show = $Page->show();
        $order_str = "order_id DESC";
        $order_list = M('order')->order($order_str)->where($where)->limit($Page->firstRow.','.$Page->listRows)->select();
        //获取订单商品
        $model = new UsersLogic();
        foreach($order_list as $k=>$v)
        {
            $order_list[$k] = set_btn_order_status($v);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
            $data = $model->get_order_goods($v['order_id']);
            $order_list[$k]['goods_list'] = $data['result'];            
        }
        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('page',$show);
        $this->assign('lists',$order_list);
        $this->assign('active_status',I('get.type'));
        $this->display();
    }

    /*
     * 订单详情
     */
    public function order_detail(){
        $id = I('get.id');

        $map['order_id'] = $id;
        $map['user_id'] = $this->user_id;
        $order_info = M('order')->where($map)->find();
        $order_info = set_btn_order_status($order_info);  // 添加属性  包括按钮显示属性 和 订单状态显示属性
        
        if(!$order_info){
            $this->error('没有获取到订单信息');
            exit;
        }
        //获取订单商品
        $model = new UsersLogic();
        $data = $model->get_order_goods($order_info['order_id']);
        $order_info['goods_list'] = $data['result'];

        $this->assign('order_status',C('ORDER_STATUS'));
        $this->assign('shipping_status',C('SHIPPING_STATUS'));
        $this->assign('pay_status',C('PAY_STATUS'));
        $this->assign('order_info',$order_info);
        $this->assign('active','order_list');
        $this->display();
    }

    /*
     * 取消订单
     */
    public function cancel_order(){
        $id = I('get.id');
        //检查是否有积分，余额支付
        $logic = new UsersLogic();
        $data = $logic->cancel_order($this->user_id,$id);
        if($data['status'] < 0)
            $this->error($data['msg']);
        $this->success($data['msg']);
    }

    /*
     * 用户地址列表
     */
    public function address_list(){
        $address_lists = get_user_address_list($this->user_id);
        $region_list = get_region_list();
        $this->assign('region_list',$region_list);
        $this->assign('lists',$address_lists);
        $this->assign('active','address_list');

        $this->display();
    }
    /*
     * 添加地址
     */
    public function add_address(){
        header("Content-type:text/html;charset=utf-8");
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,0,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');
            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $this->assign('province',$p);
        $this->display('edit_address');

    }

    /*
     * 地址编辑
     */
    public function edit_address(){
        header("Content-type:text/html;charset=utf-8");
        $id = I('get.id');
        $address = M('user_address')->where(array('address_id'=>$id,'user_id'=> $this->user_id))->find();
        if(IS_POST){
            $logic = new UsersLogic();
            $data = $logic->add_address($this->user_id,$id,I('post.'));
            if($data['status'] != 1)
                exit('<script>alert("'.$data['msg'].'");history.go(-1);</script>');

            $call_back = $_REQUEST['call_back'];
            echo "<script>parent.{$call_back}('success');</script>";
            exit(); // 成功 回调closeWindow方法 并返回新增的id
        }
        //获取省份
        $p = M('region')->where(array('parent_id'=>0,'level'=> 1))->select();
        $c = M('region')->where(array('parent_id'=>$address['province'],'level'=> 2))->select();
        $d = M('region')->where(array('parent_id'=>$address['city'],'level'=> 3))->select();
        if($address['twon']){
        	$e = M('region')->where(array('parent_id'=>$address['district'],'level'=>4))->select();
        	$this->assign('twon',$e);
        }

        $this->assign('province',$p);
        $this->assign('city',$c);
        $this->assign('district',$d);
        $this->assign('address',$address);
        $this->display();
    }

    /*
     * 设置默认收货地址
     */
    public function set_default(){
        $address_id = I('address_id');
        M('user_address')->where(array('user_id'=>$this->user_id))->save(array('is_default'=>0));
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$address_id))->setField(array('is_default'=>1));
        if(!$row){
            $res = array('status'=>-1,'msg'=>'操作失败');
        }else{
            $res = array('status'=>1,'msg'=>'操作成功');
        }
        $this->ajaxReturn($res);
    }
    
    /*
     * 地址删除
     */
    public function del_address(){
        $id = I('get.id');
        
        $address = M('user_address')->where("address_id = $id")->find();
        $row = M('user_address')->where(array('user_id'=>$this->user_id,'address_id'=>$id))->delete();                
        // 如果删除的是默认收货地址 则要把第一个地址设置为默认收货地址
        if($address['is_default'] == 1)
        {
            $address2 = M('user_address')->where("user_id = {$this->user_id}")->find();            
            $address2 && M('user_address')->where("address_id = {$address2['address_id']}")->save(array('is_default'=>1));
        }        
        if(!$row)
            $this->error('操作失败',U('User/address_list'));
        else
            $this->success("操作成功",U('User/address_list'));
    }


    /*
     * 评论晒单
     */
    public function comment(){
        $user_id = $this->user_id;
        $status = I('get.status',-1);
        $logic = new UsersLogic();
        $data = $logic->get_comment($user_id,$status); //获取评论列表
        $this->assign('page',$data['show']);// 赋值分页输出
        $this->assign('comment_list',$data['result']);
        $this->assign('active','comment');
        $this->display();
    }

    /*
     *添加评论
     */
    public function add_comment()
    {          
            $user_info = session('user');
            $comment_img = serialize(I('comment_img')); // 上传的图片文件            
            $add['goods_id'] = I('goods_id');
            $add['email'] = $user_info['email'];
            //$add['nick'] = $user_info['nickname'];
            $add['username'] = $user_info['nickname'];
            $add['order_id'] = I('order_id');
            $add['service_rank'] = I('service_rank');
            $add['deliver_rank'] = I('deliver_rank');
            $add['goods_rank'] = I('goods_rank');
            //$add['content'] = htmlspecialchars(I('post.content'));
            $add['content'] = I('content');
            $add['img'] = $comment_img;
            $add['add_time'] = time();
            $add['ip_address'] = $_SERVER['REMOTE_ADDR'];
            $add['user_id'] = $this->user_id;
            $logic = new UsersLogic();
            //添加评论
            $row = $logic->add_comment($add);            
            exit(json_encode($row));        
    }

    /*
     * 个人信息
     */
    public function info(){
        $userLogic = new UsersLogic();
        $user_info = $userLogic->get_info($this->user_id); // 获取用户信息
        $user_info = $user_info['result'];
        if(IS_POST){
            I('post.nickname') ? $post['nickname'] = I('post.nickname') : false; //昵称
            I('post.qq') ? $post['qq'] = I('post.qq') : false;  //QQ号码
            I('post.head_pic') ? $post['head_pic'] = I('post.head_pic') : false; //头像地址
            I('post.sex') ? $post['sex'] = I('post.sex') : false;  // 性别
            I('post.birthday') ? $post['birthday'] = strtotime(I('post.birthday')) : false;  // 生日
            I('post.province') ? $post['province'] = I('post.province') : false;  //省份
            I('post.city') ? $post['city'] = I('post.city') : false;  // 城市
            I('post.district') ? $post['district'] = I('post.district') : false;  //地区
            if(!$userLogic->update_info($this->user_id,$post))
                $this->error("保存失败");
            $this->success("操作成功");
            exit;
        }
        //  获取省份
        $province = M('region')->where(array('parent_id'=>0,'level'=>1))->select();
        //  获取订单城市
        $city =  M('region')->where(array('parent_id'=>$user_info['province'],'level'=>2))->select();
        //获取订单地区
        $area =  M('region')->where(array('parent_id'=>$user_info['city'],'level'=>3))->select();

        $this->assign('province',$province);
        $this->assign('city',$city);
        $this->assign('area',$area);
        $this->assign('user',$user_info);
        $this->assign('sex',C('SEX'));
        $this->assign('active','info');
        $this->display();
    }

    /**
     * 上传头像
     */
    public function meituAvatar(){
        $this->display();
    }

    /**
     * 保存头像
     */
    public function saveAvatar(){
        $config = array(
            "maxSize"   => 2000000,                     // 最大允许上传，单位B
            "rootPath"  => './',                           // 保存文件的根目录
            "savePath"  => 'Public/upload/head_pic/',      // 设置附件上传根目录
            'saveName'  => array('uniqid',''),      // 名称唯一
            "exts"      => array('gif','png','jpg','jpeg'),  // 上传图片类型
            'autoSub'   => true,
            "subName"   => array('date', 'Y/m-d'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->upload();
        if($info){
            $pic = '/'.$info['head_pic']['savepath'].$info['head_pic']['savename'];
            $user_id = $this->user_id;
            M('Users')->where(array('user_id'=>$user_id))->setField('head_pic',$pic);
            $this->ajaxReturn(array('status'=>1,'message'=>'上传成功'));
        }else{
            $error_msg = $upload->getError();
            $this->ajaxReturn(array('status'=>0,'message'=>$error_msg));
        }
        exit();

    }


    /**
     * 手机号表单
     */
    public function mobileForm(){
        $map['user_id'] = $this->user_id;   //获取当前用户id
        $mobile = M('users')->where($map)->getField('mobile'); //获取当前用户的手机号
        $this->assign('mobile',$mobile);
        $this->display('mobile_form'); //渲染模板
    }
    /***
     * 更改手机号
     */
    public function updateMobile(){
        /** 判断用户是否登录 **/
        if(!$this->user_id){
            $data = array('status'=>-1,'msg'=>'请先登录');
            $this->ajaxReturn($data);
        }

        $newMobile = trim(I('post.newMobile',''));  //接收新手机号
        /**验证是否存在用户名**/
        if(get_user_info($newMobile,2)){
            $data = array('status'=>-1,'msg'=>'手机号已存在');
            $this->ajaxReturn($data);
        }

        $map['user_id'] = $this->user_id;  //根据id查询
        $res = M('users')->where($map)->setField('mobile',$newMobile);
        if($res){
            $data = array('status'=>1,'msg'=>'手机号更改成功');
        }else{
            $data = array('status'=>-1,'msg'=>'手机号更改失败');
        }
        $this->ajaxReturn($data);   //返回json数据
    }

    /**
     * 密码表单
     */
    public function passwordForm(){

        $this->display('password_form');   //渲染模板
    }

    /**
     * 更改密码
     */
    public function updatePassword(){
        if(!$this->user_id){
            $data = array('status'=>-1,'msg'=>'请先登录');
            $this->ajaxReturn($data);
        }
        $password     = I('post.password','');      //获取原始密码
        $new_password = I('post.newPassword','');   //获取新密码
        /** 检测原始密码是否正确 **/
        $map['user_id'] = $this->user_id;
        $user = M('users')->where($map)->field('password')->find(); // 查找用户记录
        if(md5($password) !== $user['password']){
            $data = array('status'=>-1,'msg'=>'原始密码错误');
            $this->ajaxReturn($data);
        }
        /** 更改密码 **/
        $update =  M('users')->where($map)->field('password')->setField('password',md5($new_password)); // 查找用户记录
        if($update){
            $data = array('status'=>1,'msg'=>'密码更改成功');
        }else{
            $data = array('status'=>-1,'msg'=>'密码更改失败');
        }
        $this->ajaxReturn($data);
    }

    /*
     *商品收藏
     */
    public function goods_collect(){
        $userLogic = new UsersLogic();
        $data = $userLogic->get_goods_collect($this->user_id);
        $this->assign('page',$data['show']);// 赋值分页输出
        $this->assign('lists',$data['result']);
        $this->assign('active','goods_collect');
        $this->display();
    }

    /*
     * 删除一个收藏商品
     */
    public function del_goods_collect(){
        $id = I('post.id');
        if(!$id){
            $res['status']  = -1;
            $res['msg'] = 'ID不存在';
            $this->ajaxReturn($res);
        }
        $row = M('goods_collect')->where(array('collect_id'=>$id,'user_id'=>$this->user_id))->delete();
        if(!$row){
            $res['status']  = -1;
            $res['msg'] = '取消失败';
            $this->ajaxReturn($res);
        }else{
            $res['status']  = 1;
            $res['msg'] = '取消成功';
            $this->ajaxReturn($res);
        }
    }

    /*
     * 密码修改
     */
    public function password(){
        $logic = new UsersLogic();
        $data = $logic->get_info($this->user_id);
        $user = $data['result'];
        if($user['mobile'] == ''&& $user['email'] == '')
            $this->error('请先绑定手机或邮箱',U('Home/User/info'));
        if(IS_POST){
            $userLogic = new UsersLogic();
            $data = $userLogic->password($this->user_id,I('post.old_password'),I('post.new_password'),I('post.confirm_password')); // 获取用户信息
            if($data['status'] == -1)
                $this->error($data['msg']);
            $this->success($data['msg']);
            exit;
        }
        $this->display();
    }

    /**
     * 确认收货
     */
    public function order_confirm(){
        $id   = I('get.id',0);
        $data = confirm_order($id,$this->user_id);
        $this->ajaxReturn($data);
    }
}
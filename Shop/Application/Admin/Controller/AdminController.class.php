<?php

namespace Admin\Controller;

use Think\Verify;

class AdminController extends BaseController {
    /**
     * 后台首页
     */
    public function index(){
        $this->display();
    }

    /**
     * 获取管理员信息
     */
    public function admin_info(){
    	$admin_id = I('get.admin_id',0);   	
    	if($admin_id){
    		$info = D('admin')->where("admin_id=$admin_id")->find();
                $info['password'] =  "";
    		$this->assign('info',$info);
    	}
    	$act = empty($admin_id) ? 'add' : 'edit';
    	$this->assign('act',$act);
    	$role = D('admin_role')->where('1=1')->select();
    	$this->assign('role',$role);
    	$this->display();
    }

    /*
     * 管理员登录
     */
    public function login(){
        if(session('admin_id') && session('admin_id')>0){
             $this->error("您已登录",U('Admin/Index/index'));
        }
      
        if(IS_POST){
            $verify = new Verify();                                      //实例化验证类
            if (!$verify->check(I('post.code'))) {     //检测验证码
                $this->ajaxReturn(array('status'=>0,'msg'=>'验证码错误'));//输出错误信息
            }
            $condition['user_name'] = I('post.username');                //获取用户名
            $condition['password']  = I('post.password');                 //获取密码
            if(!empty($condition['user_name']) && !empty($condition['password'])){
                $condition['password'] = md5($condition['password']);//密码加密
               	$admin_info = M('admin')->where($condition)->find();     //查找密码是否存在
                if(is_array($admin_info)){
                    session('admin_id',$admin_info['admin_id']);         //将管理员id存入缓存
                    $url = session('from_url') ? session('from_url') : U('Admin/Index/index');   //设置跳转url
                    $res = array('status'=>1,'msg'=>'登录成功','url'=>$url);
                    $this->ajaxReturn($res);
                }else{
                    $this->ajaxReturn(array('status'=>0,'msg'=>'账号密码不正确'));
                }
            }else{
                $this->ajaxReturn(array('status'=>0,'msg'=>'请填写账号密码'));
            }
        }

        $this->display();   //渲染模板
    }
    
    /**
     * 退出登录
     */
    public function logout(){
        session_unset();   //清除session
        session_destroy(); //销毁session
        $this->success("退出成功",U('Admin/Admin/login'));
    }

    /**
     * 生成验证码方法
     */
    public function verify(){
        $config =    array(
            'fontSize' => 15,    // 验证码字体大小
            'length'   => 4,     // 验证码位数
            'useNoise' => false, // 关闭验证码杂点
            'imageW'   => 120,   // 图片宽度
            'imageH'   => 34,    // 图片高度
            'codeSet'  => '0123456789',//随机产生0-9中的数字
        );
        $Verify = new \Think\Verify($config);
        $Verify->entry();       // 调用Verify生成验证码
    }


    /**
     * 后台登录日志
     */
    public function log(){
    	$Log = M('admin_log');
    	$p = I('p',1);
    	$logs = $Log->join('__ADMIN__ ON __ADMIN__.admin_id =__ADMIN_LOG__.admin_id')->order('log_time DESC')->page($p.',20')->select();
    	$this->assign('list',$logs);
    	$count = $Log->where('1=1')->count();
    	$Page = new \Think\Page($count,20);
    	$show = $Page->show();
    	$this->assign('page',$show); 	
    	$this->display();
    }

    /***
     * 修改密码
     */
    public function changePassword(){
        if(IS_POST){
            $old_password = I('old_password','','md5');
            $new_password = I('new_password','','md5');
            $map['id']    = $admin_id = session('admin_id');
            $admin = M('admin')->where($map)->find();  //根据当前session('admin_id')，查找原密码
            if($old_password === $admin['password']){  //检测原始密码是否正确
                $admin = M('admin')->where(array('id'=>$admin_id))->setField('password',$new_password); //更改密码
                if($admin !== false){
                    $res['status']  = 1;
                    $res['message'] = '更改成功';
                    $this->ajaxReturn($res);
                }else{
                    $res['status']  = 0;
                    $res['message'] = '更改失败';
                    $this->ajaxReturn($res);
                }
            }else{
                $res['status']  = 0;
                $res['message'] = '更改失败，原始密码错误';
                $this->ajaxReturn($res);
            }
        }else{
            $this->display();
        }
    }

}
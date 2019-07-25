<?php

namespace Admin\Controller;
use Think\Controller;

class BaseController extends Controller {
    public function _initialize(){
        if(in_array(ACTION_NAME,array('login','logout','verify','uploadfiles')) || in_array(CONTROLLER_NAME,array('Ueditor','Uploadify'))){
            return true;
        }else{
            if(!session('admin_id')) {
                $this->error('请先登录', U('Admin/Admin/login'), 1);
            }
            $user = getUserInfo(session('admin_id'));
            $this->assign('user',$user);
        }
    }
}
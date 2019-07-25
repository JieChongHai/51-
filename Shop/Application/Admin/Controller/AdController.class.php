<?php

namespace Admin\Controller;

class AdController extends BaseController{
    public function ad(){       
        $act = I('GET.act','add');
        $ad_id = I('GET.ad_id');
        $ad_info = array();
        if($ad_id){
            $ad_info = D('ad')->where('ad_id='.$ad_id)->find();
            $ad_info['start_time'] = date('Y-m-d',$ad_info['start_time']);
            $ad_info['end_time'] = date('Y-m-d',$ad_info['end_time']);            
        }
        if($act == 'add')          
           $ad_info['pid'] = $_GET['pid'];                
        $position = D('ad_position')->where('1=1')->select();
        $this->assign('info',$ad_info);
        $this->assign('act',$act);
        $this->assign('position',$position);
        $this->display();
    }

    /**
     * 广告列表
     */
    public function adList(){
        $Ad =  M('ad'); 
        $where = "1=1";
        if(I('pid')){
        	$where = "pid=".I('pid');
        	$this->assign('pid',I('pid'));
        }
        $keywords = I('keywords',false);
        if($keywords){
        	$where = "ad_name like '%$keywords%'";
        }
        $count = $Ad->where($where)->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $res = $Ad->where($where)->order('pid desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        $list = array();
        if($res){
        	$media = array('图片','文字','flash');
        	foreach ($res as $val){
        		$val['media_type'] = $media[$val['media_type']];        		
        		$list[] = $val;
        	}
        }
                                     
        $ad_position_list = M('AdPosition')->getField("position_id,position_name,is_open");                        
        $this->assign('ad_position_list',$ad_position_list);//广告位 
        $show = $Page->show();// 分页显示输出
        $this->assign('list',$list);// 赋值数据集
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    
    public function position(){
        $act = I('GET.act','add');
        $position_id = I('GET.position_id');
        $info = array();
        if($position_id){
            $info = D('ad_position')->where('position_id='.$position_id)->find();
            $this->assign('info',$info);
        }
        $this->assign('act',$act);
        $this->display();
    }

    /**
     * 广告位置列表
     */
    public function positionList(){
        $Position =  M('ad_position');
        $count = $Position->where('1=1')->count();// 查询满足要求的总记录数
        $Page = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数
        $list = $Position->order('position_id DESC')->limit($Page->firstRow.','.$Page->listRows)->select();
        
        $this->assign('list',$list);// 赋值数据集                
        $show = $Page->show();// 分页显示输出
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    /**
     * 广告操作处理
     */
    public function adHandle(){
    	$data = I('post.');
    	$data['start_time'] = strtotime($data['begin']);
    	$data['end_time'] = strtotime($data['end']);
    	
    	if($data['act'] == 'add'){
    		$r = D('ad')->add($data);
    	}
    	if($data['act'] == 'edit'){
    		$r = D('ad')->where('ad_id='.$data['ad_id'])->save($data);
    	}
    	
    	if($data['act'] == 'del'){
    		$r = D('ad')->where('ad_id='.$data['del_id'])->delete();
    	}
        if($r){
            $res['status'] = 1;
            $res['msg'] = "操作成功";
        }else{
            $res['status'] = 0;
            $res['msg'] = "操作失败";
        }
        $this->ajaxReturn($res);
    }

    /**
     * 多选删除
     */
    public function delAllAd(){
        $ids = I('post.ids');
        // 多选删除
        $map['ad_id'] = array('in',$ids);
        $r = M('ad')->where($map)->delete();
        if($r){
            $res['status']  = 1;
            $res['message'] = "操作成功";
            $res['icon']    = 1;
        }else{

            $res['status'] = 0;
            $res['message'] = "操作失败";
            $res['icon']    = 2;
        }
        $this->ajaxReturn($res);
    }


    /**
     * 广告位置操作处理
     */
    public function positionHandle(){
        $data = I('post.');
        if($data['act'] == 'add'){
            $r = M('ad_position')->add($data);
        }
        
        if($data['act'] == 'edit'){
        	$r = M('ad_position')->where('position_id='.$data['position_id'])->save($data);
        }
        // 单选删除
        if($data['act'] == 'del'){
        	if(M('ad')->where('pid='.$data['position_id'])->count()>0){
                $res['status'] = 0;
                $res['msg'] = "此广告位下还有广告，请先清除";
                $this->ajaxReturn($res);
        	}else{
        		$r = M('ad_position')->where('position_id='.$data['position_id'])->delete();
        	}
        }
        if($r){
            $res['status'] = 1;
            $res['msg'] = "操作成功";
        }else{
            $res['status'] = 0;
            $res['msg'] = "操作失败";
        }
        $this->ajaxReturn($res);
    }

    /**
     * 多选删除
     */
    public function delAllPosition(){
        $ids = I('post.ids');
        //  判断该广告位下是否有广告
        foreach($ids as $id){
            $ad = M('ad')->where('pid='.$id)->find();
            if($ad){
                $res['status'] = 0;
                $res['message'] = "ID为".$id."的广告位下还有广告，请先清除";
                $res['icon']    = 2;
                $this->ajaxReturn($res);
            }
        }
        // 多选删除
        $map['position_id'] = array('in',$ids);
        $r = M('ad_position')->where($map)->delete();
        if($r){
            $res['status']  = 1;
            $res['message'] = "操作成功";
            $res['icon']    = 1;
        }else{

            $res['status'] = 0;
            $res['message'] = "操作失败";
            $res['icon']    = 2;
        }
        $this->ajaxReturn($res);
    }

    /**
     * 更改字段状态
     */
    public function changeAdField(){
    	$data[$_REQUEST['field']] = I('GET.value');
    	$data['ad_id'] = I('GET.ad_id');
    	M('ad')->save($data); // 根据条件保存修改的数据
    }
}
<?php

namespace Admin\Controller;
use Think\AjaxPage;
use Think\Page;

class GoodsTypeController extends BaseController {

    /**
     * 商品类型  用于设置商品的属性
     */
    public function goodsTypeList(){
        $model = M("GoodsType");
        $count = $model->count();
        $Page  = new \Think\Page($count,13);
        $page  = $Page->show();
        $goodsTypeList = $model->order("id desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        $this->assign('page',$page);
        $this->assign('goodsTypeList',$goodsTypeList);
        $this->display('goodsTypeList');
    }

    /**
     * 添加修改编辑  商品属性类型
     */
    public function addEditGoodsType(){
        $_GET['id'] = $_GET['id'] ? $_GET['id'] : 0;
        $model = M("GoodsType");
        //如果点击提交
        if(IS_POST){
            $model->create();
            if($_POST['id']){
                $model->save();
                $message = '保存成功';
            }else{
                $model->add();
                $message = '添加成功';
            }
            $this->ajaxReturn($message);
        }else{
            //显示页面
            $goodsType = $model->find($_GET['id']);
            $this->assign('goodsType',$goodsType);
            $this->display();
        }
    }

    /**
     * 删除商品类型
     */
    public function delGoodsType()
    {
        // 判断 商品规格
        $count = M("Spec")->where("type_id = {$_GET['id']}")->count("1");
        if($count){
            $res['status']  = 0;
            $res['message'] = '该类型下有商品规格不得删除!';
            $this->ajaxReturn($res);
        }
        // 判断 商品属性
        $count = M("GoodsAttribute")->where("type_id = {$_GET['id']}")->count("1");
        if($count){
            $res['status']  = 0;
            $res['message'] = '该类型下有商品属性不得删除!';
            $this->ajaxReturn($res);
        }
        // 删除分类
        $delete_id = M('GoodsType')->where("id = {$_GET['id']}")->delete();
        if($delete_id){
            $res['status']  = 1;
            $res['message'] = '删除成功';
        }else{
            $res['status']  = 0;
            $res['message'] = '删除失败';
        }
        $this->ajaxReturn($res);
    }

}
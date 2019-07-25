<?php

namespace Home\Controller;
use Think\Page;
use Think\Verify;
class IndexController extends BaseController {
    public function index(){

        //bannner 广告
        $banner = M('ad')->where(array('pid'=>2))->select();
        $this->assign('banner',$banner);

        //best_seller 热卖
        $best_seller = M('goods')->where(array('is_new'=>1,'is_hot'=>1,'is_recommend'=>1,'is_on_sale'=>1))->order('goods_id desc')->limit(4)->select();
        $this->assign('best_seller',$best_seller);

        //中部banner pid=3
        $this->assign('ad3',$this->getAD(3));
        /**  底部banner pid=4 **/
        $this->assign('ad4',$this->getAD(4));
        //首页公告上方广告位 pid=7
        $this->assign('ad7',$this->getAD(7));
        //首页公告下方广告位 pid=8
        $this->assign('ad8',$this->getAD(8));

        //公告
        $this->assign('announcement',$this->getArticle(5));
        //新闻
        $this->assign('news',$this->getArticle(6));

        /** 商品显示 **/
        $category1 = M('goods_category')->where(array('is_show'=>1,'level'=>1))->limit(7)->select(); //筛选一级分类
        foreach($category1 as $key=>$v ){
            $category2 = M('goods_category')->where(array('is_show'=>1,'parent_id'=>$v['id']))->field('id,name')->select(); //筛选二级分类
            $category[$v['name']]['sub_category'] = $category2;
            $cat_id_arr = getCatGrandson($v['id']);         //找到一级下面的所有子分类id
            $sub_id_str = implode(',',$cat_id_arr);         //将子分类id拼接成字符串
            $map['cat_id']     = array('in',$sub_id_str);   //搜索条件：商品分类id在子类id中
            $map['is_on_sale'] = 1;                         //搜索条件：商品在售
            //从商品表中，筛选7条满足以上2个条件的记录
            $category[$v['name']]['goods'] = M('goods')->where($map)->limit(7)->order('goods_id')->field('goods_id,goods_name,keywords,goods_remark,shop_price')->select();
        }
        $this->assign('category',$category);    // 模板赋值

        $this->display();//模板渲染
    }

    /**
     * 根据pid获取广告信息
     * @param $pid
     * @return mixed
     */
    public function getAD($pid){
        $ad = M('ad')->where(array('pid'=>$pid))->find();
        return $ad;
    }

    /**
     * 根据cat_id获取新闻或公告
     * @param $cat_id
     * @return mixed
     */
    public function getArticle($cat_id){
        $article =  M('article')->where(array('cat_id'=>$cat_id))->order('article_id desc')->limit(4)->select();
        return $article;
    }

}
<?php

namespace Home\Controller;
use Think\Controller;

class BaseController extends Controller {
    public $session_id;
    public $cateTrre = array();
    /*
     * 初始化操作
     */
    public function _initialize() {
    	$this->session_id = session_id(); // 当前的 session_id
        define('SESSION_ID',$this->session_id); //将当前的session_id保存为常量，供其它方法调用
        $this->public_assign();
        $this->footer();

    }
    /**
     * 保存公告变量到 smarty中
     */
    public function public_assign()
    {
       $shop_config = array();
       $config = M('config')->select();
       foreach($config as $k => $v)
       {
       	  if($v['name'] == 'hot_keywords'){
       	  	 $shop_config['hot_keywords'] = explode('|', $v['value']);
       	  }       	  
          $shop_config[$v['inc_type'].'_'.$v['name']] = $v['value'];
       }
       $goods_category_tree = get_goods_category_tree();            // 获取商品一二三级分类
       $this->cateTrre = $goods_category_tree;                      // 分类赋值
       $this->assign('goods_category_tree', $goods_category_tree);  // 模板赋值
       $brand_list = M('brand')->field('id,parent_cat_id,logo,is_hot')->where("parent_cat_id>0")->select();
       $this->assign('brand_list', $brand_list);
       $this->assign('shop_config', $shop_config);
    }

    /**
     * 网站底部文章
     */
    public function footer(){
        $map['cat_id'] = array('in',array(1,2,3,4,7));
        $article_cat = D('ArticleCat')->relation(true)->where($map)->select();
        $this->assign('article_cat',$article_cat);
    }
}
<?php

namespace Home\Controller;
use Home\Logic\CartLogic;
use Home\Logic\GoodsLogic;
use Think\AjaxPage;
use Think\Page;

class GoodsController extends BaseController {
   /**
    * 商品详情页
    */ 
    public function goodsInfo(){
        C('TOKEN_ON',true); //开启Token
        $goodsLogic = new \Home\Logic\GoodsLogic(); //实例化GoodsLogic
        $goods_id = I("get.id");    //获取商品id
        $goods = M('Goods')->where("goods_id = $goods_id")->find(); //获取商品信息
        if(empty($goods) || ($goods['is_on_sale'] == 0)){   //商品不存在跳转首页
        	$this->error('该商品已经下架',U('Index/index'));
        }
        if($goods['brand_id']){ //如果存在品牌，查找品牌信息
            $brnad = M('brand')->where("id =".$goods['brand_id'])->find();
            $goods['brand_name'] = $brnad['name'];
        }
        $goods_images_list = M('GoodsImages')->where("goods_id = $goods_id")->select(); // 获取商品图册
        $goods_attribute = M('GoodsAttribute')->getField('attr_id,attr_name'); // 获取查询属性
        $goods_attr_list = M('GoodsAttr')->where("goods_id = $goods_id")->select(); // 查询商品属性表                        
		$filter_spec = $goodsLogic->get_spec($goods_id);    //获取商品规格

        //$freight_free = tpCache('shopping.freight_free'); // 全场满多少免运费
        $spec_goods_price  = M('spec_goods_price')->where("goods_id = $goods_id")->getField("key,price,store_count"); // 规格 对应 价格 库存表
        M('Goods')->where("goods_id=$goods_id")->save(array('click_count'=>$goods['click_count']+1 )); //统计点击数
        $commentStatistics = $goodsLogic->commentStatistics($goods_id);// 获取某个商品的评论统计
        //$point_rate = tpCache('shopping.point_rate');
        //$this->assign('freight_free', $freight_free);// 全场满多少免运费

        /** 推荐商品 **/
        $recommend_goods = M('goods')->where(array('is_recommend'=>1,'cat_id'=>$goods['cat_id']))->limit(10)->select();
        $this->assign('recommend_goods',$recommend_goods);

        $this->assign('spec_goods_price', json_encode($spec_goods_price,true)); // 规格 对应 价格 库存表
        $this->assign('navigate_goods',navigate_goods($goods_id,1));// 面包屑导航 
        $this->assign('commentStatistics',$commentStatistics);//评论概览
        $this->assign('goods_attribute',$goods_attribute);//属性值     
        $this->assign('goods_attr_list',$goods_attr_list);//属性列表
        $this->assign('filter_spec',$filter_spec);//规格参数
        $this->assign('goods_images_list',$goods_images_list);//商品缩略图
        $this->assign('siblings_cate',$goodsLogic->get_siblings_cate($goods['cat_id']));//相关分类
        $this->assign('look_see',$goodsLogic->get_look_see($goods));//看了又看      
        $this->assign('goods',$goods);
        $this->display();
    }

    /**
     * 商品列表页
     */
    public function goodsList(){
        /** 获取筛选数据 **/
        $filter_param = array(); // 筛选数组                        
        $cate_id = I('get.id',1);    // 获取当前分类id
        $brand_id = I('get.brand_id',0);    //获取品牌id
        $spec = I('get.spec',0);    // 获取商品规格
        $attr = I('get.attr','');   // 获取商品属性
        $sort = I('get.sort','goods_id'); // 获取排序
        $sort_asc = I('get.sort_asc','asc'); // 获取排序方式
        $price = I('get.price',''); // 获取商品价钱
        $start_price = trim(I('post.start_price','0')); // 输入框最低价钱
        $end_price = trim(I('post.end_price','0')); // 输入框最高价钱
        if($start_price && $end_price) $price = $start_price.'-'.$end_price; // 如果输入框有价钱 则使用输入框的价钱替换商品价钱

        /** 加入删选条件 **/
        $filter_param['id'] = $cate_id; //将分类id加入筛选条件中
        $brand_id  && ($filter_param['brand_id'] = $brand_id); //加入筛选条件中
        $spec  && ($filter_param['spec'] = $spec); //加入筛选条件中
        $attr  && ($filter_param['attr'] = $attr); //加入筛选条件中
        $price  && ($filter_param['price'] = $price); //加入筛选条件中
                
        $goodsLogic = new \Home\Logic\GoodsLogic(); // 实例化前台商品操作逻辑类
        /** 分类菜单显示 **/
        $goodsCate = M('GoodsCategory')->where("id = $cate_id")->find();// 获取当前分类
        $cateArr = $goodsLogic->get_goods_cate($goodsCate); // 获取上级分类，并且$goodsCate添加相应选项，如顶级分类名称，默认展开

        $cat_id_arr = getCatGrandson ($cate_id);// 筛选商品品牌、规格、属性、价格
        $filter_goods_id = M('goods')->where("is_on_sale=1 and cat_id in(".  implode(',', $cat_id_arr).")")->getField("goods_id",true);
        /** 过滤筛选的结果集里面找商品 **/
        if($brand_id || $price)// 判断品牌或者价格是否存在
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id,$price); // 根据 品牌 或者 价格范围 查找所有商品id    
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_1); // 获取多个筛选条件的结果 的交集
        }
        if($spec)// 规格
        {
            $goods_id_2 = $goodsLogic->getGoodsIdBySpec($spec); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_2); // 获取多个筛选条件的结果 的交集
        }
        if($attr)// 属性
        {
            $goods_id_3 = $goodsLogic->getGoodsIdByAttr($attr); // 根据 规格 查找当所有商品id
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_3); // 获取多个筛选条件的结果 的交集
        }

        /** 顶部筛选菜单 **/
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param,'goodsList'); // 获取显示的筛选菜单
        $this->assign('filter_menu',$filter_menu);

        $filter_price = $goodsLogic->get_filter_price($filter_goods_id,$filter_param,'goodsList'); // 筛选的价格期间

        /** 商品品牌 **/
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id,$filter_param,'goodsList',1); // 获取指定分类下的筛选品牌
        $this->assign('filter_brand',$filter_brand);

        /** 筛选规格 **/
        $filter_spec  = $goodsLogic->get_filter_spec($filter_goods_id,$filter_param,'goodsList',1); // 获取指定分类下的筛选规格
        $this->assign('filter_spec',$filter_spec);
        /** 筛选属性 **/
        $filter_attr  = $goodsLogic->get_filter_attr($filter_goods_id,$filter_param,'goodsList',1); // 获取指定分类下的筛选属性
        $this->assign('filter_attr',$filter_attr);
                                
        $count = count($filter_goods_id);
        $page = new Page($count,10);

        if($count > 0)
        {
            $goods_list = M('goods')->where("goods_id in (".  implode(',', $filter_goods_id).")")->order("$sort $sort_asc")->limit($page->firstRow.','.$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if($filter_goods_id2)
            $goods_images = M('goods_images')->where("goods_id in (".  implode(',', $filter_goods_id2).")")->cache(true)->select();
            $this->assign('goods_images',$goods_images);  // 相册图片
        }
        // print_r($filter_menu);         
        $goods_category = M('goods_category')->where('is_show=1')->cache(true)->getField('id,name,parent_id,level'); // 键值分类数组
        $navigate_cat = navigate_goods($cate_id); // 面包屑导航

        /** 推荐商品 **/
        $recommend_goods = M('goods')->where(array('is_recommend'=>1))->field('goods_id,goods_name,market_price,shop_price')->limit(5)->select();
        $this->assign('recommend_goods',$recommend_goods);

        $this->assign('goods_list',$goods_list);
        $this->assign('navigate_cat',$navigate_cat);
        $this->assign('goods_category',$goods_category);


        $this->assign('filter_price',$filter_price);// 筛选的价格期间
        $this->assign('goodsCate',$goodsCate);
        $this->assign('cateArr',$cateArr);
        $this->assign('filter_param',$filter_param); // 筛选条件
        $this->assign('cat_id',$cate_id);
        $this->assign('page',$page);// 赋值分页输出
        $this->display();
    }    

    /**
     * 商品搜索列表页
     */
    public function search()
    {
        $filter_param = array(); // 筛选数组                        
        $id = I('get.id',0); // 当前分类id 
        $brand_id = I('brand_id',0);         
        $sort = I('sort','goods_id'); // 排序
        $sort_asc = I('sort_asc','asc'); // 排序
        $price = I('price',''); // 价钱
        $start_price = trim(I('start_price','0')); // 输入框价钱
        $end_price = trim(I('end_price','0')); // 输入框价钱
        if($start_price && $end_price) $price = $start_price.'-'.$end_price; // 如果输入框有价钱 则使用输入框的价钱
        $q = urldecode(trim(I('q',''))); // 关键字搜索
        empty($q) && $this->error('请输入搜索词');

        $id && ($filter_param['id'] = $id); //加入筛选条件中                       
        $brand_id  && ($filter_param['brand_id'] = $brand_id); //加入筛选条件中        
        $price  && ($filter_param['price'] = $price); //加入筛选条件中
        $q  && ($_GET['q'] = $filter_param['q'] = $q); //加入筛选条件中
        
        $goodsLogic = new \Home\Logic\GoodsLogic(); // 前台商品操作逻辑类
               
        $where  = array(
            'is_on_sale' => 1
        );
        //引入sphinxapi
        if(file_exists(PLUGIN_PATH.'coreseek/sphinxapi.php'))
        {
            require_once("plugins/coreseek/sphinxapi.php");
            $cl = new \SphinxClient();
            $cl->SetServer(C('SPHINX_HOST'), C('SPHINX_PORT'));
            $cl->SetConnectTimeout(10);
            $cl->SetArrayResult(true);
            $cl->SetMatchMode(SPH_MATCH_ANY);
            $res = $cl->Query($q, "*");
            if($res){
                $res = $cl->Query($q, "*");
                $goods_id_array = array();
                foreach ($res['matches'] as $key => $value) {
                    $goods_id_array[] = $value['id'];
                }
                if(!empty($goods_id_array)){
                    $where['goods_id'] = array('in',$goods_id_array);
                }else{
                    $where['goods_id'] = 0;
                }
            }else{
                $where['goods_name'] = array('like','%'.$q.'%');
            }
        }else{
            $where['goods_name'] = array('like','%'.$q.'%');
        }


        if($id)
        {
            $cat_id_arr = getCatGrandson ($id);
            $where['cat_id'] = array('in',implode(',', $cat_id_arr));
        }
        
        $search_goods = M('goods')->where($where)->getField('goods_id,cat_id');
        $filter_goods_id = array_keys($search_goods);
        $filter_cat_id = array_unique($search_goods); // 分类需要去重
        if($filter_cat_id)        
        {
            $cateArr = M('goods_category')->where("id in(".implode(',', $filter_cat_id).")")->select();            
            $tmp = $filter_param;
            foreach($cateArr as $k => $v)            
            {
                $tmp['id'] = $v['id'];
                $cateArr[$k]['href'] = U("/Home/Goods/search",$tmp);                            
            }                
        }                        
        // 过滤筛选的结果集里面找商品        
        if($brand_id || $price)// 品牌或者价格
        {
            $goods_id_1 = $goodsLogic->getGoodsIdByBrandPrice($brand_id,$price); // 根据 品牌 或者 价格范围 查找所有商品id    
            $filter_goods_id = array_intersect($filter_goods_id,$goods_id_1); // 获取多个筛选条件的结果 的交集
        }
        
        $filter_menu  = $goodsLogic->get_filter_menu($filter_param,'search'); // 获取显示的筛选菜单
        $filter_price = $goodsLogic->get_filter_price($filter_goods_id,$filter_param,'search'); // 筛选的价格期间         
        $filter_brand = $goodsLogic->get_filter_brand($filter_goods_id,$filter_param,'search',1); // 获取指定分类下的筛选品牌        
                                
        $count = count($filter_goods_id);
        $page = new Page($count,20);
        if($count > 0)
        {
            $goods_list = M('goods')->where("is_on_sale=1 and goods_id in (".  implode(',', $filter_goods_id).")")->order("$sort $sort_asc")->limit($page->firstRow.','.$page->listRows)->select();
            $filter_goods_id2 = get_arr_column($goods_list, 'goods_id');
            if($filter_goods_id2)
            $goods_images = M('goods_images')->where("goods_id in (".  implode(',', $filter_goods_id2).")")->select();       
        }

        /** 推荐商品 **/
        $recommend_goods = M('goods')->where(array('is_recommend'=>1))->field('goods_id,goods_name,market_price,shop_price')->limit(5)->select();
        $this->assign('recommend_goods',$recommend_goods);
        $this->assign('goods_list',$goods_list);  
        $this->assign('goods_images',$goods_images);  // 相册图片
        $this->assign('filter_menu',$filter_menu);  // 筛选菜单
        $this->assign('filter_brand',$filter_brand);  // 列表页筛选属性 - 商品品牌
        $this->assign('filter_price',$filter_price);// 筛选的价格期间
        $this->assign('cateArr',$cateArr);
        $this->assign('filter_param',$filter_param); // 筛选条件
        $this->assign('cat_id',$id);
        $this->assign('page',$page);// 赋值分页输出
        $this->assign('q',I('q'));
        C('TOKEN_ON',false);
        $this->display();
    }

    /**
     * 商品评论ajax分页
     */
    public function ajaxComment(){        
        $goods_id = I("goods_id",'0');        
        $commentType = I('commentType','1'); // 1 全部 2好评 3 中评 4差评
        if($commentType==5){
        	$where = "is_show = 1 and  goods_id = $goods_id and parent_id = 0 and img !='' ";
        }else{
        	$typeArr = array('1'=>'0,1,2,3,4,5','2'=>'4,5','3'=>'3','4'=>'0,1,2');
        	$where = "goods_id = $goods_id and parent_id = 0 and ceil((deliver_rank + goods_rank + service_rank) / 3) in($typeArr[$commentType])";
        }
        $count = M('Comment')->where($where)->count();
        $page = new AjaxPage($count,5);
        $show = $page->show();        
        $list = M('Comment')->where($where)->order("add_time desc")->limit($page->firstRow.','.$page->listRows)->select();
        $replyList = M('Comment')->where("goods_id = $goods_id and parent_id > 0")->order("add_time desc")->select();
        foreach($list as $k => $v){
            $list[$k]['img'] = unserialize($v['img']); // 晒单图片            
        }        
        $this->assign('commentlist',$list);// 商品评论
        $this->assign('replyList',$replyList); // 管理员回复
        $this->assign('page',$show);// 赋值分页输出        
        $this->display();        
    }    

    /**
     * 用户收藏某一件商品
     * @param type $goods_id
     */
    public function collect_goods($goods_id)
    {
        $goods_id = I('goods_id');
        $goodsLogic = new \Home\Logic\GoodsLogic();        
        $result = $goodsLogic->collect_goods(cookie('user_id'),$goods_id);
        exit(json_encode($result));
    }
    
    /**
     * 加入购物车弹出
     */
    public function open_add_cart()
    {
        $hot_goods = M('goods')->where(array('is_recommend'=>1))->limit(4)->field('goods_id,goods_name,shop_price')->select();
        $this->assign('hot_goods',$hot_goods);
        $this->display();
    }
    
}
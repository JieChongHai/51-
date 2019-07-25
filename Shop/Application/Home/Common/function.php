<?php
/**
 * 获取用户信息
 * @param $user_id_or_name  用户id 邮箱 手机 第三方id
 * @param int $type  类型 0 user_id查找 1 邮箱查找 2 手机查找 3 第三方唯一标识查找
 * @param string $oauth  第三方来源
 * @return mixed
 */
function get_user_info($user_id_or_name,$type = 0,$oauth=''){
    $map = array();
    if($type == 0)
        $map['user_id'] = $user_id_or_name;
    if($type == 1)
        $map['email'] = $user_id_or_name;
    if($type == 2)
        $map['mobile'] = $user_id_or_name;
    if($type == 3){
        $map['openid'] = $user_id_or_name;
        $map['oauth'] = $oauth;
    }
    $user = M('users')->where($map)->find();
    return $user;
}


/**
 * 面包屑导航  用于前台用户中心
 * 根据当前的控制器名称 和 action 方法
 */
function navigate_user()
{    
    $navigate = include APP_PATH.'Common/Conf/navigate.php';    
    $location = strtolower('Home/'.CONTROLLER_NAME);
    $arr = array(
        '首页'=>'/',
        $navigate[$location]['name']=>U('/Home/'.CONTROLLER_NAME),
        $navigate[$location]['action'][ACTION_NAME]=>'javascript:void();',
    );
    return $arr;
}

/**
*  面包屑导航  用于前台商品
 * @param type $id 商品id  或者是 商品分类id
 * @param type $type 默认0是传递商品分类id  id 也可以传递 商品id type则为1
 */
function navigate_goods($id,$type = 0)
{
    $cat_id = $id; //
    // 如果传递过来的是
    if($type == 1){
        $cat_id = M('goods')->where("goods_id = $id")->getField('cat_id');
    }
    $categoryList = M('GoodsCategory')->getField("id,name,parent_id");

    // 第一个先装起来
    $arr[$cat_id] = $categoryList[$cat_id]['name'];
    while (true)
    {
        $cat_id = $categoryList[$cat_id]['parent_id'];
        if($cat_id > 0)
            $arr[$cat_id] = $categoryList[$cat_id]['name'];
        else
            break;
    }
    $arr = array_reverse($arr,true);
    return $arr;
}

/**
 * 获取商品一二三级分类
 * @return type
 */
function get_goods_category_tree(){
    $result = array();
    $cat_list = M('goods_category')->where("is_show = 1")->order('sort_order')->select();   // 查询所有分类is_show为1的记录
    foreach ($cat_list as $val){
        if($val['level'] == 2){                 // 如果是二级分类
            $arr[$val['parent_id']][] = $val;   // 将该分类的内容作为$arr数组的元素
        }
        if($val['level'] == 3){                 // 如果是三级分类
            $crr[$val['parent_id']][] = $val;   // 将该分类的内容作为$crr数组的元素
        }
        if($val['level'] == 1){                 // 如果是三级分类
            $tree[] = $val;                     // 将该分类的内容作为$tree数组的元素
        }
    }

    // 遍历二级分类，将三级分类的内容赋值给二级分类
    foreach ($arr as $k=>$v){
        foreach ($v as $kk=>$vv){
            $arr[$k][$kk]['sub_menu'] = empty($crr[$vv['id']]) ? array() : $crr[$vv['id']]; // 三级分类内容作为其二级分类的sub_menu
        }
    }

    // 遍历一级分类，将二级分类的内容赋值给一级分类
    foreach ($tree as $val){
        $val['tmenu'] = empty($arr[$val['id']]) ? array() : $arr[$val['id']];   // 二级分类内容作为其一级分类的tmenu
        $result[$val['id']] = $val;
    }
    return $result;
}

/**
 *  商品缩略图 给于标签调用 拿出商品表的 original_img 原始图来裁切出来的
 * @param type $goods_id  商品id
 * @param type $width     生成缩略图的宽度
 * @param type $height    生成缩略图的高度
 */
function goods_thum_images($goods_id,$width,$height){

    if(empty($goods_id))
        return '';
    //判断缩略图是否存在
    $path = "Public/upload/goods/thumb/$goods_id/";
    $goods_thumb_name ="goods_thumb_{$goods_id}_{$width}_{$height}";

    // 这个商品 已经生成过这个比例的图片就直接返回了
    if(file_exists($path.$goods_thumb_name.'.jpg'))  return '/'.$path.$goods_thumb_name.'.jpg';
    if(file_exists($path.$goods_thumb_name.'.jpeg')) return '/'.$path.$goods_thumb_name.'.jpeg';
    if(file_exists($path.$goods_thumb_name.'.gif'))  return '/'.$path.$goods_thumb_name.'.gif';
    if(file_exists($path.$goods_thumb_name.'.png'))  return '/'.$path.$goods_thumb_name.'.png';

    $original_img = M('Goods')->where("goods_id = $goods_id")->getField('original_img');
    if(empty($original_img)) return '';

    $original_img = '.'.$original_img; // 相对路径
    if(!file_exists($original_img)) return '';

    $image = new \Think\Image();
    $image->open($original_img);

    $goods_thumb_name = $goods_thumb_name. '.'.$image->type();
    //生成缩略图
    if(!is_dir($path))
        mkdir($path,0777,true);

    //参考文章 http://www.mb5u.com/biancheng/php/php_84533.html  改动参考 http://www.thinkphp.cn/topic/13542.html
    $image->thumb($width, $height,2)->save($path.$goods_thumb_name,NULL,100); //按照原图的比例生成一个最大为$width*$height的缩略图并保存

    return '/'.$path.$goods_thumb_name;
}

/**
 * 商品相册缩略图
 */
function get_sub_images($sub_img,$goods_id,$width,$height){
    //判断缩略图是否存在
    $path = "Public/upload/goods/thumb/$goods_id/";
    $goods_thumb_name ="goods_sub_thumb_{$sub_img['img_id']}_{$width}_{$height}";
    //这个缩略图 已经生成过这个比例的图片就直接返回了
    if(file_exists($path.$goods_thumb_name.'.jpg'))  return '/'.$path.$goods_thumb_name.'.jpg';
    if(file_exists($path.$goods_thumb_name.'.jpeg')) return '/'.$path.$goods_thumb_name.'.jpeg';
    if(file_exists($path.$goods_thumb_name.'.gif'))  return '/'.$path.$goods_thumb_name.'.gif';
    if(file_exists($path.$goods_thumb_name.'.png'))  return '/'.$path.$goods_thumb_name.'.png';

    $original_img = '.'.$sub_img['image_url']; //相对路径
    if(!file_exists($original_img)) return '';

    $image = new \Think\Image();
    $image->open($original_img);

    $goods_thumb_name = $goods_thumb_name. '.'.$image->type();
    // 生成缩略图
    if(!is_dir($path))
        mkdir($path,777,true);
    $image->thumb($width, $height,2)->save($path.$goods_thumb_name,NULL,100); //按照原图的比例生成一个最大为$width*$height的缩略图并保存
    return '/'.$path.$goods_thumb_name;
}



<?php

namespace Admin\Model;
use Think\Model;
class GoodsCategoryModel extends Model {
    protected $patchValidate = true; // 系统支持数据的批量验证功能，
    protected $_validate = array(
        array('name','require','分类名称必须填写！',1 ,'',3),  // 1 必须验证
        array('sort_order','number','排序必须为数字！',2,'',3), //        
     );    
}

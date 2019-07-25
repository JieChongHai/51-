<?php
namespace Home\Model;
use Think\Model\RelationModel;

class GoodsCategoryModel extends RelationModel{
    protected $_link = array(
        'goods'  =>  array(
            'mapping_type' => self::HAS_MANY,
            'foreign_key'  => 'cat_id',
            'mapping_fields'=>'goods_id,cat_id,goods_name',
            'mapping_limit' => 4
        ),
    );

}



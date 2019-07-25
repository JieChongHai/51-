<?php
namespace Home\Model;
use Think\Model\RelationModel;

class ArticleCatModel extends RelationModel{
    protected $_link = array(
        'article'  =>  array(
            'mapping_type' => self::HAS_MANY,
            'foreign_key'  => 'cat_id',
            'mapping_fields'=>'article_id,title',
        ),
    );

}



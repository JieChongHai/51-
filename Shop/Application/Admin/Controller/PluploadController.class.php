<?php

namespace Admin\Controller;
use Think\Controller;

class PluploadController extends BaseController {

    /**
     * 删除上传的图片
     * @return bool
     */
    public function delupload(){
        $filename= isset($_GET['filename']) ? $_GET['filename'] : null;
        $filename= str_replace('../','',$filename);
        $filename= trim($filename,'.');
        $filename= trim($filename,'/');
        if(!empty($filename)){
            $size     = getimagesize($filename);
            $filetype = explode('/',$size['mime']);
            if($filetype[0]!='image'){
                return false;
                exit;
            }
            unlink($filename);
            echo true;
        }
    }

    // 上传图片
    public function uploadPicture(){
        $directory = I('directory');
        $config = array(
            "maxSize"   => 2000000,                             // 最大允许上传，单位B
            "rootPath"  => './',                                // 保存文件的根目录
            "savePath"  => 'Public/upload/'.$directory.'/',     // 设置附件上传根目录
            'saveName'  => array('uniqid',''),                  // 名称唯一
            "exts"      => array('gif','png','jpg','jpeg'),     // 上传图片类型
            'autoSub'   => true,
            "subName"   => array('date', 'Y/m-d'),
        );
        $upload = new \Think\Upload($config);// 实例化上传类
        $info = $upload->upload();
        if($info){
            $pic = '/'.$info['file']['savepath'].$info['file']['savename'];
            echo json_encode(array("error" => "0", "pic" => $pic));
        }else{
            $error_msg = $upload->getError();
            echo json_encode(array("error" => $error_msg));
        }

    }

    // 上传单图页面
    public function UploadFiles(){
        $this->display();
    }

    // 上传多图页面
    public function UploadOneFile(){
        $this->display();
    }




}
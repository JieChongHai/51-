<?php

namespace Admin\Controller;
use Think\Controller;

class PluploadController extends BaseController {

    /**
     * ɾ���ϴ���ͼƬ
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

    // �ϴ�ͼƬ
    public function uploadPicture(){
        $directory = I('directory');
        $config = array(
            "maxSize"   => 2000000,                             // ��������ϴ�����λB
            "rootPath"  => './',                                // �����ļ��ĸ�Ŀ¼
            "savePath"  => 'Public/upload/'.$directory.'/',     // ���ø����ϴ���Ŀ¼
            'saveName'  => array('uniqid',''),                  // ����Ψһ
            "exts"      => array('gif','png','jpg','jpeg'),     // �ϴ�ͼƬ����
            'autoSub'   => true,
            "subName"   => array('date', 'Y/m-d'),
        );
        $upload = new \Think\Upload($config);// ʵ�����ϴ���
        $info = $upload->upload();
        if($info){
            $pic = '/'.$info['file']['savepath'].$info['file']['savename'];
            echo json_encode(array("error" => "0", "pic" => $pic));
        }else{
            $error_msg = $upload->getError();
            echo json_encode(array("error" => $error_msg));
        }

    }

    // �ϴ���ͼҳ��
    public function UploadFiles(){
        $this->display();
    }

    // �ϴ���ͼҳ��
    public function UploadOneFile(){
        $this->display();
    }




}
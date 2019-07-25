<?php

namespace Admin\Controller;
use Think\Controller;

class IndexController extends BaseController {
    public function index()
    {
        /**��ȡ��Ʒ����**/
        $total_goods = M('goods')->where(array('is_on_sale'))->count();
        $year  = date("Y"); // ��ǰ���
        $month = date("m"); // ��ǰ�·�
        /**��ȡ���һ����������Ա**/
        $current_month_start = strtotime($year."-".$month."-01 00:00:00"); // ���µ�һ��
        $map['reg_time'] = array('EGT',$current_month_start);
        $recent_user_number = M('users')->where($map)->count();

        $start = strtotime($year."-01-01 00:00:00");   // ����ȵ�һ��
        $end   = strtotime($year."-12-31 23:59:59");   // ��������һ��
        // SQL��ѯ���
        $sql = "SELECT COUNT(*) as tnum,sum(order_amount) as amount, FROM_UNIXTIME(add_time,'%m') as month from  `order` ";
        $sql .= " where add_time> $start and add_time <$end and pay_status=1 and order_status in(1,2,4) group by month ";
        $res = M()->query($sql);
        // ���¶�������
        foreach($res as $arr){
            if($arr['month'] == $month){                 // ��ǰ�·�
                $current_tnum   = $arr['tnum'];     // ������������
                $current_amount = $arr['amount'];   // �������۶�
            }else{
                $current_tnum = 0;
                $current_amount = 0;
            }
        }
        /**ȫ���������������۶�**/
        foreach($res as $arr){
            $data[$arr['month']] = $arr['amount'];
            $tnum[$arr['month']] = $arr['tnum'];
        }
        $all_year_tnum   = array_sum($tnum);  // ȫ����������
        $all_year_amount = array_sum($data);  // ȫ�������ܶ�
        // ��֯1-12�����۶����ݸ�ʽ
        for($i=1;$i<13;$i++){
            if($i < 10){
                $i = '0'.$i;
            }
            if(!in_array($i,array_keys($data))){
                $data[$i] = 0;
            }
        }
        ksort($data);  // �·�����
        foreach($data as $key => $arr){
            $result['value'][] = $arr;
            $result['month'][] = $key;
        }

        $this->assign('recent_user_number',$recent_user_number);
        $this->assign('total_goods',$total_goods);
        $this->assign('current_tnum',$current_tnum);
        $this->assign('current_amount',$current_amount);
        $this->assign('all_year_tnum',$all_year_tnum);
        $this->assign('all_year_amount',$all_year_amount);
        $this->assign('result',json_encode($result));
        $this->display();
    }

    /**
     * ajax �޸�ָ���������ֶ�  һ���޸�״̬ ���� �Ƿ��Ƽ� �Ƿ��� �� ͼ���л���
     * table,id_name,id_value,field,value
     */
    public function changeTableVal(){
        $table = I('table'); // ����
        $id_name = I('id_name'); // ������id��
        $id_value = I('id_value'); // ������idֵ
        $field  = I('field'); // �޸��ĸ��ֶ�
        $value  = I('value'); // �޸��ֶ�ֵ
        M($table)->where("$id_name = $id_value")->save(array($field=>$value)); // �������������޸ĵ�����
    }

}
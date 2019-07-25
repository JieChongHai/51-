<?php

namespace Admin\Controller;
use Think\Controller;

class IndexController extends BaseController {
    public function index()
    {
        /**获取商品总数**/
        $total_goods = M('goods')->where(array('is_on_sale'))->count();
        $year  = date("Y"); // 当前年份
        $month = date("m"); // 当前月份
        /**获取最后一个月新增会员**/
        $current_month_start = strtotime($year."-".$month."-01 00:00:00"); // 本月第一天
        $map['reg_time'] = array('EGT',$current_month_start);
        $recent_user_number = M('users')->where($map)->count();

        $start = strtotime($year."-01-01 00:00:00");   // 本年度第一天
        $end   = strtotime($year."-12-31 23:59:59");   // 本年度最后一天
        // SQL查询语句
        $sql = "SELECT COUNT(*) as tnum,sum(order_amount) as amount, FROM_UNIXTIME(add_time,'%m') as month from  `order` ";
        $sql .= " where add_time> $start and add_time <$end and pay_status=1 and order_status in(1,2,4) group by month ";
        $res = M()->query($sql);
        // 本月订单总数
        foreach($res as $arr){
            if($arr['month'] == $month){                 // 当前月份
                $current_tnum   = $arr['tnum'];     // 本月销售数量
                $current_amount = $arr['amount'];   // 本月销售额
            }else{
                $current_tnum = 0;
                $current_amount = 0;
            }
        }
        /**全年销售数量和销售额**/
        foreach($res as $arr){
            $data[$arr['month']] = $arr['amount'];
            $tnum[$arr['month']] = $arr['tnum'];
        }
        $all_year_tnum   = array_sum($tnum);  // 全年销售数量
        $all_year_amount = array_sum($data);  // 全年销售总额
        // 组织1-12月销售额数据格式
        for($i=1;$i<13;$i++){
            if($i < 10){
                $i = '0'.$i;
            }
            if(!in_array($i,array_keys($data))){
                $data[$i] = 0;
            }
        }
        ksort($data);  // 月份排序
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
     * ajax 修改指定表数据字段  一般修改状态 比如 是否推荐 是否开启 等 图标切换的
     * table,id_name,id_value,field,value
     */
    public function changeTableVal(){
        $table = I('table'); // 表名
        $id_name = I('id_name'); // 表主键id名
        $id_value = I('id_value'); // 表主键id值
        $field  = I('field'); // 修改哪个字段
        $value  = I('value'); // 修改字段值
        M($table)->where("$id_name = $id_value")->save(array($field=>$value)); // 根据条件保存修改的数据
    }

}
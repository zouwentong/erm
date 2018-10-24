<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class TeamSaleController extends CommonController {
	public function _initialize(){
		parent::_initialize();
		$this->model=$this->_initModel();
	}

    // 列表
    public function day(){
    	// dump($this->model->day());exit;
    	$this->dayList=$this->model->day();

    	$today=I('get.collectDate')?$date=I('get.collectDate'):$date=date('Y-m-d');
    	$yesterday=date('Y-m-d',strtotime($today)-24*3600);
    	// 今日订单总数
    	$this->todayOrderNum=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("collectDate='$today' AND isCheck<>2 AND isCancel=2 ")->sum('orderNum');
        // echo M('Order')->_sql().'<br/>';
    	// 今日订单总额
   	$this->todayOrderPrice=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("collectDate='$today' AND isCheck<>2  AND isCancel=2 ")->sum('price');

    	// 昨日订单总数
    	$this->yesterdayOrderNum=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("collectDate='$yesterday' AND isCheck<>2  AND isCancel=2 ")->sum('orderNum');
    	// 昨日订单总额
    	$this->yesterdayOrderPrice=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("collectDate='$yesterday' AND isCheck<>2  AND isCancel=2 ")->sum('price');

        $this->today=$today;
        $this->yesterday=$yesterday;


        if($team=M('Team')->where("teamLeader=".session('empId'))->find()){
            // 主管
            $ext=" AND e.teamId=".$team['id'];
            $teamDay=$this->model->day($ext);
            $this->assign('teamDay',$teamDay);
        }elseif(M('Team')->where("teamManager=".session('empId'))->find()){
            // 总监
            $teamIds=M('Team')->where("teamManager=".session('empId'))->getField('id',true);
            $ext=" AND e.teamId IN (".implode(',',$teamIds).")";
            $teamDay=$this->model->day($ext);
            $this->assign('teamDay',$teamDay);
        }

    	$this->display();
    }
     // 列表
    public function month(){
    	//dump($this->model->month());exit;
    	$this->monthList=$this->model->month();

    	$month=I('get.collectMonth')?:date('Y-m');
    	$lastMonth=date('Y-m',mktime(0,0,0,date('m',strtotime($month))-1,1,date('Y',strtotime($month))));

    	// 今日订单总数
    	$this->monthOrderNum=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCheck<>2  AND isCancel=2 ")->sum('orderNum');
    	// 今日订单总额
    	$this->monthOrderPrice=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCheck<>2  AND isCancel=2 ")->sum('price');

    	// 昨日订单总数
    	$this->lastMonthOrderNum=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$lastMonth' AND isCheck<>2  AND isCancel=2 ")->sum('orderNum');
    	// 昨日订单总额
    	$this->lastMonthOrderPrice=M('Order')->alias('o')->join('ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$lastMonth' AND isCheck<>2  AND isCancel=2 ")->sum('price');

        $this->month=$month;
        $this->lastMonth=$lastMonth;

        if($team=M('Team')->where("teamLeader=".session('empId'))->find()){
            // 主管
            $ext=" AND e.teamId=".$team['id'];
            $teamMonth=$this->model->month($ext);
            $this->assign('teamMonth',$teamMonth);
        }elseif(M('Team')->where("teamManager=".session('empId'))->find()){
            // 总监
            $teamIds=M('Team')->where("teamManager=".session('empId'))->getField('id',true);
            $ext=" AND e.teamId IN (".implode(',',$teamIds).")";
            $teamMonth=$this->model->month($ext);
            $this->assign('teamMonth',$teamMonth);
        }

    	$this->display();
    }

    public function detail(){
    	if(IS_AJAX){
    		
    	}
    }
}
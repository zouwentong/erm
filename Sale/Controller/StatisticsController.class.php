<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class StatisticsController extends CommonController {
	public function _initialize(){
		parent::_initialize();
	}

    // 列表
    public function index(){
    	$this->display();
    }

    public function teamDetail(){
    	$month1=I('get.month1')?:date('Y-m',mktime(0,0,0,date('m',strtotime(date('Y-m')))-1,1,date('Y',strtotime(date('Y-m')))));
    	$month2=I('get.month2')?:date('Y-m');
    	if(empty(I('get.teamId'))){
    		$_GET['teamId']=7;
    	}
    	$teamId=I('get.teamId')?:7;
    	$where="1";
    	if($emp=I('get.emp')){
    		$where.=" AND (e.empName='$emp' OR e.empNo='$emp')";
    	}
    	$sql="SELECT e.id,e.empName,
    			sum(date_format(collectDate,'%Y-%m')='$month1') month1OrderTotalNum,
    			sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month1' AND isNew=1 THEN 1 ELSE 0 END) month1OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month1')*price) month1OrderPrice,
				sum(date_format(collectDate,'%Y-%m')='$month2') month2OrderTotalNum,
				sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month2' AND isNew=1 THEN 1 ELSE 0 END) month2OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month2')*price) month2OrderPrice,
				'$month1' month1,'$month2' month2
				FROM
				ym_emp e
				LEFT JOIN ym_order o
				ON e.id=o.empId AND date_format(collectDate,'%Y-%m') IN ('$month1','$month2')
				WHERE $where AND e.teamId=$teamId
				group by e.id order by month2OrderNum DESC";
		$data=M()->query($sql);
		// dump($data);
		$this->assign('data',$data);
		$this->assign('teamList',M('Team')->field('id,teamName')->select());
		$this->month1=$month1;
		$this->month2=$month2;
    	$this->display();
    }

    public function team(){
    	$month1=I('get.month1')?:date('Y-m',mktime(0,0,0,date('m',strtotime(date('Y-m')))-1,1,date('Y',strtotime(date('Y-m')))));
    	$month2=I('get.month2')?:date('Y-m');

    	$where="e.teamId>0 AND t.teamName <> ''";
    	if($teamIds=I('get.teamIds')){
    		$where.=" AND t.id IN (".implode(',',$teamIds).")";
    	}
    	$sql="SELECT t.teamName,sum(date_format(collectDate,'%Y-%m')='$month1') month1OrderNum,
				sum(date_format(collectDate,'%Y-%m')='$month1') month1OrderTotalNum,
    			sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month1' AND isNew=1 THEN 1 ELSE 0 END) month1OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month1')*price) month1OrderPrice,
				sum(date_format(collectDate,'%Y-%m')='$month2') month2OrderTotalNum,
				sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month2' AND isNew=1 THEN 1 ELSE 0 END) month2OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month2')*price) month2OrderPrice,
				'month1' month1,'month2' month2
				FROM
				ym_emp e
				LEFT JOIN ym_order o
				ON e.id=o.empId AND date_format(collectDate,'%Y-%m') IN ('$month1','$month2')
				LEFT JOIN ym_team t
				ON e.teamId=t.id
				WHERE $where
				group by e.teamId order by month2OrderNum DESC";
		$data=M()->query($sql);
		// dump($data);
		$this->assign('data',$data);
		$this->assign('teamList',M('Team')->field('id,teamName')->select());
		$this->month1=$month1;
		$this->month2=$month2;
    	$this->display();
    }

    public function central(){
    	$month1=I('get.month1')?:date('Y-m',mktime(0,0,0,date('m',strtotime(date('Y-m')))-1,1,date('Y',strtotime(date('Y-m')))));
    	$month2=I('get.month2')?:date('Y-m');

    	$where="a.id>0 AND a.areaName <> ''";
    	if($areaId=I('get.areaId')){
    		$where.=" AND a.id=$areaId";
    	}
    	$sql="SELECT a.areaName,sum(date_format(collectDate,'%Y-%m')='$month1') month1OrderNum,
				sum(date_format(collectDate,'%Y-%m')='$month1') month1OrderTotalNum,
    			sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month1' AND isNew=1 THEN 1 ELSE 0 END) month1OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month1')*price) month1OrderPrice,
				sum(date_format(collectDate,'%Y-%m')='$month2') month2OrderTotalNum,
				sum(CASE WHEN date_format(collectDate,'%Y-%m')='$month2' AND isNew=1 THEN 1 ELSE 0 END) month2OrderNum,
				sum((date_format(collectDate,'%Y-%m')='$month2')*price) month2OrderPrice,
				'month1' month1,'month2' month2
				FROM
				ym_emp e
				LEFT JOIN ym_order o
				ON e.id=o.empId AND date_format(collectDate,'%Y-%m') IN ('$month1','$month2')
				LEFT JOIN ym_team t
				ON e.teamId=t.id
				LEFT JOIN ym_team_area a
				ON t.areaId=a.id
				WHERE $where
				group by a.id order by month2OrderNum DESC";
		//echo $sql;
		$data=M()->query($sql);
		// dump($data);
		$this->assign('data',$data);
		$this->assign('areaList',M('TeamArea')->field('id,areaName')->select());
		$this->month1=$month1;
		$this->month2=$month2;
    	$this->display();
    }
}

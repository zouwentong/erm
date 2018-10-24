<?php
namespace Sale\Model;
use Think\Model;
class TeamSaleModel extends Model{
	protected $tablePrefix='ym_';

	protected $tableName="order";

	public function day($ext=''){
		I('get.collectDate')?$date=I('get.collectDate'):$date=date('Y-m-d');

		$where="1";
		if($ext){
			$where.=$ext;
		}

		$sql="select o.empId,e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where collectDate='$date' AND (isCheck <>2) AND isCancel=2 AND $where group by empId order by orderNum DESC,orderPrice DESC";

		$data=M()->query($sql);

		$day=[];
		foreach($data as $k=>$v){
			$day[$v['teamId']]['order'][]=$v;
		}

		foreach($day as $k=>$v){
			$orderNum=0;
			$orderPrice=0;
			foreach($v['order'] as $v2){
				$orderNum+=$v2['orderNum'];
				$orderPrice+=$v2['orderPrice'];
			}
			$day[$k]['orderNum']=$orderNum;
			$day[$k]['orderPrice']=$orderPrice;
			$day[$k]['orderEmp']=count($day[$k]['order']);
		}

		return $day;
	}

	public function month($ext=''){
		I('get.collectMonth')?$month=I('get.collectMonth'):$month=date('Y-m');

		$where="1";
		if($ext){
			$where.=$ext;
		}

		$sql="select o.empId,e.empName,e.pic headPic,t.teamName,e.teamId,t.pic,f.empName teamLeader,g.empName teamManager,sum(orderNum) orderNum,sum(o.price) orderPrice from ym_order o JOIN ym_emp e ON o.empId=e.id JOIN ym_team t ON t.id=e.teamId JOIN ym_emp f ON t.teamLeader=f.id JOIN ym_emp g ON t.teamManager=g.id where date_format(collectDate,'%Y-%m')='$month' AND (isCheck <>2) AND isCancel=2 AND $where group by empId order by orderNum DESC,orderPrice DESC";
		$data=M()->query($sql);


		$month=[];
		foreach($data as $k=>$v){
			$month[$v['teamId']]['order'][]=$v;
		}

		foreach($month as $k=>$v){
			$orderNum=0;
			$orderPrice=0;
			foreach($v['order'] as $v2){
				$orderNum+=$v2['orderNum'];
				$orderPrice+=$v2['orderPrice'];
			}
			$month[$k]['orderNum']=$orderNum;
			$month[$k]['orderPrice']=$orderPrice;
			$month[$k]['orderEmp']=count($month[$k]['order']);
		}

		return $month;
	}
	
}
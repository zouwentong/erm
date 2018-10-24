<?php
namespace Sale\Controller;
use Common\Controller\CommonController;
class ExcelController extends CommonController {

	public function _initialize(){
		parent::_initialize();
	}

    // 月销售报表
    public function monthSheet(){
		$where='o.isCancel=2 AND o.isCheck<>2 AND e.deptId <> 26';

		$month=I('get.month')?trim(I('get.month')):date('Y-m');

		$this->month=$month;
		$count=M('Order')->alias('o')->join('LEFT JOIN ym_emp e ON e.id=o.empId')->where("$where AND date_format(collectDate,'%Y-%m')='$month'")->count();

		$Page=paginate($count,5);

        $this->assign('page',$Page->show());
		$sql="SELECT  o.*,e.empName,s.sourceName,c.name,c.qq,c.mobile,c.emNum,p.productName,t.teacherName,i.address from ym_order o LEFT JOIN ym_emp e ON o.empId=e.id JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id JOIN ym_customer_info i ON o.customerId=i.id LEFT JOIN ym_teacher t ON t.id=o.teacherId  WHERE $where AND date_format(o.collectDate,'%Y-%m')='$month' ORDER BY o.collectDate ASC,e.empName DESC LIMIT $Page->firstRow,$Page->listRows";
		$monthArr=M()->query($sql);

		$this->assign('orderList',$monthArr);
		$this->assign('totalNum',M('Order')->alias('o')->join('LEFT JOIN ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26")->sum('orderNum'));
		$this->assign('totalPrice',M('Order')->alias('o')->join('LEFT JOIN ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26")->sum('price'));
		$this->assign('date',$month);
		$this->display();
    }

    public function exportMonthSheet(){
    	$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$sql="SELECT o.*,e.empName,s.sourceName,c.name,c.qq,c.mobile,c.emNum,c.source,p.productName,t.teacherName,i.address,IFNULL(cs.orderId,0) orderId,cs.sourceName chatSourceName FROM  ym_order o LEFT JOIN ym_emp e ON e.id=o.empId JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id JOIN ym_customer_info i ON o.customerId=i.id LEFT JOIN ym_teacher t ON t.id=o.teacherId LEFT JOIN ym_chat_source cs ON cs.orderId=o.id WHERE date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26 ORDER BY CONVERT(empName USING gbk) ASC";
		$list=M()->query($sql);

		// dump($list);exit;

		if(empty($list)){
			// $this->error('对不起,当前月份没有数据！');
			exit('empty');
		}
		$total_price=M('Order')->alias('o')->join('LEFT JOIN ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26")->sum('price');
		$count=M('Order')->alias('o')->join('LEFT JOIN ym_emp e ON e.id=o.empId')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26")->sum('orderNum');
		
		$auth=new \Think\Auth;
        if($auth->check('qqAndMobileNotEncypt',session('empId')) || session('empId')==C('CFG_STATIC_MANAGER_ID') ){
            $is_admin=1;
        }else{
            $is_admin=0;
        }
		exportMonthExcel($list,$month,$count,$total_price,$is_admin);
		exit;
    }

    // 提成表
    public function prizeSheet(){
    	$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$this->month=$month;
    	$this->display();
    }

    public function exportPrizeSheet(){
    	$type=I('get.type',1);
    	$month=I('get.month')?trim(I('get.month')):date('Y-m');
    	if($type==1){

			$where="empId <> 0 AND date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2";
			$sql="SELECT price FROM ym_order WHERE $where GROUP BY price ORDER BY price ASC";
			$list=M()->query($sql);
			if(empty($list)){
				$this->error('对不起,当前月份没有数据！');
			}
			// dump($data);
			$sql="SELECT empName,price,sum(orderNum) num,sum(price) total_price FROM ym_order o LEFT JOIN ym_emp e ON o.empId=e.id WHERE $where GROUP BY empName,price ORDER BY teamId,price ASC";
			$staffOrder=array();
			$tmp=M()->query($sql);
			foreach($tmp as $k=>$v){
				$staffOrder[$v['empName']][]=$v;
			}
			// dump($staffOrder);exit;
			$sql="SELECT empName,price,sum(orderNum) num,sum(price) total_price FROM ym_order  o LEFT JOIN ym_emp e ON o.empId=e.id WHERE $where GROUP BY empName,price ORDER BY teamId,price ASC";
			$tmp2=M()->query($sql);
			$staffTrueOrder=array();
			foreach($tmp2 as $k=>$v){
				$staffTrueOrder[$v['empName']][]=$v;
			}

			$sql="SELECT price,count(*) as price_num FROM ym_order WHERE $where AND sourceId=6 GROUP BY price ORDER BY price ASC";
			$list2=M()->query($sql);

			$where.=" AND (sourceId=6 OR sourceId=2)";
			$tx_price=M('Order')->where($where)->sum('price');
			$total_price=M('Order')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2")->sum('price');
			$count=M('Order')->where("date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2")->sum('orderNum');
			exportPrizeExcel($list,$staffOrder,$staffTrueOrder,$month,$count,$total_price,$list2,$tx_price);
		}elseif($type==2){
			
			$sql="SELECT o.*,e.empName,e.empNo,s.sourceName,c.name,c.source,p.productName,t1.teacherName,i.address,f.empName teamLeader,g.empName teamManager,d.deptName,a.areaName FROM  ym_order o LEFT JOIN ym_emp e ON e.id=o.empId JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id JOIN ym_customer_info i ON o.customerId=i.id LEFT JOIN ym_teacher t1 ON t1.id=o.teacherId LEFT JOIN ym_team t ON t.id=e.teamId LEFT JOIN ym_team_area a ON a.id=t.areaId LEFT JOIN ym_emp f ON t.teamLeader=f.id LEFT JOIN ym_emp g ON g.id=t.teamManager LEFT JOIN ym_dept d ON d.id=e.deptId WHERE date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26 ORDER BY a.id ASC,CONVERT(e.empName USING gbk) ASC";
			$list=M()->query($sql);

			$sql="SELECT sum(o.price) orderPrice,sum(o.orderNum) orderNum,e.empName,e.empNo,s.sourceName,c.name,c.source,p.productName,t1.teacherName,i.address,f.empName teamLeader,g.empName teamManager,d.deptName,a.areaName FROM  ym_order o LEFT JOIN ym_emp e ON e.id=o.empId JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id JOIN ym_customer_info i ON o.customerId=i.id LEFT JOIN ym_teacher t1 ON t1.id=o.teacherId LEFT JOIN ym_team t ON t.id=e.teamId LEFT JOIN ym_team_area a ON a.id=t.areaId LEFT JOIN ym_emp f ON t.teamLeader=f.id LEFT JOIN ym_emp g ON g.id=t.teamManager LEFT JOIN ym_dept d ON d.id=e.deptId WHERE date_format(collectDate,'%Y-%m')='$month' AND isCancel=2 AND isCheck<>2 AND e.deptId <> 26 GROUP by e.id ORDER BY a.id ASC,CONVERT(e.empName USING gbk) ASC";
			$list2=M()->query($sql);

			exportPrizeExcel2($list,$list2,$month);
		}
    }

    // 进线资源
    public function lineSheet(){
    	if(I('get.type')==7){
    		// 广告
    		$sourceIds=M('Source')->where("sourceName LIKE '媒体%' OR sourceName='广点通' OR sourceName LIKE '端口%'")->getField('id',true);
    		$where="sourceId IN (".implode(',',$sourceIds).") AND isCancel=2 AND isCheck<>2";
    	}else{
    		$where="(sourceId IN (2,6,8,88,89)) AND isCancel=2 AND isCheck<>2 AND isNew=1";//sourceId=6 OR sourceId=2 OR sourceId=8 OR sourceId=88 OR sourceId=89
    	}
    	
		$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$month=$month."-20";
		$y=date('Y',strtotime($month));
		$m=date('m',strtotime($month));
		$lastmonth=date('Y-m-d',mktime(0,0,0,$m-1,21,$y));
		$where.=" AND collectDate BETWEEN '$lastmonth' AND '$month'";
		
		$count=M('Order')->where($where)->count();
		$Page=paginate($count,5);
        $this->assign('page',$Page->show());
		$sql="SELECT o.*,e.empName,s.sourceName,c.source,c.name,c.qq,c.mobile,c.emNum,p.productName,t.teacherName,i.address FROM `ym_order` o LEFT JOIN ym_emp e ON e.id=o.empId JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id JOIN ym_customer_info i ON o.customerId=i.id LEFT JOIN ym_teacher t ON t.id=o.teacherId  WHERE $where  ORDER BY collectDate ASC LIMIT $Page->firstRow,$Page->listRows";

		$monthArr=M()->query($sql);
		$this->assign('orderList',$monthArr);
		$this->assign('totalNum',$count);
		$this->assign('month',date('Y-m',strtotime($month)));
		$this->assign('monthArea',$lastmonth.'日至'.$month);
		$this->display();
    }

    public function exportLineSheet(){
    	$type=I('get.type');
		if($type==1){
			$where="(sourceId=6 OR sourceId=2 OR sourceId=8 OR sourceId=88 OR sourceId=89) AND isCancel=2 AND isCheck<>2 AND isNew=1";
			$name='进线';
		}elseif($type==2){
			$where="(sourceId=3) AND isCancel=2 AND isCheck<>2";
			$name='蹦蹦';
		}elseif($type==3){
			$where="(sourceId=9) AND isCancel=2 AND isCheck<>2";
			$name='广告';
		}elseif($type==4){
			$where="(sourceId=4) AND isCancel=2 AND isCheck<>2";
			$name='微信';
		}elseif($type==6){
			$where="(sourceId=11) AND isCancel=2 AND isCheck<>2";
			$name='手动加群';
		}elseif($type==5){
			$where="(sourceId=2 OR sourceId=3 OR sourceId=9 OR sourceId=11) AND isCancel=2 AND isCheck<>2";
			$name='4项汇总';
		}elseif($type==7){
			$sourceIds=M('Source')->where("sourceName LIKE '媒体%' OR sourceName='广点通' OR sourceName LIKE '端口%'")->getField('id',true);
    		$where="sourceId IN (".implode(',',$sourceIds).") AND isCancel=2 AND isCheck<>2";
    		$name="广告资源";
		}
		
		$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$month=$month."-20";
		$y=date('Y',strtotime($month));
		$m=date('m',strtotime($month));
		$lastmonth=date('Y-m-d',mktime(0,0,0,$m-1,21,$y));
		$where.=" AND collectDate BETWEEN '$lastmonth' AND '$month'";
		
		$sql="SELECT empName,productName,collectDate,price,orderNo,name,emNum,beizhu FROM `ym_order` o LEFT JOIN ym_emp e ON e.id=o.empId JOIN ym_source s ON o.sourceId=s.id JOIN ym_product p ON p.id=o.productId JOIN ym_customer c ON o.customerId=c.id WHERE $where  ORDER BY collectDate ASC";

		$list=M()->query($sql);
		if(empty($list)){
			$this->error('对不起,当前月份没有数据！');
		}
		exportLineExcel($list,$month,$lastmonth,$name);
		exit;
    }

    // 进线明细
    public function lineDetail(){
    	$where="(sourceId=6 OR sourceId=2 OR sourceId=8 OR sourceId=88 OR sourceId=89) AND isCancel=2 AND isCheck<>2 AND isNew=1";
		$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$month=$month."-20";
		$y=date('Y',strtotime($month));
		$m=date('m',strtotime($month));
		$lastmonth=date('Y-m-d',mktime(0,0,0,$m-1,21,$y));
		$where.=" AND collectDate BETWEEN '$lastmonth' AND '$month'";

		$sql="SELECT productName, price,sum(goodsNum) num,sum(price) total_price FROM ym_order o JOIN ym_product p ON o.productId=p.id WHERE $where GROUP BY productName,price ORDER BY price ASC";

		$list=M()->query($sql);

		$orderNum=0;
		$orderPrice=0.0;
		foreach($list as $v){
			$orderNum+=$v['num'];
			$orderPrice+=$v['total_price'];
		}
		$this->assign('orderList',$list);
		$this->assign('orderNum',$orderNum);
		$this->assign('orderPrice',$orderPrice);
		$this->assign('monthArea',$lastmonth.'日至'.$month);
		$this->assign('month',date('Y-m',strtotime($month)));

		$this->display();
    }

    public function exportLineDetail(){
    	header('Content-type:text/html;charset=utf-8');
		$type=I('get.type');
		if($type==1){
			$where="(sourceId=6 OR sourceId=2 OR sourceId=8 OR sourceId=88 OR sourceId=89) AND isCancel=2 AND isCheck<>2 AND isNew=1";
			$name='进线';
		}elseif($type==2){
			$where="(sourceId=3) AND isCancel=2 AND isCheck<>2";
			$name='蹦蹦';
		}elseif($type==3){
			$where="(sourceId=9) AND isCancel=2 AND isCheck<>2";
			$name='广告';
		}elseif($type==4){
			$where="(sourceId=3 OR sourceId=6 OR sourceId=9) AND isCancel=2 AND isCheck<>2";
			$name='3项明细';
		}elseif($type==7){
			$sourceIds=M('Source')->where("sourceName LIKE '媒体%' OR sourceName='广点通' OR sourceName LIKE '端口%'")->getField('id',true);
    		$where="sourceId IN (".implode(',',$sourceIds).") AND isCancel=2 AND isCheck<>2";
    		$name='广告资源';
		}
		$month=I('get.month')?trim(I('get.month')):date('Y-m');
		$month=$month."-20";
		$y=date('Y',strtotime($month));
		$m=date('m',strtotime($month));
		$lastmonth=date('Y-m-d',mktime(0,0,0,$m-1,21,$y));
		$where.=" AND collectDate BETWEEN '$lastmonth' AND '$month'";

		$sql="SELECT productName, price, sum(goodsNum) num,sum(price) total_price FROM ym_order o JOIN ym_product p ON o.productId=p.id  WHERE $where GROUP BY productName,price ORDER BY price ASC";

		$list=M()->query($sql);

		$orderNum=0;
		$orderPrice=0.0;
		foreach($list as $v){
			$orderNum+=$v['num'];
			$orderPrice+=$v['total_price'];
		}

		$sql="SELECT empName, productName, price, sum(goodsNum) num, sum( price ) total_price FROM ym_order o JOIN ym_product p ON o.productId=p.id LEFT JOIN ym_emp e ON e.id=o.empId WHERE $where GROUP BY empName, productName, price ORDER BY e.teamId,CONVERT(empName USING gbk), price ASC";

		$data=M()->query($sql);

		$staffLine=array();
		foreach($data as $k=>$v){
			$staffLine[$v['empName']][]=$v;
		}

		$sql="SELECT empName, price, orderNo FROM ym_order o LEFT JOIN ym_emp e ON e.id=o.empId WHERE $where ORDER BY CONVERT(empName USING gbk), price ASC";
		$arr=M()->query($sql);

		$staffOrder=array();
		foreach($arr as $k=>$v){
			$staffOrder[$v['empName']][]=$v;
		}
		// dump($staffOrder);exit;
		if(empty($list)){
			$this->error('对不起,当前月份没有数据！');
		}
		exportLineDetailExcel($list,$staffLine,$staffOrder,$month,$orderNum,$orderPrice,$name);
    }
}